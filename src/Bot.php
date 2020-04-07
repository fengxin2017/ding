<?php

namespace Fengxin2017\Ding;

use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\ForwardsCalls;
use ReflectionClass;
use ReflectionMethod;

/**
 * @method static void text($text)
 * @method static void markdown($markdown)
 * @method static void exception($exception)
 * @method static Fengxin2017\Ding\Ding setToken(string $token)
 * @method static string getToken()
 * @method static Fengxin2017\Ding\Ding setSecret(string $secret)
 * @method static string getSecret()
 * @method static Fengxin2017\Ding\Ding setTitle(string $title)
 * @method static string getTitle()
 * @method static Fengxin2017\Ding\Ding setDescription(string $description)
 * @method static string getDescription()
 * @method static Fengxin2017\Ding\Ding setTrace(bool $trace)
 * @method static string getTrace()
 * @method static Fengxin2017\Ding\Ding setLimit(bool $limit)
 * @method static string getLimit()
 * @method static Fengxin2017\Ding\Ding setReportFrequency(int $reportFrequency)
 * @method static string getReportFrequency()
 *
 * @see Fengxin2017\Ding\Ding
 * Class Bot
 *
 * @package App\Ding
 */
abstract class Bot
{
    use ForwardsCalls;

    /**
     * @var array
     */
    protected static $cores = [];

    /**
     * @var array
     */
    protected static $validMethods = [];

    /**
     * Bot constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * @param string $method
     * @param array $params
     * @return mixed
     * @throws \ReflectionException|\Exception
     */
    public function __call(string $method, array $params)
    {
        if (! in_array($method, $this->validMethods())) {
            throw new Exception('call to undefine method '.$method);
        }

        return $this->forwardCallTo($this->getTheExactCore(), $method, $params);
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    protected function validMethods(): array
    {
        if (! static::$validMethods) {
            foreach ((new ReflectionClass(Ding::class))->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                $methodName = $method->getName();
                if (! in_array($methodName, ['__construct', '__call'])) {
                    static::$validMethods[] = $methodName;
                }
            }
        }

        return static::$validMethods;
    }

    /**
     * @return mixed
     */
    protected function getTheExactCore()
    {
        $snakeBotName = $this->getSnakeBotName();

        if (isset(static::$cores[$snakeBotName])) {
            return static::$cores[$snakeBotName];
        }

        return static::$cores[$snakeBotName] = App::make('ding', Config::get('ding.'.$snakeBotName));
    }

    /**
     * @return string
     */
    protected function getSnakeBotName(): string
    {
        return Str::snake(substr(static::class, strrpos(static::class, '\\') + 1));
    }

    /**
     * @param string $method
     * @param array $params
     *
     * @return mixed
     */
    public static function __callStatic($method, $params)
    {
        return (new static())->$method(...$params);
    }
}