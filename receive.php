<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('rabbittest_rabbitmq_1', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('hello', false, true, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";

$callback = function ($msg) {
  echo ' [x] Received ', $msg->body, "\n";
  echo ' [x] Received ', var_dump($msg->body), "\n";
};

$channel->basic_consume(
    'hello',
    '',
    false,
    false,
    false,
    false,
    $callback);

while ($channel->is_open()) {
    $channel->wait();
}