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
use app\admin\model\Pducat;
use app\admin\model\Unit;
use app\admin\model\Depot;

class GoodsReport extends Command
{

    protected $model         = null;
    protected $linesPerExcel = 20000;
    protected $batchLimit    = 2000;
    protected $pduList          = [];



    protected function configure()
    {
        $this
            ->setName('GoodsReport')
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

        $where = array_merge($where, $extraWhere);

        $total = MProject::alias('pro')
            ->where($where)
            ->where(['pro.pro_type' => 2])
            ->count();

         $list = MProject::alias('pro')
        ->field('pro.*, u.name as u_name, pd.pdc_name, pdc.pdc_name as pro_cat, depot.name as depot_name, dept.dept_name as dept_name')
        ->join((new Unit)->getTable() . ' u','pro.pro_unit = u.id', 'LEFT')
        ->join((new Pducat)->getTable() . ' pd', 'pro.pro_cat1 = pd.pdc_id', 'LEFT')
        ->join((new Pducat)->getTable() . ' pdc', 'pro.pro_cat2 = pdc.pdc_id', 'LEFT')
        ->join((new Depot)->getTable() . ' depot', 'pro.depot_id = depot.id', 'LEFT')
        ->join((new Deptment)->getTable() . ' dept', 'pro.dept_id = dept.dept_id', 'LEFT')
        ->where($where)
        ->where(['pro.pro_type' => 2])
        ->order(['pro.pro_id' => 'desc', 'pro.pro_status' => 'desc'])
        ->select();

        //获取职员缓存信息
        $briefAdminList = Admin::getAdminCache(Admin::ADMIN_BRIEF_CACHE_KEY);

        $currentCount   = 0;

        $excelDir = realpath(__DIR__ . '/../../data/reports/excels') . '/';
        $namePre  = '物品管理' . str_pad((string) $recordId, 11, '0', STR_PAD_LEFT);

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
        $currentExcelSheet->setCellValue('B2', '编号');
        $currentExcelSheet->setCellValue('C2', '物品名称');
        $currentExcelSheet->setCellValue('D2', '拼音码');

        $currentExcelSheet->setCellValue('E2', '成本单价');
        $currentExcelSheet->setCellValue('F2', '零售价');
        $currentExcelSheet->setCellValue('G2', '所属仓库');
        
        $currentExcelSheet->setCellValue('H2', '单位');
        $currentExcelSheet->setCellValue('I2', '规格');

        $currentExcelSheet->setCellValue('J2', '所属科目');
        $currentExcelSheet->setCellValue('K2', '所属类别');

        $currentExcelSheet->setCellValue('L2', '结算科室');
        $currentExcelSheet->setCellValue('M2', '总库存');

        $currentExcelSheet->setCellValue('N2', '状态(1显示0隐藏)');

        return 2;
    }

    /**
     * 写入每行数据
     */
    private function generateRow(&$currentExcelSheet, $lineNo, &$rowData)
    {
        $rowData['subject_type_name'] = '';
        if (isset($this->pduList[$rowData['pro_cat1']])){
            $rowData['subject_type_name']  = $this->pduList[$rowData['pro_cat1']];
        }

        $rowData['pro_cat2_name'] = '';
        if (isset($this->pduList[$rowData['pro_cat1']])){
            $rowData['pro_cat2_name']  = $this->pduList[$rowData['pro_cat2']];
        }

        $currentExcelSheet->setCellValue('A' . $lineNo, $rowData['pro_id']);
        $currentExcelSheet->setCellValue('B' . $lineNo, $rowData['pro_code']);
        $currentExcelSheet->setCellValue('C' . $lineNo, $rowData['pro_name']);
        $currentExcelSheet->setCellValue('D' . $lineNo, $rowData['pro_spell']);

        $currentExcelSheet->setCellValue('E' . $lineNo, $rowData['pro_cost']);
        $currentExcelSheet->setCellValue('F' . $lineNo, $rowData['pro_amount']);
        $currentExcelSheet->setCellValue('G' . $lineNo, $rowData['depot_name']);

        $currentExcelSheet->setCellValue('H' . $lineNo, $rowData['u_name']);
        $currentExcelSheet->setCellValue('I' . $lineNo, $rowData['pro_spec']);

        $currentExcelSheet->setCellValue('J' . $lineNo, $rowData['subject_type_name']);
        $currentExcelSheet->setCellValue('K' . $lineNo, $rowData['pro_cat2_name']);

        $currentExcelSheet->setCellValue('L' . $lineNo, $rowData['dept_name']);
        $currentExcelSheet->setCellValue('M' . $lineNo, $rowData['pro_stock']);
        $currentExcelSheet->setCellValue('N' . $lineNo, $rowData['pro_status']);
        
    }

}
