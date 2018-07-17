<?php
//+-------------------------------------------------------------
//| 
//+-------------------------------------------------------------
//| Author Liu LianSen <liansen@d3zz.com> 
//+-------------------------------------------------------------
//| Date 2018-07-16
//+-------------------------------------------------------------
namespace amqconsumer\test;

use amqconsumer\lib\Consumer;
use PhpAmqpLib\Message\AMQPMessage;
use Workerman\Worker;

class Demo extends Consumer
{

    protected $log = './demo.log';

    public function process(AMQPMessage $message)
    {
        Worker::log("processing\n");
        file_put_contents($this->log,$message->getBody().PHP_EOL,FILE_APPEND);
        return true;
    }

}