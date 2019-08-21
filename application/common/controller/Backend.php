<?php

namespace app\common\controller;

use app\admin\library\Auth;
use think\Config;
use think\Controller;
use think\Hook;
use think\Lang;
use think\Session;
use \yjy\DeptAuth;

/**
 * 后台控制器基类
 */
class Backend extends Controller
{

    /**
     * 无需登录的方法,同时也就不需要鉴权了
     * @var array
     */
    protected $noNeedLogin = [];

    /**
     * 无需鉴权的方法,但需要登录
     * @var array
     */
    protected $noNeedRight = [];

    /**
     * 布局模板
     * @var string
     */
    protected $layout = 'default';

    /**
     * 权限控制类
     * @var Auth
     */
    protected $auth = null;

    /**
     * 快速搜索时执行查找的字段
     */
    protected $searchFields = 'id';

    /**
     * 是否是关联查询
     */
    protected $relationSearch = false;

    /**
     * 是否开启Validate验证
     */
    protected $modelValidate = false;

    /**
     * 是否开启模型场景验证
     */
    protected $modelSceneValidate = false;

    /**
     * Multi方法可批量修改的字段
     */
    protected $multiFields = 'status';

    /**
     * 是否开启部门权限校验
     */
    protected $needDeptAuth = true;

    protected $deptAuth = null;

    /**
     * 引入后台控制器的traits
     */
    use \app\admin\library\traits\Backend;

    public function _initialize()
    {
        $modulename = $this->request->module();
        $controllername = strtolower($this->request->controller());
        $actionname = strtolower($this->request->action());

        $path = str_replace('.', '/', $controllername) . '/' . $actionname;

        // 定义是否Addtabs请求
        !defined('IS_ADDTABS') && define('IS_ADDTABS', input("addtabs") ? TRUE : FALSE);

        // 定义是否Dialog请求
        !defined('IS_DIALOG') && define('IS_DIALOG', input("dialog") ? TRUE : FALSE);

        // 定义是否AJAX请求
        !defined('IS_AJAX') && define('IS_AJAX', $this->request->isAjax());

        $this->auth = Auth::instance();

        // 设置当前请求的URI
        $this->auth->setRequestUri($path);
        // 检测是否需要验证登录
        if (!$this->auth->match($this->noNeedLogin))
        {
            //检测是否登录
            if (!$this->auth->isLogin())
            {
                Hook::listen('admin_nologin', $this);
                $url = Session::get('referer');
                $url = $url ? $url : $this->request->url();
                $this->error(__('Please login first'), url('index/login', ['url' => $url]));
            }
            // 判断是否需要验证权限
            if (!$this->auth->match($this->noNeedRight))
            {
                // 判断控制器和方法判断是否有对应权限
                if (!$this->auth->check($path))
                {
                    Hook::listen('admin_nopermission', $this);
                    $this->error(__('You have no permission'), '');
                }
            }
        }
        
        // 非选项卡时重定向
        if (!$this->request->isPost() && !IS_AJAX && !IS_ADDTABS && !IS_DIALOG && input("ref") == 'addtabs')
        {
            $url = preg_replace_callback("/([\?|&]+)ref=addtabs(&?)/i", function($matches) {
                return $matches[2] == '&' ? $matches[1] : '';
            }, $this->request->url());
            $this->redirect('index/index', [], 302, ['referer' => $url]);
            exit;
        }

        if ($this->needDeptAuth) {
            if ($this->auth->isLogin()) {
                $this->deptAuth = DeptAuth::instance();
            }
        }

        // 设置面包屑导航数据
        $breadcrumb = $this->auth->getBreadCrumb($path);
        array_pop($breadcrumb);
        $this->view->breadcrumb = $breadcrumb;

        // 如果有使用模板布局
        if ($this->layout)
        {
            $this->view->engine->layout('layout/' . $this->layout);
        }

        // 语言检测
        $lang = Lang::detect();

        $site = Config::get("site");

        $upload = \app\common\model\Config::upload();

        // 上传信息配置后
        Hook::listen("upload_config_init", $upload);
        // 配置信息
        $config = [
            'site'           => $site,
            // array_intersect_key($site, array_flip(['name', 'cdnurl', 'version', 'timezone', 'languages'])),
            'upload'         => $upload,
            'modulename'     => $modulename,
            'controllername' => $controllername,
            'actionname'     => $actionname,
            'jsname'         => 'backend/' . str_replace('.', '/', $controllername),
            'moduleurl'      => rtrim(url("/{$modulename}", '', false), '/'),
            'language'       => $lang,
            'fastadmin'      => Config::get('fastadmin'),
            'referer'        => Session::get("referer")
        ];
        // 配置信息后
        Hook::listen("config_init", $config);

        //精简版菜单
        if (!\think\Cookie::has('use_simple_menu')) {
            $useSimpleMenu = 1;
            if (isset($site['use_simple_menu'])) {
                $useSimpleMenu = $site['use_simple_menu'];
            }
            \think\Cookie::forever('use_simple_menu', $useSimpleMenu);
        }

        //加载当前控制器语言包
        $this->loadlang($controllername);
        //渲染站点配置
        $this->assign('site', $site);
        //渲染配置信息
        $this->assign('config', $config);
        //渲染权限对象
        $this->assign('auth', $this->auth);
        //渲染管理员对象
        $this->assign('admin', Session::get('admin'));
    }

