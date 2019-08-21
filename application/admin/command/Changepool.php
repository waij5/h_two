<?php

namespace app\admin\command;


use app\admin\model\Admin;
use app\admin\model\Changepool as CP;
use app\admin\model\CmdRecords;
use app\admin\model\Report;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;
use think\Db;
use think\lang;

class Changepool extends Command
{
    protected $model         = null;
    protected $linesPerExcel = 20000;
    protected $batchLimit    = 2000;

    protected function configure()
    {
        $this
            ->setName('changepool')
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
            $where = $params['where'];
            $extraWhere = $params['extraWhere'];
        }

        // $params = json_decode($cmdRecord->params, true);
        // $where  = array();
        // if ($params !== false && is_array($params)) {
        //     $where = $params;
        // }

        
        $list = db('Stock_log')->alias('sl')
                    ->field('sl.*,p.name,p.num,p.sizes,p.unit,p.lotnum,p.cost,p.price,p.stock,pe.name as pename,p.pdutype_id,p.pdutype2_id')
                    ->join(DB::getTable('Product'). ' p','sl.l_pid = p.id', 'LEFT')
                    ->join(DB::getTable('Protype'). ' pe','p.pdutype2_id = pe.id', 'LEFT')
                    ->where($where)
                    ->order('p.pdutype2_id','DESC')
                    ->select();
        
        if($list){
            foreach ($list as $key => $v) {
                 $pName = db('Protype')->field('name')->where(['id' => $list[$key]['pdutype_id']])->select();
                 $pName2 = db('Protype')->field('name')->where(['id' => $list[$key]['pdutype2_id']])->select(); 
                 if($pName){
                    foreach ($pName as $keys => $va) {
                        $list[$key]['pdutype_id'] = $va['name'];
                    }
                 }else{
                    $list[$key]['pdutype_id'] = '-';
                 }
                 
                 if($pName2){
                    foreach ($pName2 as $keys => $va) {
                        $list[$key]['pdutype2_id'] = $va['name'];
                    }
                 }else{
                    $list[$key]['pdutype2_id'] = '-';
                 }
                 
            }

        }
        

        $changePoolData = CP::dealArr($list);
        $total = '';
        foreach ($changePoolData as $key => $val) {
            $total += count($val);
        }
            
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
            
            foreach ($changePoolData as $key => $rowData) {
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
                        }
                        $currentExcelNo++;

                        $currentExcel      = new PHPExcel();
                        $currentExcelSheet = $currentExcel->getActiveSheet();
                        //头部标题填充
                        $currentLineNo = $this->generateHeader($currentExcelSheet, $namePre . $currentExcelNo, $cmdRecord);
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
        $currentExcelSheet->mergeCells('E2:E3');
        $currentExcelSheet->mergeCells('F2:F3');
        $currentExcelSheet->mergeCells('G2:G3');

        $currentExcelSheet->mergeCells('H2:I2');

        $currentExcelSheet->mergeCells('J2:V2');


        $currentExcelSheet->setCellValue('A1', 'feature: ' . $cmdRecord->feature_value);
        $currentExcelSheet->mergeCells('D1:O1');
        $currentExcelSheet->getStyle('D1')->getFont()->setBold(true)->setSize(20);
        $currentExcelSheet->setCellValue('D1', $title);
        $currentExcelSheet->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        
        $currentExcelSheet->mergeCells('P1:R1');
        $currentExcelSheet->setCellValue('P1', date('Y-m-d H:i'));


        


        $currentExcelSheet->setCellValue('A2', '类别');
        $currentExcelSheet->setCellValue('B2', '产品编号');
        $currentExcelSheet->setCellValue('C2', '产品名称');
        $currentExcelSheet->setCellValue('D2', '批号');
        $currentExcelSheet->setCellValue('E2', '规格');
        $currentExcelSheet->setCellValue('F2', '单位');
        $currentExcelSheet->setCellValue('G2', '成本单价');

        $currentExcelSheet->setCellValue('H2', '库存情况');

        $currentExcelSheet->setCellValue('J2', '产品变动总数');

        $currentExcelSheet->setCellValue('H3', '现有库存');
        $currentExcelSheet->setCellValue('I3', '期初库存');

