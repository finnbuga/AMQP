<?php
/*
 * Helper script for testing
 * Sends to an AMQP queue.
 */

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

define('HOST',         'impact.ccat.eu');
define('PORT',          5672);
define('USER',         'myjar');
define('PASS',         'myjar');
define('OUTPUT_QUEUE', 'solved-interest-queue');
define('TOKEN',        'florin');


$connection = new AMQPConnection(HOST, PORT, USER, PASS);
$output_channel = $connection->channel();

$msg = new AMQPMessage('{"sum":0,"days":0,"interest":0,"totalSum":3333,"token":"test"}');
$output_channel->basic_publish($msg, '', OUTPUT_QUEUE);

$output_channel->close();
$connection->close();
