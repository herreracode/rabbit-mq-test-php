<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('rabbit-mq-test-php_rabbitmq_1', 5672, 'guest', 'guest');
$channel = $connection->channel();

$msg = new AMQPMessage('Hello Worldasdsadsad!');
$channel->basic_publish($msg, "saggitarius-a");

echo " [x] Sent 'Hello World!'\n";

$channel->close();
$connection->close();