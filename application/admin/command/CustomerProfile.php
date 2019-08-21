<?php

namespace app\admin\command;

use app\admin\model\Admin;
use app\admin\model\CmdRecords;
use app\admin\model\Customer;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use PHPExcel_CachedObjectStorageFactory;
use PHPExcel_Settings;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;

class CustomerProfile extends Command
{

    protected $model         = null;
    protected $linesPerExcel = 20000;
    protected $batchLimit    = 2000;

    protected function configure()
    {
        $this
            ->setName('customerprofile')
            ->addOption('force', 'f', Option::VALUE_OPTIONAL, 'force override', false)
            ->addOption('record', 'r', option::VALUE_REQUIRED, 'command record id')
            ->setDescription('Export customer profiles');
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

        if (isset($where['ctm_mobile'])) {
                $mobileOperator  = $where['ctm_mobile'][0];
                $mobileCondition = $where['ctm_mobile'][1];
                unset($where['ctm_mobile']);
                $where[] = function ($query) use ($mobileOperator, $mobileCondition) {
                    $query->where('ctm_mobile', $mobileOperator, $mobileCondition)
                        ->whereOr('ctm_tel', $mobileOperator, $mobileCondition);
                };
            }

        // $cacheMethod = \PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;  
        // $cacheSettings = array();  
        // $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
        // $cacheSettings = array( 'memoryCacheSize' => '512MB');
        // PHPExcel_Settings::setCacheStorageMethod($cacheMethod,$cacheSettings); 

        $customerModel = new Customer;
        $total = $customerModel->getListCount($where, $extraWhere);

        $maxBatchCount  = ceil($total / $this->batchLimit);
        $maxExcelCount  = ceil($total / $this->linesPerExcel);
        $currentCount   = 0;
        $currentBatchNo = 1;
        $currentExcelNo = 1;
        //获取职员缓存信息
        // $adminModel     = new Admin;
        $briefAdminList = Admin::getAdminCache(Admin::ADMIN_BRIEF_CACHE_KEY);

        $excelDir = realpath(__DIR__ . '/../../data/reports/excels') . '/';
        $namePre  = '顾客资料_' . str_pad((string) $recordId, 11, '0', STR_PAD_LEFT);

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

        for (; $currentBatchNo <= $maxBatchCount; $currentBatchNo++) {
            $batchOffset = ($currentBatchNo - 1) * $this->batchLimit;

            $list  = $customerModel->getList($where, 'ctm_id', 'DESC', $batchOffset, $this->batchLimit, $extraWhere);
            
            foreach ($list as $key => $rowData) {
                $currentCount ++;
                $currentLineNo ++;

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
                                    'total' => $total,
                                    'status' => CmdRecords::STATUS_PROCESSING,
                                    'statusText' => "进行中...(excels: {$currentExcelNo} / {$maxExcelCount})",
                );
                $cmdRecord->delayUpdateProcessInfo($currentCount, $processJson, true);
            }


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
        $currentExcelSheet->mergeCells('D1:K1');
        $currentExcelSheet->getStyle('D1')->getFont()->setBold(true)->setSize(20);
        $currentExcelSheet->setCellValue('D1', $title);
        $currentExcelSheet->mergeCells('L1:N1');
        $currentExcelSheet->setCellValue('L1', date('Y-m-d H:i'));

        // for ($i = 'A'; $i <= 'Z'; $i ++) {
            $currentExcelSheet->getStyle('A2:Z2')->getFont()->setBold(true);
        // }

        $currentExcelSheet->setCellValue('A2', '上门状态');

        $currentExcelSheet->setCellValue('B2', '客户卡号');
        $currentExcelSheet->setCellValue('C2', '顾客');
        $currentExcelSheet->setCellValue('D2', '性别');

        $currentExcelSheet->setCellValue('E2', '地址');
        $currentExcelSheet->setCellValue('F2', '录入时间');
        $currentExcelSheet->setCellValue('G2', '手机号码');
        $currentExcelSheet->setCellValue('H2', '电话号码');
        $currentExcelSheet->setCellValue('I2', '定金余额');
        $currentExcelSheet->setCellValue('J2', '优惠券');
        $currentExcelSheet->setCellValue('K2', '消费积分');
        $currentExcelSheet->setCellValue('L2', '等级积分');
        $currentExcelSheet->setCellValue('M2', '总消费金额');


        $currentExcelSheet->setCellValue('N2', '营销渠道');
        $currentExcelSheet->setCellValue('O2', '客户来源');
        $currentExcelSheet->setCellValue('P2', '职业');

        $currentExcelSheet->setCellValue('Q2', '网络客服');
        $currentExcelSheet->setCellValue('R2', '首次客服项目');
        $currentExcelSheet->setCellValue('S2', '最近现场客服项目');
        $currentExcelSheet->setCellValue('T2', '最近现场客服');
        $currentExcelSheet->setCellValue('U2', '备注');
      
        return 2;
    }

    /**
     * 写入每行数据
     */
    private function generateRow(&$currentExcelSheet, $lineNo, &$rowData)
    {
        $currentExcelSheet->setCellValue('A' . $lineNo, $rowData['arrive_status'] ? '已上门' : '未上门');
        $currentExcelSheet->setCellValue('B' . $lineNo, $rowData['ctm_id']);
        $currentExcelSheet->setCellValue('C' . $lineNo, $rowData['ctm_name']);

        $currentExcelSheet->setCellValue('D' . $lineNo, $rowData['ctm_sex']);
        $currentExcelSheet->setCellValue('E' . $lineNo, $rowData['ctm_addr']);
        $currentExcelSheet->setCellValue('F' . $lineNo, date('Y-m-d H:i:s', $rowData['createtime']));

        $currentExcelSheet->setCellValue('G' . $lineNo, $rowData['ctm_mobile']);
        $currentExcelSheet->setCellValue('H' . $lineNo, $rowData['ctm_tel']);
        $currentExcelSheet->setCellValue('I' . $lineNo, $rowData['ctm_depositamt']);
        $currentExcelSheet->setCellValue('J' . $lineNo, $rowData['ctm_coupamt']);
        $currentExcelSheet->setCellValue('K' . $lineNo, $rowData['ctm_pay_points']);
        $currentExcelSheet->setCellValue('L' . $lineNo, $rowData['ctm_rank_points']);
        $currentExcelSheet->setCellValue('M' . $lineNo, $rowData['ctm_salamt']);
        $currentExcelSheet->setCellValue('N' . $lineNo, $rowData['ctm_explore']);

        $currentExcelSheet->setCellValue('O' . $lineNo, $rowData['ctm_source']);
        $currentExcelSheet->setCellValue('P' . $lineNo, $rowData['ctm_job']);
        $currentExcelSheet->setCellValue('Q' . $lineNo, $rowData['developStaffName']);

        $currentExcelSheet->setCellValue('R' . $lineNo, $rowData['ctm_first_cpdt_name']);
        $currentExcelSheet->setCellValue('S' . $lineNo, $rowData['ctm_last_osc_cpdt_name']);

        $currentExcelSheet->setCellValue('T' . $lineNo, $rowData['ctm_last_osc_admin_name']);
        $currentExcelSheet->setCellValue('U' . $lineNo, $rowData['ctm_remark']);
    }
}