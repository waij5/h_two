<?php

namespace app\common\controller;

use app\admin\provider\AdminProvider;
use think\controller\Rest;
use think\exception\HttpResponseException;
use think\Request;
use traits\controller\Jump;
use yjy\Passport\Exception\OAuthServerException;
use yjy\Passport\TokenGuard;
use yjy\Passport\TokenRepository;

class Api extends Rest
{
    use Jump;

    protected $noNeedLogin = [];
    protected $noNeedRight = [];
    /**
     * 快速搜索时执行查找的字段
     */
    protected $searchFields = 'id';
    /**
     * 是否是关联查询
     */
    protected $relationSearch = false;

    public function _initialize()
    {
        // if (!static::match($this->noNeedLogin)) {
        try {
            $userProvider    = new AdminProvider;
            $tokenRepository = new TokenRepository;
            $encrptyer       = new \StdClass;

            $tokenGuard = new TokenGuard($userProvider, $tokenRepository, $encrptyer);

            $admin = $tokenGuard->user(Request::instance());
            if ($admin == null || $admin->status != 'normal') {
                throw OAuthServerException::accessDenied("Access token is invalid", 1);
            }

            // 动态绑定属性
            Request::instance()->bind('admin', $admin);
        } catch (OAuthServerException $e) {
            $response = response($data = ['code' => 0, 'msg' => $e->getMessage(), 'data' => []], 419, $header = [], $type = 'json');
            throw new HttpResponseException($response);
        }
    }

    public function __construct(Request $request = null)
    {
        // 控制器初始化
        parent::__construct();
        if (is_null($request)) {
            $request = Request::instance();
        }
        $this->request = $request;
        $this->_initialize();
    }

    /**
     * 生成查询所需要的条件,排序方式
     * @param mixed $searchfields 查询条件
     * @param boolean $relationSearch 是否关联查询
     * @return array
     */
    protected function buildparams($searchfields = null, $relationSearch = null, $useWhereAsClosure = true)
    {
        $searchfields = is_null($searchfields) ? $this->searchFields : $searchfields;
        $relationSearch = is_null($relationSearch) ? $this->relationSearch : $relationSearch;
        $search = $this->request->get("search", '');
        $filter = $this->request->get("filter", '');
        $op = $this->request->get("op", '');
        $sort = $this->request->get("sort", "id");
        $order = $this->request->get("order", "DESC");
        $offset = $this->request->get("offset", 0);
        $limit = $this->request->get("limit", 0);
        $filter = json_decode($filter, TRUE);
        $op = json_decode($op, TRUE);
        $filter = $filter ? $filter : [];
        $where = [];
        $tableName = '';
        if ($relationSearch)
        {
            if (!empty($this->model))
            {
                $class = get_class($this->model);
                $name = basename(str_replace('\\', '/', $class));
                $tableName = $this->model->getQuery()->getTable($name) . ".";
            }
            if (stripos($sort, ".") === false)
            {
                $sort = $tableName . $sort;
            }
        }
        if ($search)
        {
            $searcharr = is_array($searchfields) ? $searchfields : explode(',', $searchfields);
            foreach ($searcharr as $k => &$v)
            {
                $v = $tableName . $v;
            }
            unset($v);
            $where[] = [implode("|", $searcharr), "LIKE", "%{$search}%"];
        }
        foreach ($filter as $k => $v)
        {
            $sym = isset($op[$k]) ? $op[$k] : '=';
            if (stripos($k, ".") === false)
            {
                $k = $tableName . $k;
            }
            $sym = isset($op[$k]) ? $op[$k] : $sym;
            switch ($sym)
            {
                case '=':
                case '!=':
                case 'LIKE':
                case 'NOT LIKE':
                    $where[] = [$k, $sym, $v];
                    break;
                case '>':
                case '>=':
                case '<':
                case '<=':
                    $where[] = [$k, $sym, intval($v)];
                    break;
                case 'IN(...)':
                case 'NOT IN(...)':
                    $where[] = [$k, str_replace('(...)', '', $sym), explode(',', $v)];
                    break;
                case 'BETWEEN':
                case 'NOT BETWEEN':
                    $where[] = [$k, $sym, array_slice(explode(',', $v), 0, 2)];
                    break;
                case 'LIKE %...%':
                    $where[] = [$k, 'LIKE', "%{$v}%"];
                    break;
                case 'IS NULL':
                case 'IS NOT NULL':
                    $where[] = [$k, strtolower(str_replace('IS ', '', $sym))];
                    break;
                default:
                    break;
            }
        }

        if ($useWhereAsClosure) {
            $where = function($query) use ($where) {
                foreach ($where as $k => $v)
                {
                    if (is_array($v))
                    {
                        call_user_func_array([$query, 'where'], $v);
                    }
                    else
                    {
                        $query->where($v);
                    }
                }
            };
        }
        
        return [$where, $sort, $order, $offset, $limit];
    }

