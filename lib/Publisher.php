<?php
//+-------------------------------------------------------------
//| 
//+-------------------------------------------------------------
//| Author Liu LianSen <liansen@d3zz.com> 
//+-------------------------------------------------------------
//| Date 2017-11-20
//+-------------------------------------------------------------
namespace amqconsumer\lib;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use utils\Logger;


class Publisher
{

    /**
     * 连接信息
     * @var array
     */
   protected $config = [
        'host' => '',
        'port' => 0,
        'user' => '',
        'password' => '',
        'vhost' => '',
        'log_path' => ''
    ];

    /**
     * @var AMQPStreamConnection
     */
   protected $conn = null;

    /**
     * @var Logger
     */
   protected $logger = null;

   public function __construct(array $config)
   {
        $this->config = array_merge($this->config,$config);
        $this->connect();
        $this->logger = new Logger(['path' => $this->config['log_path']]);
   }

    /**
     * 连接队列
     * @return AMQPStreamConnection
     * @throws \Exception
     */
   protected function connect()
   {
       if($this->conn) return ;
        $config = $this->config;
        $conn = new AMQPStreamConnection(  $config['host'] ,
                                            $config['port'],
                                            $config['user'],
                                            $config['password'],
                                            $config['vhost']);
        if(!$conn){
            throw new \Exception('消息队列连接失败');
        }
        $this->conn = $conn;
   }


    /**
     * 发布消息
     * @param string $exchange
     * @param string $msg
     * @param string $type    发布方式，topic,fanout
     * @param array $properties
     * @return bool
     */
    public function basicPublish($exchange,$msg,$type, $properties = [])
    {
        try {
            $channel = $this->conn->channel();
            $channel->exchange_declare($exchange, $type, false, true, false);
            $properties = array_merge($properties, [
                'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
            ]);
            $msg = new AMQPMessage($msg,$properties);
            $channel->basic_publish($msg, $exchange);
            $channel->close();
            return true;
        }catch (\Exception $e){
           $this->logger->error($e->getMessage().PHP_EOL.$e->getTraceAsString());
        }
        return false;
    }




}