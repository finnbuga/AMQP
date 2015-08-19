<?php
/*
 * Queue test task
 *
 * Problem descrption available at http://impact.ccat.eu
 */

require_once 'Loan.class.php';

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

define('HOST',         'impact.ccat.eu');
define('PORT',          5672);
define('USER',         'myjar');
define('PASS',         'myjar');
define('INPUT_QUEUE',  'interest-queue');
define('OUTPUT_QUEUE', 'solved-interest-queue');
define('TOKEN',        'florin');

$connection = new AMQPConnection(HOST, PORT, USER, PASS);
$channel = $connection->channel();

/**
 * Processes the message sent by the server.
 *
 * It reads loan data from the message,
 * then calculates the loan interest
 * and finally publishes a new message with the extra loan data.
 *
 * @param $input_msg Object containing the message body which contains loan information of the form: {sum: 123, days: 5}
 */
$callback = function($input_msg) {
  $input = json_decode($input_msg->body);
  
  $loan = new Loan($input->sum, $input->days);
  $loan->calculate_interest();

  // Publish a new message with extra loan data.
  $output = array(
    'sum' => $loan->sum,
    'days' => $loan->days,
    'interest' => $loan->interest,
    'totalSum' => $loan->sum + $loan->interest,
    'token' => TOKEN
  );
  $output_msg = new AMQPMessage(json_encode($output));
  global $channel;
  $channel->basic_publish($output_msg, '', OUTPUT_QUEUE);
};

$channel->basic_consume(INPUT_QUEUE, '', false, true, false, false, $callback);

// Wait for new messages from the server
while(count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();
