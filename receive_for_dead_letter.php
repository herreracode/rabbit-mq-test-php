<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

use PhpAmqpLib\Message\AMQPMessage;

$retries = 2;
$connection = new AMQPStreamConnection('rabbit-mq-test-php_rabbitmq_1', 5672, 'guest', 'guest');
$channel = $connection->channel();
$routingKeyDeadLetter = "sales.applicant_management.dead_letter";
$routingKeyRetry = "sales.applicant_management.retry";

echo " [*] Waiting for messages. To exit press CTRL+C\n";

$callback = function ($msg) use ($channel, $retries, $routingKeyRetry, $routingKeyDeadLetter){



    echo ' [x] Received ', $msg->body, "\n";

    echo ' [x] Received ', var_dump($msg->body), "\n";

    $array = json_decode($msg->body, true);

    if($array['secae']) {

        var_dump("se cayo");
        var_dump($array);

        $array['header']['retry_number']++;
        $jsonData = json_encode($array);

        $message = new AMQPMessage($jsonData, ['content_type' => 'application/json']);


        if($array['header']['retry_number'] < $retries)
            $channel->basic_publish($message, "saggitarius-a-retries", $routingKeyRetry);
        else
            $channel->basic_publish($message, "saggitarius-a-retries",$routingKeyDeadLetter);

    }

        $channel->basic_ack($msg->delivery_info['delivery_tag']);


};

$channel->basic_consume(
    'sales.applicant_management',
    '',
    false,
    false,
    false,
    false,
    $callback);

while ($channel->is_open()) {
    $channel->wait();
}