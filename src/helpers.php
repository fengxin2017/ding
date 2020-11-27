<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

if (!function_exists('ding')) {
    /**
     * @param $params
     *
     * @return Fengxin2017\Ding\Ding
     */
    function ding($params = [])
    {
        if (is_string($params)) {
            $params = Config::get('ding.'.$params);
        }

        return App::make('ding', $params);
    }
}
