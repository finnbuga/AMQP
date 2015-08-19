<?php
/*
 * Receives from an AMQP queue and prints to the screen.
 */

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPConnection;


define('HOST',         'impact.ccat.eu');
define('PORT',          5672);
define('USER',         'myjar');
define('PASS',         'myjar');
define('INPUT_QUEUE',  'solved-interest-queue');



$connection = new AMQPConnection(HOST, PORT, USER, PASS);
$input_channel = $connection->channel();

$callback = function($input_msg) {
  echo $input_msg->body, "\n";
};

$input_channel->basic_consume(INPUT_QUEUE, '', false, true, false, false, $callback);

while(count($input_channel->callbacks)) {
    $input_channel->wait();
}

$input_channel->close();
$connection->close();
