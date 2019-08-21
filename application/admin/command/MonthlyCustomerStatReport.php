<?php

namespace app\admin\command;

use app\admin\model\Admin;
use app\admin\model\CmdRecords;
use app\admin\model\DeductRecords;
use app\admin\model\Deptment;
use app\admin\model\MonthCustomerStat;
use PHPExcel;
use PHPExcel_IOFactory;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;

class MonthlyCustomerStatReport extends Command
{
    protected $model            = null;
    protected $linesPerExcel    = 20000;
    protected $batchLimit       = 2000;
    protected $briefAdminList   = [];
    protected $deptList         = [];
  

    protected function configure()
    {
        $this
            ->setName('monthlycustomerstatreport')
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
            $where      = $params['where'];
            $extraWhere = $params['extraWhere'];
        }

        // $total = MonthCustomerStat::where($where)->count();
        //默认当月
        $statDateStart = isset($where['stat_date'][1][0]) ? $where['stat_date'][1][0] : date('Y-m', time());
        $statDateEnd = isset($where['stat_date'][1][1]) ? $where['stat_date'][1][1] : date('Y-m', time());
        //期初
        $lastPeriod = date('Y-m', strtotime('-1 month', strtotime($statDateStart)));
        $pureWhere = $where;
        unset($pureWhere['stat_date']);
        $total = MonthCustomerStat::getDataListCnt($statDateStart, $statDateEnd, $lastPeriod, $pureWhere);


        $this->briefAdminList = Admin::getAdminCache(Admin::ADMIN_BRIEF_CACHE_KEY);
        $this->deptList = Deptment::getDeptListCache();

        $maxBatchCount  = ceil($total / $this->batchLimit);
        $maxExcelCount  = ceil($total / $this->linesPerExcel);
        $currentCount   = 0;
        $currentBatchNo = 1;
        $currentExcelNo = 1;

        $excelDir = realpath(__DIR__ . '/../../data/reports/excels') . '/';
        $namePre  = '顾客月度信息统计' . str_pad((string) $recordId, 11, '0', STR_PAD_LEFT) . '_';

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

