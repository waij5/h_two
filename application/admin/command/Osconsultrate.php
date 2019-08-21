<?php

namespace app\admin\command;

use app\admin\model\Admin;
use app\admin\model\CmdRecords;
use app\admin\model\CustomerOsconsult;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;

class Osconsultrate extends Command
{

    protected $model         = null;
    protected $linesPerExcel = 20000;
    protected $batchLimit    = 2000;

    protected function configure()
    {
        $this
            ->setName('osconsultrate')
            ->addOption('force', 'f', Option::VALUE_OPTIONAL, 'force override', false)
            ->addOption('record', 'r', option::VALUE_REQUIRED, 'command record id')
            ->setDescription('Generate all business consult statistic report');
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

        $where = array_merge($where, $extraWhere);

        $list = CustomerOsconsult::getSuccessStatistic($where);
        $total = count($list['subs']);
        //获取职员缓存信息
        // $adminModel     = new Admin;
        $briefAdminList = Admin::getAdminCache(Admin::ADMIN_BRIEF_CACHE_KEY);

        $currentCount   = 0;

        $excelDir = realpath(__DIR__ . '/../../data/reports/excels') . '/';
        $namePre  = '全业务现场统计_' . str_pad((string) $recordId, 11, '0', STR_PAD_LEFT);

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

        foreach ($list['subs'] as $key => $rowData) {
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
        $this->generateFooter($currentExcelSheet, $currentLineNo, $list['total']);

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
        $currentExcelSheet->getColumnDimension('A')->setWidth(15);
        $currentExcelSheet->getColumnDimension('P')->setWidth(12);

        $currentExcelSheet->mergeCells('A1:C1');
        $currentExcelSheet->setCellValue('A1', 'feature: ' . $cmdRecord->feature_value);
        $currentExcelSheet->mergeCells('D1:Y1');
        $currentExcelSheet->getStyle('D1')->getFont()->setBold(true)->setSize(20);
        $currentExcelSheet->setCellValue('D1', $title);
        $currentExcelSheet->mergeCells('Z1:AB1');
        $currentExcelSheet->setCellValue('Z1', date('Y-m-d H:i'));

        // for ($i = 'A'; $i <= 'Z'; $i ++) {
            $currentExcelSheet->getStyle('A2:AB2')->getFont()->setBold(true);
        // }

        $currentExcelSheet->setCellValue('A2', '现场客服');

        $currentExcelSheet->setCellValue('B2', '初次');
        $currentExcelSheet->setCellValue('C2', '成功');
        $currentExcelSheet->setCellValue('D2', '成功率');
        $currentExcelSheet->setCellValue('E2', '初次金额');

        $currentExcelSheet->setCellValue('F2', '复次');
        $currentExcelSheet->setCellValue('G2', '成功');
        $currentExcelSheet->setCellValue('H2', '成功率');
        $currentExcelSheet->setCellValue('I2', '复次金额');
        

        $currentExcelSheet->setCellValue('J2', '再消费');
        $currentExcelSheet->setCellValue('K2', '成功');
        $currentExcelSheet->setCellValue('L2', '成功率');
        $currentExcelSheet->setCellValue('M2', '再消费金额');

        $currentExcelSheet->setCellValue('N2', '复查');
        $currentExcelSheet->setCellValue('O2', '成功');
        $currentExcelSheet->setCellValue('P2', '成功率');
        $currentExcelSheet->setCellValue('Q2', '复查金额');

        $currentExcelSheet->setCellValue('R2', '其他');
        $currentExcelSheet->setCellValue('S2', '成功');
        $currentExcelSheet->setCellValue('T2', '成功率');
        $currentExcelSheet->setCellValue('U2', '其它金额');

        $currentExcelSheet->setCellValue('V2', '总接诊');
        $currentExcelSheet->setCellValue('W2', '总成功');
        $currentExcelSheet->setCellValue('X2', '总成功率');
        $currentExcelSheet->setCellValue('Y2', '总接诊率');

        $currentExcelSheet->setCellValue('Z2', '消费额');
        $currentExcelSheet->setCellValue('AA2', '占比%');
        $currentExcelSheet->setCellValue('AB2', '人均消费额');

        return 2;
    }

    /**
     * 写入每行数据
     */
    private function generateRow(&$currentExcelSheet, $lineNo, &$rowData)
    {
        $currentExcelSheet->setCellValue('A' . $lineNo, $rowData['staffName']);
        $currentExcelSheet->setCellValue('B' . $lineNo, $rowData['first_v_count']);
        $currentExcelSheet->setCellValue('C' . $lineNo, $rowData['first_v_success_count']);
        $currentExcelSheet->setCellValue('D' . $lineNo, $rowData['first_v_success_rate']);
        $currentExcelSheet->setCellValue('E' . $lineNo, $rowData['first_v_total']);

        $currentExcelSheet->setCellValue('F' . $lineNo, $rowData['return_v_count']);
        $currentExcelSheet->setCellValue('G' . $lineNo, $rowData['return_v_success_count']);
        $currentExcelSheet->setCellValue('H' . $lineNo, $rowData['return_v_success_rate']);
        $currentExcelSheet->setCellValue('I' . $lineNo, $rowData['return_v_total']);

        $currentExcelSheet->setCellValue('J' . $lineNo, $rowData['reconsume_count']);
        $currentExcelSheet->setCellValue('K' . $lineNo, $rowData['reconsume_success_count']);
        $currentExcelSheet->setCellValue('L' . $lineNo, $rowData['reconsume_success_rate']);
        $currentExcelSheet->setCellValue('M' . $lineNo, $rowData['reconsume_total']);

        $currentExcelSheet->setCellValue('N' . $lineNo, $rowData['review_v_count']);
        $currentExcelSheet->setCellValue('O' . $lineNo, $rowData['review_v_success_count']);
        $currentExcelSheet->setCellValue('P' . $lineNo, $rowData['review_v_success_rate']);
        $currentExcelSheet->setCellValue('Q' . $lineNo, $rowData['review_v_total']);

        $currentExcelSheet->setCellValue('R' . $lineNo, $rowData['other_v_count']);
        $currentExcelSheet->setCellValue('S' . $lineNo, $rowData['other_v_success_count']);
        $currentExcelSheet->setCellValue('T' . $lineNo, $rowData['other_v_success_rate']);
        $currentExcelSheet->setCellValue('U' . $lineNo, $rowData['other_v_total']);

        $currentExcelSheet->setCellValue('V' . $lineNo, $rowData['reception_total']);
        $currentExcelSheet->setCellValue('W' . $lineNo, $rowData['success_total']);
        $currentExcelSheet->setCellValue('X' . $lineNo, $rowData['success_total_rate']);
        $currentExcelSheet->setCellValue('Y' . $lineNo, $rowData['reception_percent']);

        $currentExcelSheet->setCellValue('Z' . $lineNo, $rowData['consumption_total']);
        $currentExcelSheet->setCellValue('AA' . $lineNo, $rowData['percent']);
        $currentExcelSheet->setCellValue('AB' . $lineNo, $rowData['consumption_per_person']);
    }

    /**
     * 写入每行数据
     */
    private function generateFooter(&$currentExcelSheet, $lineNo, &$rowData)
    {
        for ($i = 'A'; $i <= 'Z'; $i ++) {
            $currentExcelSheet->getStyle($i . $lineNo)->getFont()->setBold(true);
        }
        //修正 summary中文
        $rowData['staffName'] = '统计';
        return $this->generateRow($currentExcelSheet, $lineNo, $rowData);
    }
}
