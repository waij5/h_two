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
use app\admin\model\Project as MProject;
use app\admin\model\Deptment;
use app\admin\model\Fee;
use app\admin\model\Fpro;

class Project extends Command
{

    protected $model         = null;
    protected $linesPerExcel = 20000;
    protected $batchLimit    = 2000;
    protected $pduList          = [];
    protected $deptList = [];

    protected function configure()
    {
        $this
            ->setName('project')
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

        //类别
        $this->pduList = \app\admin\model\Pducat::field('*, pdc_id as id')->column('pdc_name', 'pdc_id');
        $this->deptList = Deptment::column('dept_name', 'dept_id');

        $where = array_merge($where, $extraWhere);

        // $total = MProject::alias('pro')
        //             ->join((new Deptment)->getTable() . ' dept', 'pro.dept_id = dept.dept_id', 'LEFT')
        //             ->where(['pro_type' => MProject::TYPE_PROJECT])
        //             ->where($where)
        //             ->count();
                    
        // $list =  MProject::alias('pro')
        //         ->field('pro.*, pro.deduct_addr as deduct_addr_name')
        //         ->where(['pro_type' => MProject::TYPE_PROJECT])
        //         ->where($where)
        //         ->select();

        $sort = 'pro.pro_id';
        $order = 'asc';
        $total = MProject::alias('pro')
                    ->where(['pro_type' => MProject::TYPE_PROJECT])
                    ->join((new Deptment)->getTable(). ' dept', 'dept.dept_id = pro.dept_id', 'LEFT')
                    ->join((new Fpro)->getTable() . ' fpro', 'pro.pro_id = fpro.pro_id', 'LEFT')
                    ->where($where)
                    ->order($sort, $order)
                    ->count();
                    
        $list =  MProject::alias('pro')
                    ->field('pro.*,dept.dept_name, fpro.id as fpro_id, fpro.status as fpro_status')
                    ->join((new Deptment)->getTable() . ' dept', 'dept.dept_id = pro.dept_id', 'LEFT')
                    ->join((new Fpro)->getTable() . ' fpro', 'pro.pro_id = fpro.pro_id', 'LEFT')
                    ->where(['pro_type' => MProject::TYPE_PROJECT])
                    ->where($where)
                    ->order($sort, $order)
                    // ->limit($offset, $limit)
                    ->select();


        //获取职员缓存信息
        $briefAdminList = Admin::getAdminCache(Admin::ADMIN_BRIEF_CACHE_KEY);

        $currentCount   = 0;

        $excelDir = realpath(__DIR__ . '/../../data/reports/excels') . '/';
        $namePre  = '治疗项目管理' . str_pad((string) $recordId, 11, '0', STR_PAD_LEFT);

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
        $currentExcelSheet->mergeCells('D1:T1');
        $currentExcelSheet->getStyle('D1')->getFont()->setBold(true)->setSize(20);
        $currentExcelSheet->setCellValue('D1', $title);
        $currentExcelSheet->mergeCells('U1:W1');
        $currentExcelSheet->setCellValue('N1', date('Y-m-d H:i'));

        for ($i = 'A'; $i <= 'Z'; $i ++) {
            $currentExcelSheet->getStyle($i . '2')->getFont()->setBold(true);
        }

        $currentExcelSheet->setCellValue('A2', 'ID');
        $currentExcelSheet->setCellValue('B2', '项目编号');
        $currentExcelSheet->setCellValue('C2', '项目名称');
        $currentExcelSheet->setCellValue('D2', '拼音码');
        $currentExcelSheet->setCellValue('E2', '打印简称');

        $currentExcelSheet->setCellValue('F2', '所属类别');
        $currentExcelSheet->setCellValue('G2', '类别二');
        $currentExcelSheet->setCellValue('H2', '类别三');
        $currentExcelSheet->setCellValue('I2', '单位');
        $currentExcelSheet->setCellValue('J2', '规格');

        $currentExcelSheet->setCellValue('K2', '使用次数');
        $currentExcelSheet->setCellValue('L2', '本地售价');
        $currentExcelSheet->setCellValue('M2', '项目售价');
        $currentExcelSheet->setCellValue('N2', '项目成本');

        $currentExcelSheet->setCellValue('O2', '划扣地点');
        $currentExcelSheet->setCellValue('P2', '结算科室');

        $currentExcelSheet->setCellValue('Q2', '费用类型');
        $currentExcelSheet->setCellValue('R2', '按职位提成');
        $currentExcelSheet->setCellValue('S2', '客服成功率');
        $currentExcelSheet->setCellValue('T2', '自动划扣');

        $currentExcelSheet->setCellValue('U2', '赠送积分');
        $currentExcelSheet->setCellValue('V2', '状态');
        $currentExcelSheet->setCellValue('W2', '项目说明');

        return 2;
    }

