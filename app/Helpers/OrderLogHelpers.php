<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Request;
use App\Models\OrderLog as OrderLogModel;

class OrderLogHelpers
{
    public static function addToOrderLog($orderno, $action)
    {
        $log = [];
        $log['orderno'] = $orderno;
        $log['action'] = $action;
        $log['url'] = Request::fullUrl();
        $log['method'] = Request::method();
        $log['agent'] = Request::header('user-agent');
        $log['admin'] = auth()->check() ? auth()->user()->id : 1;

        OrderLogModel::create($log);
    }

    public static function OrderLog()
    {
        return OrderLogModel::latest()->get();
    }
}