    protected function buildparams2($where)
    {
        $bWhere                                      = [];
        $extraWhere                                  = [];
        foreach ($where as $key => $value) {
            if (strpos($value[0], '.') === false) {
                $bWhere[$value[0]] = [$value[1], $value[2]];
            } else {
                $extraWhere[$value[0]] = [$value[1], $value[2]];
            }
        }

        return [$bWhere, $extraWhere];
    }

    /**
     * Selectpage的实现方法
     * 
     * 当前方法只是一个比较通用的搜索匹配,请按需重载此方法来编写自己的搜索逻辑,$where按自己的需求写即可
     * 这里示例了所有的参数，所以比较复杂，实现上自己实现只需简单的几行即可
     * 
     */
    protected function selectpage()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags', 'htmlspecialchars']);

        //搜索关键词,客户端输入以空格分开,这里接收为数组
        $word = (array) $this->request->request("q_word/a");
        //当前页
        $pageNumber = $this->request->request("pageNumber");
        $page = $this->request->request("page");
        $page = $pageNumber ? $pageNumber : $page;
        //分页大小 per_page
        $pagesize = $this->request->request("pageSize");

        //搜索条件
        $andor = strtoupper(trim($this->request->request("andOr", 'AND')));
        //排序方式
        $orderby = (array) $this->request->request("order_by/a");
        //显示的字段
        $field = $this->request->request("field");
        //主键
        $primarykey = $this->request->request("pkey_name");
        //主键值
        $primaryvalue = $this->request->request("pkey_value");
        //搜索字段
        // search_field
        $searchfield = (array) $this->request->request("searchField/a");

        //自定义搜索条件
        $custom = (array) $this->request->request("custom/a");

        //自定义搜索条件2
        $yjyCustom = (array) $this->request->request("yjyCustom/a");
        $order = [];
        foreach ($orderby as $k => $v)
        {
            $order[$v[0]] = $v[1];
        }
        $field = $field ? $field : 'name';

        //如果有primaryvalue,说明当前是初始化传值
        if ($primaryvalue)
        {
            $where = [$primarykey => ['in', $primaryvalue]];
        }
        else
        {
            $where = function($query) use($word, $andor, $field, $searchfield, $custom) {
                foreach ($word as $k => $v)
                {
                    foreach ($searchfield as $m => $n)
                    {
                        // $query->where($n, "like", "%{$v}%", $andor)
                        if (strcmp($andor, "OR") == 0) {
                            $query->whereOr($n, "like", "%{$v}%");
                        } else {
                            // $query->where($n, "like", "%{$v}%");
                            $query->where($n, "like", "%{$v}%", $andor);
                        }
                    }
                }
                if ($custom && is_array($custom))
                {
                    foreach ($custom as $k => $v)
                    {
                        $query->where($k, '=', $v);
                    }
                }
            };

            $yjyWhere = function($query) use($yjyCustom) {
                if ($yjyCustom && is_array($yjyCustom))
                {
                    foreach ($yjyCustom as $k => $v)
                    {
                        $query->where($k, '=', $v);
                    }
                }
            };
        }
        $list = [];
        $total = $this->model->where($where)->where($yjyWhere)->count();
        
        if ($pagesize > 0) {
            $maxPage = ceil($total / $pagesize);
            if ($page > $maxPage) {
                $page = $maxPage;
            }
        }
        if ($total > 0)
        {
            $list = $this->model
                    ->where($where)
                    ->where($yjyWhere)
                    ->order($order)
                    ->page($page, $pagesize)
                    ->field("{$primarykey},{$field}")
                    ->field("password,salt", true)
                    ->select();
        }

        //这里一定要返回有list这个字段,total是可选的,如果total<=list的数量,则会隐藏分页按钮
        return json(['list' => $list, 'total' => $total, 'totalRow' => $total]);
    }

    public static function ruleMatch($arr = [])
    {
        $request = Request::instance();
        $arr     = is_array($arr) ? $arr : explode(',', $arr);
        if (!$arr) {
            return false;
        }
        $arr = array_map('strtolower', $arr);
        // 是否存在
        if (in_array(strtolower($request->action()), $arr) || in_array('*', $arr)) {
            return true;
        }

        // 没找到匹配
        return false;
    }

    public function match()
    {
        return false;
    }
}
