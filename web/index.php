<?php

require_once('./LINEBotTiny.php');



$channelAccessToken = 'ufu230I+sy++55QUixRga1o8fVYazEJwWpt3COlQD8MHqgqG9bFjCGIhaEMOi/787y04DT28xhExqTztjc4Y0fDBr8kIGT8EPQxnbQiI8NVlrDnH+If4Ia49KFOnthpd7BB7o2fI8PbASr3owR5BZQdB04t89/1O/w1cDnyilFU=';
$channelSecret = 'c4720e166942bddbdd6160021cc6363c';

$client = new LINEBotTiny($channelAccessToken, $channelSecret);
foreach ($client->parseEvents() as $event) {
    switch ($event['type']) {
        case 'message':
            $message = $event['message'];
            switch ($message['type']) {
                case 'text':
                	$m_message = $message['text'];
                	if($m_message!="")
                	{
                		$client->replyMessage(array(
                        'replyToken' => $event['replyToken'],
                        'messages' => array(
                            array(
                                'type' => 'text',
                                'text' => $m_message
                            )
                        )
                    	));
                	}
                    break;
                
            }
            break;
        default:
            error_log("Unsupporeted event type: " . $event['type']);
            break;
    }
};

