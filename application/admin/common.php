<?php

use app\common\model\Category;
use fast\Form;
use fast\Tree;
use think\Db;
use app\admin\model\Job;

/**
 * 生成下拉列表
 * @param string $name
 * @param mixed $options
 * @param mixed $selected
 * @param mixed $attr
 * @return string
 */
function build_select($name, $options, $selected = [], $attr = [])
{
    $options = is_array($options) ? $options : explode(',', $options);
    $selected = is_array($selected) ? $selected : explode(',', $selected);
    return Form::select($name, $options, $selected, $attr);
}

/**
 * 生成单选按钮组
 * @param string $name
 * @param array $list
 * @param mixed $selected
 * @return string
 */
function build_radios($name, $list = [], $selected = null)
{
    $html = [];
    $selected = is_null($selected) ? key($list) : $selected;
    $selected = is_array($selected) ? $selected : explode(',', $selected);
    foreach ($list as $k => $v)
    {
        $html[] = sprintf(Form::label("{$name}-{$k}", "%s {$v}"), Form::radio($name, $k, in_array($k, $selected), ['id' => "{$name}-{$k}"]));
    }
    return implode(' ', $html);
}

/**
 * 生成复选按钮组
 * @param string $name
 * @param array $list
 * @param mixed $selected
 * @return string
 */
function build_checkboxs($name, $list = [], $selected = null)
{
    $html = [];
    $selected = is_null($selected) ? [] : $selected;
    $selected = is_array($selected) ? $selected : explode(',', $selected);
    foreach ($list as $k => $v)
    {
        $html[] = sprintf(Form::label("{$name}-{$k}", "%s {$v}"), Form::checkbox($name, $k, in_array($k, $selected), ['id' => "{$name}-{$k}"]));
    }
    return implode(' ', $html);
}

/**
 * 生成分类下拉列表框
 * @param string $name
 * @param string $type
 * @param mixed $selected
 * @param array $attr
 * @return string
 */
function build_category_select($name, $type, $selected = null, $attr = [], $header = [])
{
    $tree = Tree::instance();
    $tree->init(Category::getCategoryArray($type), 'pid');
    $categorylist = $tree->getTreeList($tree->getTreeArray(0), 'name');
    $categorydata = $header ? $header : [];
    foreach ($categorylist as $k => $v)
    {
        $categorydata[$v['id']] = $v['name'];
    }
    $attr = array_merge(['id' => "c-{$name}", 'class' => 'form-control selectpicker'], $attr);
    return build_select($name, $categorydata, $selected, $attr);
}

/**
 * 生成表格操作按钮栏
 * @param array $btns 按钮组
 * @param array $attr 按钮属性值
 * @return string
 */
function build_toolbar($btns = NULL, $attr = [])
{
    $auth = \app\admin\library\Auth::instance();
    $controller = strtolower(think\Request::instance()->controller());

    /**
     * modified by Leekaen, BUG修复
     * check 时 auth/admin/add型, 原$controller为 auth.admin/add型
     */
    $controller = str_replace('.', '/', $controller);

    $btns = $btns ? $btns : ['refresh', 'add', 'edit', 'del'];
    $btns = is_array($btns) ? $btns : explode(',', $btns);
    $index = array_search('delete', $btns);
    if ($index !== FALSE)
    {
        $btns[$index] = 'del';
    }
    $btnAttr = [
        'refresh' => ['javascript:;', 'btn btn-primary btn-refresh', 'fa fa-refresh', ''],
        'add'     => ['javascript:;', 'btn btn-success btn-add', 'fa fa-plus', __('Add')],
        'edit'    => ['javascript:;', 'btn btn-success btn-edit btn-disabled disabled', 'fa fa-pencil', __('Edit')],
        'del'     => ['javascript:;', 'btn btn-danger btn-del btn-disabled disabled', 'fa fa-trash', __('Delete')],
    ];
    $btnAttr = array_merge($btnAttr, $attr);
    $html = [];
    foreach ($btns as $k => $v)
    {
        //如果未定义或没有权限
        if (!isset($btnAttr[$v]) || ($v !== 'refresh' && !$auth->check("{$controller}/{$v}")))
        {
            continue;
        }
        list($href, $class, $icon, $text) = $btnAttr[$v];
        $html[] = '<a href="' . $href . '" class="' . $class . '" ><i class="' . $icon . '"></i> ' . $text . '</a>';
    }
    return implode(' ', $html);
}

