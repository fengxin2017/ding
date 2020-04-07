<?php

if (! function_exists('ding')) {
    /**
     * @param array $params
     *
     * @return Fengxin2017\Ding\Ding
     */
    function ding($params = [])
    {
        if (is_string($params)) {
            $params = config('ding.'.$params);
        }

        return app('ding', $params);
    }
}