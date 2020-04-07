<?php

namespace Fengxin2017\Ding\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void text($text)
 * @method static void markdown($markdown)
 * @method static void exception($exception)
 * @method static \Fengxin2017\Ding\Ding setToken($token)
 * @method static string getToken()
 * @method static \Fengxin2017\Ding\Ding setSecret($secret)
 * @method static string getSecret()
 * @method static \Fengxin2017\Ding\Ding setTitle($title)
 * @method static string getTitle()
 * @method static \Fengxin2017\Ding\Ding setDescription($description)
 * @method static string getDescription()
 * @method static \Fengxin2017\Ding\Ding setTrace($trace)
 * @method static string getTrace()
 * @method static \Fengxin2017\Ding\Ding setLimit($limit)
 * @method static string getLimit()
 * @method static \Fengxin2017\Ding\Ding setReportFrequency($reportFrequency)
 * @method static string getReportFrequency()
 *
 * @see \Fengxin2017\Ding\Ding
 */
class Ding extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ding';
    }
}