function build_toolbar_edit($btns = NULL, $attr = [])
{
    $auth = \app\admin\library\Auth::instance();
    $controller = strtolower(think\Request::instance()->controller());

    /**
     * modified by Leekaen, BUG修复
     * check 时 auth/admin/add型, 原$controller为 auth.admin/add型
     */
    $controller = str_replace('.', '/', $controller);

    $btns = $btns ? $btns : ['refresh', 'add', 'edit', 'del'];
    $btns = is_array($btns) ? $btns : explode(',', $btns);
    $index = array_search('delete', $btns);
    if ($index !== FALSE)
    {
        $btns[$index] = 'del';
    }
    $btnAttr = [
        'refresh' => ['javascript:;', 'btn btn-primary btn-refresh', 'fa fa-refresh', ''],
        // 'add'     => ['javascript:;', 'btn btn-success btn-add', 'fa fa-plus', __('Add')],
        'edit'    => ['javascript:;', 'btn btn-success btn-edit btn-disabled disabled', 'fa fa-pencil', '库存详情'],
        // 'del'     => ['javascript:;', 'btn btn-danger btn-del btn-disabled disabled', 'fa fa-trash', __('Delete')],
    ];
    $btnAttr = array_merge($btnAttr, $attr);
    $html = [];
    foreach ($btns as $k => $v)
    {
        //如果未定义或没有权限
        if (!isset($btnAttr[$v]) || ($v !== 'refresh' && !$auth->check("{$controller}/{$v}")))
        {
            continue;
        }
        list($href, $class, $icon, $text) = $btnAttr[$v];
        $html[] = '<a href="' . $href . '" class="' . $class . '" ><i class="' . $icon . '"></i> ' . $text . '</a>';
    }
    return implode(' ', $html);
}

/**
 * 生成页面Heading
 *
 * @param string $title
 * @param string $content
 * @return string
 */
function build_heading($title = NULL, $content = NULL)
{
    if (is_null($title) && is_null($content))
    {
        $path = request()->pathinfo();
        $path = $path[0] == '/' ? $path : '/' . $path;
        // 根据当前的URI自动匹配父节点的标题和备注
        $data = Db::name('auth_rule')->where('name', $path)->field('title,remark')->find();
        if ($data)
        {
            $title = $data['title'];
            $content = $data['remark'];
        }
    }
    if (!$content)
        return '';
    return '<div class="panel-heading"><div class="panel-lead"><em>' . $title . '</em>' . $content . '</div></div>';
}

function getStockChang($date ='', $type)
{
    $data[] = [];
    return model('Stock')->saveAll($data);
}
/**
* 数字转换为中文
* @param  string|integer|float  $num  目标数字
* @param  integer $mode 模式[true:金额（默认）,false:普通数字表示]
* @param  boolean $sim 使用小写（默认）
* @return string
*/
 function number2chinese($num, $mode = true, $sim = true){
    if(!is_numeric($num)) return '含有非数字非小数点字符！';
    $char    = $sim ? array('零','一','二','三','四','五','六','七','八','九')
    : array('零','壹','贰','叁','肆','伍','陆','柒','捌','玖');
    $unit    = $sim ? array('','十','百','千','','万','亿','兆')
    : array('','拾','佰','仟','','萬','億','兆');
    $retval  = $mode ? '元':'点';
    //小数部分
    if(strpos($num, '.')){
        list($num,$dec) = explode('.', $num);
        $dec = strval(round($dec,2));
        if($mode){
            $retval .= @"{$char[$dec['0']]}角{$char[$dec['1']]}分";
        }else{
            for($i = 0,$c = strlen($dec);$i < $c;$i++) {
                $retval .= $char[$dec[$i]];
            }
        }
    }
    //整数部分
    $str = $mode ? strrev(intval($num)) : strrev($num);
    for($i = 0,$c = strlen($str);$i < $c;$i++) {
        $out[$i] = $char[$str[$i]];
        if($mode){
            $out[$i] .= $str[$i] != '0'? $unit[$i%4] : '';
                if($i>1 and $str[$i]+$str[$i-1] == 0){
                $out[$i] = '';
            }
                if($i%4 == 0){
                $out[$i] .= $unit[4+floor($i/4)];
            }
        }
    }
    $retval = join('',array_reverse($out)) . $retval;
    return $retval;
 }

function calcAge($birthDate) {
    $age = '';
    if(!empty($birthDate) && $birthDate != '0000-00-00' && $birthDate != '0000-00-00 00:00:00'){
        $age = strtotime($birthDate);
        // if($age === false){
        // 1950-01-01前
        if($age === false){
            return '';
        }
        list($y1,$m1,$d1) = explode("-",date("Y-m-d", $age));
        list($y2,$m2,$d2) = explode("-",date("Y-m-d"));
        $age = (int)($y2 - $y1);
        if((int)($m2 . $d2) < (int)($m1 . $d1)) {
            $age -= 1;
        }
        $age .= ' 岁';
    }

    return $age;
}

function getBirthDate($age) {
    return date('Y-m-d', strtotime('- ' . intval($age) . ' years', strtotime(date('Y-m-d'))));
}