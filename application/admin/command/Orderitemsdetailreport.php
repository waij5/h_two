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
use app\admin\model\Osctype;

class Orderitemsdetailreport extends Command
{
    protected $model         = null;
    protected $linesPerExcel = 20000;
    protected $batchLimit    = 4000;

    protected $briefAdminList = [];
    protected $deptList = [];
    protected $toolList = [];
    protected $cpdtList = [];
    protected $itemType = [
        '9'    => '项目单',
        '1'    => '处方单',
        '2'    => '产品单',
    ];

    protected function configure()
    {
        $this
            ->setName('orderitemsdetailreport')
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
        $summary = Report::getOrderItemsDetailCntNdSummary2($where, $extraWhere, true);
        $total = $summary['count'];
        
        //初始化额外列表信息
        $this->briefAdminList = Admin::getAdminCache(Admin::ADMIN_BRIEF_CACHE_KEY);
        $this->deptList = \app\admin\model\Deptment::getDeptListCache();
        $this->toolList = \app\admin\model\CocAcceptTool::getList();
        $this->cpdtList = \app\admin\model\CProject::field('*')->column('cpdt_name', 'id');

        $maxBatchCount  = ceil($total / $this->batchLimit);
        $maxExcelCount  = ceil($total / $this->linesPerExcel);
        $currentCount   = 0;
        $currentBatchNo = 1;
        $currentExcelNo = 1;

        $excelDir = realpath(__DIR__ . '/../../data/reports/excels') . '/';
        $namePre  = '顾客订购项目明细_' . str_pad((string) $recordId, 11, '0', STR_PAD_LEFT) . '_';

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
            $list = Report::getOrderItemsDetail2($where, $batchOffset, $this->batchLimit, $extraWhere);
            foreach ($list as $key => $rowData) {
                $currentCount++;
                $currentLineNo++;
                //个数超过当前EXCEL的设定限制后，生成新的EXCEL文档
                if ($currentCount > ($currentExcelNo * $this->linesPerExcel)) {
                    if ($currentExcel) {
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

                $this->generateRow($currentExcelSheet, $currentLineNo, $rowData);

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
    private function generateHeader(&$currentExcelSheet, $title, &$cmdRecord, $data = [])
    {
        // 1-14 A-N
        //column style
        $currentExcelSheet->getColumnDimension('A')->setWidth(18);
        $currentExcelSheet->getColumnDimension('C')->setWidth(18);

        $currentExcelSheet->mergeCells('A1:C1');
        $currentExcelSheet->setCellValue('A1', 'feature: ' . $cmdRecord->feature_value);
        $currentExcelSheet->mergeCells('D1:R1');
        $currentExcelSheet->getStyle('D1')->getFont()->setBold(true)->setSize(20);
        $currentExcelSheet->setCellValue('D1', $title);
        $currentExcelSheet->mergeCells('S1:U1');
        $currentExcelSheet->setCellValue('S1', date('Y-m-d H:i'));


        $currentExcelSheet->getStyle('A2:O2')->getFont()->setBold(false)->setSize(18);
        $currentExcelSheet->setCellValue('A2', '营收总额');
        $currentExcelSheet->setCellValue('B2', $data['item_pay_total']);
        $currentExcelSheet->setCellValue('C2', '初始营收');
        $currentExcelSheet->setCellValue('D2', $data['item_original_pay_total']);
        $currentExcelSheet->getStyle('A3:N3')->getFont()->setBold(false)->setSize(16);
        $currentExcelSheet->setCellValue('A3', '项目总数');



        $currentExcelSheet->setCellValue('B3', $data['uniq_customer_count']);
        $currentExcelSheet->setCellValue('C3', '顾客数');

        $currentExcelSheet->setCellValue('D3', $data['count']);
        $currentExcelSheet->setCellValue('E3', '已消费金额');
        $currentExcelSheet->setCellValue('F3', $data['deducted_total']);
        $currentExcelSheet->setCellValue('G3', '未消费金额');
        $currentExcelSheet->setCellValue('H3', $data['undeducted_total']);
        $currentExcelSheet->setCellValue('I3', ' 总次数');
        $currentExcelSheet->setCellValue('J3', $data['total_times']);
        $currentExcelSheet->setCellValue('K3', '已使用次数');
        $currentExcelSheet->setCellValue('L3', $data['used_total_times']);


        $currentExcelSheet->getStyle('D1:R1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $currentExcelSheet->getStyle('A4:O4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $currentExcelSheet->getStyle('A4:O4')->getFont()->setBold(true)->setSize(16);
        $currentExcelSheet->getStyle('J2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $currentExcelSheet->setCellValue('A5', '收银时间');
        $currentExcelSheet->setCellValue('B5', '客户卡号');
        $currentExcelSheet->setCellValue('C5', '顾客');
        $currentExcelSheet->setCellValue('D5', '项目名');

        $currentExcelSheet->setCellValue('E5', '项目规格');
        $currentExcelSheet->setCellValue('F5', '首次项目[网]');
        $currentExcelSheet->setCellValue('G5', '划扣科室');
        $currentExcelSheet->setCellValue('H5', '使用次数');
        $currentExcelSheet->setCellValue('I5', '总次数');
        $currentExcelSheet->setCellValue('J5', '单次价格');
        $currentExcelSheet->setCellValue('K5', '项目总价');

        $currentExcelSheet->setCellValue('L5', '收款额');
        $currentExcelSheet->setCellValue('M5', '未划扣额');

        $currentExcelSheet->setCellValue('N5', '初始折后额');
        $currentExcelSheet->setCellValue('O5', '初始支付额');
        
        $currentExcelSheet->setCellValue('P5', '网络客服');
        // $currentExcelSheet->setCellValue('P5', '开发');
        $currentExcelSheet->setCellValue('Q5', '现场客服');
        $currentExcelSheet->setCellValue('R5', '分派人员');
        $currentExcelSheet->setCellValue('S5', '受理工具');
        $currentExcelSheet->setCellValue('T5', '初复次');
        $currentExcelSheet->setCellValue('U5', '首次来院时间');
        $currentExcelSheet->setCellValue('V5', '订单类型');
        $currentExcelSheet->setCellValue('W5', '收费人');

        return 5;
    }

    /**
     * 写入每行数据
     */
    private function generateRow(&$currentExcelSheet, $lineNo, &$rowData)
    {
        $rowData['consult_admin_name'] = '';
        if(isset($this->briefAdminList[$rowData['consult_admin_id']])) {
            $rowData['consult_admin_name'] = $this->briefAdminList[$rowData['consult_admin_id']];
        }
        $rowData['osconsult_admin_name'] = '';
        if(isset($this->briefAdminList[$rowData['osconsult_admin_id']])) {
            $rowData['osconsult_admin_name'] = $this->briefAdminList[$rowData['osconsult_admin_id']];
        }
        $rowData['develop_admin_name'] = '';
        if(isset($this->briefAdminList[$rowData['develop_admin_id']])) {
            $rowData['develop_admin_name'] = $this->briefAdminList[$rowData['develop_admin_id']];
        }
        $rowData['recept_admin_name'] = '';
        if(isset($this->briefAdminList[$rowData['recept_admin_id']])) {
            $rowData['recept_admin_name'] = $this->briefAdminList[$rowData['recept_admin_id']];
        }
        $rowData['prescriber_name'] = '';
        if(isset($this->briefAdminList[$rowData['prescriber']])) {
            $rowData['prescriber_name'] = $this->briefAdminList[$rowData['prescriber']];
        }

        $rowData['dept_name'] = '';
        if (!empty($rowData['dept_id']) && isset($this->deptList[$rowData['dept_id']])) {
            $rowData['dept_name'] = $this->deptList[$rowData['dept_id']]['dept_name'];
        }
        $rowData['osc_dept_name'] = '';
        if (!empty($rowData['osc_dept_id']) && isset($this->deptList[$rowData['osc_dept_id']])) {
            $rowData['osc_dept_name'] = $this->deptList[$rowData['osc_dept_id']];
        }
        // ctm_first_tool_id
        $rowData['ctm_first_tool'] = '';
        if (!empty($rowData['ctm_first_tool_id']) && isset($this->toolList[$rowData['ctm_first_tool_id']])) {
            $rowData['ctm_first_tool'] = $this->toolList[$rowData['ctm_first_tool_id']];
        }

        $rowData['item_type_name'] = '';
        if (!empty($rowData['item_type']) && isset($this->itemType[$rowData['item_type']])) {
            $rowData['item_type_name'] = $this->itemType[$rowData['item_type']];
        }

        $rowData['ctm_first_cpdt_name'] = '';
        if (!empty($rowData['ctm_first_cpdt_id']) && isset($this->cpdtList[$rowData['ctm_first_cpdt_id']])) {
            $rowData['ctm_first_cpdt_name'] = $this->cpdtList[$rowData['ctm_first_cpdt_id']];
        }
       
        $currentExcelSheet->setCellValue('A' . $lineNo, date('Y-m-d', $rowData['item_paytime']));
        $currentExcelSheet->setCellValue('B' . $lineNo, $rowData['customer_id']);
        $currentExcelSheet->setCellValue('C' . $lineNo, $rowData['ctm_name']);


        $currentExcelSheet->setCellValue('D' . $lineNo, $rowData['pro_name']);
        $currentExcelSheet->setCellValue('E' . $lineNo, $rowData['pro_spec']);
        $currentExcelSheet->setCellValue('F' . $lineNo, $rowData['ctm_first_cpdt_name']);
        $currentExcelSheet->setCellValue('G' . $lineNo, $rowData['dept_name']);

        $currentExcelSheet->setCellValue('H' . $lineNo, $rowData['item_used_times']);
        $currentExcelSheet->setCellValue('I' . $lineNo, $rowData['item_total_times']);
        $currentExcelSheet->setCellValue('J' . $lineNo, $rowData['item_amount_per_time']);


        $currentExcelSheet->setCellValue('K' . $lineNo, $rowData['item_total']);
        $currentExcelSheet->setCellValue('L' . $lineNo, $rowData['item_pay_total']);
        $currentExcelSheet->setCellValue('M' . $lineNo, $rowData['item_undeducted_total']);

        $currentExcelSheet->setCellValue('N' . $lineNo, $rowData['item_original_total']);
        $currentExcelSheet->setCellValue('O' . $lineNo, $rowData['item_original_pay_total']);

        $currentExcelSheet->setCellValue('P' . $lineNo, $rowData['consult_admin_name']);
        // $currentExcelSheet->setCellValue('P' . $lineNo, $rowData['develop_admin_name']);
        $currentExcelSheet->setCellValue('Q' . $lineNo, $rowData['osconsult_admin_name']);
        $currentExcelSheet->setCellValue('R' . $lineNo, $rowData['recept_admin_name']);
        $currentExcelSheet->setCellValue('S' . $lineNo, $rowData['ctm_first_tool']);
        $currentExcelSheet->setCellValue('T' . $lineNo, Osctype::getTypeById(@$rowData['osc_type']));
        $currentExcelSheet->setCellValue('U' . $lineNo, date('Y-m-d H:i:s', $rowData['ctm_first_recept_time']));
        $currentExcelSheet->setCellValue('V' . $lineNo, $rowData['item_type_name']);
        $currentExcelSheet->setCellValue('W' . $lineNo, $rowData['prescriber_name']);
    }

}
