<?php

namespace app\admin\command;

use app\admin\model\Admin;
use app\admin\model\CmdRecords;
use app\admin\model\Manifest;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;
use think\Db;

class Stocksurplus extends Command
{
    protected $model         = null;
    protected $linesPerExcel = 20000;
    protected $batchLimit    = 2000;

    protected function configure()
    {
        $this
            ->setName('stocksurplus')
            ->addOption('force', 'f', Option::VALUE_OPTIONAL, 'force override', false)
            ->addOption('record', 'r', option::VALUE_REQUIRED, 'command record id')
            ->setDescription('Generate customer ordered items summary report');
    }

    /**
     * 分批查询记录
     * 按记录数划分为不同EXCEL文件
     * 打包文件，删除EXCEL
     * 记录压缩文件位置
     * 注意中文路径的转换，存储文件时转换，存入DB时未转换
     */
    protected function execute(Input $input, Output $output)
    {
        //覆盖安装
        $force    = $input->getOption('force');
        $recordId = $input->getOption('record');

        $cmdRecord = CmdRecords::find($recordId);
        if ($cmdRecord == null) {
            return;
        }
        //开始毫秒数
        $startMTime            = microtime(true);
        $cmdRecord->createtime = time();
        $cmdRecord->save();

        $params = json_decode($cmdRecord->params, true);

        //参数异常，取消生成
        if (!isset($params['where']) || !isset($params['extraWhere'])) {
            $processJson = array(
                'completedCount' => 0,
                'total'          => 0,
                'status'         => CmdRecords::STATUS_FAILED,
                'statusText'     => CmdRecords::STATUS_FAILED,
            );
            $cmdRecord->process = json_encode($processJson);
            $cmdRecord->status  = CmdRecords::STATUS_FAILED;
            $cmdRecord->save();
            return false;
        } else {
            $wheres     = $params['where'];
            $extraWhere = $params['extraWhere'];
        }

        //初始化进度条，防止过长等待
        //进度信息
        $processJson = array(
            'completedCount' => 0,
            'total'          => 0,
            'status'         => CmdRecords::STATUS_PROCESSING,
            'statusText'     => CmdRecords::STATUS_PROCESSING,
        );
        $cmdRecord->delayUpdateProcessInfo(0, $processJson, false);

        $where      = [];
        $otherWhere = [];
        $where      = $wheres;
        unset($where['sl.sltime']);
        $otherWhere  = $where;
        $nowDate     = date('Y-m-d');
        $etime       = date('Y-m-d', $wheres['sl.sltime'][1][1]);
        $otherSlData = '';
        if ($nowDate > $etime) {
            $otherWhere['sl.sltime'] = ['between', [strtotime($etime . '23:59:59')+1, strtotime($nowDate . '23:59:59')]];

            $otherSlData = db('wm_stocklog')->alias('sl')
                ->field('p.pro_id, p.pro_name, p.pro_stock, p.pro_code, p.pro_spec, p.pro_cat1,sl.*, lot.lotnum, lot.lstock, lot.lcost, lot.lprice, u.name as uname')
                ->join('yjy_wm_lotnum lot', 'sl.slotid = lot.lot_id', 'LEFT')
                ->join('yjy_project p', 'lot.lpro_id = p.pro_id', 'LEFT')
                ->join('yjy_unit u', 'p.pro_unit = u.id', 'LEFT')
                ->where($otherWhere)
                ->order('p.pro_id', 'ASC')
                ->select();

            $otherSlData = Manifest::changepoolsArrDeal($otherSlData, '1');

        }

        $proData = db('project')->alias('p')
            ->field('p.pro_id, p.pro_name, p.pro_code, p.pro_unit, p.pro_stock, p.pro_spec, p.pro_cat1, u.name as uname')
            ->join('yjy_wm_lotnum l', 'p.pro_id = l.lpro_id', 'LEFT')
            ->join('yjy_unit u', 'p.pro_unit = u.id', 'LEFT')
            ->where('p.pro_type', '<>', '9')
            ->where($where)
            ->order('p.pro_id', 'DESC')
            ->select();

        $slData = db('wm_stocklog')->alias('sl')
            ->field('p.pro_id, p.pro_name, p.pro_stock, p.pro_code, p.pro_spec, p.pro_cat1,sl.*, lot.lotnum, lot.lstock, lot.lcost, lot.lprice, u.name as uname')
            ->join('yjy_wm_lotnum lot', 'sl.slotid = lot.lot_id', 'LEFT')
            ->join('yjy_project p', 'lot.lpro_id = p.pro_id', 'LEFT')
            ->join('yjy_unit u', 'p.pro_unit = u.id', 'LEFT')
            ->where($wheres)
            ->order('p.pro_id', 'ASC')
            ->select();

        $datasss = Manifest::changepoolsArrDeal($slData, '1');

        $proDatas        = [];
        $mergeData       = [];
        $mergeDatas      = [];
        $mergeOtherData  = [];
        $mergeOtherDatas = [];
        $issetOtherData  = ''; //用于模板判断 otherSlData 数据是否存在
        if ($proData && $datasss) {
            foreach ($proData as $key => $value) {
                $proDatas[$value['pro_id']] = $value;
                // $slDatas[$value['lot_id']] = 0;
            }

            foreach ($proDatas as $k => $v) {
                if (isset($datasss[$k])) {
                    $mergeData[] = array_merge($proDatas[$k], $datasss[$k]);
                } else {
                    $mergeData[] = $v;
                }

                if ($otherSlData) {
                    if (isset($otherSlData[$k])) {
                        $mergeOtherData[] = array_merge($proDatas[$k], $otherSlData[$k]);
                    } else {
                        $mergeOtherData[] = $v;
                    }
                }

            }

        }

// var_dump($nowDate);die();
        if ($mergeData && $mergeOtherData) {
            $issetOtherData = '1';
            foreach ($mergeData as $k => $v) {
                $mergeDatas[$v['pro_id']] = $v;
            }
            foreach ($mergeOtherData as $k => $v) {
                $mergeOtherDatas[$v['pro_id']] = $v;
            }
            $mergeDatas      = Manifest::stockTypeDeal($mergeDatas);
            $mergeOtherDatas = Manifest::stockTypeDeal($mergeOtherDatas);

            foreach ($mergeDatas as $k => $v) {
                //将Other数据存入slData
                $surplusProData[$k] = db('project')->alias('p')
                    ->field('p.pro_id, l.lot_id, l.lcost, l.lprice, l.lstock')
                    ->join('yjy_wm_lotnum l', 'p.pro_id = l.lpro_id', 'LEFT')
                    ->where('l.lstock', '>', '0')
                    ->where('p.pro_id', $k)
                    ->select();

                $mergeDatas[$k]['otherjh']         = $mergeOtherDatas[$k]['jh'];
                $mergeDatas[$k]['otherjhallcost']  = $mergeOtherDatas[$k]['jhallcost'];
                $mergeDatas[$k]['otherjhallprice'] = $mergeOtherDatas[$k]['jhallprice'];

                $mergeDatas[$k]['otherqtrk']         = $mergeOtherDatas[$k]['qtrk'];
                $mergeDatas[$k]['otherqtrkallcost']  = $mergeOtherDatas[$k]['qtrkallcost'];
                $mergeDatas[$k]['otherqtrkallprice'] = $mergeOtherDatas[$k]['qtrkallprice'];

                $mergeDatas[$k]['otherdbrk']         = $mergeOtherDatas[$k]['dbrk'];
                $mergeDatas[$k]['otherdbrkallcost']  = $mergeOtherDatas[$k]['dbrkallcost'];
                $mergeDatas[$k]['otherdbrkallprice'] = $mergeOtherDatas[$k]['dbrkallprice'];

                $mergeDatas[$k]['otherpyrk']         = $mergeOtherDatas[$k]['pyrk'];
                $mergeDatas[$k]['otherpyrkallcost']  = $mergeOtherDatas[$k]['pyrkallcost'];
                $mergeDatas[$k]['otherpyrkallprice'] = $mergeOtherDatas[$k]['pyrkallprice'];

                $mergeDatas[$k]['otherthrk']         = $mergeOtherDatas[$k]['thrk'];
                $mergeDatas[$k]['otherthrkallcost']  = $mergeOtherDatas[$k]['thrkallcost'];
                $mergeDatas[$k]['otherthrkallprice'] = $mergeOtherDatas[$k]['thrkallprice'];

                $mergeDatas[$k]['otherrkcj']         = $mergeOtherDatas[$k]['rkcj'];
                $mergeDatas[$k]['otherrkcjallcost']  = $mergeOtherDatas[$k]['rkcjallcost'];
                $mergeDatas[$k]['otherrkcjallprice'] = $mergeOtherDatas[$k]['rkcjallprice'];

                $mergeDatas[$k]['otherpkcj']         = $mergeOtherDatas[$k]['pkcj'];
                $mergeDatas[$k]['otherpkcjallcost']  = $mergeOtherDatas[$k]['pkcjallcost'];
                $mergeDatas[$k]['otherpkcjallprice'] = $mergeOtherDatas[$k]['pkcjallprice'];

                $mergeDatas[$k]['othergqcj']         = $mergeOtherDatas[$k]['gqcj'];
                $mergeDatas[$k]['othergqcjallcost']  = $mergeOtherDatas[$k]['gqcjallcost'];
                $mergeDatas[$k]['othergqcjallprice'] = $mergeOtherDatas[$k]['gqcjallprice'];

                $mergeDatas[$k]['otherqtcj']         = $mergeOtherDatas[$k]['qtcj'];
                $mergeDatas[$k]['otherqtcjallcost']  = $mergeOtherDatas[$k]['qtcjallcost'];
                $mergeDatas[$k]['otherqtcjallprice'] = $mergeOtherDatas[$k]['qtcjallprice'];

                $mergeDatas[$k]['otherly']         = $mergeOtherDatas[$k]['ly'];
                $mergeDatas[$k]['otherlyallcost']  = $mergeOtherDatas[$k]['lyallcost'];
                $mergeDatas[$k]['otherlyallprice'] = $mergeOtherDatas[$k]['lyallprice'];

                $mergeDatas[$k]['otherll']         = $mergeOtherDatas[$k]['ll'];
                $mergeDatas[$k]['otherllallcost']  = $mergeOtherDatas[$k]['llallcost'];
                $mergeDatas[$k]['otherllallprice'] = $mergeOtherDatas[$k]['llallprice'];

                $mergeDatas[$k]['otherfy']         = $mergeOtherDatas[$k]['fy'];
                $mergeDatas[$k]['otherfyallcost']  = $mergeOtherDatas[$k]['fyallcost'];
                $mergeDatas[$k]['otherfyallprice'] = $mergeOtherDatas[$k]['fyallprice'];

                $mergeDatas[$k]['othercy']         = $mergeOtherDatas[$k]['cy'];
                $mergeDatas[$k]['othercyallcost']  = $mergeOtherDatas[$k]['cyallcost'];
                $mergeDatas[$k]['othercyallprice'] = $mergeOtherDatas[$k]['cyallprice'];

            }
            foreach ($surplusProData as $key => $value) {

                $mergeDatas[$key]['allSurplusCost']  = 0;
                $mergeDatas[$key]['allSurplusPrice'] = 0;
                foreach ($value as $k => $va) {
                    $mergeDatas[$key]['allSurplusCost'] += $va['lcost'] * $va['lstock'];
                    $mergeDatas[$key]['allSurplusPrice'] += $va['lprice'] * $va['lstock'];
                }

            }

            // $fristPrice = 0;
            foreach ($mergeDatas as $k => $v) {

                $mergeDatas[$k]['beginStock'] = intval($v['pro_stock'] - $v['jh'] - $v['otherjh'] - $v['qtrk'] - $v['otherqtrk'] - $v['dbrk'] - $v['otherdbrk'] - $v['pyrk'] - $v['otherpyrk'] - $v['thrk'] - $v['otherthrk'] + $v['rkcj'] + $v['otherrkcj'] + $v['pkcj'] + $v['otherpkcj'] + $v['gqcj'] + $v['othergqcj'] + $v['qtcj'] + $v['otherqtcj'] + $v['ly'] + $v['otherly'] + $v['ll'] + $v['otherll'] + $v['fy'] + $v['otherfy'] - $v['cy'] - $v['othercy']);

                $mergeDatas[$k]['beginCost'] = bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd($v['allSurplusCost'], -$v['jhallcost'], 4), -$v['otherjhallcost'], 4), -$v['qtrkallcost'], 4), -$v['otherqtrkallcost'], 4), -$v['dbrkallcost'], 4), -$v['otherdbrkallcost'], 4), -$v['pyrkallcost'], 4), -$v['otherpyrkallcost'], 4), -$v['thrkallcost'], 4), -$v['otherthrkallcost'], 4), $v['rkcjallcost'], 4), $v['otherrkcjallcost'], 4), $v['pkcjallcost'], 4), $v['otherpkcjallcost'], 4), $v['gqcjallcost'], 4), $v['othergqcjallcost'], 4), $v['qtcjallcost'], 4), $v['otherqtcjallcost'], 4), $v['lyallcost'], 4), $v['otherlyallcost'], 4), $v['llallcost'], 4), $v['otherllallcost'], 4), $v['fyallcost'], 4), $v['otherfyallcost'], 4), -$v['cyallcost'], 4), -$v['othercyallcost'], 4);

                $mergeDatas[$k]['nowEnterStock'] = intval($v['jh'] + $v['qtrk'] + $v['dbrk'] + $v['pyrk'] + $v['thrk'] - $v['rkcj'] - $v['pkcj'] - $v['gqcj'] - $v['qtcj']);
                $mergeDatas[$k]['nowOutStock']   = intval($v['ly'] + $v['ll'] + $v['fy'] - $v['cy']);

                $mergeDatas[$k]['nowEnterCost'] = bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd($v['jhallcost'], $v['qtrkallcost'], 4), $v['dbrkallcost'], 4), $v['pyrkallcost'], 4), $v['thrkallcost'], 4), -$v['rkcjallcost'], 4), -$v['pkcjallcost'], 4), -$v['gqcjallcost'], 4), -$v['qtcjallcost'], 4);
                $mergeDatas[$k]['nowOutCost']   = bcadd(bcadd(bcadd($v['lyallcost'], $v['llallcost'], 4), $v['fyallcost'], 4), -$v['cyallcost'], 4);

                // $mergeDatas[$k]['fristPrice'] = intval(100 * ($v['allSurplusPrice'] - $v['jhallprice'] - $v['otherjhallprice'] - $v['qtrkallprice'] - $v['otherqtrkallprice'] - $v['dbrkallprice'] - $v['otherdbrkallprice'] - $v['pyrkallprice'] - $v['otherpyrkallprice'] - $v['thrkallprice'] - $v['otherthrkallprice'] + $v['rkcjallprice'] + $v['otherrkcjallprice'] + $v['pkcjallprice'] + $v['otherpkcjallprice'] + $v['gqcjallprice'] + $v['othergqcjallprice'] + $v['qtcjallprice'] + $v['otherqtcjallprice'] + $v['lyallprice'] + $v['otherlyallprice'] + $v['llallprice'] + $v['otherllallprice'])) / 100;
                $mergeDatas[$k]['beginPrice'] = bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd($v['allSurplusPrice'], -$v['jhallprice'], 2), -$v['otherjhallprice'], 2), -$v['qtrkallprice'], 2), -$v['otherqtrkallprice'], 2), -$v['dbrkallprice'], 2), -$v['otherdbrkallprice'], 2), -$v['pyrkallprice'], 2), -$v['otherpyrkallprice'], 2), -$v['thrkallprice'], 2), -$v['otherthrkallprice'], 2), $v['rkcjallprice'], 2), $v['otherrkcjallprice'], 2), $v['pkcjallprice'], 2), $v['otherpkcjallprice'], 2), $v['gqcjallprice'], 2), $v['othergqcjallprice'], 2), $v['qtcjallprice'], 2), $v['otherqtcjallprice'], 2), $v['lyallprice'], 2), $v['otherlyallprice'], 2), $v['llallprice'], 2), $v['otherllallprice'], 2), $v['fyallprice'], 2), $v['otherfyallprice'], 2), -$v['cyallprice'], 2), -$v['othercyallprice'], 2);

                $mergeDatas[$k]['endStock'] = intval($v['pro_stock'] - $v['otherjh'] - $v['otherqtrk'] - $v['otherdbrk'] - $v['otherpyrk'] - $v['otherthrk'] + $v['otherrkcj'] + $v['otherpkcj'] + $v['othergqcj'] + $v['otherqtcj'] + $v['otherly'] + $v['otherll'] + $v['otherfy'] - $v['othercy']);

                $mergeDatas[$k]['endCost'] = bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd($v['allSurplusCost'], -$v['otherjhallcost'], 4), -$v['otherqtrkallcost'], 4), -$v['otherdbrkallcost'], 4), -$v['otherpyrkallcost'], 4), -$v['otherthrkallcost'], 4), $v['otherrkcjallcost'], 4), $v['otherpkcjallcost'], 4), $v['othergqcjallcost'], 4), $v['otherqtcjallcost'], 4), $v['otherlyallcost'], 4), $v['otherllallcost'], 4), $v['otherfyallcost'], 4), -$v['othercyallcost'], 4);

                $mergeDatas[$k]['endPrice'] = bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd($v['allSurplusPrice'], -$v['otherjhallprice'], 2), -$v['otherqtrkallprice'], 2), -$v['otherdbrkallprice'], 2), -$v['otherpyrkallprice'], 2), -$v['otherthrkallprice'], 2), $v['otherrkcjallprice'], 2), $v['otherpkcjallprice'], 2), $v['othergqcjallprice'], 2), $v['otherqtcjallprice'], 2), $v['otherlyallprice'], 2), $v['otherllallprice'], 2), $v['otherfyallprice'], 2), -$v['othercyallprice'], 2);
                // floor
                // $fristCost = round($fristCost * 100)/ 100;
                // var_dump($v);
            }
            // var_dump(bcadd($left=1.0321456, $right=0.0243456, 7));

        } elseif ($mergeData && empty($mergeOtherData)) {
            foreach ($mergeData as $k => $v) {
                $mergeDatas[$v['pro_id']] = $v;
            }
            $mergeDatas = Manifest::stockTypeDeal($mergeDatas);
            foreach ($mergeDatas as $k => $v) {
                $surplusProData[$k] = db('project')->alias('p')
                    ->field('p.pro_id, l.lot_id, l.lcost, l.lprice, l.lstock')
                    ->join('yjy_wm_lotnum l', 'p.pro_id = l.lpro_id', 'LEFT')
                    ->where('l.lstock', '>', '0')
                    ->where('p.pro_id', $k)
                    ->select();

            }
            foreach ($surplusProData as $key => $value) {

                $mergeDatas[$key]['allSurplusCost']  = 0;
                $mergeDatas[$key]['allSurplusPrice'] = 0;
                foreach ($value as $k => $va) {
                    $mergeDatas[$key]['allSurplusCost'] += $va['lcost'] * $va['lstock'];
                    $mergeDatas[$key]['allSurplusPrice'] += $va['lprice'] * $va['lstock'];
                }

            }

            foreach ($mergeDatas as $k => $v) {

                $mergeDatas[$k]['beginStock'] = intval($v['pro_stock'] - $v['jh'] - $v['qtrk'] - $v['dbrk'] - $v['pyrk'] - $v['thrk'] + $v['rkcj'] + $v['pkcj'] + $v['gqcj'] + $v['qtcj'] + $v['ly'] + $v['ll'] + $v['fy'] - $v['cy']);

                $mergeDatas[$k]['beginCost'] = bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd($v['allSurplusCost'], -$v['jhallcost'], 4), -$v['qtrkallcost'], 4), -$v['dbrkallcost'], 4), -$v['pyrkallcost'], 4), -$v['thrkallcost'], 4), $v['rkcjallcost'], 4), $v['pkcjallcost'], 4), $v['gqcjallcost'], 4), $v['qtcjallcost'], 4), $v['lyallcost'], 4), $v['llallcost'], 4), $v['fyallcost'], 4), -$v['cyallcost'], 4);

                $mergeDatas[$k]['beginPrice'] = bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd($v['allSurplusPrice'], -$v['jhallprice'], 2), -$v['qtrkallprice'], 2), -$v['dbrkallprice'], 2), -$v['pyrkallprice'], 2), -$v['thrkallprice'], 2), $v['rkcjallprice'], 2), $v['pkcjallprice'], 2), $v['gqcjallprice'], 2), $v['qtcjallprice'], 2), $v['lyallprice'], 2), $v['llallprice'], 2), $v['fyallprice'], 2), -$v['cyallprice'], 2);

                $mergeDatas[$k]['nowEnterStock'] = intval($v['jh'] + $v['qtrk'] + $v['dbrk'] + $v['pyrk'] + $v['thrk'] - $v['rkcj'] - $v['pkcj'] - $v['gqcj'] - $v['qtcj']);
                $mergeDatas[$k]['nowOutStock']   = intval($v['ly'] + $v['ll'] + $v['fy'] - $v['cy']);

                $mergeDatas[$k]['nowEnterCost'] = bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd($v['jhallcost'], $v['qtrkallcost'], 4), $v['dbrkallcost'], 4), $v['pyrkallcost'], 4), $v['thrkallcost'], 4), -$v['rkcjallcost'], 4), -$v['pkcjallcost'], 4), -$v['gqcjallcost'], 4), -$v['qtcjallcost'], 4);
                $mergeDatas[$k]['nowOutCost']   = bcadd(bcadd(bcadd($v['lyallcost'], $v['llallcost'], 4), $v['fyallcost'], 4), -$v['cyallcost'], 4);

                $mergeDatas[$k]['endStock'] = $v['pro_stock'];
                $mergeDatas[$k]['endCost']  = $v['allSurplusCost'];
                $mergeDatas[$k]['endPrice'] = $v['allSurplusPrice'];
            }

        }

        $total = '';
        $total = count($mergeDatas);

        // var_dump($total);
        //获取职员缓存信息
        // $adminModel     = new Admin;
        $briefAdminList = Admin::getAdminCache(Admin::ADMIN_BRIEF_CACHE_KEY);

        $maxBatchCount  = ceil($total / $this->batchLimit);
        $maxExcelCount  = ceil($total / $this->linesPerExcel);
        $currentCount   = 0;
        $currentBatchNo = 1;
        $currentExcelNo = 1;

        $excelDir = realpath(__DIR__ . '/../../data/reports/excels') . '/';
        $namePre  = '产品库存结余表' . str_pad((string) $recordId, 11, '0', STR_PAD_LEFT) . '_';

        //进度信息
        $processJson = array(
            'completedCount' => $currentCount,
            'total'          => $total,
            'status'         => CmdRecords::STATUS_PROCESSING,
            'statusText'     => CmdRecords::STATUS_PROCESSING,
        );
        $cmdRecord->delayUpdateProcessInfo($currentCount, $processJson, false);
        $currentExcel      = new PHPExcel();
        $currentExcelSheet = $currentExcel->getActiveSheet();
        //头部标题填充
        $currentLineNo = $this->generateHeader($currentExcelSheet, $namePre . $currentExcelNo, $cmdRecord);

        $batchOffset = ($currentBatchNo - 1) * $this->batchLimit;

        foreach ($mergeDatas as $key => $rowData) {

            $currentCount++;
            $currentLineNo++;

            //个数超过当前EXCEL的设定限制后，生成新的EXCEL文档
            if ($currentCount > ($currentExcelNo * $this->linesPerExcel)) {
                if ($currentExcel) {
                    $PHPWriter     = PHPExcel_IOFactory::createWriter($currentExcel, 'Excel2007');
                    $excelFileName = iconv('utf-8', 'gb2312', $namePre) . $currentExcelNo . '.xlsx';
                    $excelPath     = $excelDir . $excelFileName;
                    $PHPWriter->save($excelPath);
                    unset($PHPWriter);
                    $currentExcel->Destroy();
                    unset($currentExcel);
                }
                $currentExcelNo++;

                $currentExcel      = new PHPExcel();
                $currentExcelSheet = $currentExcel->getActiveSheet();
                //头部标题填充
                $currentLineNo = $this->generateHeader($currentExcelSheet, $namePre . $currentExcelNo, $cmdRecord);
                $currentLineNo++;
            }

            $this->generateRow($currentExcelSheet, $currentLineNo, $rowData);

            // var_dump($stockDetailData);

            //进度信息
            $processJson = array(
                'completedCount' => $currentCount,
                'total'          => $total,
                'status'         => CmdRecords::STATUS_PROCESSING,
                'statusText'     => CmdRecords::STATUS_PROCESSING,
            );
            $cmdRecord->delayUpdateProcessInfo($currentCount, $processJson, true);
        }

        $PHPWriter     = PHPExcel_IOFactory::createWriter($currentExcel, 'Excel2007');
        $excelFileName = iconv('utf-8', 'gb2312', $namePre) . $currentExcelNo . '.xlsx';
        $excelPath     = $excelDir . $excelFileName;
        $PHPWriter->save($excelPath);
        unset($PHPWriter);
        //进度信息--打包中
        $processJson = array(
            'completedCount' => $currentCount,
            'total'          => $total,
            'status'         => CmdRecords::STATUS_PROCESSING,
            //打包中
            'statusText'     => 'Packing',
        );
        $cmdRecord->delayUpdateProcessInfo($currentCount, $processJson, false);
        if (chdir($excelDir) === false) {
            // throw new Exception();
        }
        $targetCompressFile = '../compressed_files/' . iconv('utf-8', 'gb2312', rtrim($namePre, '_')) . '.tgz';
        $excelFilePattern   = '*' . str_pad((string) $recordId, 11, '0', STR_PAD_LEFT) . '*';
        system('tar -czvf ' . $targetCompressFile . ' ' . $excelFilePattern);
        chmod($targetCompressFile, 0775);

        array_map(function ($filePath) {
            @unlink($filePath);
        }, glob($excelFilePattern));

        $cmdRecord->filepath = '/reports/compressed_files/' . rtrim($namePre, '_') . '.tgz';
        $cmdRecord->status   = CmdRecords::STATUS_COMPLETED;
        $cmdRecord->save();

        //进度信息--完成
        $cmdRecord->endtime  = time();
        $cmdRecord->costtime = microtime(true) - $startMTime;
        $processJson         = array(
            'completedCount' => $currentCount,
            'total'          => $currentCount,
            'status'         => CmdRecords::STATUS_COMPLETED,
            //打包中
            'statusText'     => CmdRecords::STATUS_COMPLETED,
        );
        $cmdRecord->delayUpdateProcessInfo($currentCount, $processJson, false);

        $output->info("Generate Successed!");
    }

    //@return int 起始行(不包括)
    private function generateHeader(&$currentExcelSheet, $title, &$cmdRecord)
    {
        //column style     18列

        $currentExcelSheet->mergeCells('A1:C1');

        $currentExcelSheet->mergeCells('A2:A3');
        $currentExcelSheet->mergeCells('B2:B3');
        $currentExcelSheet->mergeCells('C2:C3');
        $currentExcelSheet->mergeCells('D2:D3');
        $currentExcelSheet->mergeCells('E2:E3');

        $currentExcelSheet->mergeCells('F2:K2');

        $currentExcelSheet->setCellValue('A1', 'feature: ' . $cmdRecord->feature_value);
        $currentExcelSheet->mergeCells('D1:J1');
        $currentExcelSheet->getStyle('D1')->getFont()->setBold(true)->setSize(20);
        $currentExcelSheet->setCellValue('D1', $title);
        $currentExcelSheet->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $currentExcelSheet->mergeCells('K1:O1');
        $currentExcelSheet->setCellValue('K1', date('Y-m-d H:i'));

        $currentExcelSheet->setCellValue('A2', '产品编号');
        $currentExcelSheet->setCellValue('B2', '产品名称');
        $currentExcelSheet->setCellValue('C2', '规格');
        $currentExcelSheet->setCellValue('D2', '单位');
        $currentExcelSheet->setCellValue('E2', '现有库存');
        $currentExcelSheet->setCellValue('F2', '库存变动汇总');

        $currentExcelSheet->setCellValue('F3', '期初库存');
        $currentExcelSheet->setCellValue('G3', '参考成本');
        $currentExcelSheet->setCellValue('H3', '应销金额');

        $currentExcelSheet->setCellValue('I3', '本期入库');
        $currentExcelSheet->setCellValue('J3', '参考成本');
        $currentExcelSheet->setCellValue('K3', '本期出库');
        $currentExcelSheet->setCellValue('L3', '参考成本');

        $currentExcelSheet->setCellValue('M3', '期末库存');
        $currentExcelSheet->setCellValue('N3', '参考成本');
        $currentExcelSheet->setCellValue('O3', '应销金额');

        $letter = array('A2', 'B2', 'C2', 'D2', 'E2', 'F2', 'F3', 'G3', 'H3', 'I3', 'J3', 'K3', 'L3', 'M3', 'N3', 'O3');
        foreach ($letter as $key => $value) {
            //加粗、居中
            $currentExcelSheet->getStyle($value)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $currentExcelSheet->getStyle($value)->getFont()->setBold(true);
        }
        $abc = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O');
        foreach ($abc as $key => $value) {
            $currentExcelSheet->getColumnDimension($value)->setWidth(16);
        }

        return 3;
    }

    /**
     * 写入每行数据
     */
    private function generateRow(&$currentExcelSheet, $lineNo, &$rowData)
    {

        $currentExcelSheet->setCellValue('A' . $lineNo, $rowData['pro_code']);
        $currentExcelSheet->setCellValue('B' . $lineNo, $rowData['pro_name']);
        $currentExcelSheet->setCellValue('C' . $lineNo, $rowData['pro_spec']);
        $currentExcelSheet->setCellValue('D' . $lineNo, $rowData['uname']);
        $currentExcelSheet->setCellValue('E' . $lineNo, $rowData['pro_stock']);
        $currentExcelSheet->setCellValue('F' . $lineNo, $rowData["beginStock"]);
        $currentExcelSheet->setCellValue('G' . $lineNo, $rowData['beginCost']);
        $currentExcelSheet->setCellValue('H' . $lineNo, $rowData['beginPrice']);

        $currentExcelSheet->setCellValue('I' . $lineNo, $rowData['nowEnterStock']);
        $currentExcelSheet->setCellValue('J' . $lineNo, $rowData["nowEnterCost"]);
        $currentExcelSheet->setCellValue('K' . $lineNo, $rowData['nowOutStock']);
        $currentExcelSheet->setCellValue('L' . $lineNo, $rowData['nowOutCost']);

        $currentExcelSheet->setCellValue('M' . $lineNo, $rowData['endStock']);
        $currentExcelSheet->setCellValue('N' . $lineNo, $rowData['endCost']);
        $currentExcelSheet->setCellValue('O' . $lineNo, $rowData['endPrice']);

    }

}
