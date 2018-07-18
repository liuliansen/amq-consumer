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
use PhpAmqpLib\Message\AMQPMessage;
use utils\Logger;

/**
 * 消息消费者基类
 * @package consumer\lib
 */
abstract class Consumer implements AMQProcessor
{
    /**
     * 日志记录路径
     * @var string
     */
    protected $logPath = '';
    /**
     * @var Logger
     */
    protected $logger = null;

    public function __construct()
    {
        $this->logger = new Logger(['path' => $this->logPath]);
    }

    /**
     * 记录错误
     * @param string|\Throwable $error
     * @param AMQPMessage $message
     */
    public function error($error,AMQPMessage $message)
    {
        if($error instanceof \Throwable){
            $error = $error->getMessage().PHP_EOL.'message body: '.$message->getBody().PHP_EOL.
                $error->getTraceAsString();
        }else{
            $error = $error.PHP_EOL.'message body: '.$message->getBody().PHP_EOL;
        }
        $this->logger->error($error);
    }

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