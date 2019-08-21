<?php

namespace app\admin\command;

use PHPExcel;
use PHPExcel_IOFactory;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;

use app\admin\model\OrderChangeLog;
use app\admin\model\Customer;
use app\admin\model\OrderItems;
use app\admin\model\Admin;
use app\admin\model\CmdRecords;


class Orderitemschangereport extends Command
{
    protected $model          = null;
    protected $linesPerExcel  = 20000;
    protected $batchLimit     = 2000;
    protected $briefAdminList = [];
    protected $deptList       = [];

    protected function configure()
    {
        $this
            ->setName('Orderitemschangereport')
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
        $where = array_merge($where, $extraWhere);

        $total = OrderChangeLog::alias('order_change_log')
            ->join(Customer::getTable() . ' customer', 'order_change_log.customer_id = customer.ctm_id', 'LEFT')
            ->join(OrderItems::getTable() . ' items', 'order_change_log.original_item_id = items.item_id', 'LEFT')
            ->join(admin::getTable() . ' developer', 'items.consult_admin_id = developer.id', 'LEFT')
            ->join(admin::getTable() . ' osconsulter', 'items.admin_id = osconsulter.id', 'LEFT')
            ->join(admin::getTable() . ' recepter', 'items.recept_admin_id = recepter.id', 'LEFT')
            ->where($where)
            ->count();

        $maxBatchCount  = ceil($total / $this->batchLimit);
        $maxExcelCount  = ceil($total / $this->linesPerExcel);
        $currentCount   = 0;
        $currentBatchNo = 1;
        $currentExcelNo = 1;

        $excelDir = realpath(__DIR__ . '/../../data/reports/excels') . '/';
        $namePre  = '订单项变动' . str_pad((string) $recordId, 11, '0', STR_PAD_LEFT) . '_';

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

            $sort    = 'order_change_log.createtime';
            $order   = 'asc';
            $list = OrderChangeLog::alias('order_change_log')
                ->join(Customer::getTable() . ' customer', 'order_change_log.customer_id = customer.ctm_id', 'LEFT')
                ->join(OrderItems::getTable() . ' items', 'order_change_log.original_item_id = items.item_id', 'LEFT')
                ->join(admin::getTable() . ' developer', 'items.consult_admin_id = developer.id', 'LEFT')
                ->join(admin::getTable() . ' osconsulter', 'items.admin_id = osconsulter.id', 'LEFT')
                ->join(admin::getTable() . ' recepter', 'items.recept_admin_id = recepter.id', 'LEFT')
                ->where($where)
                ->order($sort, $order)
                ->column('order_change_log.log_id, order_change_log.original_item_id, order_change_log.customer_id, customer.ctm_name, order_change_log.type, ( CASE WHEN order_change_log.change_type = "CHANGE_TYPE_RETURN" THEN "退" ELSE "换" END) AS change_type, order_change_log.old_name, order_change_log.new_name, order_change_log.deposit_change, FROM_UNIXTIME( order_change_log.createtime, "%Y-%m-%d %H:%i:%s" ) AS createtime, developer.nickname AS developer_name, osconsulter.nickname AS osconsulter_name, recepter.nickname AS recepter_name');

            foreach($list as $key => $rowData){

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

        $currentExcelSheet->setCellValue('A1', '编号');
        $currentExcelSheet->setCellValue('B1', '原订单号');
        $currentExcelSheet->setCellValue('C1', '卡号');
        $currentExcelSheet->setCellValue('D1', '姓名');

        $currentExcelSheet->setCellValue('E1', '类型');
        $currentExcelSheet->setCellValue('F1', '退换');
        $currentExcelSheet->setCellValue('G1', '原项目名');
        $currentExcelSheet->setCellValue('H1', '新项目名');

        $currentExcelSheet->setCellValue('I1', '定金');
        $currentExcelSheet->setCellValue('J1', '时间');
        $currentExcelSheet->setCellValue('K1', '网络客服');

        $currentExcelSheet->setCellValue('L1', '现场客服');
        $currentExcelSheet->setCellValue('M1', '分派人员');

        return 1;
    }

    /**
     * 写入每行数据
     */
    private function generateRow(&$currentExcelSheet, $lineNo, &$rowData)
    {
        $currentExcelSheet->setCellValue('A' . $lineNo, $rowData['log_id']);
        $currentExcelSheet->setCellValue('B' . $lineNo, $rowData['original_item_id']);
        $currentExcelSheet->setCellValue('C' . $lineNo, $rowData['customer_id']);
        $currentExcelSheet->setCellValue('D' . $lineNo, $rowData['ctm_name']);

        $currentExcelSheet->setCellValue('E' . $lineNo, $rowData['type']);

        $currentExcelSheet->setCellValue('F' . $lineNo, $rowData['change_type']);
        $currentExcelSheet->setCellValue('G' . $lineNo, $rowData['old_name']);
        $currentExcelSheet->setCellValue('H' . $lineNo, $rowData['new_name']);
        $currentExcelSheet->setCellValue('I' . $lineNo, $rowData['deposit_change']);

        $currentExcelSheet->setCellValue('J' . $lineNo, $rowData['createtime']);
        $currentExcelSheet->setCellValue('K' . $lineNo, $rowData['developer_name']);
        $currentExcelSheet->setCellValue('L' . $lineNo, $rowData['osconsulter_name']);
        $currentExcelSheet->setCellValue('M' . $lineNo, $rowData['recepter_name']);
    }
}
