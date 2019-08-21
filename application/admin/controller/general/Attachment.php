<?php

namespace app\admin\controller\general;

use app\common\controller\Backend;

/**
 * 附件管理
 *
 * @icon fa fa-circle-o
 * @remark 主要用于管理上传到又拍云的数据或上传至本服务的上传数据
 */
class Attachment extends Backend
{

    protected $model = null;

    protected $noNeedRight = ['downloadtgz'];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Attachment');
    }

    /**
     * 查看
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total                                       = $this->model
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            $cdnurl = preg_replace("/\/(\w+)\.php$/i", '', $this->request->root());
            foreach ($list as $k => &$v) {
                $v['fullurl'] = ($v['storage'] == 'local' ? $cdnurl : $this->view->config['upload']['cdnurl']) . $v['url'];
            }
            unset($v);
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 选择附件
     */
    public function select()
    {
        if ($this->request->isAjax()) {
            return $this->index();
        }
        return $this->view->fetch();
    }

    /**
     * 添加
     */
    public function add()
    {
        if ($this->request->isAjax()) {
            $this->error();
        }
        return $this->view->fetch();
    }

    public function del($ids = "")
    {
        if ($ids) {
            $count = $this->model->destroy($ids);
            if ($count) {
                \think\Hook::listen("upload_after", $this);
                $this->success();
            }
        }
        $this->error(__('Parameter %s can not be empty', 'ids'));
    }

    /**
     * 下载tgz
     * @param string $type 类型
     * @param int $id cmdRecord id
     */
    public function downloadtgz($id)
    {
        $cmdRecord = model('CmdRecords')->find($id);
        if (empty($cmdRecord)) {
            return;
        }

        $admin = \think\Session::get('admin');
        if (!$this->auth->isSuperAdmin() && $cmdRecord->admin_id != $admin->id) {
            $this->error();
        }

        return $this->download($cmdRecord->filepath);
    }

    public function displayimg()
    {
        $filePath = input('filePath');
        if (empty($filePath)) {
            return;
        }

        $baseDir  = APP_PATH . 'data';
        $fileName = basename($filePath);
        $filePath = realpath($baseDir . iconv('utf8', 'gb2312', $filePath));
        ob_start();
        ob_clean();
        if (file_exists($filePath)) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            return \think\Response::create(file_get_contents($filePath), '', 200, ['Content-type' => finfo_file($finfo, $filePath)]);
        } else {
            $this->error('file not exist: ' . $filePath);
        }
    }

    private function download($filePath)
    {
        if (empty($filePath)) {
            return;
        }

        $baseDir  = APP_PATH . 'data';
        $fileName = basename($filePath);
        $filePath = realpath($baseDir . iconv('utf8', 'gb2312', $filePath));
        ob_start();
        ob_clean();
        if (file_exists($filePath)) {
            //输出文件
            header("Content-type: application/octet-stream");
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
            header("Content-Length: " . filesize($filePath));

            echo file_get_contents($filePath);
            exit();
        } else {
            $this->error('file not exist: ' . $filePath);
        }
    }

}
