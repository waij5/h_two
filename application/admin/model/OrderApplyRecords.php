<?php

namespace app\admin\model;

use app\admin\model\OrderItems;
use think\Config;
use think\Model;

class OrderApplyRecords extends Model
{
    // 表名
    protected $name = 'order_apply_records';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    // 追加属性
    protected $append = [
    ];

    const STATUS_CANCELED = -2;
    const STATUS_DENYED   = -1;
    const STATUS_PENDING  = 0;
    const STATUS_ACCEPTED = 1;

    /**
     * 处理审批
     * @param string $type ACCEPT / DENY / CANCEL
     * site.discount_limit_flag maxRateLimit 同时有值时 检验价格折扣
     */
    public function dealApply($type, $replyAdminId, $replyInfo = '', $maxRateLimit = false)
    {
        $result       = ['error' => true, 'msg' => 'Error occurs'];
        $relatedOrder = OrderItems::get($this->item_id);
        if (empty($relatedOrder)) {
            //审批的订单未找到
            $result['msg'] = __('Can not find related order!');
            return $result;
        }

        if ($relatedOrder->item_status != OrderItems::STATUS_APPLYING) {
            //审批的订单 状态已更改
            $result['msg'] = __('Related order\' status has been changed!');
            return $result;
        }

        $updateData = ['reply_info' => $replyInfo, 'reply_admin_id' => $replyAdminId];
        $type       = strtoupper($type);
        if ($type == 'ACCEPT') {
            //是否启用折扣限制
            $discountLimitFlag = \think\Config::get('site.discount_limit_flag', false);
            $itemAmount = $relatedOrder->item_total / $relatedOrder->item_qty;
            $admin = \think\Session::get('admin');
            $discountLimit     = $admin->getDiscountLimit();

            //启用折扣限制，并且超出折扣限制
            if ($discountLimitFlag && !isDiscountAllowed($itemAmount, $relatedOrder->pro_amount, $relatedOrder->pro_min_amount, $discountLimit)) {
                $result['msg'] = __('Discount is out of your permission!');
            } else {
                if ($relatedOrder->save(['item_status' => OrderItems::STATUS_PENDING]) != false) {
                    $updateData['reply_status'] = self::STATUS_ACCEPTED;
                    $this->save($updateData);
                    $result['error'] = false;
                    $result['msg']   = '';
                }
            }
        } elseif ($type == 'CANCEL') {
            //只能本人取消
            // if ($relatedOrder->admin_id == $replyAdminId) {// }
            if ($relatedOrder->save(['item_status' => OrderItems::STATUS_CANCELED]) != false) {
                $updateData['reply_status'] = self::STATUS_CANCELED;
                $this->save($updateData);
                $result['error'] = false;
                $result['msg']   = '';
            } else {
                $result['msg'] = 'You have no permission';
            }
        } elseif ($type == 'DENY') {
            if ($relatedOrder->item_status != OrderItems::STATUS_PENDING && $relatedOrder->item_status != OrderItems::STATUS_APPLYING) {
                $return['msg'] = __('Can not cancel order with this status!');
            } else {
                try
                {
                    \think\Db::startTrans();
                    if ($relatedOrder->save(['item_status' => OrderItems::STATUS_CANCELED]) !== false) {
                        $updateData['reply_status'] = self::STATUS_DENYED;
                        $this->save($updateData);
                    }
                    \think\Db::commit();
                    $result['error'] = false;
                    $result['msg']   = '';
                } catch (think\exception\PDOException $e) {
                    \think\Db::rollback();
                    $return['msg'] = $e->getMessage();
                }
            }
        } else {
            $result['msg'] = 'Invalid parameters';
        }

        return $result;
    }
}
