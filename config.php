<?php

return [
    'server'     => [
//设置程序进入后台作为守护进程运行
        'daemonize'                => 0,
        //指定Reactor线程数
        'reactor_num'              => 8,
        // 指定启动的worker进程数
        'worker_num'               => 4,
        //服务器开启的task进程数
        'task_worker_num'          => 2,
        //backlog队列长度
        'backlog'                  => 128,
        //启用open_tcp_nodelay
        'open_tcp_nodelay'         => 1,
        // swoole server数据包分发策略
        'dispatch_mode'            => 1,
        //swoole server设置端口重用
        'enable_reuse_port'        => 1,
        //设置心跳检测间隔
        'heartbeat_check_interval' => 60,
        //
        'user'                     => 'xfb_user',
        //用户组
        'group'                   => 'staff',
        //日志
        'log_file' => __DIR__ . DIRECTORY_SEPARATOR . 'run.log'
    ],
    'http'       => [
        'host' => '127.0.0.1',
        'port' => 9000
    ],
    'components' => [
        'eventManager' => [
            'class'  => \SF\Events\EventManager::class,
            'events' => [
                \SF\Events\Server\BufferEmpty::class,
                \SF\Events\Server\BufferFull::class,
                \SF\Events\Server\Close::class,
                \SF\Events\Server\Connect::class,
                \SF\Events\Server\Finish::class,
                \SF\Events\Server\ManagerStart::class,
                \SF\Events\Server\ManagerStop::class,
                \SF\Events\Server\Packet::class,
                \SF\Events\Server\PipeMessage::class,
                \SF\Events\Server\Receive::class,
                \SF\Events\Server\Shutdown::class,
                \SF\Events\Server\Start::class,
                \SF\Events\Server\Task::class,
                \SF\Events\Server\WorkerError::class,
                \SF\Events\Server\WorkerStart::class,
                \SF\Events\Server\WorkerStop::class,
                //http
                \SF\Events\Server\Http\Request::class,
                //WebSocket
//                \SF\Events\Server\Http\WebSocket\HandShake::class,
//                \SF\Events\Server\Http\WebSocket\Message::class,
//                \SF\Events\Server\Http\WebSocket\Open::class
            ],
        ],
        'router'   => [
            'class' => \SF\Http\Routing\Router::class,
            'rules' => include_once ('config/routes.php'),
        ],
        'database' => [
            'class'     => SF\Databases\DatabaseServiceProvider::class,
            'connector' => [
                'class'    => SF\Databases\Mysql\Connector::class,
                'host'     => '127.0.0.1',
                'port'     => 3306,
                'username' => 'root',
                'password' => '123456',
                'database'  => 'demo',
                'timeout'  => 60,
                'charset'  => 'utf8'
            ],
            'pool' => [
                'class' => SF\Pool\DatabaseConnectPool::class,
                'maxConnections' => 30
            ],
            'tables' => include_once ('config/tables.php')
        ],
        'cache' => [
            'class' => \SF\Cache\CacheServiceProvider::class,
            'driver' => [
                'class' => SF\Redis\RedisCache::class,
                'host' => '127.0.0.1',
                'port' => 6379,
                'maxConnections' => 30
            ]
        ]
    ]
];