    /**
     * 加载语言文件
     * @param string $name
     */
    protected function loadlang($name)
    {
        Lang::load(APP_PATH . $this->request->module() . '/lang/' . Lang::detect() . '/' . str_replace('.', '/', $name) . '.php');
    }

    /**
     * 渲染配置信息
     * @param mixed $name 键名或数组
     * @param mixed $value 值 
     */
    protected function assignconfig($name, $value = '')
    {
        $this->view->config = array_merge($this->view->config ? $this->view->config : [], is_array($name) ? $name : [$name => $value]);
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


    protected function checkDeptAuth($owerAdminId, $redirect403 = true)
    {
        $flag = $this->deptAuth->checkAuth($owerAdminId);
        if (!$flag && $redirect403) {
            $this->error(__('You have no permission'));
        }

        return $flag;
    }

    /**
     * 公用获取进度信息
     */
    protected function commondownloadprocess($command, $downloadTitle = 'download', $whereAddon = [], $extraWhereAddon = [], $extra = [])
    {
        $admin   = \think\Session::get('admin');

        if ($this->request->isAjax()) {
            $id = input('id', false);
            $force = input('force', false);
            $delete = input('delete', false);

            $cmdRecord = model('CmdRecords')->find($id);
            
            if (empty($cmdRecord)) {
                $this->error();
            } else {
                if ($this->auth->isSuperAdmin() || $cmdRecord->admin_id == $admin->id) {
                    //删除
                    if ($delete) {
                        if (!empty($cmdRecord->filepath)) {
                            @unlink(APP_PATH . 'data' . iconv('utf8', 'gb2312', $cmdRecord->filepath));
                        }
                        $cmdRecord->delete();

                        $this->success();
                    }
                    //强制重新生成
                    if ($force) {
                        $cmdRecord->save(['process' => '', 'filepath' => '']);
                        //开始EXCEL生成， 进度更新
                        $cmdRecord->startCmd();
                        $this->success();
                    }
                } else {
                    $this->error();
                }
            }


            //获取进度
            $processInfo = $cmdRecord->getProcessInfo();
            if (!empty($processInfo)) {
                $response = new \think\Response();
                $response->contentType('application/json', 'utf-8');
                $response->data($processInfo);
                return $response;
            }
            return;
        }
        list($where, $sort, $order, $offset, $limit) = $this->buildparams(null, null, false);
        $bWhere                                      = [];
        $extraWhere                                  = [];
        foreach ($where as $key => $value) {
            if (strpos($value[0], '.') === false) {
                $bWhere[$value[0]] = [$value[1], $value[2]];
            } else {
                $extraWhere[$value[0]] = [$value[1], $value[2]];
            }
        }
        $bWhere = array_merge($bWhere, $whereAddon);
        $extraWhere = array_merge($extraWhere, $extraWhereAddon);

        $params       = json_encode(['where' => $bWhere, 'extraWhere' => $extraWhere, 'extra' => $extra]);
        //以日期，命令，参数，管理员ID作为特征值
        $featureValue = md5(date('Y-m-d') . '_' . $command . '_' . $params . '_' . $admin->id);
        $cmdRecord    = model('CmdRecords')->where(['feature_value' => $featureValue])->order('id', 'DESC')->find();

        if (empty($cmdRecord)) {
            $cmdRecord  = model('CmdRecords');
            $saveResult = $cmdRecord->save(
                                        array(
                                            'feature_value' => $featureValue,
                                            'command'       => $command,
                                            'params'        => $params,
                                            'admin_id'      => $admin->id,
                                        )
                                    );

            if ($saveResult === false) {
                return __('Failed to start');
            } else {
                //开始生成文件并自动更新进度
                $cmdRecord->startCmd();
            }
        } else {
            //已有相关记录，即文件在生成或者已生成
        }

        $this->view->assign('downloadTitle', $downloadTitle);
        $this->view->assign('cmdRecord', $cmdRecord);
        $this->view->assign('downloadLink', url('/general/attachment/downloadtgz/id/' . $cmdRecord->id));
        
        return $this->view->fetch('common/downloadprocess');
    }
}
