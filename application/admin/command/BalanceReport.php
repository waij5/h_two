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

class BalanceReport extends Command
{

    protected $model         = null;
    protected $linesPerExcel = 20000;
    protected $batchLimit    = 2000;
    // protected $sourceList = [];
    // protected $exploreList = [];

    protected function configure()
    {
        $this
            ->setName('balancereport')
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
        $namePre  = '收银明细_' . str_pad((string) $recordId, 11, '0', STR_PAD_LEFT);

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

        $currentExcelSheet->setCellValue('A2', 'ID');
        $currentExcelSheet->setCellValue('B2', '顾客');
        $currentExcelSheet->setCellValue('C2', '顾客卡号');
        $currentExcelSheet->setCellValue('D2', '类型');

        $currentExcelSheet->setCellValue('E2', '总金额');
        $currentExcelSheet->setCellValue('F2', '支付额(现金+卡+微信+支付宝+其他)');
        $currentExcelSheet->setCellValue('G2', '现金收款');
        $currentExcelSheet->setCellValue('H2', '卡收款');
        $currentExcelSheet->setCellValue('I2', '微信收款');
        $currentExcelSheet->setCellValue('J2', '支付宝收款');
        $currentExcelSheet->setCellValue('K2', '其他收款');
        $currentExcelSheet->setCellValue('L2', '优惠券额');
        $currentExcelSheet->setCellValue('M2', '定金支付额');

        $currentExcelSheet->setCellValue('N2', '收银时间');
        $currentExcelSheet->setCellValue('O2', '网络客服');
        $currentExcelSheet->setCellValue('P2', '现场客服');
        $currentExcelSheet->setCellValue('Q2', '客户来源');
        $currentExcelSheet->setCellValue('R2', '营销渠道');
        $currentExcelSheet->setCellValue('S2', '退款类型');
        $currentExcelSheet->setCellValue('T2', '备注');

        return 2;
    }

    /**
     * 写入每行数据
     */
    private function generateRow(&$currentExcelSheet, $lineNo, &$rowData)
    {
        $refundTypeList = BalanceType::getRefundList();

        $currentExcelSheet->setCellValue('A' . $lineNo, $rowData['balance_id']);
        $currentExcelSheet->setCellValue('B' . $lineNo, $rowData['ctm_name']);
        $currentExcelSheet->setCellValue('C' . $lineNo, $rowData['customer_id']);
        $currentExcelSheet->setCellValue('D' . $lineNo, $rowData['balance_type']);

        $currentExcelSheet->setCellValue('E' . $lineNo, $rowData['total']);
        $currentExcelSheet->setCellValue('F' . $lineNo, $rowData['pay_total']);
        $currentExcelSheet->setCellValue('G' . $lineNo, $rowData['cash_pay_total']);
        $currentExcelSheet->setCellValue('H' . $lineNo, $rowData['card_pay_total']);
        $currentExcelSheet->setCellValue('I' . $lineNo, $rowData['wechatpay_pay_total']);
        $currentExcelSheet->setCellValue('J' . $lineNo, $rowData['alipay_pay_total']);
        $currentExcelSheet->setCellValue('K' . $lineNo, $rowData['other_pay_total']);
        $currentExcelSheet->setCellValue('L' . $lineNo, $rowData['coupon_total']);
        $currentExcelSheet->setCellValue('M' . $lineNo, $rowData['deposit_total']);

        $currentExcelSheet->setCellValue('N' . $lineNo, date('Y-m-d', $rowData['createtime']));
        $currentExcelSheet->setCellValue('O' . $lineNo, $rowData['develop_admin_name']);
        $currentExcelSheet->setCellValue('P' . $lineNo, $rowData['osc_admin_name']);

        $currentExcelSheet->setCellValue('Q' . $lineNo, $rowData['source_name']);
        $currentExcelSheet->setCellValue('R' . $lineNo, $rowData['explore_name']);

        $currentExcelSheet->getStyle('S' . $lineNo)->getAlignment() ->setWrapText(TRUE);
        $currentExcelSheet->setCellValue('S' . $lineNo, isset($refundTypeList[$rowData['refund_type']]) ? $refundTypeList[$rowData['refund_type']] : '');
        $currentExcelSheet->setCellValue('T' . $lineNo, $rowData['balance_remark']);
    }
}
