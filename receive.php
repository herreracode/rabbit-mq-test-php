<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('rabbit-mq-test-php_rabbitmq_1', 5672, 'guest', 'guest');
$channel = $connection->channel();

echo " [*] Waiting for messages. To exit press CTRL+C\n";

$callback = function ($msg) use ($channel){



    echo ' [x] Received ', $msg->body, "\n";

    echo ' [x] Received ', var_dump($msg->body), "\n";

    $array = json_decode($msg->body, true);

    if($array['secae']) {

        $array['secae'] = 0;

        var_dump("se cayo");
        var_dump($array);

        $jsonData = json_encode($array);

        $message = new AMQPMessage($jsonData, ['content_type' => 'application/json']);

        $channel->basic_publish($message, "saggitarius-a-retry","financial.online_payments.retry");

    }

        $channel->basic_ack($msg->delivery_info['delivery_tag']);


};

$channel->basic_consume(
    'financial.online_payments',
    '',
    false,
    false,
    false,
    false,
    $callback);

while ($channel->is_open()) {
    $channel->wait();
}