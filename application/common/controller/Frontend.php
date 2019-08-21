<?php

namespace app\common\controller;

use think\Config;
use think\Controller;
use think\Lang;

class Frontend extends Controller
{

    /**
     * 布局模板
     * @var string
     */
    protected $layout      = 'default';
    protected $pageScripts = [];
    protected $pageJs      = '';
    protected $breadcrumb  = [];

    public function _initialize()
    {
        //移除HTML标签
        $this->request->filter('strip_tags');
        $modulename     = $this->request->module();
        $controllername = strtolower($this->request->controller());
        $actionname     = strtolower($this->request->action());

        // 如果有使用模板布局
        if ($this->layout) {
            $this->view->engine->layout('layout/' . $this->layout);
        }

        // 语言检测
        $lang = Lang::detect();

        $site = Config::get("site");

        // 配置信息
        $config = [
            'site'           => array_intersect_key($site, array_flip(['name', 'cdnurl', 'version', 'timezone', 'languages'])),
            'upload'         => \app\common\model\Config::upload(),
            'modulename'     => $modulename,
            'controllername' => $controllername,
            'actionname'     => $actionname,
            'jsname'         => 'frontend/' . str_replace('.', '/', $controllername),
            'moduleurl'      => rtrim(url("/{$modulename}", '', false), '/'),
            'language'       => $lang,
        ];

        //不使用SESSION， 用登录后存储的COOKIE 渲染初始头像等信息【也可使用JS】
        if (!empty(\think\Cookie::get('staffInfo')) && ($staffInfo = json_decode(\think\Cookie::get('staffInfo'), true))) {
            $this->assign('staffInfo', $staffInfo);
        }
        if (!empty(\think\Cookie::get('oscInfo')) && ($oscInfo = json_decode(\think\Cookie::get('oscInfo'), true))) {
            $this->assign('oscInfo', $oscInfo);
        }
        $this->loadlang($controllername);
        $this->assign('site', $site);
        $this->assign('config', $config);
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

    //添加页面需要的SCRIPTS
    protected function scripts($scripts = null)
    {
        if (!is_null($scripts)) {
            if (!is_array($scripts)) {
                $scripts = array($scripts);
            }

            foreach ($scripts as $key => $script) {
                //链接 不以HTTP开关 添加头
                if (mb_strpos($script, 'http') !== 0 && mb_strpos($script, 'assets') === false) {
                    $script = __CDN__ . '/assets' . ltrim($script, '/');
                }
                array_push($this->pageScripts, $script);
            }

            $this->view->assign('pageScripts', $this->pageScripts);
        }
    }

    //添加页面需要的 JS
    protected function js($js = '')
    {
        $this->pageJs = $this->pageJs . PHP_EOL . $js;
        $this->view->assign('pageJs', $this->pageJs);
    }
}
