<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('rabbit-mq-test-php_rabbitmq_1', 5672, 'guest', 'guest');
$channel = $connection->channel();

$array = [
    "header" => [
        "retry_number" => 0,
    ],
    "secae"  => 1,
];

$jsonData = json_encode($array);

$message = new AMQPMessage($jsonData, ['content_type' => 'application/json']);

$msg = new AMQPMessage('Hello Worldasdsadsad!');

while(true){
    $channel->basic_publish($message, "saggitarius-a");
}


echo " [x] Sent 'Hello World!'\n";

$channel->close();
$connection->close();