<?php
//+-------------------------------------------------------------
//| 
//+-------------------------------------------------------------
//| Author Liu LianSen <liansen@d3zz.com> 
//+-------------------------------------------------------------
//| Date 2018-07-16
//+-------------------------------------------------------------
namespace amqconsumer\lib;

use PhpAmqpLib\Message\AMQPMessage;

interface AMQProcessor
{
    /**
     * 启动运行
     * @param array $config  消息队列配置信息
     * @return bool true表示启动成功，否则启动失败
     */
    public function run(array $config);


    /**
     * 消息处理方法
     * @param AMQPMessage $message
     * @return bool true表示消息已被正确消费，否则消息将会被重新放回队列中
     */
    public function process(AMQPMessage $message);


}