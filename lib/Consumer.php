<?php
//+-------------------------------------------------------------
//| 
//+-------------------------------------------------------------
//| Author Liu LianSen <liansen@d3zz.com> 
//+-------------------------------------------------------------
//| Date 2018-07-16
//+-------------------------------------------------------------
namespace amqconsumer\lib;

use PhpAmqpLib\Connection\AMQPStreamConnection;

/**
 * 消息消费者基类
 * @package consumer\lib
 */
abstract class Consumer implements AMQProcessor
{
    /**
     * @see AMQProcessor::run()
     */
    public function run(array $config)
    {
        \Workerman\Worker::log($config['name']." running...\n");
        $_this = $this;
        $conn = new AMQPStreamConnection(  $config['host'],
                                            $config['port'],
                                            $config['user'],
                                            $config['password'],
                                            $config['vhost']);
        $channel = $conn->channel();
        $channel->queue_declare($config['queue'], false, true, false, false);
        $channel->basic_consume($config['queue'], '', false, false, false, false,
            function ($msg) use($_this){
                try{
                    if($_this->process($msg)){
                        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
                    }else{
                        //让消息回到队列
                        $msg->delivery_info['channel']->basic_nack($msg->delivery_info['delivery_tag'],false,true);
                    }
                }catch (\Throwable $e){
                    //让消息回到队列
                    $msg->delivery_info['channel']->basic_nack($msg->delivery_info['delivery_tag'],false,true);
                }
            });
        while (true){
            $channel->wait();
        }
    }
}