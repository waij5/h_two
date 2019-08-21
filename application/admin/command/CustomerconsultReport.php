<?php

namespace app\admin\command;

use app\admin\model\Admin;
use app\admin\model\CmdRecords;
use app\admin\model\CocAcceptTool;
use app\admin\model\CustomerOsconsult;
use app\admin\model\CustomerConsult;
use app\admin\model\Osctype;
use PHPExcel;
use PHPExcel_IOFactory;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;
use think\Db;

class CustomerconsultReport extends Command
{
    protected $model          = null;
    protected $linesPerExcel  = 20000;
    protected $batchLimit     = 2000;
    protected $briefAdminList = [];
    protected $deptList       = [];

    protected function configure()
    {
        $this
            ->setName('CustomerconsultReport')
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
        
        if (isset($where['customer.ctm_mobile'])) {
            $mobileOperator  = $where['customer.ctm_mobile'][0];
            $mobileCondition = $where['customer.ctm_mobile'][1];
            unset($where['customer.ctm_mobile']);
            $where[] = function ($query) use ($mobileOperator, $mobileCondition) {
                $query->where('customer.ctm_mobile', $mobileOperator, $mobileCondition)
                    ->whereOr('customer.ctm_tel', $mobileOperator, $mobileCondition);
            };
        }

        $cst   = new CustomerConsult;
        $total = $cst->getListCount($where, $extraWhere);

        $maxBatchCount  = ceil($total / $this->batchLimit);
        $maxExcelCount  = ceil($total / $this->linesPerExcel);
        $currentCount   = 0;
        $currentBatchNo = 1;
        $currentExcelNo = 1;

        $excelDir = realpath(__DIR__ . '/../../data/reports/excels') . '/';
        $namePre  = '网电信息' . str_pad((string) $recordId, 11, '0', STR_PAD_LEFT) . '_';

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

            $sort  = 'cst.cst_id';
            $order = 'desc';

            $list = $cst->getList($where, $sort, $order, $batchOffset, $this->batchLimit, $extraWhere);

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
        $currentExcelSheet->getColumnDimension('A')->setWidth(18);
        $currentExcelSheet->getColumnDimension('C')->setWidth(18);

        $currentExcelSheet->setCellValue('A1', '客户状态');
        $currentExcelSheet->setCellValue('B1', '受理时间');
        $currentExcelSheet->setCellValue('C1', '最近到诊');

        $currentExcelSheet->setCellValue('D1', '顾客卡号');
        $currentExcelSheet->setCellValue('E1', '顾客姓名');
        $currentExcelSheet->setCellValue('F1', '手机号码');
        $currentExcelSheet->setCellValue('G1', '地址');

        $currentExcelSheet->setCellValue('H1', '客服项目');
        $currentExcelSheet->setCellValue('I1', '客服科室');
        $currentExcelSheet->setCellValue('J1', '备注');

        $currentExcelSheet->setCellValue('K1', '网络客服');
        $currentExcelSheet->setCellValue('L1', '营销渠道');
        $currentExcelSheet->setCellValue('M1', '客户来源');
        $currentExcelSheet->setCellValue('N1', '受理人员');
        $currentExcelSheet->setCellValue('O1', '受理工具');
        $currentExcelSheet->setCellValue('P1', '现场客服');
        $currentExcelSheet->setCellValue('Q1', '预约时间');
        $currentExcelSheet->setCellValue('R1', '预约状态');

        return 1;
    }

    /**
     * 写入每行数据
     */
    private function generateRow(&$currentExcelSheet, $lineNo, &$rowData)
    {
        $oscTypeList = Osctype::getList();
        $toolList    = CocAcceptTool::getList();
        $arrStatus      = [
            '0'  => '未上门',
            '1'  => '已上门',
        ];
        $cstStatus      = [
            '0'  => '未预约',
            '1'  => '预约',
            '2'  => '已到诊',
            '3'  => '已过时',
        ];

        if ($rowData['createtime'] == 0){
            $createtime = '--';
        } else {
            $createtime = (date('Y-m-d H:i:s', $rowData['createtime']));
        }
        if ($rowData['coctime'] == 0){
            $coctime = '--';
        } else {
            $coctime = (date('Y-m-d H:i:s', $rowData['coctime']));
        }
        if ($rowData['coc_admin_id'] == 0){
            $coc_admin_id = '--';
        } else {
            $coc_admin_id = $rowData['coc_admin_id'];
        }

        $currentExcelSheet->setCellValue('A' . $lineNo, isset($arrStatus[$rowData['arrive_status']]) ? $arrStatus[$rowData['arrive_status']] : '');

        $currentExcelSheet->setCellValue('B' . $lineNo, $createtime);
        $currentExcelSheet->setCellValue('C' . $lineNo, $coctime);
        $currentExcelSheet->setCellValue('D' . $lineNo, $rowData['ctm_id']);

        $currentExcelSheet->setCellValue('E' . $lineNo, $rowData['ctm_name']);
        $currentExcelSheet->setCellValue('F' . $lineNo, $rowData['ctm_mobile']);
        $currentExcelSheet->setCellValue('G' . $lineNo, $rowData['ctm_addr']);
        $currentExcelSheet->setCellValue('H' . $lineNo, $rowData['cpdt_name']);

        $currentExcelSheet->setCellValue('I' . $lineNo, $rowData['dept_id']);
        $currentExcelSheet->setCellValue('J' . $lineNo, $rowData['cst_content']);
        $currentExcelSheet->setCellValue('K' . $lineNo, $rowData['develop_staff_name']);

        $currentExcelSheet->setCellValue('L' . $lineNo, $rowData['ctm_explore']);
        $currentExcelSheet->setCellValue('M' . $lineNo, $rowData['ctm_source']);
        $currentExcelSheet->setCellValue('N' . $lineNo, $rowData['admin_nickname']);
        $currentExcelSheet->setCellValue('O' . $lineNo, isset($toolList[$rowData['tool_id']]) ? $toolList[$rowData['tool_id']] : '');
        $currentExcelSheet->setCellValue('P' . $lineNo, $coc_admin_id);
        $currentExcelSheet->setCellValue('Q' . $lineNo, is_null($rowData['book_time']) ?  '' : (date('Y-m-d H:i:s', $rowData['book_time'])));
        $currentExcelSheet->setCellValue('R' . $lineNo, isset($cstStatus[$rowData['cst_status']]) ? $cstStatus[$rowData['cst_status']] : '');
    }
}
