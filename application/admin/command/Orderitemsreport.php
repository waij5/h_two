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

class Orderitemsreport extends Command
{
    protected $model         = null;
    protected $linesPerExcel = 20000;
    protected $batchLimit    = 2000;

    protected function configure()
    {
        $this
            ->setName('orderitemsreport')
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

        $currentCount   = 0;
        $currentBatchNo = 1;
        $currentExcelNo = 1;

        $total = 99999;

        /*此项查询较慢，先更新进度*/
        $processJson = array(
                            'completedCount' => $currentCount,
                            'total' => $total,
                            'status' => CmdRecords::STATUS_PROCESSING,
                            'statusText' => '初始化中...',
        );
        $cmdRecord->delayUpdateProcessInfo($currentCount, $processJson, false);

        $where = array_merge($where, $extraWhere);
        $summary = Report::getCustomerOrderItemSummaryCount($where, true);
        $total = $summary['count'];
        //获取职员缓存信息
        // $adminModel     = new Admin;
        $briefAdminList = Admin::getAdminCache(Admin::ADMIN_BRIEF_CACHE_KEY);

        $maxBatchCount  = ceil($total / $this->batchLimit);
        $maxExcelCount  = ceil($total / $this->linesPerExcel);

        $excelDir = realpath(__DIR__ . '/../../data/reports/excels') . '/';
        $namePre  = '项目汇总表_' . str_pad((string) $recordId, 11, '0', STR_PAD_LEFT) . '_';

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

            $itemsSummarys = Report::getCustomerOrderItemSummary($where, $batchOffset, $this->batchLimit);
            foreach ($itemsSummarys as $key => $itemsSummary) {
                $currentCount++;
                $currentLineNo++;

                //个数超过当前EXCEL的设定限制后，生成新的EXCEL文档
                if ($currentCount > ($currentExcelNo * $this->linesPerExcel)) {
                    if ($currentExcel) {
                        //进度信息
                        $processJson = array(
                                            'completedCount' => $currentCount,
                                            'total' => $total,
                                            'status' => CmdRecords::STATUS_PROCESSING,
                                            'statusText' => "进行中...第{$currentExcelNo}个EXCEL保存中(excels: {$currentExcelNo} / {$maxExcelCount})",
                        );
                        $cmdRecord->delayUpdateProcessInfo($currentCount, $processJson, false);


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
                    $currentLineNo ++;
                }

                $this->generateRow($currentExcelSheet, $currentLineNo, $itemsSummary, $briefAdminList);

                //进度信息
                $processJson = array(
                                    'completedCount' => $currentCount,
                                    'total' => $total,
                                    'status' => CmdRecords::STATUS_PROCESSING,
                                    'statusText' => "进行中...(excels: {$currentExcelNo} / {$maxExcelCount})",
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
    private function generateHeader(&$currentExcelSheet, $title, &$cmdRecord, $summary)
    {
        //column style
        for ($charAsc = ord('A'); $charAsc <= ord('I'); $charAsc ++) {
            $currentExcelSheet->getColumnDimension(chr($charAsc))->setWidth(18);
        }

        $currentExcelSheet->mergeCells('A1:C1');
        $currentExcelSheet->setCellValue('A1', 'feature: ' . $cmdRecord->feature_value);
        $currentExcelSheet->mergeCells('D1:F1');
        $currentExcelSheet->getStyle('D1')->getFont()->setBold(true)->setSize(20);
        $currentExcelSheet->setCellValue('D1', $title);
        $currentExcelSheet->mergeCells('G1:I1');
        $currentExcelSheet->setCellValue('G1', date('Y-m-d H:i'));

        $currentExcelSheet->getRowDimension('2')->setRowHeight(40);
        $currentExcelSheet->mergeCells('A2:I2');
        $currentExcelSheet->getStyle('A2')->getAlignment()->setWrapText(true);
        $currentExcelSheet->setCellValue('A2', sprintf('顾客数: %s 订购项目数: %s 原始支付额 %.2s 本期变动额 %.2s ' . PHP_EOL . '实付额 %.2s 未划扣额: %.2s 券额 %.2s', $summary['count'], $summary['item_count'], $summary['item_original_pay_total'], $summary['item_switch_total'], $summary['item_pay_total'], $summary['undeducted_total'], $summary['item_coupon_total']));


        $currentExcelSheet->getStyle('A3:I3')->getFont()->setBold(true)->setSize(16);
        $currentExcelSheet->getStyle('A3:I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $currentExcelSheet->setCellValue('A3', '顾客');
        $currentExcelSheet->setCellValue('B3', '定金余额');
        $currentExcelSheet->setCellValue('C3', '网络客服');
        $currentExcelSheet->setCellValue('D3', '订购项目数');

        $currentExcelSheet->setCellValue('E3', '原始支付额');
        $currentExcelSheet->setCellValue('F3', '本期变动额');
        $currentExcelSheet->setCellValue('G3', '实付额');
        $currentExcelSheet->setCellValue('H3', '未划扣额');
        $currentExcelSheet->setCellValue('I3', '券额');

        return 3;
    }

    /**
     * 写入每行数据
     */
    private function generateRow(&$currentExcelSheet, $lineNo, &$rowData, &$briefAdminList)
    {
        $customerStr = '';
        if ($rowData['customer_id']) {
            $customerStr = '<' . $rowData['customer_id'] . '>' . ($rowData['ctm_name'] ? $rowData['ctm_name'] : '');
        }
        $developAdmin = '';
        if ($rowData['develop_admin_id']) {
            if (isset($briefAdminList[$rowData['develop_admin_id']])) {
                $developAdmin = $briefAdminList[$rowData['develop_admin_id']];
            }
        }

        $currentExcelSheet->setCellValue('A' . $lineNo, $customerStr);
        $currentExcelSheet->setCellValue('B' . $lineNo, $rowData['ctm_depositamt']);
        $currentExcelSheet->setCellValue('C' . $lineNo, $developAdmin);
        $currentExcelSheet->setCellValue('D' . $lineNo, $rowData['item_count']);

        $currentExcelSheet->setCellValue('E' . $lineNo, $rowData['item_original_pay_total']);
        $currentExcelSheet->setCellValue('F' . $lineNo, $rowData['item_switch_total']);
        $currentExcelSheet->setCellValue('G' . $lineNo, $rowData['item_total']);
        $currentExcelSheet->setCellValue('H' . $lineNo, $rowData['undeducted_total']);
        $currentExcelSheet->setCellValue('I' . $lineNo, $rowData['item_coupon_total']);
    }
}
