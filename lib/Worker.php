<?php
//+-------------------------------------------------------------
//| 
//+-------------------------------------------------------------
//| Author Liu LianSen <liansen@d3zz.com> 
//+-------------------------------------------------------------
//| Date 2018-07-16
//+-------------------------------------------------------------
namespace amqconsumer\lib;

class Worker
{

    /**
     * 启动所有服务
     * @param array $config
     */
    public static function run(array $config)
    {
        foreach ($config['task'] as $task){
            $worker = new \Workerman\Worker();
            $worker->count = $task['processNum'];
            $worker->name  = $task['name'];
            $worker->onWorkerStart = function(\Workerman\Worker $worker) use ($task){
                \Workerman\Worker::log("{$task['name']} starting...\n");
                /**
                 * @var AMQProcessor
                 */
                $serv = new $task['class'];
                if(!($serv instanceof AMQProcessor)){
                    \Workerman\Worker::log('消息消费者'.$task['class'].'不是AMQProcessor的实例');
                    return;
                }
                $serv->run($task);
            };
        }
        \Workerman\Worker::runAll();
    }

}