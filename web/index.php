<?php

require_once('./LINEBotTiny.php');
require('../vendor/autoload.php');


$channelAccessToken = 'ufu230I+sy++55QUixRga1o8fVYazEJwWpt3COlQD8MHqgqG9bFjCGIhaEMOi/787y04DT28xhExqTztjc4Y0fDBr8kIGT8EPQxnbQiI8NVlrDnH+If4Ia49KFOnthpd7BB7o2fI8PbASr3owR5BZQdB04t89/1O/w1cDnyilFU=';
$channelSecret = 'c4720e166942bddbdd6160021cc6363c';

$client = new LINEBotTiny($channelAccessToken, $channelSecret);
$client = new LINEBotTiny($channelAccessToken, $channelSecret);
foreach ($client->parseEvents() as $event) {
    switch ($event['type']) {
        case 'message':
            $message = $event['message'];

            $json = file_get_contents('https://spreadsheets.google.com/feeds/list/1jK7Ol4WNnaLtjcJNgDAm5Apkty2eOb_g9C1o98KTI8I/od6/public/values?alt=json');
            $data = json_decode($json, true);
            $result = array();

            foreach ($data['feed']['entry'] as $item) {
                $keywords = explode(',', $item['gsx$keyword']['$t']);

                foreach ($keywords as $keyword) {
                    if (mb_strpos($message['text'], $keyword) !== false) {
                        $candidate = array(
                            'thumbnailImageUrl' => $item['gsx$photourl']['$t'],
                            'title' => $item['gsx$title']['$t'],
                            'text' => $item['gsx$title']['$t'],
                            'actions' => array(
                                array(
                                    'type' => 'uri',
                                    'label' => '查看詳情',
                                    'uri' => $item['gsx$url']['$t'],
                                    ),
                                ),
                            );
                        array_push($result, $candidate);
                    }
                }
            }

            switch ($message['type']) {
                case 'text':
                    $client->replyMessage(array(
                        'replyToken' => $event['replyToken'],
                        'messages' => array(
                            array(
                                'type' => 'text',
                                'text' => $message['text'].'美食這個您就問對人了，馬上為您提供…',
                            ),
                            array(
                                'type' => 'template',
                                'altText' => '為您推薦的精選美食如下：',
                                'template' => array(
                                    'type' => 'carousel',
                                    'columns' => $result,
                                ),
                            ),
                            array(
                                'type' => 'text',
                                'text' => '建議這些都給他來個一份!!！',
                            ),
                            array(
                                'type' => 'sticker',
                                'packageId' => '1',
                                'stickerId' => '2',
                            ),
                        ),
                    ));
                    break;
                default:
                    error_log("Unsupporeted message type: " . $message['type']);
                    break;
            }
            break;
        default:
            error_log("Unsupporeted event type: " . $event['type']);
            break;
    }
};

/*
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
*/

