<?php

namespace app\admin\controller\base;

use app\admin\model\Customer;
use app\admin\model\OperateBook;
use app\admin\model\OperatePro;
use app\admin\Model\OperateRota as MOperateRota;
use app\admin\model\Operator;
use app\admin\model\Project;
use app\common\controller\Backend;
use think\Controller;
use think\DB;
use think\Hook;
use think\Request;
use yjy\exception\TransException;

/**
 * 手术值班管理
 *
 * @icon fa fa-circle-o
 */
class Operaterota extends Backend
{

    /**
     * OperateRota模型对象
     */
    protected $model        = null;
    protected $noNeedRights = ['index'];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('OperateRota');

        $staffs = Operator::where('status', '=', 1)->order('id', 'asc')->cache(Operator::CACHE_KEY)->column('*', 'id');
        $this->view->assign('staffs', $staffs);
    }

    /**
     * 查看
     */
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name')) {
                return $this->selectpage();
            }
        }

        $targetDate = input('ort_date', date('Y-m-d'));
        $rotaTable  = $this->model->getTable();

        $list        = array();
        $bookPeriods = array();
        $rotaList = DB::table($rotaTable . ' rota')
            ->where(['ort_date' => ['=', $targetDate]])
            ->order('operator_id asc, ort_period', 'asc')
            ->select();

        foreach ($rotaList as $key => $rotaData) {
            if (!isset($list[$rotaData['operator_id']])) {
                $list[$rotaData['operator_id']] = array();
            }
            array_push($list[$rotaData['operator_id']], $rotaData);

            if ($rotaData['book_id'] != 0) {
                if (!isset($bookPeriods[$rotaData['book_id']])) {
                    $bookPeriods[$rotaData['book_id']] = array();
                }
                if (!isset($bookPeriods[$rotaData['book_id']][$rotaData['operator_id']])) {
                    $operatorName = isset($this->view->staffs[$rotaData['operator_id']]) ? $this->view->staffs[$rotaData['operator_id']]['name'] : '';

                    $bookPeriods[$rotaData['book_id']][$rotaData['operator_id']] = array('operatorId' => $rotaData['operator_id'], 'operatorName' => $operatorName, 'periods' => array());
                }

                array_push($bookPeriods[$rotaData['book_id']][$rotaData['operator_id']]['periods'], $rotaData['ort_period']);
            }
        };

        //由我发起的手术预约
        $myOperateBooks = OperateBook::alias('operate_book')
            ->with('operatePros')
            ->where([
                // 'operate_book.admin_id' => $this->view->admin->id,
                'operate_book.obk_date' => $targetDate,
            ])
            ->join(\app\admin\model\Customer::getTable() . ' customer', 'operate_book.customer_id = customer.ctm_id', 'LEFT')
            ->join(\app\admin\model\Admin::getTable() . ' admin', 'operate_book.admin_id = admin.id', 'LEFT')
            ->order('operate_book.obk_status desc, obk_id', 'desc')
            // ->column('operate_book.*, customer.ctm_name, customer.ctm_sex, customer.ctm_birthdate, customer.ctm_mobile, admin.nickname, admin.username');
            ->field('operate_book.*, customer.ctm_name, customer.ctm_sex, customer.ctm_birthdate, customer.ctm_mobile, admin.nickname, admin.username')
            ->select();

        $this->view->assign(compact('list', 'myOperateBooks', 'targetDate', 'bookPeriods'));
        $this->view->assign('bookCancelStatus', OperateBook::STATUS_CANCELED);

        return $this->view->fetch();
    }

    /**
     * 科室排班 -- 新增 / 调整
     */
    public function newschedule()
    {
        $operateConfig = include_once APP_PATH . 'operate_config.php';

        $ortDate      = input('ortDate', date('Y-m-d'));
        $ortTimeStamp = strtotime($ortDate);
        if ($ortTimeStamp === false) {
            $this->error('日期格式不正确');
        }
        if ($ortTimeStamp > strtotime('+' . $operateConfig['maxRotaDays'] . ' days')) {
            $this->error("暂不允许进行 {$operateConfig['maxRotaDays']} 天后的排班");
        }
        if ($ortTimeStamp < strtotime(date('Y-m-d'))) {
            $this->error("无法对已过去的时间进行排班");
        }

        $operateRotaOri = MOperateRota::where('ort_date', '=', $ortDate)->order('operator_id asc, ort_period', 'asc')->select();
        $operatorRotas  = array();

        foreach ($operateRotaOri as $key => $row) {
            if (!isset($operatorRotas[$row->operator_id])) {
                $operatorRotas[$row->operator_id] = array();
            }
            $operatorRotas[$row->operator_id][$row->ort_period] = [
                'book_id'    => $row->book_id,
                'ort_status' => $row->ort_status,
                'ort_id'     => $row->ort_id,
            ];
        }
        if ($this->request->isPost()) {
            $rotaData = input('rotaData', '[]');
            $rotaData = json_decode($rotaData, true);
            if ($rotaData === false) {
                $this->error('错误的数据格式');
            }
            try {
                DB::startTrans();
                $operatorRota = new MOperateRota;
                foreach ($rotaData as $key => $row) {
                    foreach ($row['timePeriods'] as $period => $periodSet) {
                        //过滤掉 已预约的时间片段
                        if ($periodSet['ort_status'] == MOperateRota::STATUS_BOOKED) {
                            continue;
                        }

                        if (empty($periodSet['ort_id'])) {
                            $operateRota              = new MOperateRota;
                            $operateRota->ort_date    = date('Y-m-d', $ortTimeStamp);
                            $operateRota->ort_period  = $period;
                            $operateRota->ort_status  = $periodSet['ort_status'];
                            $operateRota->operator_id = $row['staffId'];
                            $operateRota->book_id     = 0;
                            $operateRota->save();
                        } else {
                            MOperateRota::update(['ort_status' => $periodSet['ort_status']], ['ort_id' => $periodSet['ort_id']]);
                        }
                    }
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                $this->error($e->getMessage());
            }

            $this->success();
        }

        $operators = Operator::where([
            'status' => 1,
        ])
            ->order('id', 'asc')
            ->column('*', 'id');

        $this->view->assign('operators', $operators);
        $this->view->assign('operatorRotas', $operatorRotas);
        $this->view->assign('ortDate', $ortDate);
        $this->view->assign('timePeriods', $operateConfig['periods']);

        return $this->view->fetch();
    }

    public function book()
    {
        $targetDate = input('targetDate', false);
        $periods    = input('periods', '[]');
        if (!is_array($periods)) {
            $periods = json_decode(urldecode($periods), true);
        }
        if (!is_array($periods)) {
            $this->error('无效参数');
        }
        if ($targetDate < date('Y-m-d')) {
            $this->error('预约时间不正确');
        }

        if ($this->request->isPost()) {
            $customerId = input('customer_id', true);
            // $projectId  = input('pro_id', '');
            $proIds = $this->request->post('pro_id/a', []);

            try {
                DB::startTrans();
                $projects = Project::where(['pro_id' => ['in', $proIds]])->column('pro_name, pro_spec, pro_amount, pro_stock', 'pro_id');
                if (empty($projects)) {
                    $this->error('未找到相应预约项目，请检查后重试');
                }
                $customer = Customer::find($customerId);
                if (empty($customer)) {
                    $this->error('未找到相应顾客，请检查后重试');
                }

                $bookStartTime = '23:59';
                $bookEndTime   = '00:00';

                //创建预约
                $operateBook              = new OperateBook;
                $operateBook->customer_id = $customerId;
                $operateBook->admin_id    = $this->view->admin->id;
                //预留暂不使用 过于严格
                $operateBook->order_item_id = 0;
                $operateBook->obk_date       = $targetDate;
                $operateBook->obk_start_time = $bookStartTime;
                $operateBook->obk_end_time   = $bookEndTime;

                $operateBook->obk_status = OperateBook::STATUS_BOOKED;
                if ($operateBook->save() == false) {
                    throw new TransException('预约失败');
                }

                //更新排班表
                foreach ($periods as $operatorId => $subPeriods) {
                    $cnt      = count($subPeriods);
                    $subRotas = MOperateRota::where(['ort_date' => $targetDate, 'operator_id' => $operatorId, 'ort_status' => MOperateRota::STATUS_READY, 'ort_period' => ['in', array_keys($subPeriods)]])->select();

                    if ($cnt != count($subRotas)) {
                        throw new TransException('所选时间可能已被预约，请刷新页面确认后重试');
                    }
                    foreach ($subRotas as $key => $subRota) {
                        if ($subRota->ort_period > $bookEndTime) {
                            $bookEndTime = $subRota->ort_period;
                        }
                        if ($subRota->ort_period < $bookStartTime) {
                            $bookStartTime = $subRota->ort_period;
                        }

                        $subRota->book_id    = $operateBook->obk_id;
                        $subRota->ort_status = MOperateRota::STATUS_BOOKED;

                        if ($subRota->save() == false) {
                            throw new TransException("预约失败，请稍后重试");
                        }
                    }
                }

                //生成预约项目表，为以后可能的拓展，查询做准备
                foreach ($projects as $key => $project) {
                    $operatePro = new OperatePro;
                    $operatePro->book_id = $operateBook->obk_id;
                    $operatePro->pro_id = $project['pro_id'];
                    $operatePro->pro_name = $project['pro_name'];
                    $operatePro->pro_spec = $project['pro_spec'];

                    if ($operatePro->save() == false) {
                        throw new TransException("预约失败，请稍后重试");
                    }
                }

                $operateBook->obk_start_time = $bookStartTime;
                $operateBook->obk_end_time   = $bookEndTime;
                $operateBook->save();

                DB::commit();

                /**预约短信**/
                $content = <<<CONTENT
尊敬的【%s】：
您好， 已帮您预约【%s】的【%s】，请提前安排好时间和行程。
地址：【%s】
你，一遇见就更美
CONTENT;

                $hospitalAddr = isset($this->view->site['hospital_address']) ? $this->view->site['hospital_address'] : '';
                $content      = vsprintf($content, [$customer->ctm_name, $targetDate . ' ' . $bookStartTime, implode(', ', array_column($projects, 'pro_name')), $hospitalAddr]);
                \think\Hook::listen('book_success', $operateBook, ['mobile' => $customer->ctm_mobile, 'content' => $content]);
                /**预约短信**/

                $this->success('预约手术成功');
            } catch (TransException $e) {
                DB::rollback();
                $this->error($e->getMessage());
            } catch (\think\exception\PDOException $e) {
                DB::rollback();
                $this->error($e->getMessage());
            }
        }

        $operateConfig = include_once APP_PATH . 'operate_config.php';

        $this->view->assign('operateConfig', $operateConfig);
        $this->view->assign('targetDate', $targetDate);
        $this->view->assign('periods', $periods);
        return $this->view->fetch();
    }

    public function cancelbook()
    {
        $bookId = input('obk_id');
        $book   = OperateBook::find($bookId);

        if ($book) {
            $operateConfig = include_once APP_PATH . 'operate_config.php';
            $bookStartTime = strtotime($book->obk_date . ' ' . $book->obk_start_time);

            $cancenLimitTime = strtotime('-' . $operateConfig['cancelLimit'] . ' minutes', $bookStartTime);

            if (time() > $cancenLimitTime) {
                $this->error('无法取消【只能在开始 ' . $operateConfig['cancelLimit'] . ' 分钟前取消】');
            }

            // cancelLimitMinutes
            // 是否是超级管理员
            $admin      = \think\Session::get('admin');
            $superadmin = $this->auth->isSuperAdmin();

            if ($book->admin_id != $admin->id && !$superadmin && !$this->auth->check('base/operaterota/forcecancelbook')) {
                $this->error('你不能取消该预约');
            }

            $book->obk_status = OperateBook::STATUS_CANCELED;
            if ($book->save() !== false) {
                MOperateRota::where(['book_id' => $bookId])->update(['ort_status' => MOperateRota::STATUS_READY, 'book_id' => 0]);
            }

            $this->success('取消成功');
        } else {
            $this->error('取消失败');
        }
    }

    public function forcecancelbook()
    {
        $bookId = input('obk_id');
        $book   = OperateBook::find($bookId);

        if ($book) {
            $operateConfig = include_once APP_PATH . 'operate_config.php';
            $bookStartTime = strtotime($book->obk_date . ' ' . $book->obk_start_time);
            if (time() > $bookStartTime) {
                $this->error('无法取消【只能在开始前取消】');
            }
            $book->obk_status = OperateBook::STATUS_CANCELED;
            if ($book->save() !== false) {
                MOperateRota::where(['book_id' => $bookId])->update(['ort_status' => MOperateRota::STATUS_READY, 'book_id' => 0]);
            }

            $this->success('取消成功');
        } else {
            $this->error('取消失败');
        }
    }
}
