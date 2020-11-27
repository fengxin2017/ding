<?php

namespace Fengxin2017\Ding;

use Carbon\Carbon;
use Exception;
use Fengxin2017\Ding\Contracts\CoreContract;
use Fengxin2017\Ding\Exceptions\DingRequestException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;

/**
 * Class Ding.
 */
class Ding implements CoreContract
{
    use Macroable {
        __call as macroCall;
    }

    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $secret;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var bool
     */
    protected $trace;

    /**
     * @var bool
     */
    protected $limit;

    /**
     * @var int
     */
    protected $reportFrequency;

    /**
     * @var array
     */
    protected $defaultConfig;

    /**
     * @var string
     */
    protected $apiUrl;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * Ding constructor.
     *
     * @param array|null $params
     */
    public function __construct(array $params = null)
    {
        $this->apiUrl = Config::get('ding.ding-api-url', 'https://oapi.dingtalk.com/robot/send?access_token=%s&timestamp=%s&sign=%s');
        $this->client = new Client([
            'timeout'         => Config::get('ding.request_timeout', 10),
            'connect_timeout' => Config::get('ding.connect_timeout', 30),
            'http_errors'     => Config::get('ding.http_errors', false),
            'verify'          => Config::get('ding.verify', false),
        ]);

        $this->defaultConfig = $this->getDefaultConfig();
        $this->token = $params['token'] ?? $this->defaultConfig['token'];
        $this->secret = $params['secret'] ?? $this->defaultConfig['secret'];
        $this->title = $params['title'] ?? $this->defaultConfig['title'];
        $this->description = $params['description'] ?? $this->defaultConfig['description'];
        $this->trace = $params['trace'] ?? $this->defaultConfig['trace'];
        $this->limit = $params['limit'] ?? $this->defaultConfig['limit'];
        $this->reportFrequency = $params['report_frequency'] ?? $this->defaultConfig['report_frequency'];
    }

    /**
     * @return array
     */
    protected function getDefaultConfig(): array
    {
        return Config::get('ding.'.$this->getDefaultBotName());
    }

    /**
     * @return string
     */
    protected function getDefaultBotName(): string
    {
        return Config::get('ding.default');
    }

    /**
     * @param string $token
     *
     * @return $this
     */
    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $secret
     *
     * @return $this
     */
    public function setSecret(string $secret): self
    {
        $this->secret = $secret;

        return $this;
    }

    /**
     * @return string
     */
    public function getSecret(): string
    {
        return $this->secret;
    }

