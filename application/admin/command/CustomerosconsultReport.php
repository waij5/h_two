<?php

namespace app\admin\command;

use app\admin\model\Admin;
use app\admin\model\CmdRecords;
use app\admin\model\CocAcceptTool;
use app\admin\model\CustomerOsconsult;
use app\admin\model\Osctype;
use PHPExcel;
use PHPExcel_IOFactory;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;
use think\Db;

class CustomerosconsultReport extends Command
{
    protected $model          = null;
    protected $linesPerExcel  = 20000;
    protected $batchLimit     = 2000;
    protected $briefAdminList = [];
    protected $deptList       = [];

    protected function configure()
    {
        $this
            ->setName('CustomerosconsultReport')
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
        // $where = array_merge($where, $extraWhere);

        $total = 1;
        $currentCount   = 0;
        $currentBatchNo = 1;
        $currentExcelNo = 1;

        $excelDir = realpath(__DIR__ . '/../../data/reports/excels') . '/';
        $namePre  = '到诊信息' . str_pad((string) $recordId, 11, '0', STR_PAD_LEFT) . '_';

        //进度信息
        $processJson = array(
            'completedCount' => $currentCount,
            'total'          => $total,
            'status'         => CmdRecords::STATUS_PROCESSING,
            'statusText'     => CmdRecords::STATUS_PROCESSING,
        );
        $cmdRecord->delayUpdateProcessInfo($currentCount, $processJson, false);


        $osc   = new CustomerOsconsult;
        $total = $osc->getListCount($where, $extraWhere);

        $maxBatchCount  = ceil($total / $this->batchLimit);
        $maxExcelCount  = ceil($total / $this->linesPerExcel);

        $currentExcel      = new PHPExcel();
        $currentExcelSheet = $currentExcel->getActiveSheet();
        //头部标题填充
        $currentLineNo = $this->generateHeader($currentExcelSheet, $namePre . $currentExcelNo, $cmdRecord);

        for (; $currentBatchNo <= $maxBatchCount; $currentBatchNo++) {
            $batchOffset = ($currentBatchNo - 1) * $this->batchLimit;

            $sort  = 'coc.osc_id';
            $order = 'asc';

            $list = $osc->getList($where, $sort, $order, $batchOffset, $this->batchLimit, $extraWhere);

            foreach ($list as $key => $rowData) {

                $currentCount++;
                $currentLineNo++;

                //个数超过当前EXCEL的设定限制后，生成新的EXCEL文档
                if ($currentCount > ($currentExcelNo * $this->linesPerExcel)) {
                    if ($currentExcel) {
                        //进度信息
                        $processJson = array(
                            'completedCount' => $currentCount,
                            'total'          => $total,
                            'status'         => CmdRecords::STATUS_PROCESSING,
                            'statusText'     => "进行中...第{$currentExcelNo}个EXCEL保存中(excels: {$currentExcelNo} / {$maxExcelCount})",
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
                    $currentLineNo++;
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
        $currentExcelSheet->getColumnDimension('K')->setWidth(16);
        $currentExcelSheet->getColumnDimension('L')->setWidth(13);
        $currentExcelSheet->getColumnDimension('M')->setWidth(13);
        $currentExcelSheet->getColumnDimension('N')->setWidth(13);
        $currentExcelSheet->getColumnDimension('P')->setWidth(13);
        $currentExcelSheet->getColumnDimension('O')->setWidth(24);
        $currentExcelSheet->getColumnDimension('S')->setWidth(10);
        $currentExcelSheet->getColumnDimension('U')->setWidth(18);

        $currentExcelSheet->getStyle('A1:V1')->getFont()->setBold(true)->setSize(13);
        $currentExcelSheet->getStyle('O')->getAlignment()->setWrapText(true);


        $currentExcelSheet->setCellValue('A1', '客服状态');
        $currentExcelSheet->setCellValue('B1', '分诊时间');
        $currentExcelSheet->setCellValue('C1', '类型');
        $currentExcelSheet->setCellValue('D1', '顾客姓名');

        $currentExcelSheet->setCellValue('E1', '顾客卡号');
        $currentExcelSheet->setCellValue('F1', '消费积分');
        $currentExcelSheet->setCellValue('G1', '手机号码');
        $currentExcelSheet->setCellValue('H1', '项目(网)');
        $currentExcelSheet->setCellValue('I1', '科室(网)');
        $currentExcelSheet->setCellValue('J1', '现场客服');

        $currentExcelSheet->setCellValue('K1', '网络客服');
        $currentExcelSheet->setCellValue('L1', '营销渠道');
        $currentExcelSheet->setCellValue('M1', '客户来源');
        $currentExcelSheet->setCellValue('N1', '指派人员');
        $currentExcelSheet->setCellValue('O1', '客服内容');
        $currentExcelSheet->setCellValue('P1', '客服项目');

        $currentExcelSheet->setCellValue('Q1', '客服科室');
        $currentExcelSheet->setCellValue('R1', '受理工具');
        $currentExcelSheet->setCellValue('S1', '日期');
        $currentExcelSheet->setCellValue('T1', '失败原由');
        $currentExcelSheet->setCellValue('U1', '系统录入时间');
        $currentExcelSheet->setCellValue('V1', '导医');

        $currentExcelSheet->freezePane('A2');

        return 1;
    }

    /**
     * 写入每行数据
     */
    private function generateRow(&$currentExcelSheet, $lineNo, &$rowData)
    {
        $oscTypeList = Osctype::getList();
        $toolList    = CocAcceptTool::getList();
        $status      = [
            '0'  => '已分派',
            '1'  => '服务中',
            '2'  => '成功',
            '3'  => '已成交',
            '-1' => '拒绝',
            '-2' => '未成交',
            '-3' => '中止',
        ];

        $currentExcelSheet->setCellValue('A' . $lineNo, isset($status[$rowData['osc_status']]) ? $status[$rowData['osc_status']] : '');
        $currentExcelSheet->setCellValue('B' . $lineNo, date('H:i', $rowData['createtime']));
        $currentExcelSheet->setCellValue('C' . $lineNo, $oscTypeList[$rowData['osc_type']]);
        $currentExcelSheet->setCellValue('D' . $lineNo, $rowData['ctm_name']);

        $currentExcelSheet->setCellValue('E' . $lineNo, $rowData['customer_id']);
        $currentExcelSheet->setCellValue('F' . $lineNo, $rowData['ctm_pay_points']);
        $currentExcelSheet->setCellValue('G' . $lineNo, $rowData['ctm_mobile']);
        $currentExcelSheet->setCellValue('H' . $lineNo, $rowData['cst_cpdt_name']);
        $currentExcelSheet->setCellValue('I' . $lineNo, $rowData['cst_dept_name']);
        $currentExcelSheet->setCellValue('J' . $lineNo, $rowData['admin_name']);

        $currentExcelSheet->setCellValue('K' . $lineNo, $rowData['develop_admin_name']);
        $currentExcelSheet->setCellValue('L' . $lineNo, $rowData['ctm_explore']);
        $currentExcelSheet->setCellValue('M' . $lineNo, $rowData['ctm_source']);
        $currentExcelSheet->setCellValue('N' . $lineNo, $rowData['operator_name']);
        $currentExcelSheet->setCellValue('O' . $lineNo, $rowData['osc_content']);
        $currentExcelSheet->setCellValue('P' . $lineNo, $rowData['cpdt_name']);

        $currentExcelSheet->setCellValue('Q' . $lineNo, $rowData['dept_name']);
        // $toolList[$rowData['tool_id']]
        $currentExcelSheet->setCellValue('R' . $lineNo, isset($toolList[$rowData['tool_id']]) ? $toolList[$rowData['tool_id']] : '');
        $currentExcelSheet->setCellValue('S' . $lineNo, date('Y-m-d', $rowData['createtime']));
        $currentExcelSheet->setCellValue('T' . $lineNo, $rowData['fat_name']);
        $currentExcelSheet->setCellValue('U' . $lineNo, date('Y-m-d H:i:s', $rowData['ctm_createtime']));
        $currentExcelSheet->setCellValue('V' . $lineNo, $rowData['service_admin_name']);
    }
}
