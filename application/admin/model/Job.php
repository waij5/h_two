<?php

namespace app\admin\model;

class Job
{
    static $data = [
                    '' => '--',
                    '14' => '自由职业',
                    '2' => '教师',
                    '3' => '工程师',
                    '4' => '公司老总',
                    '5' => '护士',
                    '6' => '金融财务',
                    '7' => '家庭主妇',
                    '8' => '夜场从业者',
                    '9' => '演艺人员',
                    '10' => '政府人员',
                    '11' => '国企人员',
                    '12' => '职业小三',
                    '13' => '农民',
                    '15' => '军人',
                    '16' => '普通工人',
                    '17' => '白领阶层',
                    '18' => '个体工商户',
                    '19' => 'IT从业者',
                    '20' => '军官',
                    '21' => '退伍军人',
                    '22' => '律师',
                    '23' => '会计',
                    '24' => '退休人员',
                    '25' => '医生',
                    '26' => '文员',
                    '27' => '渔民',
                    '28' => '农民工',
                    '29' => '银行',
                    '30' => '导游',
                    '31' => '销售',
                    '1'  => '学者',

    ];


    public static function getTitleById($id)
    {
        $title = '';
        if (isset(self::$data[$id])) {
            $title = self::$data[$id];
        }

        return $title;
    }

    public static function getList()
    {
        return self::$data;
    }
}