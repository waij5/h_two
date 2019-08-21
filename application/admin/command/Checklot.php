<?php

namespace app\admin\command;

use app\admin\model\Admin;
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

class Checklot extends Command
{
    protected $model         = null;
    protected $linesPerExcel = 20000;
    protected $batchLimit    = 2000;

    protected function configure()
    {
        $this
            ->setName('checklot')
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

        
        $pdcatData = db('pducat')->where(['pdc_zpttype'=> '4'])->column('pdc_id,pdc_name');
        $lists = db('project')->alias('p')
                    ->field('p.pro_id, p.pro_name, p.pro_code,  p.pro_spec, p.depot_id, p.pro_cat2, p.pro_cat1, d.name as dname, u.name as uname, lot.lot_id,lot.lotnum,lot.lstock,lot.lcost,lot.letime,lot.lproducer,sup.sup_name')
                    ->join('yjy_depot d', 'p.depot_id = d.id', 'LEFT')
                    ->join('yjy_unit u', 'p.pro_unit = u.id', 'LEFT')
                    ->join('yjy_wm_lotnum lot', 'p.pro_id = lot.lpro_id', 'LEFT')
                    ->join('yjy_wm_manlist ml', 'lot.lot_id = ml.lotid', 'LEFT')
                    ->join('yjy_wm_manifest mf', 'ml.manid = mf.man_id', 'LEFT')
                    ->join('yjy_wm_supplier sup', 'mf.msupplier_id = sup.sup_id', 'LEFT')
                    ->whereIn('mf.mprimary_type',['1','2'],'or')
                    ->where('lot.lstock','>','0')
                    ->where($where)
                    ->order('lot.lot_id','ASC')
                    ->select();
        $total =0;
        $list = [];
        $total = count($lists);
        foreach ($lists as $key => $v) {
            $lists[$key]['pro_cat1'] = isset($pdcatData[$v['pro_cat1']]) ? $pdcatData[$v['pro_cat1']] : '';
            $lists[$key]['pro_cat2'] = isset($pdcatData[$v['pro_cat2']]) ? $pdcatData[$v['pro_cat2']] : '';
        }
        foreach ($lists as $k => $v) {
            $list[$v['pro_id']][] = $v;
        }
        /*foreach ($lists as $key => $v) {
            $list[$v['pro_id']][] = $v;
        }*/
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
        $namePre  = '产品批号盘点表' . str_pad((string) $recordId, 11, '0', STR_PAD_LEFT) . '_';

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

            foreach ($list as $key => $rowData) {
                foreach ($rowData as $ke => $va) {
                    $va['letime'] = (int)$va['letime']?date('Y-m-d', (int)$va['letime']):'';
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

                    $this->generateRow($currentExcelSheet, $currentLineNo, $va);

                    // var_dump($list);

                    //进度信息
                    $processJson = array(
                                        'completedCount' => $currentCount,
                                        'total' => $total,
                                        'status' => CmdRecords::STATUS_PROCESSING,
                                        'statusText' => CmdRecords::STATUS_PROCESSING,
                    );
                    $cmdRecord->delayUpdateProcessInfo($currentCount, $processJson, true);
                }
                
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
        //column style     18列
        
        $currentExcelSheet->mergeCells('A1:C1');
        $currentExcelSheet->setCellValue('A1', 'feature: ' . $cmdRecord->feature_value);
        $currentExcelSheet->mergeCells('D1:I1');
        $currentExcelSheet->getStyle('D1')->getFont()->setBold(true)->setSize(20);
        $currentExcelSheet->setCellValue('D1', $title);
        $currentExcelSheet->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        
        $currentExcelSheet->mergeCells('J1:L1');
        $currentExcelSheet->setCellValue('J1', date('Y-m-d H:i'));


        


        $currentExcelSheet->setCellValue('A2', '产品编号');
        $currentExcelSheet->setCellValue('B2', '产品名称 ');
        $currentExcelSheet->setCellValue('C2', '单位');
        $currentExcelSheet->setCellValue('D2', '规格');
        $currentExcelSheet->setCellValue('E2', '所属仓库');
        $currentExcelSheet->setCellValue('F2', '类别');
        $currentExcelSheet->setCellValue('G2', '批号');
        $currentExcelSheet->setCellValue('H2', '成本单价');
        $currentExcelSheet->setCellValue('I2', '库存');
        $currentExcelSheet->setCellValue('J2', '有效期');
        $currentExcelSheet->setCellValue('K2', '供应商');
        $currentExcelSheet->setCellValue('L2', '生产厂家');

        $letter = array('A2','B2','C2','D2','E2','F2','G2','H2','I2','J2','K2','L2');
        foreach ($letter as $key => $value) {           //加粗、居中
            $currentExcelSheet->getStyle($value)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $currentExcelSheet->getStyle($value)->getFont()->setBold(true);
        }
        $abc = array('A','B','C','D','E','F','G','H','I','J','K','L');
        foreach ($abc as $key => $value) {
            $currentExcelSheet->getColumnDimension($value)->setWidth(16);
        }
        


        return 2;
    }

    /**
     * 写入每行数据
     */
    private function generateRow(&$currentExcelSheet, $lineNo, &$rowData)
    {
        

        

        $currentExcelSheet->setCellValue('A' . $lineNo, $rowData['pro_code']);
        $currentExcelSheet->setCellValue('B' . $lineNo, $rowData['pro_name']);
        $currentExcelSheet->setCellValue('C' . $lineNo, $rowData['uname']);
        $currentExcelSheet->setCellValue('D' . $lineNo, $rowData['pro_spec']);
        $currentExcelSheet->setCellValue('E' . $lineNo, $rowData['dname']);
        $currentExcelSheet->setCellValue('F' . $lineNo, $rowData['pro_cat1'].'*'.$rowData['pro_cat2']);
        $currentExcelSheet->setCellValue('G' . $lineNo, $rowData['lotnum']);
        $currentExcelSheet->setCellValue('H' . $lineNo, $rowData['lcost']);
        $currentExcelSheet->setCellValue('I' . $lineNo, $rowData['lstock']);
        $currentExcelSheet->setCellValue('J' . $lineNo, $rowData["letime"]);
        $currentExcelSheet->setCellValue('K' . $lineNo, $rowData['sup_name']);
        $currentExcelSheet->setCellValue('L' . $lineNo, $rowData['lproducer']);


        
    }

}
