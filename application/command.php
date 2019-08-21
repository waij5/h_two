<?php

// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------

return [
    'app\admin\command\Crud',
    'app\admin\command\Menu',
    'app\admin\command\Install',
    'app\admin\command\Min',
    'app\admin\command\Addon',

    'app\admin\command\Stat',
    'app\admin\command\Orderitemsreport',
    'app\admin\command\Orderitemsdetailreport',
    'app\admin\command\Operatebenefit',
    'app\admin\command\Osconsultrate',
    'app\admin\command\Consultrate',
    'app\admin\command\Rvinforeport',
    'app\admin\command\BalanceReport',
    'app\admin\command\Dailystat',
    'app\admin\command\Changedetail',
    'app\admin\command\Deductrecordsreport',
    'app\admin\command\Customerconfiscate',
    'app\admin\command\Stockdetail',
    'app\admin\command\Changepool',
    'app\admin\command\Receive',
    'app\admin\command\Stockbalance',
    'app\admin\command\UpdateRvinfoValid',
    'app\admin\command\CustomerProfile',
    'app\admin\command\Check',
 
    'app\admin\command\Fixmobile',
    // 合并顾客信息
    'app\admin\command\MergeCustomer',
    // 合并顾客信息 非完全匹配经确认的顾客信息
    'app\admin\command\MergeCustomerPassed',
    // 同步积分变动到商城
    'app\admin\command\SyncPointsToMall',
    // 同步积分变动到HIS
    'app\admin\command\SyncPointsToHIS',
    'app\admin\command\MonthlyCustomerStat',
    'app\admin\command\PointsClear',

    'app\admin\command\Psi',
    'app\admin\command\Changepools',
    'app\admin\command\Changedetails',
    'app\admin\command\Depdraw',
    'app\admin\command\Checks',
    'app\admin\command\Checklot',
    'app\admin\command\Stocksurplus',
    'app\admin\command\Recipe',
    'app\admin\command\Goodsrecipe',

    'app\admin\command\MonthlyCustomerStat',
    'app\admin\command\PointsClear',

    'app\admin\command\MonthlyCustomerStatReport',
    'app\admin\command\TempT',
    
    'app\admin\command\Consultstat',
    'app\admin\command\FixChargeBackForV2',

    'app\admin\command\Orderitemschangereport',
    
    'app\admin\command\CustomerosconsultReport',
    'app\admin\command\CustomerconsultReport',
    'app\admin\command\DailyFeeSummary',
    'app\admin\command\Project',
    'app\admin\command\DrugsReport',
    'app\admin\command\GoodsReport',
    'app\admin\command\ImportCst',
    'app\admin\command\CashdetailsReport',
    
    'app\admin\command\MergeHisCustomer',

    'app\admin\command\mapCus',

    'app\admin\command\DeductlistReport',
];
