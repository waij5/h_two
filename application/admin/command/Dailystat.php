<?php

namespace app\admin\command;

use app\admin\model\Admin;
use app\admin\model\CmdRecords;
use app\admin\model\DailyStat as MDailyStat;
use think\Log;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;

class Dailystat extends Command
{

    protected $model         = null;
    protected $linesPerExcel = 20000;
    protected $batchLimit    = 2000;

    protected function configure()
    {
        $this
            ->setName('dailystat')
            // ->addOption('force', 'f', Option::VALUE_OPTIONAL, 'force override', false)
            ->addOption('record', 'r', option::VALUE_REQUIRED, 'command record id')
            ->setDescription('Generate customer ordered items summary report');
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

        $sort = 'stat_date';
        $order = 'ASC';
        $total = MDailyStat::where($where)
                    ->order($sort, $order)
                    ->count() + 1;

        $list = MDailyStat::where($where)
                    ->order($sort, $order)
                    ->select();
        // SUM(order_count) AS order_count,SUM(ori_order_total) AS ori_order_total,SUM(ori_order_pay_total) AS ori_order_pay_total,SUM(order_total) AS order_total,SUM(order_used_coupon_total) AS order_used_coupon_total,SUM(order_deposit_change_total) AS order_deposit_change_total,
        $summary = MDailyStat::field('SUM(pay_total) AS pay_total,SUM(deposit_total) AS deposit_total,SUM(in_pay_total) AS in_pay_total,SUM(out_pay_total) AS out_pay_total,SUM(coupon_cost) AS coupon_cost,SUM(coupon_total) AS coupon_total, SUM(balance_count) AS balance_count, SUM(in_cash_pay_total) AS in_cash_pay_total,SUM(in_card_pay_total) AS in_card_pay_total,SUM(in_wechatpay_pay_total) AS in_wechatpay_pay_total,SUM(in_alipay_pay_total) AS in_alipay_pay_total,SUM(in_other_pay_total) AS in_other_pay_total,SUM(out_cash_pay_total) AS out_cash_pay_total,SUM(out_card_pay_total) AS out_card_pay_total,SUM(out_wechatpay_pay_total) AS out_wechatpay_pay_total,SUM(out_alipay_pay_total) AS out_alipay_pay_total,SUM(out_other_pay_total) AS out_other_pay_total,SUM(cash_pay_total) AS cash_pay_total,SUM(card_pay_total) AS card_pay_total,SUM(wechatpay_pay_total) AS wechatpay_pay_total,SUM(alipay_pay_total) AS alipay_pay_total,SUM(other_pay_total) AS other_pay_total')
            ->where($where)
            ->order($sort, $order)
            ->select();

        //获取职员缓存信息
        // $adminModel     = new Admin;
        $briefAdminList = Admin::getAdminCache(Admin::ADMIN_BRIEF_CACHE_KEY);

        $currentCount   = 0;

        $excelDir = realpath(__DIR__ . '/../../data/reports/excels') . '/';
        $namePre  = '营收日结_' . str_pad((string) $recordId, 11, '0', STR_PAD_LEFT);

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

        foreach ($list as $key => $rowData) {
            $currentCount ++;
            $currentLineNo ++;

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

        //总计
        $currentLineNo ++;
        $currentCount ++;
        $this->generateFooter($currentExcelSheet, $currentLineNo, $summary[0]);
        $processJson = array(
                            'completedCount' => $currentCount,
                            'total' => $total,
                            'status' => CmdRecords::STATUS_PROCESSING,
                            'statusText' => CmdRecords::STATUS_PROCESSING,
        );
        $cmdRecord->delayUpdateProcessInfo($currentCount, $processJson, true);

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
        //column style
        $currentExcelSheet->getRowDimension('1')->setRowHeight(36);
        $currentExcelSheet->getRowDimension('2')->setRowHeight(24);
        $currentExcelSheet->getRowDimension('3')->setRowHeight(24);

        $lastColOrd = ord('L');
        for($currentColOrd = ord('A'); $currentColOrd <= $lastColOrd; $currentColOrd ++) {
            $col = chr($currentColOrd);
            $currentExcelSheet->getColumnDimension($col)->setWidth(12);
        }
        $styleArr = [
            'alignment' => [
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ],
            'font'      => [
                'bold' => true,
            ],
        ];
        $titleStyle = [
            'alignment' => [
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ],
            'font'      => [
                'bold' => true,
                'size' => 20,
            ],
        ];

        $currentExcelSheet->mergeCells('A1:C1');
        $currentExcelSheet->setCellValue('A1', 'feature: ' . $cmdRecord->feature_value);
        $currentExcelSheet->mergeCells('D1:I1');
        $currentExcelSheet->getStyle('D1')->applyFromArray($titleStyle);
        $currentExcelSheet->setCellValue('D1', $title);
        $currentExcelSheet->mergeCells('J1:L1');
        $currentExcelSheet->setCellValue('J1', date('Y-m-d H:i'));

        $currentExcelSheet->getStyle('A2:L2')->applyFromArray($styleArr);
        $currentExcelSheet->getStyle('A3:L3')->applyFromArray($styleArr);

        $currentExcelSheet->mergeCells('A2:C2');
        $currentExcelSheet->setCellValue('A2', '已日结记录');
        $currentExcelSheet->mergeCells('D2:E2');
        $currentExcelSheet->setCellValue('D2', '总发生金额');
        $currentExcelSheet->mergeCells('F2:L2');
        $currentExcelSheet->setCellValue('F2', '收款分类明细');

        $currentExcelSheet->setCellValue('A3', '营业日期');
        $currentExcelSheet->setCellValue('B3', '营收总额');
        $currentExcelSheet->setCellValue('C3', '收银次数');

        $currentExcelSheet->setCellValue('D3', '收款总额');
        $currentExcelSheet->setCellValue('E3', '现金收款');

        $currentExcelSheet->setCellValue('F3', '卡收款');
        $currentExcelSheet->setCellValue('G3', '微信收款');
        $currentExcelSheet->setCellValue('H3', '支付宝收款');
        $currentExcelSheet->setCellValue('I3', '其它收款');
        $currentExcelSheet->setCellValue('J3', '消费券收款');
        $currentExcelSheet->setCellValue('K3', '定金使用');
        $currentExcelSheet->setCellValue('L3', '退款总额');

        return 3;
    }

    /**
     * 写入每行数据
     */
    private function generateRow(&$currentExcelSheet, $lineNo, &$rowData)
    {
        $currentExcelSheet->setCellValue('A' . $lineNo, $rowData['stat_date']);
        $currentExcelSheet->setCellValue('B' . $lineNo, $rowData['pay_total']);
        $currentExcelSheet->setCellValue('C' . $lineNo, $rowData['balance_count']);


        $currentExcelSheet->setCellValue('D' . $lineNo, $rowData['in_pay_total']);
        $currentExcelSheet->setCellValue('E' . $lineNo, $rowData['in_cash_pay_total']);

        $currentExcelSheet->setCellValue('F' . $lineNo, $rowData['in_card_pay_total']);
        $currentExcelSheet->setCellValue('G' . $lineNo, $rowData['in_wechatpay_pay_total']);
        $currentExcelSheet->setCellValue('H' . $lineNo, $rowData['in_alipay_pay_total']);
        $currentExcelSheet->setCellValue('I' . $lineNo, $rowData['in_other_pay_total']);
        $currentExcelSheet->setCellValue('J' . $lineNo, $rowData['coupon_cost']);
        $currentExcelSheet->setCellValue('K' . $lineNo, $rowData['deposit_total']);
        $currentExcelSheet->setCellValue('L' . $lineNo, $rowData['out_pay_total']);
    }

    /**
     * 写入每行数据
     */
    private function generateFooter(&$currentExcelSheet, $lineNo, &$rowData)
    {
        $currentExcelSheet->getStyle('A'. $lineNo . ':' . 'L' . $lineNo)->getFont()->setBold(true);
        //修正 summary中文
        $rowData['stat_date'] = '总额合计';
        return $this->generateRow($currentExcelSheet, $lineNo, $rowData);
    }
}
