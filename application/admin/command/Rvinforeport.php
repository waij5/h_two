<?php

namespace app\admin\command;

use app\admin\model\Admin;
use app\admin\model\CmdRecords;
use app\admin\model\Rvinfo;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;
use app\admin\model\Gender;

class Rvinforeport extends Command
{
    protected $model         = null;
    protected $linesPerExcel = 20000;
    protected $batchLimit    = 5000;

    protected function configure()
    {
        $this
            ->setName('rvinforeport')
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
        ini_set('memory_limit', '512M');
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
        if (!isset($params['where']) || !isset($params['extraWhere']) || !isset($params['extra'])) {
            $cmdRecord->status = CmdRecords::STATUS_FAILED;
            $cmdRecord->save();
            return false;
        }

        //初始化
        include APP_PATH . 'admin' . DS . 'common.php';
        $currentCount   = 0;
        $currentBatchNo = 1;
        $currentExcelNo = 1;
        $processJson = array(
                            'completedCount' => $currentCount,
                            'total' => 99999,
                            'status' => CmdRecords::STATUS_PROCESSING,
                            'statusText' => '初始化中...',
        );
        $cmdRecord->delayUpdateProcessInfo(0, $processJson, false);

        $where = $params['where'];
        $extraWhere = $params['extraWhere'];
        $extra = $params['extra'];
        $sort = $extra['sort'];
        $order = $extra['order'];
        $summary = Rvinfo::getListSummary($where, $extraWhere);
        $total = $summary['count'];
        $maxBatchCount  = ceil($total / $this->batchLimit);
        $maxExcelCount  = ceil($total / $this->linesPerExcel);

        $excelDir = realpath(__DIR__ . '/../../data/reports/excels') . '/';
        $namePre  = '客户回访记录_' . str_pad((string) $recordId, 11, '0', STR_PAD_LEFT) . '_';

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
        $currentLineNo = $this->generateHeader($currentExcelSheet, $namePre . $currentExcelNo, $cmdRecord, $summary);

        for (; $currentBatchNo <= $maxBatchCount; $currentBatchNo++) {
            $batchOffset = ($currentBatchNo - 1) * $this->batchLimit;

            $list = Rvinfo::getList($where, $sort, $order, $batchOffset, $this->batchLimit, $extraWhere, $isSuperAdmin = false);
            foreach ($list as $key => $row) {
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
                    $currentLineNo = $this->generateHeader($currentExcelSheet, $namePre . $currentExcelNo, $cmdRecord, $summary);
                    $currentLineNo++;
                }

                $this->generateRow($currentExcelSheet, $currentLineNo, $row);

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
        chmod($targetCompressFile, 0777);

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
    private function generateHeader(&$currentExcelSheet, $title, &$cmdRecord, $summary)
    {
        //column style
        $currentExcelSheet->getColumnDimension('A')->setWidth(10);
        $currentExcelSheet->getColumnDimension('C')->setWidth(5);
        $currentExcelSheet->getColumnDimension('E')->setWidth(14);
        $currentExcelSheet->getColumnDimension('E')->setWidth(15);
        $currentExcelSheet->getColumnDimension('G')->setWidth(30);
        $currentExcelSheet->getColumnDimension('H')->setWidth(14);
        $currentExcelSheet->getColumnDimension('I')->setWidth(13);
        $currentExcelSheet->getColumnDimension('J')->setWidth(18);
        $currentExcelSheet->getColumnDimension('K')->setWidth(30);
        $currentExcelSheet->getColumnDimension('L')->setWidth(25);

        $currentExcelSheet->mergeCells('A1:C1');
        $currentExcelSheet->setCellValue('A1', 'feature: ' . $cmdRecord->feature_value);
        $currentExcelSheet->mergeCells('D1:I1');
        $currentExcelSheet->getStyle('D1')->getFont()->setBold(true)->setSize(20);
        $currentExcelSheet->setCellValue('D1', $title);
        $currentExcelSheet->mergeCells('J1:L1');
        $currentExcelSheet->setCellValue('J1', date('Y-m-d H:i'));

        $currentExcelSheet->mergeCells('A2:N2');
        $currentExcelSheet->setCellValue('A2', '回访数 ' . $summary['count'] . ' 已回访数 ' . $summary['visited_count'] .  ' 有效已回访数 ' . $summary['avaiable_visited_count'] .  ' 回访顾客数 ' . $summary['customer_count'] .  ' 有效已回访顾客数 ' . $summary['avaiable_visited_customer_count']);

        $currentExcelSheet->setCellValue('A3', '顾客');
        $currentExcelSheet->setCellValue('B3', '顾客卡号');
        $currentExcelSheet->setCellValue('C3', '上门状态');
        $currentExcelSheet->setCellValue('D3', '性别');
        $currentExcelSheet->setCellValue('E3', '年龄');

        $currentExcelSheet->setCellValue('F3', '手机');
        $currentExcelSheet->setCellValue('G3', '回访类型');
        $currentExcelSheet->setCellValue('H3', '回访计划');
        $currentExcelSheet->setCellValue('I3', '回访人');
        $currentExcelSheet->setCellValue('J3', '回访部门');

        $currentExcelSheet->setCellValue('K3', '回访日期(预)');
        $currentExcelSheet->setCellValue('L3', '回访时间(实)');
        $currentExcelSheet->setCellValue('M3', '回访添加时间');

        // $currentExcelSheet->setCellValue('K3', '回访状态');
        $currentExcelSheet->setCellValue('N3', '回访内容');
        $currentExcelSheet->setCellValue('O3', '失败原由');
        $currentExcelSheet->setCellValue('P3', '首次项目[网]');
        $currentExcelSheet->setCellValue('Q3', '首次科室[网]');
        $currentExcelSheet->setCellValue('R3', '首次项目[现]');
        $currentExcelSheet->setCellValue('S3', '首次科室[现]');
        $currentExcelSheet->setCellValue('T3', '下次回访日期');

        return 3;
    }

    /**
     * 写入每行数据
     */
    private function generateRow(&$currentExcelSheet, $lineNo, &$rowData)
    {
        $currentExcelSheet->getStyle('G' . $lineNo)->getAlignment()->setWrapText(TRUE);
        $currentExcelSheet->getStyle('K' . $lineNo)->getAlignment()->setWrapText(TRUE);
        $currentExcelSheet->getStyle('L' . $lineNo)->getAlignment()->setWrapText(TRUE);

        $currentExcelSheet->setCellValue('A' . $lineNo, $rowData['ctm_name']);
        $currentExcelSheet->setCellValue('B' . $lineNo, $rowData['ctm_id']);
        $currentExcelSheet->setCellValue('C' . $lineNo, $rowData['arrive_status'] ? '已上门' : '未上门');
        $currentExcelSheet->setCellValue('D' . $lineNo, Gender::getTitleById($rowData['ctm_sex']));

        $currentExcelSheet->setCellValue('E' . $lineNo, $rowData['ctm_age']);
        $currentExcelSheet->setCellValue('F' . $lineNo, getMaskString((string)($rowData['ctm_mobile']), $maskSymbol = '*', $maskLenth = 4));

        $currentExcelSheet->setCellValue('G' . $lineNo, $rowData['rvt_type']);
        $currentExcelSheet->setCellValue('H' . $lineNo, $rowData['rv_plan']);
        $currentExcelSheet->setCellValue('I' . $lineNo, $rowData['nickname']);
        $currentExcelSheet->setCellValue('J' . $lineNo, $rowData['dept_name'] ? $rowData['dept_name'] : '');


        $currentExcelSheet->setCellValue('K' . $lineNo, $rowData['rv_date']);

        if (((time()-strtotime($rowData['rv_date'])) > 6*24*3600) && $rowData['rv_time'] == ''){
            $rvTime = '超过7天未回访';
        } else {
            $rvTime = $rowData['rv_time'] ? date('Y-m-d H:i:s', $rowData['rv_time']) : '';
        }

        $currentExcelSheet->setCellValue('L' . $lineNo, $rvTime);
        $currentExcelSheet->setCellValue('M' . $lineNo, date('Y-m-d H:i:s', $rowData['createtime']));
        // $currentExcelSheet->setCellValue('K' . $lineNo, $rowData['rv_time'] ? date('Y-m-d H:i:s') : '');
        // $currentExcelSheet->setCellValue('K' . $lineNo, $rowData['rv_status']);
        $currentExcelSheet->setCellValue('N' . $lineNo, $rowData['rvi_content']);
        $currentExcelSheet->setCellValue('O' . $lineNo, $rowData['fat_name'] ? $rowData['fat_name'] : '');
        $currentExcelSheet->setCellValue('P' . $lineNo, $rowData['ctm_first_cpdt_name']);
        $currentExcelSheet->setCellValue('Q' . $lineNo, $rowData['ctm_first_dept_name']);
        $currentExcelSheet->setCellValue('R' . $lineNo, $rowData['ctm_first_osc_cpdt_name']);
        $currentExcelSheet->setCellValue('S' . $lineNo, $rowData['ctm_first_osc_dept_name']);
        $currentExcelSheet->setCellValue('T' . $lineNo, $rowData['ctm_next_rvinfo'] ? $rowData['ctm_next_rvinfo'] : '无');

    }

}
