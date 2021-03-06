<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use PHP\Classes\Messages\InitialMessagesLoader;

class InitialMessagesLoaderTest extends TestCase {

  private $message_loader;

  protected function setUp():void {
    parent::setUp();
    $this -> message_loader = new InitialMessagesLoader();
  }

  public function testSetUpAndFormatAndSendMessages() {
    $result = $this -> message_loader -> setUp();
    $this -> assertTrue($result);

    $this -> message_loader -> query_result[0]['message_id'] = 3;
    $this -> message_loader -> query_result[0]['username'] = 'janek';
    $this -> message_loader -> query_result[0]['content'] = 'no elo xd';
    $this -> message_loader -> query_result[0]['date'] = '2022-06-14 10:15:08';
    $this -> message_loader -> query_result[1]['message_id'] = 2;
    $this -> message_loader -> query_result[1]['username'] = 'adam';
    $this -> message_loader -> query_result[1]['content'] = 'xd';
    $this -> message_loader -> query_result[1]['date'] = '2022-06-14 10:15:04';
    $this -> message_loader -> query_result[2]['message_id'] = 1;
    $this -> message_loader -> query_result[2]['username'] = 'adam';
    $this -> message_loader -> query_result[2]['content'] = 'siema';
    $this -> message_loader -> query_result[2]['date'] = '2022-06-14 10:15:03';
    $this -> message_loader -> result_len = 3;

    $this -> message_loader -> formatAndSendMessages();

    $this -> assertSame(
      1, $this -> message_loader -> return_data -> oldest_message_id
    );
    $this -> assertSame(
      3, $this -> message_loader -> return_data -> latest_message_id
    );
    $dt1 = new \DateTime();
    $dt1 = $dt1 -> format('U');
    $dt2 = $this -> message_loader -> return_data -> server_time;
    $dt_test = abs($dt1 - $dt2);
    $this -> assertLessThanOrEqual(60, $dt_test);

    $this -> assertSame(
      'janek', $this -> message_loader -> return_data ->
      messages_data[0] -> username
    );
    $this -> assertSame(
      'no elo xd', $this -> message_loader -> return_data ->
      messages_data[0] -> content
    );
    $this -> assertSame(
      '2022-06-14 10:15:08', $this -> message_loader -> return_data ->
      messages_data[0] -> date
    );

    $this -> assertSame(
      'adam', $this -> message_loader -> return_data ->
      messages_data[1] -> username
    );
    $this -> assertSame(
      'xd', $this -> message_loader -> return_data ->
      messages_data[1] -> content
    );
    $this -> assertSame(
      '2022-06-14 10:15:04', $this -> message_loader -> return_data ->
      messages_data[1] -> date
    );

    $this -> assertSame(
      'adam', $this -> message_loader -> return_data ->
      messages_data[2] -> username
    );
    $this -> assertSame(
      'siema', $this -> message_loader -> return_data ->
      messages_data[2] -> content
    );
    $this -> assertSame(
      '2022-06-14 10:15:03', $this -> message_loader -> return_data ->
      messages_data[2] -> date
    );
  }

}