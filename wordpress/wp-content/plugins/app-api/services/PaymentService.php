<?php

class PaymentService {

    public static function mark_as_paid($order_id, $method, $transaction_id) {

        update_post_meta($order_id, 'status', 'paid');
        update_post_meta($order_id, 'payment_method', $method);
        update_post_meta($order_id, 'transaction_id', $transaction_id);
        update_post_meta($order_id, 'paid_at', current_time('mysql'));
    }

    public static function fail($order_id, $reason = '') {

        update_post_meta($order_id, 'status', 'failed');
        update_post_meta($order_id, 'failure_reason', $reason);
    }
}
