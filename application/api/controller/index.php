<?php

namespace app\api\controller\deduct;

use app\common\controller\api;
use think\Request;

class index extends api
{
    // $user = model('user')->find(1);

    public function index()
    {
        //如果发送的来源是Selectpage，则转发到Selectpage
        if ($this->request->request('pkey_name')) {
            return $this->selectpage();
        }
        list($where, $sort, $order, $offset, $limit) = $this->buildparams(null, null, false);

        list($bWhere, $extraWhere) = $this->handleRequest($type, $where);

        if (is_null(input('op'))) {
            if ($type == 'invalid') {
                $bWhere['ctm_status'] = 0;
            } else {
                $bWhere['ctm_status'] = 1;
            }
        }

        if (isset($extraWhere['customer.month'])) {
            $birthWhereF                 = '%-' . str_pad($extraWhere['customer.month'][1], 2, '0', STR_PAD_LEFT) . '-%';
            $extraWhere['ctm_birthdate'] = ['like', $birthWhereF];
            unset($extraWhere['customer.month']);
        }

        if (isset($bWhere['ctm_birthdate'])) {
            $ageStart = $bWhere['ctm_birthdate'][1][0];
            $ageEnd   = $bWhere['ctm_birthdate'][1][1] + 1;
            $bigAge   = getBirthDate($ageStart);
            $smallAge = getBirthDate($ageEnd);

            $bWhere['ctm_birthdate'][1][0] = $smallAge;
            $bWhere['ctm_birthdate'][1][1] = $bigAge;

        }

        $total = $this->model->getListCount($bWhere, $extraWhere);
        $list  = $this->model->getList($bWhere, $sort, $order, $offset, $limit, $extraWhere);

        $result = array("total" => $total, "rows" => $list);

        return json($result);
    }
}