    /**
     * 写入每行数据
     */
    private function generateRow(&$currentExcelSheet, $lineNo, &$rowData)
    {
        $rowData['pro_cat1_name'] = '';
        if (isset($this->pduList[$rowData['pro_cat1']])){
            $rowData['pro_cat1_name']  = $this->pduList[$rowData['pro_cat1']];
        }
        $rowData['pro_cat2_name'] = '';
        if (isset($this->pduList[$rowData['pro_cat2']])){
            $rowData['pro_cat2_name']  = $this->pduList[$rowData['pro_cat2']];
        }
        $rowData['pro_cat3_name'] = '';
        if (isset($this->pduList[$rowData['pro_cat3']])){
            $rowData['pro_cat3_name']  = $this->pduList[$rowData['pro_cat3']];
        }

        $fee = Fee::getList();
        $rowData['pro_fee_type_name'] = '';
        if (isset($fee[$rowData['pro_fee_type']])){
            $rowData['pro_fee_type_name']  = $fee[$rowData['pro_fee_type']];
        }

        $currentExcelSheet->setCellValue('A' . $lineNo, $rowData['pro_id']);
        $currentExcelSheet->setCellValue('B' . $lineNo, $rowData['pro_code']);
        $currentExcelSheet->setCellValue('C' . $lineNo, $rowData['pro_name']);
        $currentExcelSheet->setCellValue('D' . $lineNo, $rowData['pro_spell']);
        $currentExcelSheet->setCellValue('E' . $lineNo, $rowData['pro_print']);

        $currentExcelSheet->setCellValue('F' . $lineNo, $rowData['pro_cat1_name']);
        $currentExcelSheet->setCellValue('G' . $lineNo, $rowData['pro_cat2_name']);
        $currentExcelSheet->setCellValue('H' . $lineNo, $rowData['pro_cat3_name']);
        $currentExcelSheet->setCellValue('I' . $lineNo, $rowData['pro_unit']);
        $currentExcelSheet->setCellValue('J' . $lineNo, $rowData['pro_spec']);

        $currentExcelSheet->setCellValue('K' . $lineNo, $rowData['pro_use_times']);
        $currentExcelSheet->setCellValue('L' . $lineNo, $rowData['pro_local_amount']);
        $currentExcelSheet->setCellValue('M' . $lineNo, $rowData['pro_amount']);
        $currentExcelSheet->setCellValue('N' . $lineNo, $rowData['pro_cost']);
        
        $deductDept = !empty($rowData['dept_id']) && isset($this->deptList[$rowData['dept_id']]) ? $this->deptList[$rowData['dept_id']] : '';
        $deductAddr = !empty($rowData['deduct_addr']) && isset($this->deptList[$rowData['deduct_addr']]) ? $this->deptList[$rowData['deduct_addr']] : '';
        
        $currentExcelSheet->setCellValue('O' . $lineNo, $deductAddr);
        $currentExcelSheet->setCellValue('P' . $lineNo, $deductDept);

        $currentExcelSheet->setCellValue('Q' . $lineNo, $rowData['pro_fee_type_name']);
        $currentExcelSheet->setCellValue('R' . $lineNo, $rowData['allow_position_bonus'] > 0 ? '是' : '否');
        $currentExcelSheet->setCellValue('S' . $lineNo, $rowData['allow_consult_calc'] > 0 ? '是' : '否');
        $currentExcelSheet->setCellValue('T' . $lineNo, $rowData['deduct_switch'] > 0 ? '是' : '否');

        $currentExcelSheet->setCellValue('U' . $lineNo, $rowData['allow_bonus'] > 0 ? '是' : '否');
        $currentExcelSheet->setCellValue('V' . $lineNo, $rowData['pro_status'] > 0 ? '正常' : '禁用');
        $currentExcelSheet->setCellValue('W' . $lineNo, $rowData['pro_remark']);
    }

}