    /**
     * @param string $title
     *
     * @return $this|mixed
     */
    public function setTitle(string $title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param $description
     *
     * @return $this|mixed
     */
    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return bool
     */
    public function getTrace(): bool
    {
        return $this->trace;
    }

    /**
     * @param bool $trace
     *
     * @return $this
     */
    public function setTrace(bool $trace): self
    {
        $this->trace = $trace;

        return $this;
    }

    /**
     * @param bool $limit
     *
     * @return $this
     */
    public function setLimit(bool $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @return bool
     */
    public function getLimit(): bool
    {
        return $this->limit;
    }

    /**
     * @param int $reportFrequency
     *
     * @return $this|mixed
     */
    public function setReportFrequency(int $reportFrequency): self
    {
        $this->reportFrequency = $reportFrequency;

        return $this;
    }

    /**
     * @return int
     */
    public function getReportFrequency(): int
    {
        return $this->reportFrequency;
    }

    /**
     * @param string $text
     *
     * @throws \Fengxin2017\Ding\Exceptions\DingRequestException
     *
     * @return mixed|void
     */
    public function text(string $text)
    {
        return $this->ding('text', $text);
    }

    /**
     * @param string $type
     * @param string $content
     * @param string $contentType
     *
     * @throws \Fengxin2017\Ding\Exceptions\DingRequestException
     */
    protected function ding(string $type, string $content, string $contentType = 'content')
    {
        if ($type === 'markdown') {
            $contentType = 'text';
        }

        $this->sendDingTalkRobotMessage([
            'msgtype' => $type,
            $type     => [
                'title'      => $this->title,
                $contentType => $content,
            ],
        ]);
    }

    /**
     * @param array $msg
     *
     * @throws \Fengxin2017\Ding\Exceptions\DingRequestException
     *
     * @return bool|mixed
     */
    public function sendDingTalkRobotMessage(array $msg)
    {
        try {
            if (static::hasMacro('sendMessage')) {
                return $this->macroCall('sendMessage', $msg);
            }

            $timestamp = (string) (time() * 1000);
            $secret = $this->getSecret();
            $token = $this->getToken();
            $sign = urlencode(base64_encode(hash_hmac('sha256', $timestamp."\n".$secret, $secret, true)));
            $response = $this->client->post(sprintf($this->apiUrl, $token, $timestamp, $sign), ['json' => $msg]);
            $result = json_decode($response->getBody(), true);
            if (!isset($result['errcode']) || $result['errcode']) {
                throw new DingRequestException('DingTalk: send robot message fail, "errcode" is NOT 0, response body is '.$result);
            }

            return true;
        } catch (Exception $exception) {
            throw new DingRequestException('DingTalk: send robot message fail, '.$exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * @param string $markdown
     *
     * @throws \Fengxin2017\Ding\Exceptions\DingRequestException
     *
     * @return mixed|void
     */
    public function markdown(string $markdown)
    {
        return $this->ding('markdown', $markdown);
    }

    /**
     * @param \Exception $exception
     *
     * @throws \Fengxin2017\Ding\Exceptions\DingRequestException
     *
     * @return mixed|void
     */
    public function exception(Exception $exception)
    {
        if (!$this->shouldReport($exception)) {
            return;
        }

        $this->sendDingTalkRobotMessage([
            'msgtype'  => 'markdown',
            'markdown' => [
                'title' => $this->title,
                'text'  => $this->formatToMarkdown($exception),
            ],
        ]);
    }

    /**
     * @param \Exception $exception
     *
     * @return bool
     */
    protected function shouldReport(Exception $exception): bool
    {
        if (false == $this->limit) {
            return true;
        }

        if (Cache::get($key = md5($exception->getMessage()))) {
            return false;
        }

        Cache::put($key, true, $this->reportFrequency);

        return true;
    }

    /**
     * @param $exception
     *
     * @return array|string
     */
    protected function formatToMarkdown(Exception $exception)
    {
        /** @var Exception $exception */
        $class = get_class($exception);
        $message = $exception->getMessage();
        $file = $exception->getFile();
        $line = $exception->getLine();
        $time = Carbon::now()->toDateTimeString();
        $fullUrl = app('request')->fullUrl();
        $userAgent = app('request')->userAgent();
        $method = app('request')->method();
        $ip = app('request')->getClientIp() ?: '127.0.0.1';
        /** @noinspection JsonEncodingApiUsageInspection */
        $params = json_encode(app('request')->all());
        $hostName = gethostname();
        $env = app()->environment();

        $explode = explode("\n", $exception->getTraceAsString());
        array_unshift($explode, '');

        $limit = $this->getLimit() && $this->reportFrequency;

        $reportFrequency = $this->limit ? $this->reportFrequency : null;

        $messageBody = [
            ['描述', $this->description],
            ['主机名称', $hostName],
            ['环境', $env],
            ['类名', $class],
            ['请求IP', $ip],
            ['请求参数', $params],
            ['时间', $time],
            ['请求方式', $method],
            ['请求地址', $fullUrl],
            ['用户代理', $userAgent],
            ['异常描述', $message],
            ['当前播报限制', $limit ? '开启(每'.$reportFrequency.'s 一次)' : '关闭'],
            ['参考位置', sprintf('%s:%d', str_replace([app()->basePath(), '\\'], ['', '/'], $file), $line)],
        ];

        if ($this->getTrace()) {
            $messageBody[] = [
                '堆栈信息',
                PHP_EOL.'>'.implode(PHP_EOL.'> - ', $explode),
            ];
        }

        $messageBody = array_map(function ($item) {
            [$key, $val] = $item;

            return sprintf('- %s: %s> %s', $key, PHP_EOL, $val);
        }, $messageBody);
        $messageBody = implode(PHP_EOL, $messageBody);

        return $messageBody;
    }

    /**
     * @param string $method
     * @param array  $parameters
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function __call(string $method, array $parameters)
    {
        if (static::hasMacro($method)) {
            return $this->macroCall($method, $parameters);
        }

        if ($config = Config::get('ding.'.Str::snake($method))) {
            $this->token = $config['token'] ?? $this->token;
            $this->secret = $config['secret'] ?? $this->secret;
            $this->title = $config['title'] ?? $this->title;
            $this->description = $config['description'] ?? $this->description;
            $this->trace = $config['trace'] ?? $this->trace;
            $this->limit = $config['limit'] ?? $this->limit;
            $this->reportFrequency = $config['report_frequency'] ?? $this->reportFrequency;

            return $this;
        }

        throw new Exception('call to undefined function '.$method);
    }
}
