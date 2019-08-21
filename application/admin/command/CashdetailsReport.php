<?php

namespace app\admin\command;

use app\admin\model\Admin;
use app\admin\model\CmdRecords;
use app\admin\model\CustomerBalance;
use app\admin\model\CtmSource;
use app\admin\model\CtmChannels;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;
use app\admin\model\BalanceType;

class CashdetailsReport extends Command
{

    protected $model         = null;
    protected $linesPerExcel = 20000;
    protected $batchLimit    = 2000;
    // protected $sourceList = [];
    // protected $exploreList = [];

    protected function configure()
    {
        $this
            ->setName('cashdetailsreport')
            ->addOption('force', 'f', Option::VALUE_OPTIONAL, 'force override', false)
            ->addOption('record', 'r', option::VALUE_REQUIRED, 'command record id')
            ->setDescription('Generate balance detail');
    }

    /**
     * 分批查询记录
     * 按记录数划分为不同EXCEL文件
     * 打包文件，删除EXCEL
     * 记录压缩文件位置
     * 注意中文路径的转换，存储文件时转换，存入DB时未转换
     * -- 由于记录条数较少，不做划分EXCEL处理 --
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


        $total = CustomerBalance::getListCount2($where, $extraWhere);

        $maxBatchCount  = ceil($total / $this->batchLimit);
        $maxExcelCount  = ceil($total / $this->linesPerExcel);
        $currentCount   = 0;
        $currentBatchNo = 1;
        $currentExcelNo = 1;

        $excelDir = realpath(__DIR__ . '/../../data/reports/excels') . '/';
        $namePre  = '收款业绩_' . str_pad((string) $recordId, 11, '0', STR_PAD_LEFT);

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
        $currentLineNo = $this->generateHeader($currentExcelSheet, $namePre, $cmdRecord);

        $sort = 'balance_id';
        $order = 'DESC';
        for (; $currentBatchNo <= $maxBatchCount; $currentBatchNo++) {
            $batchOffset = ($currentBatchNo - 1) * $this->batchLimit;
            \think\Log::record('entering loop');
            $list  = CustomerBalance::getList2($where, $sort, $order, $batchOffset, $this->batchLimit, $extraWhere);
            foreach ($list as $key => $rowData) {
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
                    $currentLineNo ++;
                }

                $this->generateRow($currentExcelSheet, $currentLineNo, $rowData);

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
        $excelFileName = iconv('utf-8', 'gb2312', $namePre) . '.xlsx';
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
        for ($cIndex = ord('A'); $cIndex <= ord('K'); $cIndex ++) {
            $currentExcelSheet->getColumnDimension(chr($cIndex))->setWidth(16);
        }
        $currentExcelSheet->getColumnDimension('L')->setWidth(36);

        $currentExcelSheet->mergeCells('A1:C1');
        $currentExcelSheet->setCellValue('A1', 'feature: ' . $cmdRecord->feature_value);
        $currentExcelSheet->mergeCells('D1:I1');
        $currentExcelSheet->getStyle('D1')->getFont()->setBold(true)->setSize(20);
        $currentExcelSheet->setCellValue('D1', $title);
        $currentExcelSheet->mergeCells('J1:N1');
        $currentExcelSheet->setCellValue('K1', date('Y-m-d H:i'));

        $currentExcelSheet->getStyle('A2:M2')->getFont()->setBold(true);

        $currentExcelSheet->setCellValue('A2', '付款时间');
        $currentExcelSheet->setCellValue('B2', '顾客卡号');
        $currentExcelSheet->setCellValue('C2', '顾客');
        $currentExcelSheet->setCellValue('D2', '初复次');
        $currentExcelSheet->setCellValue('E2', '支付额(现金+卡+微信+支付宝+其他)');
        $currentExcelSheet->setCellValue('F2', '网络客服');
        $currentExcelSheet->setCellValue('G2', '现场客服');
        $currentExcelSheet->setCellValue('H2', '营销部门');
        $currentExcelSheet->setCellValue('I2', '营销渠道');
        $currentExcelSheet->setCellValue('J2', '客户来源');
        $currentExcelSheet->setCellValue('K2', '首次受理工具');
        $currentExcelSheet->setCellValue('L2', '类型');
        $currentExcelSheet->setCellValue('M2', '退款类型');
        $currentExcelSheet->setCellValue('N2', '备注');

        return 2;
    }

    /**
     * 写入每行数据
     */
    private function generateRow(&$currentExcelSheet, $lineNo, &$rowData)
    {
        $refundTypeList = BalanceType::getRefundList();

        $currentExcelSheet->setCellValue('A' . $lineNo, date('Y-m-d H:i:s', $rowData['createtime']));
        $currentExcelSheet->setCellValue('B' . $lineNo, $rowData['customer_id']);
        $currentExcelSheet->setCellValue('C' . $lineNo, $rowData['ctm_name']);
        $currentExcelSheet->setCellValue('D' . $lineNo, $rowData['b_osc_type_name']);
        $currentExcelSheet->setCellValue('E' . $lineNo, $rowData['pay_total']);

        $currentExcelSheet->setCellValue('F' . $lineNo, $rowData['customer_admin_name']);
        $currentExcelSheet->setCellValue('G' . $lineNo, $rowData['last_osc_admin']);
        $currentExcelSheet->setCellValue('H' . $lineNo, $rowData['customer_admin_dept']);
        $currentExcelSheet->setCellValue('I' . $lineNo, $rowData['explore_name']);
        $currentExcelSheet->setCellValue('J' . $lineNo, $rowData['source_name']);
        $currentExcelSheet->setCellValue('K' . $lineNo, $rowData['tool_name']);
        $currentExcelSheet->setCellValue('L' . $lineNo, $rowData['balance_type']);
        $currentExcelSheet->setCellValue('M' . $lineNo, $rowData['refund_type_name']);
        $currentExcelSheet->setCellValue('N' . $lineNo, $rowData['balance_remark']);

      
    }
}
