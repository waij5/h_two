<?php

namespace app\admin\command;

use app\admin\model\Admin;
use app\admin\model\CmdRecords;
use app\admin\model\DeductStaffRecords;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;

class Operatebenefit extends Command
{
    protected $model         = null;
    protected $linesPerExcel = 10000;
    protected $batchLimit    = 2000;
    protected $typeList = array(
                                'project' => '项目',
                                'product_1' => '药品',
                                'product_2' => '物资',
    );

    protected function configure()
    {
        $this
            ->setName('operatebenefit')
            ->addOption('force', 'f', Option::VALUE_OPTIONAL, 'force override', false)
            ->addOption('record', 'r', option::VALUE_REQUIRED, 'command record id')
            ->setDescription('Generate work detail');
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

        $where = array_merge($where, $extraWhere);
        $summary = DeductStaffRecords::operateBenefitSummary($where, true);
        $total = $summary['count'];

        $maxBatchCount  = ceil($total / $this->batchLimit);
        $maxExcelCount  = ceil($total / $this->linesPerExcel);
        $currentCount   = 0;
        $currentBatchNo = 1;
        $currentExcelNo = 1;

        $excelDir = realpath(__DIR__ . '/../../data/reports/excels') . '/';
        $namePre  = '工作明细_' . str_pad((string) $recordId, 11, '0', STR_PAD_LEFT) . '_';

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

            $list = DeductStaffRecords::operateBenefitList($where, 'rec.createtime asc, staff_rec.admin_id', 'ASC', $batchOffset, $this->batchLimit);
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
                    $currentLineNo = $this->generateHeader($currentExcelSheet, $namePre . $currentExcelNo, $cmdRecord, $summary);
                    // $this->generateHeader($currentExcelSheet, $namePre . $currentExcelNo, $cmdRecord);
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
    private function generateHeader(&$currentExcelSheet, $title, &$cmdRecord, $data)
    {
        //column style
        $currentExcelSheet->getColumnDimension('A')->setWidth(18);
        $currentExcelSheet->getColumnDimension('C')->setWidth(18);

        $currentExcelSheet->mergeCells('A1:C1');
        $currentExcelSheet->setCellValue('A1', 'feature: ' . $cmdRecord->feature_value);
        $currentExcelSheet->mergeCells('D1:H1');
        $currentExcelSheet->getStyle('D1')->getFont()->setBold(true)->setSize(20);
        $currentExcelSheet->setCellValue('D1', $title);
        $currentExcelSheet->mergeCells('I1:K1');
        $currentExcelSheet->setCellValue('I1', date('Y-m-d H:i'));

        $currentExcelSheet->mergeCells('A2:K2');
        $currentExcelSheet->getStyle('A2')->getFont()->setBold(true)->setSize(16);
        $currentExcelSheet->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $currentExcelSheet->setCellValue('A2', '总划扣次数: ' . $data['total_deduct_times'] . ' 总划扣金额: ' . $data['total_deduct_amount'] . ' 总划扣收益金额: ' . $data['total_deduct_benefit_amount'] . ' 总提成(毛): ' . $data['total_final_amount'] . ' 总提成(净): ' . $data['total_final_benefit_amount']);

        $currentExcelSheet->setCellValue('A3', '职员');
        $currentExcelSheet->setCellValue('B3', '角色');
        $currentExcelSheet->setCellValue('C3', '划扣时间');
        $currentExcelSheet->setCellValue('D3', '项目/产品');
        $currentExcelSheet->setCellValue('E3', '类型');

        $currentExcelSheet->setCellValue('F3', '客户');
        $currentExcelSheet->setCellValue('G3', '次数');
        $currentExcelSheet->setCellValue('H3', '划扣金额');
        $currentExcelSheet->setCellValue('I3', '提成比例');
        $currentExcelSheet->setCellValue('J3', '提成金额(毛)');
        $currentExcelSheet->setCellValue('K3', '提成金额(净)');


        return 3;
    }

    /**
     * 写入每行数据
     */
    private function generateRow(&$currentExcelSheet, $lineNo, &$rowData)
    {
        $customerStr = '';
        if ($rowData['customer_id']) {
            $customerStr = '<' . $rowData['customer_id'] . '>' . ($rowData['ctm_name'] ? $rowData['ctm_name'] : '');
        }
        $typeStr = isset($this->typeList[$rowData['item_type']]) ? $this->typeList[$rowData['item_type']] : '';

        $currentExcelSheet->setCellValue('A' . $lineNo, $rowData['nickname']);
        $currentExcelSheet->setCellValue('B' . $lineNo, empty($rowData['deduct_role_name']) ? '' : $rowData['deduct_role_name']);
        $currentExcelSheet->setCellValue('C' . $lineNo, date('Y-m-d', $rowData['createtime']));
        $currentExcelSheet->setCellValue('D' . $lineNo, $rowData['pro_name']);
        $currentExcelSheet->setCellValue('E' . $lineNo, $typeStr);

        $currentExcelSheet->setCellValue('F' . $lineNo, $customerStr);
        $currentExcelSheet->setCellValue('G' . $lineNo, $rowData['deduct_times']);
        $currentExcelSheet->setCellValue('H' . $lineNo, $rowData['deduct_amount']);
        $currentExcelSheet->setCellValue('I' . $lineNo, $rowData['final_percent']);
        $currentExcelSheet->setCellValue('J' . $lineNo, $rowData['final_amount']);
        $currentExcelSheet->setCellValue('K' . $lineNo, $rowData['final_benefit_amount']);
    }

}
