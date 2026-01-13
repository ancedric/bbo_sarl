<?php

function api_response($data = null, $message = '', $status = 200) {
    return new WP_REST_Response([
        'success' => $status < 400,
        'message' => $message,
        'data' => $data
    ], $status);
}
