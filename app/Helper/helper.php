<?php

use App\Models\User;
use Illuminate\Support\Facades\Log;

if (!function_exists('store_log')) {
    function store_log($txt, $type = 'info')
    {
        if ($type == 'error') {
            return Log::error($txt);
        } else {
            return Log::info($txt);
        }
    }
}

if (!function_exists('get_option')) {
    function get_option($key)
    {
        switch ($key) {
            case 'site_title':
                return "ABC Bank";
                break;
            default:
                return "";
                break;
        }
    }
}

if (!function_exists('format_price')) {
    function format_price($price)
    {
        $formatted = number_format($price, 2);
        if (substr($formatted, -3) === '.00') {
            return '$' . substr($formatted, 0, -3);
        }
        return '$' . $formatted;
    }
}

if(!function_exists('pending_users_count')){
    function pending_users_count(){
        return User::where('status', 'pending')->where('type', 'user')->count();
    }
}
