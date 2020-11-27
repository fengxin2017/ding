<?php

return [
    // 默认机器人
    'default' => 'foo',

    'foo' => [
        'token'  => '',
        'secret' => '',
        // 钉钉报错标题
        'title' => '生产环境发生问题',
        // 默认钉钉报错描述。
        'description' => '生产环境发生问题',
        // 异常发生时是否开启追踪
        'trace' => true,
        // 异常发生时是否限制上报频率
        'limit' => true,
        // 异常发生时开启limit后，每多少秒上报一次，limit为false不影响。
        'report_frequency' => 60,
        // 机器人别名。
        'nickname' => '生产',
    ],

    'bar' => [
        'token'            => '',
        'secret'           => '',
        'title'            => '沙盒环境发生问题',
        'description'      => '沙盒环境发生问题',
        'trace'            => true,
        'limit'            => true,
        'report_frequency' => 60,
        'nickname'         => '沙盒',
    ],

    'money_maker' => [
        'token'            => 'c74d4684b4641e76971085586b769226642250209141aa0f77ac1abed92c17b1',
        'secret'           => 'SEC2a474bc9f35e0903845c7e94a51a547fba2fcbe19fdc70dde53ee4a42e8069f8',
        'title'            => '异常咯',
        'description'      => '改BUG',
        'trace'            => false,
        'limit'            => true,
        'report_frequency' => 20,
        'nickname'         => 'MoneyMaker',
    ],

    // 开发|沙盒异常捕获
    // ding(config('ding.' . $environment))->exception($e);
    // ding(config('ding.' . $environment))->exception($e);
    'production' => 'foo',
    'sandbox'    => 'bar',
];
