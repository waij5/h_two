<?php

namespace app\admin\command;

use app\admin\model\Admin;
use app\admin\model\CmdRecords;
use app\admin\model\DeductRecords;
use app\admin\model\DeductRole;
use app\admin\model\DeductStaffRecords;
use app\admin\model\Deptment;
use app\admin\model\Fee;
use app\admin\model\Osctype;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;

class Deductrecordsreport extends Command
{
    protected $model            = null;
    protected $linesPerExcel    = 20000;
    protected $batchLimit       = 500;
    protected $briefAdminList   = [];
    protected $deptList         = [];
    protected $pduList          = [];
    protected $sourceList       = [];
    protected $channelsList     = [];
    protected $deductStatusList = [
        'deduct_status_' . DeductRecords::STATUS_PENGING   => '未出库',
        'deduct_status_' . DeductRecords::STATUS_COMPLETED => '已完成',
        'deduct_status_' . DeductRecords::STATUS_REVERSE   => '反划扣',
        'deduct_status_' . DeductRecords::STATUS_REVERSED  => '被反划扣',
    ];
    protected $roleList = array();

    protected function configure()
    {
        $this
            ->setName('deductrecordsreport')
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

        // $params = json_decode($cmdRecord->params, true);
        // $where  = array();
        // if ($params !== false && is_array($params)) {
        //     $where = $params;
        // }
        $this->roleList = DeductRole::order('id', 'asc')->column('name', 'id');

        $summary = DeductRecords::getRecordsCntNdSummary($where, $extraWhere, true);
        $total   = $summary['count'];
        //获取职员缓存信息
        // $adminModel     = new Admin;
        $this->briefAdminList = Admin::getAdminCache(Admin::ADMIN_BRIEF_CACHE_KEY);
        $this->deptList       = Deptment::getDeptListCache();
        $this->pduList        = \app\admin\model\Pducat::field('*, pdc_id as id')->column('pdc_name', 'pdc_id');

        $this->sourceList   = \app\admin\model\Ctmsource::field('*, sce_id as id')->column('sce_name', 'sce_id');
        $this->channelsList = \app\admin\model\Ctmchannels::field('*, chn_id as id')->column('chn_name', 'chn_id');

        $maxBatchCount  = ceil($total / $this->batchLimit);
        $maxExcelCount  = ceil($total / $this->linesPerExcel);
        $currentCount   = 0;
        $currentBatchNo = 1;
        $currentExcelNo = 1;

        $excelDir = realpath(__DIR__ . '/../../data/reports/excels') . '/';
        $namePre  = '划扣记录表_' . str_pad((string) $recordId, 11, '0', STR_PAD_LEFT) . '_';

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
        $currentLineNo = $this->generateHeader($currentExcelSheet, $namePre . $currentExcelNo, $cmdRecord, $summary);

        for (; $currentBatchNo <= $maxBatchCount; $currentBatchNo++) {
            $batchOffset = ($currentBatchNo - 1) * $this->batchLimit;

            $list            = DeductRecords::getRecords($where, 'id', 'DESC', $batchOffset, $this->batchLimit, $extraWhere);
            $deductStaffList = array();

            $recIds    = array_column($list, 'id');
            $recIdArrs = array_chunk($recIds, 100);
            unset($recIds);
            foreach ($recIdArrs as $key => $recIdArr) {
                $tmpStaffList = DeductStaffRecords::where(['deduct_record_id' => ['in', $recIdArr]])->column('*');
                foreach ($tmpStaffList as $key => $tmpStaffRec) {
                    if (!isset($deductStaffList[$tmpStaffRec['deduct_record_id']])) {
                        $deductStaffList[$tmpStaffRec['deduct_record_id']] = array();
                    }
                    if (!isset($deductStaffList[$tmpStaffRec['deduct_record_id']][$tmpStaffRec['deduct_role_id']])) {
                        $deductStaffList[$tmpStaffRec['deduct_record_id']][$tmpStaffRec['deduct_role_id']] = array();
                    }

                    array_push($deductStaffList[$tmpStaffRec['deduct_record_id']][$tmpStaffRec['deduct_role_id']], $tmpStaffRec);

                }
            }

            $staffRecs = array();

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
                    $currentLineNo = $this->generateHeader($currentExcelSheet, $namePre . $currentExcelNo, $cmdRecord, $summary);
                    $currentLineNo++;
                }

                $rowData['staff_records'] = isset($deductStaffList[$rowData['id']]) ? $deductStaffList[$rowData['id']] : array();
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
    private function generateHeader(&$currentExcelSheet, $title, &$cmdRecord, $data)
    {
        //column style
        $currentExcelSheet->getColumnDimension('A')->setWidth(18);
        $currentExcelSheet->getColumnDimension('C')->setWidth(18);

        $currentExcelSheet->mergeCells('A2:M2');
        $currentExcelSheet->setCellValue('A2', '划扣次数' . $data['deduct_times'] . ' 划扣总金额 ' . $data['deduct_total'] . ' 划扣总收益 ' . $data['deduct_benefit_total']);

        $currentExcelSheet->setCellValue('A3', '顾客');
        $currentExcelSheet->getColumnDimension('A')->setWidth(16);
        $currentExcelSheet->setCellValue('B3', '卡号');
        $currentExcelSheet->setCellValue('C3', '项目/产品');
        $currentExcelSheet->getColumnDimension('C')->setWidth(30);
        $currentExcelSheet->getStyle('C')->getAlignment()->setWrapText(true);
        $currentExcelSheet->setCellValue('D3', '规格');
        $currentExcelSheet->getColumnDimension('D')->setWidth(30);
        $currentExcelSheet->getStyle('D')->getAlignment()->setWrapText(true);

        $currentExcelSheet->setCellValue('E3', '费用类型');
        $currentExcelSheet->setCellValue('F3', '类别一');
        $currentExcelSheet->setCellValue('G3', '类别二');
        $currentExcelSheet->setCellValue('H3', '类别三');
        $currentExcelSheet->setCellValue('I3', '初复次状态');

        $currentExcelSheet->setCellValue('J3', '划扣次数');
        $currentExcelSheet->setCellValue('K3', '划扣金额(终)');
        $currentExcelSheet->getColumnDimension('K')->setWidth(14);
        $currentExcelSheet->setCellValue('L3', '本次收益(终)');
        $currentExcelSheet->getColumnDimension('L')->setWidth(14);
        $currentExcelSheet->setCellValue('M3', '状态');
        $currentExcelSheet->setCellValue('N3', '结算科室');

        $currentExcelSheet->setCellValue('O3', '网络客服');
        $currentExcelSheet->getColumnDimension('O')->setWidth(16);
        $currentExcelSheet->getStyle('O')->getAlignment()->setWrapText(true);
        $currentExcelSheet->setCellValue('P3', '现场客服');
        $currentExcelSheet->getColumnDimension('P')->setWidth(16);
        $currentExcelSheet->getStyle('P')->getAlignment()->setWrapText(true);
        $currentExcelSheet->setCellValue('Q3', '分派人员');
        $currentExcelSheet->getColumnDimension('Q')->setWidth(16);
        $currentExcelSheet->getStyle('Q')->getAlignment()->setWrapText(true);
        $currentExcelSheet->setCellValue('R3', '划扣人');
        $currentExcelSheet->getColumnDimension('R')->setWidth(16);
        $currentExcelSheet->getStyle('R')->getAlignment()->setWrapText(true);
        $currentExcelSheet->setCellValue('S3', '收费人');

        $curColName = 'R';
        foreach ($this->roleList as $roleId => $roleName) {
            $curColName = cal_excel_col($curColName, 1);
            $currentExcelSheet->getColumnDimension($curColName)->setWidth(20);
            $currentExcelSheet->getStyle($curColName)->getAlignment()->setWrapText(true);
            $currentExcelSheet->setCellValue($curColName . '3', $roleName);
        }

        $midEndCol    = $curColName    = cal_excel_col($curColName, 1);
        $lastStartCol = cal_excel_col($midEndCol, 1);
        $currentExcelSheet->setCellValue($curColName . '3', '付款时间');
        $currentExcelSheet->getColumnDimension($curColName)->setWidth(10);
        $currentExcelSheet->setCellValue(($curColName = cal_excel_col($curColName, 1)) . '3', '划扣时间');
        $currentExcelSheet->getColumnDimension($curColName)->setWidth(10);
        $currentExcelSheet->setCellValue(($curColName = cal_excel_col($curColName, 1)) . '3', '营销渠道');
        $currentExcelSheet->setCellValue(($curColName = cal_excel_col($curColName, 1)) . '3', '客户来源');

        $currentExcelSheet->mergeCells('A1:C1');
        $currentExcelSheet->setCellValue('A1', 'feature: ' . $cmdRecord->feature_value);
        $currentExcelSheet->mergeCells('D1:' . $midEndCol . '1');
        $currentExcelSheet->getStyle('D1')->getFont()->setBold(true)->setSize(20);
        $currentExcelSheet->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $currentExcelSheet->setCellValue('D1', $title);

        $currentExcelSheet->mergeCells($lastStartCol . '1:' . $curColName . '1');
        $currentExcelSheet->setCellValue($lastStartCol . '1', date('Y-m-d H:i'));

        $currentExcelSheet->getStyle('A3:' . $curColName . '3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $currentExcelSheet->getStyle('A3:' . $curColName . '3')->getFont()->setBold(true);

        $currentExcelSheet->freezePane('A4');

        return 3;
    }

    /**
     * 写入每行数据
     * 注意 此处 $lineNo 传址传递
     */
    private function generateRow(&$currentExcelSheet, &$lineNo, &$rowData)
    {
        if (isset($this->briefAdminList[$rowData['admin_id']])) {
            $rowData['admin_nickname'] = $this->briefAdminList[$rowData['admin_id']];
        } else {
            $rowData['admin_nickname'] = '';
        }
        if (isset($this->briefAdminList[$rowData['consult_admin_id']])) {
            $rowData['consult_admin_name'] = $this->briefAdminList[$rowData['consult_admin_id']];
        } else {
            $rowData['consult_admin_name'] = '';
        }
        if (isset($this->briefAdminList[$rowData['osconsult_admin_id']])) {
            $rowData['osconsult_admin_name'] = $this->briefAdminList[$rowData['osconsult_admin_id']];
        } else {
            $rowData['osconsult_admin_name'] = '';
        }
        if (isset($this->briefAdminList[$rowData['recept_admin_id']])) {
            $rowData['recept_admin_name'] = $this->briefAdminList[$rowData['recept_admin_id']];
        } else {
            $rowData['recept_admin_name'] = '';
        }
        if (isset($this->briefAdminList[$rowData['prescriber']])) {
            $rowData['prescriber_name'] = $this->briefAdminList[$rowData['prescriber']];
        } else {
            $rowData['prescriber_name'] = '';
        }
        $rowData['dept_name'] = '';
        if (isset($this->deptList[$rowData['dept_id']])) {
            $rowData['dept_name'] = $this->deptList[$rowData['dept_id']]['dept_name'];
        }
        $rowData['status_str'] = '';
        if (isset($this->deductStatusList['deduct_status_' . $rowData['status']])) {
            $rowData['status_str'] = $this->deductStatusList['deduct_status_' . $rowData['status']];
        }
        $rowData['pro_cat1_name'] = '';
        if (isset($this->pduList[$rowData['pro_cat1']])) {
            $rowData['pro_cat1_name'] = $this->pduList[$rowData['pro_cat1']];
        }
        $rowData['pro_cat2_name'] = '';
        if (isset($this->pduList[$rowData['pro_cat2']])) {
            $rowData['pro_cat2_name'] = $this->pduList[$rowData['pro_cat2']];
        }
        $rowData['pro_cat3_name'] = '';
        if (isset($this->pduList[$rowData['pro_cat3']])) {
            $rowData['pro_cat3_name'] = $this->pduList[$rowData['pro_cat3']];
        }
        $rowData['ctm_explore_name'] = '';
        if (isset($this->channelsList[$rowData['ctm_explore']])) {
            $rowData['ctm_explore_name'] = $this->channelsList[$rowData['ctm_explore']];
        }
        $rowData['ctm_source_name'] = '';
        if (isset($this->sourceList[$rowData['ctm_source']])) {
            $rowData['ctm_source_name'] = $this->sourceList[$rowData['ctm_source']];
        }
        //费用类型
        $proFeeType = Fee::getList();
        //初复诊状态
        $oscTypeList = Osctype::getList();

        $currentExcelSheet->setCellValue('A' . $lineNo, $rowData['ctm_name']);
        $currentExcelSheet->setCellValue('B' . $lineNo, $rowData['ctm_id']);
        $currentExcelSheet->setCellValue('C' . $lineNo, $rowData['item_name']);
        $currentExcelSheet->setCellValue('D' . $lineNo, $rowData['item_spec']);

        $currentExcelSheet->setCellValue('E' . $lineNo, isset($proFeeType[$rowData['pro_fee_type']]) ? $proFeeType[$rowData['pro_fee_type']] : '');
        $currentExcelSheet->setCellValue('F' . $lineNo, $rowData['pro_cat1_name']);
        $currentExcelSheet->setCellValue('G' . $lineNo, $rowData['pro_cat2_name']);
        $currentExcelSheet->setCellValue('H' . $lineNo, $rowData['pro_cat3_name']);
        $currentExcelSheet->setCellValue('I' . $lineNo, isset($rowData['osc_type']) ? $oscTypeList[$rowData['osc_type']] : '');

        $currentExcelSheet->setCellValue('J' . $lineNo, $rowData['deduct_times']);
        $currentExcelSheet->setCellValue('K' . $lineNo, $rowData['deduct_amount']);
        $currentExcelSheet->setCellValue('L' . $lineNo, $rowData['deduct_benefit_amount']);

        $currentExcelSheet->setCellValue('M' . $lineNo, $rowData['status_str']);
        $currentExcelSheet->setCellValue('N' . $lineNo, $rowData['dept_name']);

        $currentExcelSheet->setCellValue('O' . $lineNo, $rowData['consult_admin_name']);
        $currentExcelSheet->setCellValue('P' . $lineNo, $rowData['osconsult_admin_name']);
        $currentExcelSheet->setCellValue('Q' . $lineNo, $rowData['recept_admin_name']);
        $currentExcelSheet->setCellValue('R' . $lineNo, $rowData['admin_nickname']);
        $currentExcelSheet->setCellValue('S' . $lineNo, $rowData['prescriber_name']);

        $curColName = 'R';
        // $maxLine = $lineNo;

        foreach ($this->roleList as $roleId => $roleName) {
            $curColName   = cal_excel_col($curColName, 1);
            $curStaffLine = $lineNo - 1;

            $staffRecStr = '';
            if (isset($rowData['staff_records'][$roleId])) {
                foreach ($rowData['staff_records'][$roleId] as $key => $staffInfo) {
                    // $curStaffLine ++;
                    // if ($maxLine < $curStaffLine) {
                    //     $maxLine = $curStaffLine;
                    // }
                    $staffName = isset($this->briefAdminList[$staffInfo['admin_id']]) ? $this->briefAdminList[$staffInfo['admin_id']] : $staffInfo['admin_id'];
                    $staffRecStr .= '[ ' . $staffName . ' ]';

                    // $currentExcelSheet->setCellValue($curColName . $curStaffLine, '[ ' . $staffName . '(' . $staffInfo['final_amount'] . ') ]');
                }
            }
            $currentExcelSheet->setCellValue($curColName . $lineNo, $staffRecStr);
        }

        $currentExcelSheet->setCellValue(($curColName = cal_excel_col($curColName, 1)) . $lineNo, date('Y-m-d', $rowData['item_paytime']));
        $currentExcelSheet->setCellValue(($curColName = cal_excel_col($curColName, 1)) . $lineNo, date('Y-m-d', $rowData['createtime']));

        $currentExcelSheet->setCellValue(($curColName = cal_excel_col($curColName, 1)) . $lineNo, $rowData['ctm_explore_name']);
        $currentExcelSheet->setCellValue(($curColName = cal_excel_col($curColName, 1)) . $lineNo, $rowData['ctm_source_name']);

        // $lineNo = $maxLine;
    }

}
