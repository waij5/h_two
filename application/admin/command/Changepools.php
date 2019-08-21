<?php

namespace app\admin\command;


use app\admin\model\Admin;
use app\admin\model\CmdRecords;
use app\admin\model\Report;
use app\admin\model\Manifest;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;
use think\Db;
use think\lang;

class Changepools extends Command
{
    protected $model         = null;
    protected $linesPerExcel = 20000;
    protected $batchLimit    = 2000;

    protected function configure()
    {
        $this
            ->setName('changepools')
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
        $startMTime = microtime(true);
        $cmdRecord->createtime = time();
        $cmdRecord->save();


        $params = json_decode($cmdRecord->params, true);

        //参数异常，取消生成
        if (!isset($params['where']) || !isset($params['extraWhere'])) {
            $processJson = array(
                                'completedCount' => 0,
                                'total' => 0,
                                'status' => CmdRecords::STATUS_FAILED,
                                'statusText' => CmdRecords::STATUS_FAILED,
            );
            $cmdRecord->process = json_encode($processJson);
            $cmdRecord->status = CmdRecords::STATUS_FAILED;
            $cmdRecord->save();
            return false;
        } else {
            $wheres = $params['where'];
            $extraWhere = $params['extraWhere'];
        }

        

            $where =[];
            $otherWhere =[];
            $where = $wheres;
            unset($where['sl.sltime']);
            $otherWhere = $where;
            $nowDate = date('Y-m-d');
            $etime = date('Y-m-d',$wheres['sl.sltime'][1][1]);
            $otherSlData='';
            if($nowDate > $etime){
                $otherWhere['sl.sltime'] = ['between',[strtotime($etime.'23:59:59')+1,strtotime($nowDate.'23:59:59')]];
                // $w['sl.sltime'] = ['between',[strtotime($postData['etime'].'23:59:59'),strtotime($nowDate.'23:59:59')]];

                $otherSlData = db('wm_stocklog')->alias('sl')
                                ->field('p.pro_id, p.pro_name, p.pro_stock, p.pro_code, p.pro_spec,sl.*, lot.lotnum, lot.lstock, lot.lcost, lot.lprice, u.name as uname')
                                ->join('yjy_wm_lotnum lot', 'sl.slotid = lot.lot_id', 'LEFT')
                                ->join('yjy_project p', 'lot.lpro_id = p.pro_id', 'LEFT')
                                ->join('yjy_unit u', 'p.pro_unit = u.id', 'LEFT')
                                ->where($otherWhere)
                                ->order('p.pro_id','ASC')
                                ->select();
                                
                $otherSlData = Manifest::changepoolsArrDeal($otherSlData);

            }


            
//更新20190111
/*            $proData = db('project')->alias('p')
                        ->field('p.pro_id, p.pro_name, p.pro_code, p.pro_unit, p.pro_stock, p.pro_spec, u.name as uname')
                        ->join('yjy_wm_lotnum l', 'p.pro_id = l.lpro_id', 'LEFT')
                        ->join('yjy_unit u', 'p.pro_unit = u.id', 'LEFT')
                        ->where('p.pro_type', '<>','9')
                        ->where($where)
                        ->order('p.pro_id', 'DESC')
                        ->select();*/
//更新20190111
            $slData = db('wm_stocklog')->alias('sl')
                ->field('p.pro_id, p.pro_name, p.pro_stock, p.pro_code, p.pro_spec,sl.*, lot.lotnum, lot.lstock, lot.lcost, lot.lprice, u.name as uname')
                ->join('yjy_wm_lotnum lot', 'sl.slotid = lot.lot_id', 'LEFT')
                ->join('yjy_project p', 'lot.lpro_id = p.pro_id', 'LEFT')
                ->join('yjy_unit u', 'p.pro_unit = u.id', 'LEFT')
                ->where($wheres)
                ->order('p.pro_id','ASC')
                ->select();

            $datasss = Manifest::changepoolsArrDeal($slData);

            // $proDatas =[];
            $mergeData =[];
            $mergeDatas =[];
            $mergeOtherData =[];
            $mergeOtherDatas =[];
            // $issetOtherData ='';  

//更新20190111          
            /*if($proData && $datasss){
                foreach ($proData as $key => $value) {
                    $proDatas[$value['pro_id']] = $value;
                    // $slDatas[$value['lot_id']] = 0;
                }

                foreach ($proDatas as $k => $v) {
                    if(isset($datasss[$k])){
                        $mergeData[] = array_merge($proDatas[$k], $datasss[$k]);
                    }else {
                        $mergeData[] = $v;
                    }

                    if ($otherSlData) {
                        if(isset($otherSlData[$k])){
                            $mergeOtherData[] = array_merge($proDatas[$k], $otherSlData[$k]);
                        }else{
                            $mergeOtherData[] = $v;
                        }
                    }
                    
                }


            }*/

//统计库存结余表全部金额  改为 统计日期内有变动的数据
            $mergeData = $datasss;
            $mergeOtherData = $otherSlData;
//更新20190111

// var_dump($nowDate);die();
            if($mergeData && $mergeOtherData){
                // $issetOtherData = '1';
                foreach ($mergeData as $k => $v) {
                    $mergeDatas[$v['pro_id']] = $v;
                }
                foreach ($mergeOtherData as $k => $v) {
                    $mergeOtherDatas[$v['pro_id']] = $v;
                }
                $mergeDatas = Manifest::stockTypeDeal($mergeDatas,'1');
                $mergeOtherDatas = Manifest::stockTypeDeal($mergeOtherDatas,'1');

                foreach($mergeDatas as $k => $v){           //将Other数据存入slData

//更新20190111
                    $mergeDatas[$k]['otherjh'] = isset($mergeOtherDatas[$k]['jh'])?$mergeOtherDatas[$k]['jh']:0;
                    $mergeDatas[$k]['otherqtrk'] = isset($mergeOtherDatas[$k]['qtrk'])?$mergeOtherDatas[$k]['qtrk']:0;

                    $mergeDatas[$k]['otherdbrk'] = isset($mergeOtherDatas[$k]['dbrk'])?$mergeOtherDatas[$k]['dbrk']:0;

                    $mergeDatas[$k]['otherpyrk'] = isset($mergeOtherDatas[$k]['pyrk'])?$mergeOtherDatas[$k]['pyrk']:0;

                    $mergeDatas[$k]['otherthrk'] = isset($mergeOtherDatas[$k]['thrk'])?$mergeOtherDatas[$k]['thrk']:0;

                    $mergeDatas[$k]['otherrkcj'] = isset($mergeOtherDatas[$k]['rkcj'])?$mergeOtherDatas[$k]['rkcj']:0;

                    $mergeDatas[$k]['otherpkcj'] = isset($mergeOtherDatas[$k]['pkcj'])?$mergeOtherDatas[$k]['pkcj']:0;

                    $mergeDatas[$k]['othergqcj'] = isset($mergeOtherDatas[$k]['gqcj'])?$mergeOtherDatas[$k]['gqcj']:0;

                    $mergeDatas[$k]['otherqtcj'] = isset($mergeOtherDatas[$k]['qtcj'])?$mergeOtherDatas[$k]['qtcj']:0;

                    $mergeDatas[$k]['otherly'] = isset($mergeOtherDatas[$k]['ly'])?$mergeOtherDatas[$k]['ly']:0;

                    $mergeDatas[$k]['otherll'] = isset($mergeOtherDatas[$k]['ll'])?$mergeOtherDatas[$k]['ll']:0;

                    $mergeDatas[$k]['otherfy'] = isset($mergeOtherDatas[$k]['fy'])?$mergeOtherDatas[$k]['fy']:0;

                    $mergeDatas[$k]['othercy'] = isset($mergeOtherDatas[$k]['cy'])?$mergeOtherDatas[$k]['cy']:0;

//更新20190111
                    
                }
                


                // $fristPrice = 0;
                foreach($mergeDatas as $k => $v){

                    // $mergeDatas[$k]['fristCost'] = intval(100 * ($v['allSurplusCost'] - $v['jhallcost'] - $v['otherjhallcost'] - $v['qtrkallcost'] - $v['otherqtrkallcost'] - $v['dbrkallcost'] - $v['otherdbrkallcost'] - $v['pyrkallcost'] - $v['otherpyrkallcost'] - $v['thrkallcost'] - $v['otherthrkallcost'] + $v['rkcjallcost'] + $v['otherrkcjallcost'] + $v['pkcjallcost'] + $v['otherpkcjallcost'] + $v['gqcjallcost'] + $v['othergqcjallcost'] + $v['qtcjallcost'] + $v['otherqtcjallcost']+ $v['lyallcost'] + $v['otherlyallcost'] + $v['llallcost'] + $v['otherllallcost'])) / 100;
                    $mergeDatas[$k]['beginStock'] = intval($v['pro_stock']-$v['jh']-$v['otherjh']-$v['qtrk']-$v['otherqtrk']-$v['dbrk']-$v['otherdbrk']-$v['pyrk']-$v['otherpyrk']-$v['thrk']-$v['otherthrk']+$v['rkcj']+$v['otherrkcj']+$v['pkcj']+$v['otherpkcj']+$v['gqcj']+$v['othergqcj']+$v['qtcj']+$v['otherqtcj']+$v['ly']+$v['otherly']+$v['ll']+$v['otherll']+$v['fy']+$v['otherfy']-$v['cy']-$v['othercy']);

                    


                    $mergeDatas[$k]['endStock'] = intval($v['pro_stock']-$v['otherjh']-$v['otherqtrk']-$v['otherdbrk']-$v['otherpyrk']-$v['otherthrk']+$v['otherrkcj']+$v['otherpkcj']+$v['othergqcj']+$v['otherqtcj']+$v['otherly']+$v['otherll']+$v['otherfy']-$v['othercy']);

                    
                    // var_dump($v);
                }
                 // var_dump(bcadd($left=1.0321456, $right=0.0243456, 7));
                


            }elseif($mergeData){
                foreach ($mergeData as $k => $v) {
                    $mergeDatas[$v['pro_id']] = $v;
                }
                $mergeDatas = Manifest::stockTypeDeal($mergeDatas,'1');
                               

                foreach($mergeDatas as $k => $v){

                    $mergeDatas[$k]['beginStock'] = intval($v['pro_stock']-$v['jh']-$v['qtrk']-$v['dbrk']-$v['pyrk']-$v['thrk']+$v['rkcj']+$v['pkcj']+$v['gqcj']+$v['qtcj']+$v['ly']+$v['ll']+$v['fy']-$v['cy']);

                    $mergeDatas[$k]['endStock'] = $v['pro_stock'];
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
        $namePre  = '产品变动汇总表' . str_pad((string) $recordId, 11, '0', STR_PAD_LEFT) . '_';

        //进度信息
        $processJson = array(
                            'completedCount' => $currentCount,
                            'total' => $total,
                            'status' => CmdRecords::STATUS_PROCESSING,
                            'statusText' => CmdRecords::STATUS_PROCESSING,
        );
        $cmdRecord->delayUpdateProcessInfo($currentCount, $processJson, false);
        $currentExcel      = new PHPExcel();
        $currentExcelSheet = $currentExcel->getActiveSheet();
        //头部标题填充
        $currentLineNo = $this->generateHeader($currentExcelSheet, $namePre . $currentExcelNo, $cmdRecord);

        for (; $currentBatchNo <= $maxBatchCount; $currentBatchNo++) {
            $batchOffset = ($currentBatchNo - 1) * $this->batchLimit;
            
            foreach ($mergeDatas as $key => $rowData) {
                // foreach ($rowData as $ke => $va) {
                    // $va['createtime'] = date('Y-m-d', (int)$va['expirestime']);
                    // $va['expirestime'] = date('Y-m-d', (int)$va['expirestime']);

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
                                        'total' => $total,
                                        'status' => CmdRecords::STATUS_PROCESSING,
                                        'statusText' => CmdRecords::STATUS_PROCESSING,
                    );
                    $cmdRecord->delayUpdateProcessInfo($currentCount, $processJson, true);
                // }
                
            }
        }

        $PHPWriter     = PHPExcel_IOFactory::createWriter($currentExcel, 'Excel2007');
        $excelFileName = iconv('utf-8', 'gb2312', $namePre) . $currentExcelNo . '.xlsx';
        $excelPath     = $excelDir . $excelFileName;
        $PHPWriter->save($excelPath);
        unset($PHPWriter);
        
        //进度信息--打包中
        $processJson = array(
                            'completedCount' => $currentCount,
                            'total' => $total,
                            'status' => CmdRecords::STATUS_PROCESSING,
                            //打包中
                            'statusText' => 'Packing',
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
        $cmdRecord->status = CmdRecords::STATUS_COMPLETED;
        $cmdRecord->save();

        //进度信息--完成
        $cmdRecord->endtime = time();
        $cmdRecord->costtime = microtime(true) - $startMTime;
        $processJson = array(
                            'completedCount' => $currentCount,
                            'total' => $currentCount,
                            'status' => CmdRecords::STATUS_COMPLETED,
                            //打包中
                            'statusText' => CmdRecords::STATUS_COMPLETED,
        );
        $cmdRecord->delayUpdateProcessInfo($currentCount, $processJson, false);

        $output->info("Generate Successed!");
    }

    //@return int 起始行(不包括)
    private function generateHeader(&$currentExcelSheet, $title, &$cmdRecord)
    {
        //column style     32列AF
        
        $currentExcelSheet->mergeCells('A1:C1');

        $currentExcelSheet->mergeCells('A2:A3');
        $currentExcelSheet->mergeCells('B2:B3');
        $currentExcelSheet->mergeCells('C2:C3');
        $currentExcelSheet->mergeCells('D2:D3');
        $currentExcelSheet->mergeCells('E2:G2');

        $currentExcelSheet->mergeCells('H2:T2');


        $currentExcelSheet->setCellValue('A1', 'feature: ' . $cmdRecord->feature_value);
        $currentExcelSheet->mergeCells('D1:O1');
        $currentExcelSheet->getStyle('D1')->getFont()->setBold(true)->setSize(20);
        $currentExcelSheet->setCellValue('D1', $title);
        $currentExcelSheet->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        
        $currentExcelSheet->mergeCells('P1:T1');
        $currentExcelSheet->setCellValue('P1', date('Y-m-d H:i'));


        



        $currentExcelSheet->setCellValue('A2', '产品编号');
        $currentExcelSheet->setCellValue('B2', '产品名称');
        $currentExcelSheet->setCellValue('C2', '规格');
        $currentExcelSheet->setCellValue('D2', '单位');

        $currentExcelSheet->setCellValue('E2', '库存情况');

        $currentExcelSheet->setCellValue('E3', '现有库存');
        $currentExcelSheet->setCellValue('F3', '期初库存');
        $currentExcelSheet->setCellValue('G3', '期末库存');

        $currentExcelSheet->setCellValue('H2', '产品变动总数');

        $currentExcelSheet->setCellValue('H3', '进货');

        $currentExcelSheet->setCellValue('I3', '其他入库');
        $currentExcelSheet->setCellValue('J3', '调拨入库');
        $currentExcelSheet->setCellValue('K3', '盘盈入库');
        $currentExcelSheet->setCellValue('L3', '退货入库');

        $currentExcelSheet->setCellValue('M3', '入库冲减');
        $currentExcelSheet->setCellValue('N3', '盘亏冲减');
        $currentExcelSheet->setCellValue('O3', '过期冲减');
        $currentExcelSheet->setCellValue('P3', '其他冲减');

        $currentExcelSheet->setCellValue('Q3', '领药');
        $currentExcelSheet->setCellValue('R3', '领料');

        $currentExcelSheet->setCellValue('S3', '处方出库');
        $currentExcelSheet->setCellValue('T3', '处方撤回');


        $letter = array('A2','B2','C2','D2','E2','E3','F3','G3','H2','H3','I3','J3','K3','L3','M3','N3','O3','P3','Q3','R3','S3','T3');
        foreach ($letter as $key => $value) {           //加粗、居中
            $currentExcelSheet->getStyle($value)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $currentExcelSheet->getStyle($value)->getFont()->setBold(true);
        }
        
        $abcefg = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T');
        foreach ($abcefg as $key => $value) {
            $currentExcelSheet->getColumnDimension($value)->setWidth(12);
        }
        


        return 3;
    }

    /**
     * 写入每行数据
     */
    private function generateRow(&$currentExcelSheet, $lineNo, &$rowData)
    {
        

        
// !empty($rowData['pro_code'])?$rowData['pro_code']:'';



        $currentExcelSheet->setCellValue('A' . $lineNo, $rowData['pro_code']);
        $currentExcelSheet->setCellValue('B' . $lineNo, $rowData['pro_name']);
        $currentExcelSheet->setCellValue('C' . $lineNo, $rowData['pro_spec']);
        $currentExcelSheet->setCellValue('D' . $lineNo, $rowData['uname']);
        $currentExcelSheet->setCellValue('E' . $lineNo, $rowData['pro_stock']);
        $currentExcelSheet->setCellValue('F' . $lineNo, $rowData['beginStock']);
        $currentExcelSheet->setCellValue('G' . $lineNo, $rowData['endStock']);

        $currentExcelSheet->setCellValue('H' . $lineNo, !empty($rowData['jh'])?$rowData['jh']:'');

        $currentExcelSheet->setCellValue('I' . $lineNo, !empty($rowData['qtrk'])?$rowData['qtrk']:'');
        $currentExcelSheet->setCellValue('J' . $lineNo, !empty($rowData['dbrk'])?$rowData['dbrk']:'');
        $currentExcelSheet->setCellValue('K' . $lineNo, !empty($rowData['pyrk'])?$rowData['pyrk']:'');
        $currentExcelSheet->setCellValue('L' . $lineNo, !empty($rowData['thrk'])?$rowData['thrk']:'');

        $currentExcelSheet->setCellValue('M' . $lineNo, !empty($rowData['rkcj'])?$rowData['rkcj']:'');
        $currentExcelSheet->setCellValue('N' . $lineNo, !empty($rowData['pkcj'])?$rowData['pkcj']:'');
        $currentExcelSheet->setCellValue('O' . $lineNo, !empty($rowData['gqcj'])?$rowData['gqcj']:'');
        $currentExcelSheet->setCellValue('P' . $lineNo, !empty($rowData['qtcj'])?$rowData['qtcj']:'');

        $currentExcelSheet->setCellValue('Q' . $lineNo, !empty($rowData['ly'])?$rowData['ly']:'');
        $currentExcelSheet->setCellValue('R' . $lineNo, !empty($rowData['ll'])?$rowData['ll']:'');

        $currentExcelSheet->setCellValue('S' . $lineNo, !empty($rowData['fy'])?$rowData['fy']:'');
        $currentExcelSheet->setCellValue('T' . $lineNo, !empty($rowData['cy'])?$rowData['cy']:'');





        
    }

}
