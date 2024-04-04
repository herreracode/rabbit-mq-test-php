<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$exchangeName = "saggitarius-a";
$exchangeTopic = "saggitarius-a-retries";

$queues = [
    'sales.applicant_management',
    'financial.online_payments',
];


$connection = new AMQPStreamConnection('rabbit-mq-test-php_rabbitmq_1', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->exchange_declare($exchangeName,'fanout',false, true);
$channel->exchange_declare($exchangeTopic,'topic',false, true);

foreach($queues as $queue){

    $channel->queue_declare($queue, false, true,false,false);
    $channel->queue_bind($queue, $exchangeName);

}

foreach($queues as $queue){

    $channel->queue_declare($queue . ".retry" , false, true, false, false, false, [
        'x-message-ttl' => ['I', 6000],
        'x-dead-letter-exchange' => ['S', $exchangeName],
    ]);

    $channel->queue_bind($queue . ".retry", $exchangeTopic, $queue . ".retry");

}

foreach($queues as $queue){

    $channel->queue_declare($queue . ".dead_letter" , false, true,false,false);

    $channel->queue_bind($queue . ".dead_letter", $exchangeTopic, $queue . ".dead_letter");

}