        $currentExcelSheet->setCellValue('J3', '进货');
        $currentExcelSheet->setCellValue('K3', '调拨入库');
        $currentExcelSheet->setCellValue('L3', '盘盈入库');
        $currentExcelSheet->setCellValue('M3', '退货入库');
        $currentExcelSheet->setCellValue('N3', '其它入库');
        $currentExcelSheet->setCellValue('O3', '入库冲减');
        $currentExcelSheet->setCellValue('P3', '盘亏冲减');

        $currentExcelSheet->setCellValue('Q3', '过期冲减');
        $currentExcelSheet->setCellValue('R3', '其它冲减');
        $currentExcelSheet->setCellValue('S3', '发药');
        $currentExcelSheet->setCellValue('T3', '撤药');
        $currentExcelSheet->setCellValue('U3', '领药');
        $currentExcelSheet->setCellValue('V3', '领料');
        // $currentExcelSheet->setCellValue('Y2', '');
        // $currentExcelSheet->setCellValue('Z2', '删除');
        // $currentExcelSheet->setCellValue('AA2', '');
        // $currentExcelSheet->setCellValue('AB2', '删除');
        // $currentExcelSheet->setCellValue('AC2', '');
        // $currentExcelSheet->setCellValue('AD2', '删除');
        // $currentExcelSheet->setCellValue('AE2', '');
        // $currentExcelSheet->setCellValue('AF2', '删除');,'Y2','Z2','AA2','AB2','AC2','AD2','AE2','AF2'


        $letter = array('A2','B2','C2','D2','E2','F2','G2','H2','I2','J2','H3','I3','J3','K3','L3','M3','N3','O3','P3','Q3','R3','S3','T3','U3','V3');
        foreach ($letter as $key => $value) {           //加粗、居中
            $currentExcelSheet->getStyle($value)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $currentExcelSheet->getStyle($value)->getFont()->setBold(true);
        }
        $abc = array('A','B','C','D','E','F','G');
        foreach ($abc as $key => $value) {
            $currentExcelSheet->getColumnDimension($value)->setWidth(16);
        }
        $abcefg = array('H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V');
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
        

        

        $currentExcelSheet->setCellValue('A' . $lineNo, $rowData['pdutype_id'].' * '.$rowData['pdutype2_id']);
        $currentExcelSheet->setCellValue('B' . $lineNo, $rowData['num']);
        $currentExcelSheet->setCellValue('C' . $lineNo, $rowData['name']);
        $currentExcelSheet->setCellValue('D' . $lineNo, $rowData['lotnum']);
        $currentExcelSheet->setCellValue('E' . $lineNo, $rowData['sizes']);
        $currentExcelSheet->setCellValue('F' . $lineNo, $rowData["unit"]);
        $currentExcelSheet->setCellValue('G' . $lineNo, $rowData['cost']);
        $currentExcelSheet->setCellValue('H' . $lineNo, $rowData['stock']);

        $currentExcelSheet->setCellValue('I' . $lineNo, $rowData['stock']-$rowData['jh']-$rowData['dbrk']-$rowData['pyrk']-$rowData['thrk']-$rowData['qtrk']+$rowData['rkcj']+$rowData['pkcj']+$rowData['gqcj']+$rowData['qtcj']+$rowData['fy']-$rowData['cy']+$rowData['ly']+$rowData['ll']);

        $currentExcelSheet->setCellValue('J' . $lineNo, $rowData['jh']);
        $currentExcelSheet->setCellValue('K' . $lineNo, $rowData['dbrk']);
        $currentExcelSheet->setCellValue('L' . $lineNo, $rowData['pyrk']);
        $currentExcelSheet->setCellValue('M' . $lineNo, $rowData['thrk']);
        $currentExcelSheet->setCellValue('N' . $lineNo, $rowData['qtrk']);
        $currentExcelSheet->setCellValue('O' . $lineNo, $rowData['rkcj']);
        $currentExcelSheet->setCellValue('P' . $lineNo, $rowData['pkcj']);
        $currentExcelSheet->setCellValue('Q' . $lineNo, $rowData['gqcj']);
        $currentExcelSheet->setCellValue('R' . $lineNo, $rowData['qtcj']);
        $currentExcelSheet->setCellValue('S' . $lineNo, $rowData['fy']);
        $currentExcelSheet->setCellValue('T' . $lineNo, $rowData['cy']);
        $currentExcelSheet->setCellValue('U' . $lineNo, $rowData['ly']);
        $currentExcelSheet->setCellValue('V' . $lineNo, $rowData['ll']);



        
    }

}
