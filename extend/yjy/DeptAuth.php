<?php
/**
 * 部门权限类 直接下属, 所有下属, 所有
 * singleton mode, please use SecAuth::instance to init
 * Author: Leekaen
 */
namespace yjy;

use think\Db;
use think\Config;
use think\Session;
use think\Request;
use think\Loader;
use \fast\Tree;
use \app\admin\model\Deptment;

use \traits\controller\Jump;
use app\admin\library\Auth;

class DeptAuth
{
    protected static $instance;
    private $allowedDeptIds = null;
    private $allowedAdminIds = null;
    private $deptCondition = null;

    private $adminId = null;
    private $dminDept = null;
    private $adminPosition = null;
    private $adminSecRules = [];
    public $deptTree = [];
            

    private function __construct()
    {
    }

    /**
     * 初始化
     * @access public
     * @param array $options 参数
     * @return Auth
     */
    public static function instance($options = [])
    {
        if (is_null(self::$instance))
        {
            self::$instance = new static($options);
            self::$instance->init();
        }
        
        return self::$instance;
    }

    private function init()
    {
        if (empty($this->adminId)) {
            $admin = Session::get('admin');
            $this->adminId = $admin->id;
            $this->adminDept = $admin->dept_id;
            $this->adminPosition = $admin->position;
            $this->adminSecRules = json_decode($admin['sec_rules'], true);
        }
        $this->deptTree = Deptment::getDeptTreeCache();
    }

    /**
     * 获取允许操作的部门ID
     * 非普通职工，先检查是否已处理，未处理则处理并赋值$this->secIds, 返回$this->secIds
     * @return mixed 普通职工[],所有权限 *,其它 []-本部门,其它允许部门ids
     */
    public function getDeptIds($includeStaffDept = false)
    {
        $this->init();
        //普通职工直接返回空数组
        if ($this->adminPosition == 0) {
            if ($includeStaffDept) {
                return [$this->adminDept];
            } else {
                return [];
            }
        }

        // if ($this->allowedDeptIds == null) {
        if ($this->adminSecRules['all']) {
            $this->allowedDeptIds = '*';
        } else {
            $deptArr = [];
            if ($this->adminSecRules['all_sub']) {
                $subSecArr = $this->deptTree->getChildren($this->adminDept, false);
                $deptArr = array_merge($deptArr, $subSecArr);
            } elseif ($this->adminSecRules['direct_sub']) {
                $subSecArr = $this->deptTree->getChild($this->adminDept);
                $deptArr = array_merge($deptArr, $subSecArr);
            }
            //默认包含本部门数据
            $deptIds = array($this->adminDept);
            foreach ($deptArr as $dept) {
                array_push($deptIds, $dept['id']);
            }

            $this->allowedDeptIds = array_unique($deptIds);
        }

        return $this->allowedDeptIds;
        // }
    }

    /**
     * 根据部门相应权限，获得查询条件
     */
    private function getdeptCondition($includeStaffDept = false, $force = false)
    {
        // $this->init();
        if ($this->deptCondition === null || $force) {
            $this->allowedDeptIds = $this->getDeptIds($includeStaffDept);

            if ($this->allowedDeptIds == '*') {
                $this->deptCondition = [];
            } else {
                //attention userIds bv 至少包含自己，不可能为空

                if (empty($this->allowedDeptIds)) {
                    //不存在
                    $this->deptCondition = ['dept_id' => ['=', -1]];
                } else {
                    if (count($this->allowedDeptIds) == 1) {
                        $this->deptCondition = ['dept_id' => $this->allowedDeptIds[0]];
                    } else {
                       $this->deptCondition = ['dept_id' => ['in', $this->allowedDeptIds]];
                    }
                }

                
            }
        }

        return $this->deptCondition;
    }

    /**
     * 根据部门相应权限，获得查询条件 admin, department
     */
    public function getAdmindeptCondition($fields = 'id')
    {
       $deptCondition = $this->getDeptCondition();
        if (empty($this->getDeptCondition())) {
            return model('Admin')->field($fields)->buildSql();
        } else {
            return model('Admin')->field($fields)->where($deptCondition)->buildSql();
        }
    }

    /**
     * 根据部门相应权限，获得查询条件 admin, department
     */
    public function getAdminCondition($fields = 'id', $owerAdminId, $includeNone = false, $useSql = true)
    {
       $deptCondition = $this->getDeptCondition();
       if (empty($owerAdminId)) {
            $owerAdminId = $this->adminId;
       }

        $tmp = model('Admin');
        if ($deptCondition == []) {
            $tmp = model('Admin')->field($fields);
        } else {
            if ($includeNone) {
                $tmp = model('Admin')->field($fields)->where(function ($query) use ($deptCondition, $owerAdminId) {
                        $query->where($deptCondition)->whereOr(['id' => ['in', array($owerAdminId, 0)]]);
                    });
            } else {
                $tmp = model('Admin')->field($fields)->where(function ($query) use ($deptCondition, $owerAdminId) {
                    $query->where($deptCondition)->whereOr(['id' => $owerAdminId]);
                });
            }
        }

        if ($useSql) {
            return $tmp->buildSql();
        } else {
            return $tmp;
        }
    }

    public function getCusdeptCondition($deptfieldName = null, $includeStaffDept = false)
    {
        $deptCondition = $this->getdeptCondition($includeStaffDept);
        if (empty($deptCondition)) {
            return $deptCondition;
        }
        if (empty($deptfieldName)) {
            return $deptCondition;
        } else {
            return ["$deptfieldName" => $deptCondition['dept_id']];
        }
    }

    /**
     * @param  [type]
     * @param  [type]
     * @param  boolean
     * @return [type]
     */
    public function checkAuth($owerAdminId, $strict = false) {
        $auth = Auth::instance();
        if ($auth->isSuperAdmin()) {
            return true;
        }

        $this->init();
        $flag = false;

        if ($this->adminId == $owerAdminId) {
            $flag = true;
        } else {
            $deptCondition = $this->getdeptCondition();
           
            if (empty($deptCondition)) {
                $flag = true;
            } else {
                $extraWhere = ['position' => ['<=', $this->adminPosition]];
                if ($strict) {
                    $extraWhere['position'] = ['<', $this->adminPosition];
                }
                $isOwnerInDepts = model('Admin')
                                ->where(['id' => $owerAdminId])
                                ->where($this->getdeptCondition())
                                ->where($extraWhere)
                                ->count();
                if ($isOwnerInDepts) {
                    $flag = true;
                }
            }
            
        }

        return $flag;
    }


    public function __get($name)
    {
        if ($name && isset($this->$name)) {
            return $this->$name;
        }
        return null;
    }

    public function __call($method, $arg_array)
    {
        if (strlen($method) > 3) {
            if (substr($method, 0, 3) == 'get') {
                $name = lcfirst(substr($method, 3));
                if (isset($this->$name)) {
                    return $this->$name;
                }
            }
        }
    }
}