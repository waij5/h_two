<?php

namespace app\admin\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\console\input\Option;
use \app\admin\model\Rvinfo;
use \app\admin\model\RevisitFilter;
use think\Db;

class UpdateRvinfoValid extends Command
{

    protected $model      = null;
    protected $batchLimit = 2000;

    protected function configure()
    {
        $this
            ->setName('updatervinfovalid')
            ->addOption('type', 't', option::VALUE_REQUIRED, 'type 1 transfer to valid, type 2 transfer to 1')
            ->setDescription('update rvinfo valid');
    }

    /**
     * 分批查询记录

     */
    protected function execute(Input $input, Output $output)
    {
        ini_set('memory_limit', '256M');
        $type = $input->getOption('type') ?: 1;

        $rvinfoTable = (new Rvinfo)->getTable();

        if ($type == 1) {
            $where = ['rv_time' => ['notnull', true], 'rvi_content' => ['neq', ''], 'rv_is_valid' => 0];
        } else {
            $where = ['rv_is_valid' => 1];
        }

        $currentMaxId = 0;
        $currentCount = 0;
        $updateCount = 0;

        $total = Rvinfo::where($where)->where(['rvi_id' => ['>', $currentMaxId]])->count();
        $initTotal = $total;
        $output->info('summary total: ' . $total);

        $currentLoop = 0;

        $filterWords = RevisitFilter::where(['filter_status' => 1])
                    ->order('filter_sort desc, filter_id', 'desc')->column('filter_name');
        $revisitFilterSet = \think\Config::get('strict_revisit_filter');


        do {
            $currentLoop ++;
            $list = Rvinfo::where($where)->where(['rvi_id' => ['>', $currentMaxId]])->order('rvi_id', 'asc')->limit(0, $this->batchLimit)->column('*');

            foreach ($list as $key => $rvinfo) {
                $currentCount ++;
                $currentMaxId = $rvinfo['rvi_id'];

                $rvIsValid = 1;
                if ($revisitFilterSet) {
                    foreach ($filterWords as $filterWord) {
                        //UTF8，忽略大小写
                        if (preg_match('/\b' . preg_quote($filterWord) . '\b/ui', $rvinfo['rvi_content'])) {
                            $rvIsValid = 0;
                            break;
                        }
                    }
                } else {
                    foreach ($filterWords as $filterWord) {
                        if (mb_stripos($rvinfo['rvi_content'], $filterWord, 0, 'utf-8') !== false) {
                            $rvIsValid = 0;
                            break;
                        }
                    }
                }
                if ($rvinfo['rv_is_valid'] != $rvIsValid) {
                    $updateCount += DB::table($rvinfoTable)->where(['rvi_id' => $rvinfo['rvi_id']])->update(['rv_is_valid' => $rvIsValid]);
                }

                if ($currentCount % 50 == 0) {
                    $output->info("processing " . $currentCount . ' / ' . $initTotal . ' update ' . $updateCount);
                }

                $rvinfo = null;
                unset($rvinfo);
                $list[$key] = null;
                unset($list[$key]);
            }

            unset($list);
            $list = null;

            $total = Rvinfo::where($where)->where(['rvi_id' => ['>', $currentMaxId]])->count();

            $output->info('loop memory usage: ' . floor(memory_get_peak_usage() / 1024 /1024) . ' MB');
        } while ($total);

        $output->info("Generate Successed!");
    }

}
