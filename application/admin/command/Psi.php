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
use think\Db;
use think\lang;

class Psi extends Command
{
    protected $model         = null;
    protected $linesPerExcel = 20000;
    protected $batchLimit    = 2000;

    protected function configure()
    {
        $this
            ->setName('psi')
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

        
        $list = db('wm_manifest')->alias('mf')
                    ->field('mf.man_id, mf.msecond_type, mf.man_num, mf.mcreatetime, mf.mremark, ml.mpro_num, ml.mcost, ml.mallcost, ml.mprice, ml.mallprice, ml.metime, lot.lotnum, lot.lproducer,s.sup_name,pro.pro_code, pro.pro_name, pro.pro_unit, pro.pro_spec, u.name as uname')
                    ->join('yjy_wm_manlist ml', 'mf.man_id = ml.manid', 'LEFT')
                    ->join('yjy_wm_lotnum lot', 'ml.lotid = lot.lot_id', 'LEFT')
                    ->join('yjy_project pro', 'lot.lpro_id = pro.pro_id', 'LEFT')
                    ->join('yjy_wm_supplier s', 'mf.msupplier_id = s.sup_id', 'LEFT')
                    ->join('yjy_unit u', 'pro.pro_unit = u.id', 'LEFT')
                    ->where($where)
                    ->order('mf.mcreatetime',"DESC")
                    ->select();

            $psiData = [];
            $total = 0;
            foreach ($list as $key => $v) {
                $psiData[$v['man_id']][]=$v;
                $total += 1;
            }
            // var_dump($list);die();
        //获取职员缓存信息
        // $adminModel     = new Admin;
        $briefAdminList = Admin::getAdminCache(Admin::ADMIN_BRIEF_CACHE_KEY);

        $maxBatchCount  = ceil($total / $this->batchLimit);
        $maxExcelCount  = ceil($total / $this->linesPerExcel);
        $currentCount   = 0;
        $currentBatchNo = 1;
        $currentExcelNo = 1;

        $excelDir = realpath(__DIR__ . '/../../data/reports/excels') . '/';
        $namePre  = '产品进入冲明细表' . str_pad((string) $recordId, 11, '0', STR_PAD_LEFT) . '_';

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
        $currentLineNo = $this->generateHeader($currentExcelSheet, $namePre . $currentExcelNo, $cmdRecord);

        for (; $currentBatchNo <= $maxBatchCount; $currentBatchNo++) {
            $batchOffset = ($currentBatchNo - 1) * $this->batchLimit;
            
            foreach ($psiData as $key => $rowData) {
                foreach ($rowData as $ke => $va) {
                    $va['mcreatetime'] = date('Y-m-d', (int)$va['mcreatetime']);
                    $va['metime'] = (int)$va['metime']?date('Y-m-d', (int)$va['metime']):'';

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
                        $currentLineNo = $this->generateHeader($currentExcelSheet, $namePre . $currentExcelNo, $cmdRecord);
                        $currentLineNo++;
                    }

                    $this->generateRow($currentExcelSheet, $currentLineNo, $va);

                    // var_dump($stockDetailData);

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
    private function generateHeader(&$currentExcelSheet, $title, &$cmdRecord)
    {
        //column style     18列
        
        $currentExcelSheet->mergeCells('A1:C1');
        $currentExcelSheet->setCellValue('A1', 'feature: ' . $cmdRecord->feature_value);
        $currentExcelSheet->mergeCells('D1:N1');
        $currentExcelSheet->getStyle('D1')->getFont()->setBold(true)->setSize(20);
        $currentExcelSheet->setCellValue('D1', $title);
        $currentExcelSheet->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        
        $currentExcelSheet->mergeCells('O1:Q1');
        $currentExcelSheet->setCellValue('O1', date('Y-m-d H:i'));


        


        $currentExcelSheet->setCellValue('A2', '单号');
        $currentExcelSheet->setCellValue('B2', '供应商 ');
        $currentExcelSheet->setCellValue('C2', '进货日期');
        $currentExcelSheet->setCellValue('D2', '产品编号');
        $currentExcelSheet->setCellValue('E2', '产品名称');
        $currentExcelSheet->setCellValue('F2', '批号');
        $currentExcelSheet->setCellValue('G2', '单位');
        $currentExcelSheet->setCellValue('H2', '规格');
        $currentExcelSheet->setCellValue('I2', '数量');
        $currentExcelSheet->setCellValue('J2', '成本单价');
        $currentExcelSheet->setCellValue('K2', '总成本');
        $currentExcelSheet->setCellValue('L2', '零售价');
        $currentExcelSheet->setCellValue('M2', '总售价');
        $currentExcelSheet->setCellValue('N2', '进销差额');
        $currentExcelSheet->setCellValue('O2', '有效日期');
        $currentExcelSheet->setCellValue('P2', '生产厂家');
        $currentExcelSheet->setCellValue('Q2', '说明');

        $letter = array('A2','B2','C2','D2','E2','F2','G2','H2','I2','J2','K2','L2','M2','N2','O2','P2','Q2');
        foreach ($letter as $key => $value) {           //加粗、居中
            $currentExcelSheet->getStyle($value)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $currentExcelSheet->getStyle($value)->getFont()->setBold(true);
        }
        $abc = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q');
        foreach ($abc as $key => $value) {
            $currentExcelSheet->getColumnDimension($value)->setWidth(16);
        }
        


        return 2;
    }

    /**
     * 写入每行数据
     */
    private function generateRow(&$currentExcelSheet, $lineNo, &$rowData)
    {
        

        

        $currentExcelSheet->setCellValue('A' . $lineNo, $rowData['man_num']);
        $currentExcelSheet->setCellValue('B' . $lineNo, $rowData['sup_name']);
        $currentExcelSheet->setCellValue('C' . $lineNo, $rowData['mcreatetime']);
        $currentExcelSheet->setCellValue('D' . $lineNo, $rowData['pro_code']);
        $currentExcelSheet->setCellValue('E' . $lineNo, $rowData['pro_name']);
        $currentExcelSheet->setCellValue('F' . $lineNo, $rowData["lotnum"]);
        $currentExcelSheet->setCellValue('G' . $lineNo, $rowData['uname']);
        $currentExcelSheet->setCellValue('H' . $lineNo, $rowData['pro_spec']);
        $currentExcelSheet->setCellValue('I' . $lineNo, $rowData['mpro_num']);
        $currentExcelSheet->setCellValue('J' . $lineNo, $rowData['mcost']);
        $currentExcelSheet->setCellValue('K' . $lineNo, $rowData['mallcost']);
        $currentExcelSheet->setCellValue('L' . $lineNo, $rowData['mprice']);
        $currentExcelSheet->setCellValue('M' . $lineNo, $rowData['mallprice']);
        $currentExcelSheet->setCellValue('N' . $lineNo, $rowData['mallprice']-$rowData['mallcost']);
        $currentExcelSheet->setCellValue('O' . $lineNo, $rowData['metime']);
        $currentExcelSheet->setCellValue('P' . $lineNo, $rowData['lproducer']);
        $currentExcelSheet->setCellValue('Q' . $lineNo, $rowData['mremark']);


        
    }

}
