框架说明
-------
本程序基于php-amqplib/php-amqplib库和Workerman框架的一个简单的rabbitMQ的消费者框架。

目的是为php项目整合RabbitMQ提供一个快速的方法。

````安装方法````
------------
~~~
推荐使用composer
composer require liuliansen/amq-consumer 

也可以通过 github 克隆安装
git clone https://github.com/liuliansen/amq-consumer.git consumer 

~~~

``其他``
---------
消息发送：本包提供了一个基本的消息发送器 lib/Publisher.php;

消息消费：    
    消费者是以进程形式运行的，启动方式参见 test/run.php    
    核心是 lib\Worker::run方法 ，通过传入配置(参见test/config.php)
    即可启动消费者进程(消费者进程数量，以及消费服务可以是多个)
    
    
          
