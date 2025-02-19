<?php
require_once __DIR__ . "/../../classes/order/Order.php";
require_once __DIR__ . "/../../classes/user/user.php";
require_once __DIR__ . "/../../utils/validator.php";


class CheckOrderController {
    private $orderModel;
    private $userModel;

    public function __construct() {
        $this->orderModel = new Order();
        $this->userModel = new User();
    }

    public function get_all_orders_with_items() {
        return $this->orderModel->get_all_orders_with_items();
    }

    public function get_all_users() {
        return $this->userModel->get_all_users();
    }

    public function filter_orders_by_date_and_user($date_from, $date_to, $user_id) {
        return $this->orderModel->filterOrderbyDateAndUser($date_from, $date_to, $user_id);
    }

    public function filter_orders_by_date($date_from, $date_to) {
        return $this->orderModel->filterOrderbyDate($date_from, $date_to);
    }

    public function filter_orders_by_user($user_id) {
        return $this->orderModel->filterOrderbyUser($user_id);
    }
}
?>