        for (; $currentBatchNo <= $maxBatchCount; $currentBatchNo++) {
            $batchOffset = ($currentBatchNo - 1) * $this->batchLimit;
        // $statDateStart = isset($where['stat_date'][1][0]) ? $where['stat_date'][1][0] : date('Y-m', time());
        // $lastPeriod = date('Y-m', strtotime('-1 month', strtotime($statDateStart)));
        $sort = 'customer_id';
        $order = 'asc';
        $list = MonthCustomerStat::getDataList($statDateStart, $statDateEnd, $lastPeriod, $pureWhere, $sort, $order, $batchOffset, $this->batchLimit);
            foreach ($list as $key => $rowData) {
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
                    $currentLineNo = $this->generateHeader($currentExcelSheet, $namePre . $currentExcelNo, $cmdRecord);
                    $currentLineNo ++;
                }

                $this->generateRow($currentExcelSheet, $currentLineNo, $rowData);

                //进度信息
                $processJson = array(
                    'completedCount' => $currentCount,
                    'total'          => $total,
                    'status'         => CmdRecords::STATUS_PROCESSING,
                    'statusText'     => CmdRecords::STATUS_PROCESSING,
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
        //column style
        $currentExcelSheet->getColumnDimension('A')->setWidth(18);
        $currentExcelSheet->getColumnDimension('C')->setWidth(18);

        $currentExcelSheet->mergeCells('A1:C1');
        $currentExcelSheet->setCellValue('A1', 'feature: ' . $cmdRecord->feature_value);
        $currentExcelSheet->mergeCells('D1:AB1');
        $currentExcelSheet->getStyle('D1')->getFont()->setBold(true)->setSize(20);
        $currentExcelSheet->setCellValue('D1', $title);
        $currentExcelSheet->mergeCells('AC1:AE1');
        $currentExcelSheet->setCellValue('AC1', date('Y-m-d H:i'));

        $currentExcelSheet->setCellValue('A2', '顾客卡号');
        $currentExcelSheet->setCellValue('B2', '顾客');
        $currentExcelSheet->setCellValue('C2', '定金余额');
        $currentExcelSheet->setCellValue('D2', '上期定金余额');

        $currentExcelSheet->setCellValue('E2', '定金变动');
        $currentExcelSheet->setCellValue('F2', '上期定金变动');
        $currentExcelSheet->setCellValue('G2', '未划扣余额');
        $currentExcelSheet->setCellValue('H2', '上期未划扣余额');

        $currentExcelSheet->setCellValue('I2', '总未出帐金额');
        $currentExcelSheet->setCellValue('J2', '上期总未出帐金额');
        $currentExcelSheet->setCellValue('K2', '本月划扣金额');
        $currentExcelSheet->setCellValue('L2', '上期划扣余额');

        $currentExcelSheet->setCellValue('M2', '划扣收益金额');
        $currentExcelSheet->setCellValue('N2', '上期划扣收益金额');
        $currentExcelSheet->setCellValue('O2', '等级积分');
        $currentExcelSheet->setCellValue('P2', '上期等级积分');
        $currentExcelSheet->setCellValue('Q2', '消费积分');
        $currentExcelSheet->setCellValue('R2', '上期消费积分');
        $currentExcelSheet->setCellValue('S2', '等级积分变动');
        $currentExcelSheet->setCellValue('T2', '上期等级积分变动');

        $currentExcelSheet->setCellValue('U2', '消费积分变动');
        $currentExcelSheet->setCellValue('V2', '上期消费积分变动');
        
        $currentExcelSheet->setCellValue('W2', '原始支付额');
        $currentExcelSheet->setCellValue('X2', '上期原始支付额');
        $currentExcelSheet->setCellValue('Y2', '变动额');

        $currentExcelSheet->setCellValue('Z2', '上期变动额');
        $currentExcelSheet->setCellValue('AA2', '收银金额');
        $currentExcelSheet->setCellValue('AB2', '上期收银金额');
        $currentExcelSheet->setCellValue('AC2', '阶段收款');
        $currentExcelSheet->setCellValue('AD2', '结算日期');

        return 2;
    }

    /**
     * 写入每行数据
     */
    private function generateRow(&$currentExcelSheet, $lineNo, &$rowData)
    {
        $currentExcelSheet->setCellValue('A' . $lineNo, $rowData['customer_id']);
        $currentExcelSheet->setCellValue('B' . $lineNo, $rowData['customer_name']);
        $currentExcelSheet->setCellValue('C' . $lineNo, $rowData['depositamt']);
        $currentExcelSheet->setCellValue('D' . $lineNo, $rowData['last_depositamt']);
        $currentExcelSheet->setCellValue('E' . $lineNo, $rowData['deposit_change']);
        $currentExcelSheet->setCellValue('F' . $lineNo, $rowData['last_deposit_change']);
        $currentExcelSheet->setCellValue('G' . $lineNo, $rowData['undeducted_total']);
        $currentExcelSheet->setCellValue('H' . $lineNo, $rowData['last_undeducted_total']);
        $currentExcelSheet->setCellValue('I' . $lineNo, $rowData['not_out_total']);

        $currentExcelSheet->setCellValue('J' . $lineNo, $rowData['last_not_out_total']);
        $currentExcelSheet->setCellValue('K' . $lineNo, $rowData['deducted_total']);
        $currentExcelSheet->setCellValue('L' . $lineNo, $rowData['last_deducted_total']);
        $currentExcelSheet->setCellValue('M' . $lineNo, $rowData['deducted_benefit_total']);

        $currentExcelSheet->setCellValue('N' . $lineNo, $rowData['last_deducted_benefit_total']);
        $currentExcelSheet->setCellValue('O' . $lineNo, $rowData['rank_points']);
        $currentExcelSheet->setCellValue('P' . $lineNo, $rowData['last_rank_points']);
        $currentExcelSheet->setCellValue('Q' . $lineNo, $rowData['pay_points']);
        $currentExcelSheet->setCellValue('R' . $lineNo, $rowData['last_pay_points']);
        $currentExcelSheet->setCellValue('S' . $lineNo, $rowData['rank_points_change']);
        $currentExcelSheet->setCellValue('T' . $lineNo, $rowData['last_rank_points_change']);

        $currentExcelSheet->setCellValue('U' . $lineNo, $rowData['pay_points_change']);
        $currentExcelSheet->setCellValue('V' . $lineNo, $rowData['last_pay_points_change']);
        $currentExcelSheet->setCellValue('W' . $lineNo, $rowData['item_original_pay_total']);
        $currentExcelSheet->setCellValue('X' . $lineNo, $rowData['last_item_original_pay_total']);
        
        $currentExcelSheet->setCellValue('Y' . $lineNo, $rowData['item_switch_total']);
        $currentExcelSheet->setCellValue('Z' . $lineNo, $rowData['last_item_switch_total']);
        $currentExcelSheet->setCellValue('AA' . $lineNo, $rowData['balance_total']);
        $currentExcelSheet->setCellValue('AB' . $lineNo, $rowData['last_balance_total']);
        $currentExcelSheet->setCellValue('AC' . $lineNo, $rowData['period_balance_total']);
        $currentExcelSheet->setCellValue('AD' . $lineNo, $rowData['stat_date']);

    }
}
