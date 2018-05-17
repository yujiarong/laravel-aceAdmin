<?php
/*
 * rabbitMQ封装函数
 * exchange_declare
	name: $exchange
	type: fanout
	passive: false //don't check is an exchange with the same name exists
	durable: false //the exchange won't survive server restarts
	auto_delete: true //the exchange will be deleted once the channel is closed.
 *
 * @modify lzx, date 20140610
 */
namespace App;
// define('AMQP_PASSIVE', true);
// define('AMQP_DEBUG', false);
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQ{

    private $ch;
    private $msg;
    private $conn;
    private $system;
    private $consumer_tag;
    private $isRePublish = false;
    private $mid;
    private $callback;


    public function __construct($system = ''){
        if(!empty($system)){
            $this->connection($system);
        }
    }

    public function __destruct(){
        if(is_object($this->ch)){
            $this->ch->close();
        }
        if(is_object($this->conn)){
            $this->conn->close();
        }

    }

    /**
     * 实例化后rabbitMQ连接
     * @param string $system 系统名称
     * @return bool
     * @author lzx
     */
    public function connection($system){;
        if(empty($this->conn)){
            return $this->resetConnection($system);
        }
        if($system != $this->system){
            return $this->resetConnection($system);
        }

        return true;
    }

    /**
     * 强制重置rabbitMQ连接
     * @param string $system 系统名称
     * @return bool
     * @author lzx
     */
    public function resetConnection($system){
        $rmqconfig = \Config::get('sw.RMQ_CONFIG');
        if(isset($rmqconfig[$system])){
            if(is_object($this->ch)){
                $this->ch->close();
            }
            if(is_object($this->conn)){
                $this->conn->close();
            }
            list($host, $user, $password, $port, $vhost) = $rmqconfig[$system];
            $this->conn         = new AMQPConnection($host, $port, $user, $password, $vhost);
            if(!$this->conn->isConnected()){
                return false;
            }
            $this->consumer_tag = getNow().':'.getmypid();
            $this->ch           = $this->conn->channel();
            $this->system       = $system;
            //			$this->ackHandler();
            return true;
        }

        return false;
    }

    /*
     * 设置消息体大小限制
     * @param string|int $bytes 字节数
     * @author xzy
     */
    private function setBodySizeLimit($bytes=0){
        $this->ch->setBodySizeLimit($bytes);
    }
    /*
     * 添加交换器
     * @param string $ename 交换器名称
     * @param string $type 交换器的消息传递方式 可选:'fanout','direct','topic','headers'
     * 'fanout':不处理(忽略)路由键，将消息广播给绑定到该交换机的所有队列
     * 'diect':处理路由键，对路由键进行全文匹配。对于路由键为"xzy_rain"的消息只会分发给路由键绑定为"xzy_rain"的队列,不会分发给路由键绑定为"xzy_music"的队列
     * 'topic':处理路由键，按模式匹配路由键。模式符号 "#" 表示一个或多个单词，"*" 仅匹配一个单词。如 "xzy.#" 可匹配 "xzy.rain.music"，但 "xzy.*" 只匹配 "xzy.rain"和"xzy.music"。只能用"."进行连接，键长度不超过255字节
     * @param boolean $durable 是否持久化
     * @param boolean $auto_delete 当所有绑定队列都不再使用时，是否自动删除该交换机
     * @author xzy
     */
    public function addExchange($ename, $type = 'fanout', $durable = true, $auto_delete = false){
        $this->ch->exchange_declare($ename, $type, false, $durable, $auto_delete);
    }

    /*
     * 添加队列
     * @param string $qname 队列名称
     * @param boolean $durable 是否持久化
     * @param boolean $exclusive 仅创建者可以使用的私有队列，断开后自动删除
     * @param boolean $auto_delete 当所有消费客户端连接断开后，是否自动删除队列
     * return int 该队列的ready消息数量
     * @author xzy
     */
    public function addQueue($qname, $durable = true, $exclusive = false, $auto_delete = false){
        $this->ch->queue_declare($qname, false, $durable, $exclusive, $auto_delete);
    }

    /*
     * 绑定队列和交换器
     * @param string $qname 队列名称
     * @param string $ename 交换器名称
     * @param string $routing_key 路由键 注:在fanout的交换器中路由键会被忽略
     * @author xzy
     */
    public function bind($qname, $ename, $routing_key = ''){
        $this->ch->queue_bind($qname, $ename, $routing_key);
    }

    /*
     * 设置消费者预取消息数量
     * @param string|int $count 预取消息数量
     * @author xzy
     */
    public function setQos($count = 1){
        $this->ch->basic_qos(null, $count, null);
    }

    /**
     * 基础模型之消息发布
     * @param string $exchange 交换器名称
     * @param string|array $msg 发布内容
     * @param string $mqtype 发布消息的类型
     * @return bool
     * @author lzx
     */
    public function basicPublish($exchange, $msg){
        $tosend = new AMQPMessage(is_array($msg) ? json_encode($msg) : $msg, array('content_type'  => 'text/plain',
                                                                                   'delivery_mode' => 2,
        ));
        try{
            if(empty($this->ch)){
                die('error: mq ch is empty() exchagne:'.$exchange.' file:lib/extend/rabbitmq/rabbitmq_extend.php');
            }
            $this->ch->basic_publish($tosend, $exchange);
        }catch(Exception $e){
            $tmp = 'error: $exchange:'.$exchange;
            $tmp .= '  file: file:lib/extend/rabbitmq/rabbitmq_extend.php';
            $tmp .= $e->getMessage();
            die($tmp);
        }

    }

    /**
     * 基础模型之消息接受
     * @param string $exchange
     * @param string $queue
     * @param array $callback
     * @param string $mqtype
     * @return string
     * @author lzx
     */

    public function basicReceive($queue, $callback = array('RabbitMQ', 'process_message')){
        $this->ch->basic_consume($queue, $this->consumer_tag, false, false, false, false, $callback);
//         while(count($this->ch->callbacks)){
//             $this->ch->wait();
//         }
        while (count($this->ch->callbacks)) {
        	$read   = array($this->conn->getSocket()); // add here other sockets that you need to attend
        	$write  = null;
        	$except = null;
        	if (false == ($num_changed_streams = stream_select($read, $write, $except, 60))) {
        		echo "\n##################\n";
        		echo "接收队列有问题";
        		echo "\n##################\n";
        		exit;
        		/* Error handling */
        	} elseif ($num_changed_streams > 0) {
        		$this->ch->wait();
        	}
        }
        
    }

    /**
     * Pub/Sub 之消息发布
     * @param string $exchange 交换器名称
     * @param string|array $msg 发布内容
     * @param string $mqtype 发布消息的类型
     * @return bool
     * @author lzx
     */

    public function queuePublish($exchange, $msg){
        $this->ackHandler();
        $tosend = new AMQPMessage(json_encode($msg), array('content_type' => 'text/plain', 'delivery_mode' => 2));
        $this->ch->basic_publish($tosend, $exchange);
        $this->waitAck();
    }


    /**
     * Pub/Sub 之消息接受
     * @param string $exchange 交换器名称
     * @param string $queue 队列名称
     * @param string $callback 注册回调函数
     * @param string|array $msg 发布内容
     * @param string $mqtype 发布消息的类型
     * @return bool
     * @author lzx
     */
    public function queueSubscribe($queue, $callback = array('RabbitMQ', 'process_message')){
        $this->ch->basic_consume($queue, $this->consumer_tag, false, false, false, false, $callback);
        while(count($this->ch->callbacks)){
            $this->ch->wait();
        }
    }

    /**
     * 根据主题进行消息推送
     * @param string $exchange 交换器名称
     * @param string|array $msg 发布内容
     * @param string $routing_key 路由键 注:在fanout的交换器中路由键会被忽略
     * @return bool
     * @author xzy
     */
    //“Delivery Mode”（投递模式）,　　1为非持久化,2为持久化.
    public function topicPublish($exchange, $msg, $routing_key=''){
        $msg = is_array($msg) ? json_encode($msg) : $msg;
        $tosend = new AMQPMessage($msg, array('content_type' => 'text/plain', 'delivery_mode' => 2));
        $this->ch->basic_publish($tosend, $exchange, $routing_key);
        $this->waitAck();
    }

    public function topicBatchPublish($exchange,$msg,$routing_key=''){
        if(!is_array($msg))return false;
        foreach ($msg as  $v) {
            if(is_array($v))$v = json_encode($v);
            $tosend = new AMQPMessage($v, array('content_type' => 'text/plain', 'delivery_mode' => 2));
            $this->ch->batch_basic_publish($tosend,$exchange,$routing_key);
        }
        $this->ch->publish_batch();
    }



    /**
     * 根据主题进行消息消费
     * @param string $queue 队列名称
     * @param string $callback 注册回调函数
     * @param boolean $no_ack 是否不需要发送ACK
     * @return bool
     * @author xzy
     */
    public function topicSubscribe($queue, $callback, $no_ack = false){
        $this->callback = $callback;
        $this->ch->basic_consume($queue, $this->consumer_tag, false, $no_ack, false, false, [$this,'process_message']);
//         while(count($this->ch->callbacks)){
//             echo 'waiting for msg...'.PHP_EOL;
//             $this->ch->wait();
//         }
        while (count($this->ch->callbacks)) {
        	$read   = array($this->conn->getSocket()); // add here other sockets that you need to attend
        	$write  = null;
        	$except = null;
        	if (false == ($num_changed_streams = stream_select($read, $write, $except, 60))) {
        		echo "\n##################\n";
        		echo "接收队列有问题";
        		echo "\n##################\n";
        		exit;
        		/* Error handling */
        	} elseif ($num_changed_streams > 0) {
        		$this->ch->wait();
        	}
        }
    }

    /**
     * Pub/Sub 之批量消息接受，默认接受200条数据
     * @param string $exchange 交换器名称
     * @param string $queue 队列名称
     * @param int $limit 返回条数
     * @param bool $extral 返回数据类型， true为json_decode， false为json
     * @return array
     * @author lzx
     */
    public function queueSubscribeLimit($exchange, $queue, $limit = 200, $extral = true, $mqtype = 'fanout'){

        $messageCount = $this->ch->queue_declare($queue, false, true, false, false);
        $this->ch->queue_bind($queue, $exchange);
        $i        = 0;
        $max      = $limit < 200 ? $limit : 200;
        $orderids = array();
        while($i < $messageCount[1] && $i < $max){
            $this->msg = $this->ch->basic_get($queue);
            $this->ch->basic_ack($this->msg->delivery_info['delivery_tag']);
            if($extral === false){
                array_push($orderids, $this->msg->body);
            }else{
                array_push($orderids, json_decode($this->msg->body, true));
            }
            $i++;
        }

        return $orderids;
    }

    /**
     * 重推消息
     * @param string|int $mid 重推消息id
     * @param string $exchange 交换器名称
     * @param string|array $msg 发布内容
     * @param string $routing_key 路由键 注:在fanout的交换器中路由键会被忽略
     * @return bool
     * @author xzy
     */

    public function rePublish($mid,$exchange, $msg, $routing_key=''){
        $this->isRePublish = true;
        $this->mid = $mid;
        $msg = is_array($msg) ? json_encode($msg) : $msg;
        $tosend = new AMQPMessage($msg, array('content_type' => 'text/plain', 'delivery_mode' => 2));
        $this->ch->basic_publish($tosend, $exchange, $routing_key);
        $this->waitAck();
        $this->isRePublish = false;//为了防止之后调用其他推送方法出现异常
    }

    /**
     * 销毁队列中的数据
     * @param $msg_obj
     * @return bool
     * @author lzx
     */
    public function basicAck($msg_obj){
        $this->ch->basic_ack($msg_obj->delivery_info['delivery_tag']);
    }


    /**
     * 推送回调处理
     * @author xzy
     */
    public function ackHandler(){
        $this->ch->set_ack_handler(function (AMQPMessage $message){
            if($this->isRePublish){
                $funcInfo = ['id'=>$this->mid,'status'=>1];
                $this->updatePublishInfo($funcInfo);
                $this->addTryCount($funcInfo);
            }
        });

        $this->ch->set_nack_handler(function (AMQPMessage $message){
            if($this->isRePublish){
                $funcInfo = ['id'=>$this->mid];
                $this->addTryCount($funcInfo);
            }else{
                $funcInfo = $this->getPublishInfo();
                $this->savePublishInfo($funcInfo);
            }
        });
        $this->ch->confirm_select();
    }

    public function waitAck(){
        $this->ch->wait_for_pending_acks();
    }


    /**
     * 默认回调函数
     * @param object $msg_obj
     * @return bool
     * @author xzy
     */
    public function process_message($msg_obj){
        try{
            $rerult = call_user_func($this->callback,$msg_obj);

        }catch (Exception $e){
            echo 'fk!!!!!!!!';
            echo $e->getMessage();
        }
        if(($rerult['ack'])){
            $this->basicAck($msg_obj);
        }
    }

    /**
     * 关闭消费者
     * @param $msg_obj
     * @return array
     * @author xzy
     */
    public function cancelConsumer($msg_obj){
        $msg_obj->delivery_info['channel']->basic_cancel($msg_obj->delivery_info['consumer_tag']);
    }


    /**
     * 获取消息推送失败时的快照信息，以便之后再次推送
     * @return array
     * @author xzy
     */
    public function getPublishInfo(){

        $backtraceList = debug_backtrace();//TODO 个人觉得还有其他方法,这个也不错
        $funcInfo  = [];
        foreach($backtraceList as $bt){
            $function = $bt['function'];
            $args     = $bt['args'];
            if(substr($function, -7) == 'Publish'){
                $funcInfo['function'] = $function;
                $funcInfo['exchange'] = $args[0];
                $funcInfo['msg']      = is_array($args[1]) ? json_encode($args[1]) : $args[1];
                $funcInfo['routeKey'] = $args[2];
                $funcInfo['system']   = $this->system;
                break;
            }
        }

        return $funcInfo;
    }

    public function savePublishInfo($publishInfo){
        return A('MqPublishFail')->insert($publishInfo);
    }

    public function updatePublishInfo($publishInfo){
        return A('MqPublishFail')->update($publishInfo);
    }

    public function addTryCount($publishInfo){
        return A('MqPublishFail')->addTryCount($publishInfo);
    }
}
