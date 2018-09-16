<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-16 10:45:12
 */

namespace Yeskn\Support\Http;

use Symfony\Component\HttpFoundation\JsonResponse;

class ApiResponse extends JsonResponse
{
    public function __construct($status = 1, $detail = null, $message = '操作成功')
    {
        parent::__construct([
            'status' => $status,
            'message' => $message,
            'detail' => $detail,
        ]);
    }
}