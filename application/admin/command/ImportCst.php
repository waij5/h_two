<?php

namespace app\admin\command;

use app\admin\model\Admin;
use app\admin\model\CmdRecords;
use app\admin\model\CProject;
use app\admin\model\Customer;
use app\admin\model\CustomerConsult;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;
use think\Db;
use yjy\exception\TransException;

class importCst extends Command
{

    protected function configure()
    {
        $this
            ->setName('importcst')
            ->addOption('force', 'f', Option::VALUE_OPTIONAL, 'force override', false)
            ->addOption('record', 'r', option::VALUE_REQUIRED, 'command record id')
            ->setDescription('Generate all business consult statistic report');
    }

    /**
     * 依次读取 上传CSV文件行
     * 逐行处理
     * 记录错误到EXCEL
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
        $startMTime            = microtime(true);
        $cmdRecord->createtime = time();
        $cmdRecord->save();

        $params = json_decode($cmdRecord->params, true);
        //参数异常，取消生成
        if (!isset($params['filePath']) || empty($params['source']) || empty($params['explore']) || empty($params['tool']) || empty($params['type'])) {
            $processJson = array(
                'completedCount' => 0,
                'total'          => 0,
                'successCount'   => 0,
                'failedCount'    => 0,
                'status'         => CmdRecords::STATUS_FAILED,
                'statusText'     => CmdRecords::STATUS_FAILED,
            );
            $cmdRecord->process = json_encode($processJson);
            $cmdRecord->status  = CmdRecords::STATUS_FAILED;
            $cmdRecord->save();

            $output->info('params not valid');
            return false;
        } else {
            $filePath = $params['filePath'];
        }

        $rvdays  = array();
        $curTime = time();
        if ($params['rvplan']) {
            $rvdays = \app\admin\model\Rvdays::alias('rvdays')
                ->join(\app\admin\model\Rvplan::getTable() . ' rvplan', 'rvdays.rvplan_id = rvplan.rvp_id')
                ->join(\app\admin\model\Rvtype::getTable() . ' rv_type', 'rvdays.rvtype_id = rv_type.rvt_id', 'LEFT')
                ->where('rvplan.rvp_id', '=', $params['rvplan'])
                ->where('rvplan.rvp_status', '=', 1)
                ->where('rvdays.rvd_status', '=', 1)
                ->order('rvdays.rvd_days', 'asc')
                ->column('rvdays.rvtype_id, rv_type.rvt_name, rvd_days, rvp_name, rvd_name', 'rvd_id');

            $rvdays = array_map(function ($rvday) use ($curTime) {
                $rvday['rvd_days'] = date('Y-m-d', strtotime('+' . $rvday['rvd_days'] . ' days', $curTime));
                $rvday['rvp_name'] = $rvday['rvp_name'] . '+' . $rvday['rvd_name'];

                return $rvday;
            }, $rvdays);
        }

        $fileHandler       = fopen($filePath, 'rb');
        $excelDir          = APP_PATH . 'data/reports/excels/';
        $failedFileName    = 'cst_fails_' . str_pad((string) $recordId, 11, '0', STR_PAD_LEFT) . '.csv';
        $failedFilePath    = $excelDir . $failedFileName;
        $failedFileHandler = fopen($failedFilePath, 'wb');
        fputcsv($failedFileHandler, [
            mb_convert_encoding('姓名', "gb2312", "UTF-8"),
            mb_convert_encoding('电话', "gb2312", "UTF-8"),
            mb_convert_encoding('项目', "gb2312", "UTF-8"),
            mb_convert_encoding('备注', "gb2312", "UTF-8"),
            mb_convert_encoding('网络客服', "gb2312", "UTF-8"),
            mb_convert_encoding('错误原因', "gb2312", "UTF-8"),
        ]);

        if ($fileHandler !== false) {
            //不考虑 客服项目名重复情况
            $cProjectList = CProject::where('cpdt_status', '=', 1)->column('id, dept_id', 'cpdt_name');
            $adminList    = Admin::where('status', '=', 'normal')->column('id', 'username');

            $currentCount = -1;
            $successCount = 0;
            $failedCount  = 0;

            while (($line = fgetcsv($fileHandler)) !== false) {
                sleep(1);
                //忽略第一行
                if ($currentCount++ == -1) {
                    continue;
                }
                //LINE 0 姓名 1 手机 2 项目 4 备注 5 营销人员
                // mb_convert_encoding('', "UTF-8", "gb2312")
                try {
                    // if (count($line) < 5) {
                    //     throw new TransException('参数个数不正确');
                    // }

                    $oriName        = @trim(strip_tags($line[0]));
                    $name           = @mb_convert_encoding($oriName, 'utf-8', 'gb2312');
                    $phone          = @trim($line[1]);
                    $oriCProject    = @trim($line[2]);
                    $cProject       = @mb_convert_encoding($oriCProject, 'utf-8', 'gb2312');
                    $oriMemo        = @trim(strip_tags($line[3]));
                    $memo           = @mb_convert_encoding($oriMemo, 'utf-8', 'gb2312');
                    $developerUName = @trim($line[4]);

                    if (empty($name)) {
                        throw new TransException('姓名格式错误');
                    }
                    //简单检查 手机格式
                    if (preg_match('/^[\w\-\+]{1,20}$/', $phone) == 0) {
                        throw new TransException('号码格式错误');
                    }
                    //检测 客服项目
                    if (!isset($cProjectList[$cProject])) {
                        throw new TransException('错误的客服项目');
                    } else {
                        $cpdtId = $cProjectList[$cProject]['id'];
                        $deptId = $cProjectList[$cProject]['dept_id'];
                    }
                    //检测 营销人员
                    if (!$developerUName || !isset($adminList[$developerUName])) {
                        throw new TransException('未找到对应网络客服');
                    } else {
                        $developerId = $adminList[$developerUName];
                    }
                    //检测 是否撞单
                    $sameCustomer = Customer::where('ctm_mobile', '=', $phone)->whereOr('ctm_tel', '=', $phone)->order('ctm_id', 'desc')->field(['ctm_id', 'ctm_name'])->find();
                    if ($sameCustomer && $sameCustomer['ctm_id']) {
                        throw new TransException("撞单： 卡号: {$sameCustomer->ctm_id} 姓名: {$sameCustomer->ctm_name}");
                    }
                } catch (TransException $e) {
                    $failedCount++;
                    fputcsv($failedFileHandler, [$oriName, $phone, $oriCProject, $oriMemo, $developerUName, mb_convert_encoding($e->getMessage(), "gb2312", "UTF-8")]);
                    $processJson = array(
                        'completedCount' => $currentCount,
                        'total'          => $currentCount,
                        'successCount'   => $successCount,
                        'failedCount'    => $failedCount,
                        'status'         => CmdRecords::STATUS_PROCESSING,
                        //打包中
                        'statusText'     => CmdRecords::STATUS_PROCESSING,
                    );
                    $cmdRecord->delayUpdateProcessInfo($currentCount, $processJson, false);
                    continue;
                } catch (exception $e) {
                    $output->info($e->getMessage());
                    return false;
                }

                try {
                    Db::startTrans();

                    $customer                    = new Customer;
                    $customer->ctm_name          = $name;
                    $customer->ctm_sex           = 1;
                    $customer->ctm_tel           = $phone;
                    $customer->ctm_mobile        = $phone;
                    $customer->ctm_name          = $name;
                    $customer->ctm_source        = $params['source'];
                    $customer->ctm_explore       = $params['explore'];
                    $customer->ctm_first_tool_id = $params['tool'];
                    $customer->ctm_first_dept_id = $deptId;
                    $customer->ctm_first_cpdt_id = $cpdtId;
                    $customer->ctm_first_search  = '';
                    $customer->admin_id          = $developerId;
                    $customer->ctm_remark        = '后台导入';

                    if ($customer->save()) {
                        $customerConsult              = new CustomerConsult;
                        $customerConsult->customer_id = $customer->ctm_id;
                        $customerConsult->admin_id    = $developerId;
                        $customerConsult->dept_id     = $deptId;
                        $customerConsult->cpdt_id     = $cpdtId;
                        $customerConsult->type_id     = $params['type'];
                        $customerConsult->tool_id     = $params['tool'];
                        $customerConsult->cst_content = $memo;
                        $customerConsult->cst_status  = 0;
                        $customerConsult->status      = 1;

                        //添加回访
                        foreach ($rvdays as $key => $rvdaySet) {
                            $rvinfo              = new \app\admin\model\Rvinfo();
                            $rvinfo->rvi_tel     = $customer->ctm_mobile;
                            $rvinfo->customer_id = $customer->ctm_id;
                            $rvinfo->rvt_type    = $rvdaySet['rvt_name'];
                            $rvinfo->rv_plan     = $rvdaySet['rvp_name'];
                            $rvinfo->rvi_content = '';
                            $rvinfo->admin_id    = $developerId;
                            $rvinfo->rv_date     = $rvdaySet['rvd_days'];
                            $rvinfo->rv_is_valid = 0;
                            $rvinfo->save();
                        }

                        if ($customerConsult->save()) {
                            Db::commit();
                            $successCount++;
                            $processJson = array(
                                'completedCount' => $currentCount,
                                'total'          => $currentCount,
                                'successCount'   => $successCount,
                                'failedCount'    => $failedCount,
                                'status'         => CmdRecords::STATUS_PROCESSING,
                                //打包中
                                'statusText'     => CmdRecords::STATUS_PROCESSING,
                            );
                            $cmdRecord->delayUpdateProcessInfo($currentCount, $processJson, false);
                        } else {
                            Db::rollback();
                            $failedCount++;
                            fputcsv($failedFileHandler, [$name, $phone, $cProject, $memo, $developerUName, "保存客服数据时出错"]);
                            $processJson = array(
                                'completedCount' => $currentCount,
                                'total'          => $currentCount,
                                'successCount'   => $successCount,
                                'failedCount'    => $failedCount,
                                'status'         => CmdRecords::STATUS_PROCESSING,
                                //打包中
                                'statusText'     => CmdRecords::STATUS_PROCESSING,
                            );
                            $cmdRecord->delayUpdateProcessInfo($currentCount, $processJson, false);
                            continue;
                        }

                    } else {
                        Db::rollback();
                        $failedCount++;
                        fputcsv($failedFileHandler, [$name, $phone, $cProject, $memo, $developerUName, "保存顾客数据时出错"]);
                        $processJson = array(
                            'completedCount' => $currentCount,
                            'total'          => $currentCount,
                            'successCount'   => $successCount,
                            'failedCount'    => $failedCount,
                            'status'         => CmdRecords::STATUS_PROCESSING,
                            //打包中
                            'statusText'     => CmdRecords::STATUS_PROCESSING,
                        );
                        $cmdRecord->delayUpdateProcessInfo($currentCount, $processJson, false);
                        continue;
                    }
                } catch (think\exception\PDOException $e) {
                    Db::rollback();
                    $failedCount++;
                    fputcsv($failedFileHandler, [$name, $phone, $cProject, $memo, $developerUName, "保存数据时出错"]);
                    $processJson = array(
                        'completedCount' => $currentCount,
                        'total'          => $currentCount,
                        'successCount'   => $successCount,
                        'failedCount'    => $failedCount,
                        'status'         => CmdRecords::STATUS_PROCESSING,
                        //打包中
                        'statusText'     => CmdRecords::STATUS_PROCESSING,
                    );
                    $cmdRecord->delayUpdateProcessInfo($currentCount, $processJson, false);
                    continue;
                }
            }

            @fclose($failedFileHandler);
            @fclose($fileHandler);

            if ($failedCount == 0) {
                @unlink($failedFilePath);
            } else {
                $cmdRecord->filepath = '/reports/excels/' . $failedFileName;
            }

            // $cmdRecord->filepath = '/reports/compressed_files/' . rtrim($namePre, '_') . '.tgz';
            $cmdRecord->status = CmdRecords::STATUS_COMPLETED;
            $cmdRecord->save();

            //进度信息--完成
            $cmdRecord->endtime  = time();
            $cmdRecord->costtime = microtime(true) - $startMTime;
            $processJson         = array(
                'completedCount' => $currentCount,
                'total'          => $currentCount,
                'successCount'   => $successCount,
                'failedCount'    => $failedCount,
                'status'         => CmdRecords::STATUS_COMPLETED,
                //打包中
                'statusText'     => CmdRecords::STATUS_COMPLETED,
            );
            $cmdRecord->delayUpdateProcessInfo($currentCount, $processJson, false);

            $output->info("Generate Successed!");
        } else {
            //打开文件失败
            $processJson = array(
                'completedCount' => 0,
                'total'          => 0,
                'successCount'   => $successCount,
                'failedCount'    => $failedCount,
                'status'         => CmdRecords::STATUS_FAILED,
                'statusText'     => CmdRecords::STATUS_FAILED,
            );
            $cmdRecord->process = json_encode($processJson);
            $cmdRecord->status  = CmdRecords::STATUS_FAILED;
            $cmdRecord->save();
            $output->info("Could not open file: " . $filePath);
            return false;
        }
    }
}
