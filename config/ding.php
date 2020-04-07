<?php

return [
    // 开发环境|沙盒环境
    // ding('production')->text()
    // ding('production')->exception()
    'production' => 'foo',
    'sandbox' => 'bar',

    // 本地开发默认机器人，自己申请一下。
    'default' => 'foo',

    'foo' => [
        'token' => 'b9b16a2b17bf27fca9fb17b197bd53486434fcd42c629308dcf87613b993bf84',
        'secret' => 'SEC9fb02fd3b0f2355a61939b91db64f8cefa5ce39fe2846ee112a77a481fd2b2aa',
        // 以下配置适用于机器人捕获异常
        // 钉钉报错标题
        'title' => '生产环境发生问题',
        // 默认钉钉报错描述。上报频率限制就是根据描述进行md5hash后进行比对
        'description' => '生产环境发生问题',
        // 异常发生时是否开启追踪
        'trace' => true,
        // 异常发生时是否限制上报频率
        'limit' => true,
        // 异常发生时开启limit后，每多少秒上报一次，limit为false不影响。随便好多无所谓
        'report_frequency' => 60,
        // 机器人别名。
        'nickname' => '生产',
    ],

    'bar' => [
        'token' => '30c22865131e24dc6c83d97ed60f7a8bad85d1a047808e48a1121ec5739bb3b6',
        'secret' => 'SEC4be28c900954776bb8543228a086d1856a9989ef27783899d58549703ef0ca70',
        'title' => '沙盒环境发生问题',
        'description' => '沙盒环境发生问题',
        'trace' => true,
        'limit' => true,
        'report_frequency' => 60,
        'nickname' => '沙盒',
    ],

    'money_maker' => [
        'token' => 'c74d4684b4641e76971085586b769226642250209141aa0f77ac1abed92c17b1',
        'secret' => 'SEC2a474bc9f35e0903845c7e94a51a547fba2fcbe19fdc70dde53ee4a42e8069f8',
        'title' => '异常咯',
        'description' => '改BUG',
        'trace' => false,
        'limit' => true,
        'report_frequency' => 20,
        'nickname' => 'MoneyMaker',
    ],
];