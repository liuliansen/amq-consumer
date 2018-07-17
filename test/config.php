<?php
//+-------------------------------------------------------------
//| 
//+-------------------------------------------------------------
//| Author Liu LianSen <liansen@d3zz.com> 
//+-------------------------------------------------------------
//| Date 2018-07-16
//+-------------------------------------------------------------
return [
    'task' => [
        /*
        * 需要启动的服务
        * 配置格式
        *  [
        *     'name'           => 'demo演示',              // workerman中的进程名称
        *     'processNum'     => 4,                       // 启动的处理进程数量,
        *     'class'          => yourServiceClass::class, // 提供服务的类
        *     'host'           => 'localhost',             // mq所在host
        *     'port'           => 1234,                    // mq的端口
        *     'user'           => 'test',                  // mq的user
        *     'password'       => 'test',                  // mq user的密码
        *     'vhost'          => 'test',                  // 目标队列所在的vhost
        *     'queue'          => 'test',                  // 目标队列名称
        *  ]
        */
        [
            'name'           => 'demo演示',
            'processNum'     => 1,
            'class'          => \amqconsumer\test\Demo::class,
            'host'           => '192.168.0.123',
            'port'           => 7562,
            'user'           => 'test',
            'password'       => 'test',
            'vhost'          => 'test',
            'queue'          => 'test',
        ]

    ],
];


