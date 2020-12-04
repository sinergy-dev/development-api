<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Message;
use Google\Auth\CredentialsLoader;

class TestController extends Controller
{
    //

    public function postGuzzleRequest()
	{ 

		$link = json_decode($this->postGuzzleRequestTest(),true);

		$weblink = $link["webLink"];

		return $weblink;


	 //    $client = new Client();
	 //    $url = "https://webexapis.com/v1/meetings";

	 //    $response =  $client->request('POST', $url, [
		// 	'headers' => [
		// 		'Content-Type'     => 'application/json',
		// 		'Authorization'      => 'Bearer NDE3ZThiOWUtYTY3MC00ZjMyLTkzODYtM2MyYjRiYTlmNzUxNzAzMDBlOTUtZGRm_P0A1_0a3c49be-fce4-4450-8609-1ef1499b8df4' 
		// 	],'json' => [
		// 		  "title" => "Meeting",
		// 		  "agenda" => "Meeting propose agenda",
		// 		  "hostDisplayName" => "Moderator",
		// 		  "hostEmail" => "moderator@sinergy.co.id",
		// 		  "password" => "1922",
		// 		  "start" => "2020-08-12 14:00:00",
		// 		  "end" => "2020-08-12 15:00:00",
		// 		  "enabledAutoRecordMeeting" => true,
		// 		  "allowAnyUserToBeCoHost" => true
				
		// 	]
		// ]);



		
	   
	    // $myBody['name'] = "Demo";
	    // $request = $client->post($url,  ['body'=>$myBody]);
	    // $response = $request->send();
	  
	    // dd($response);

	 //    $data = '{
		//     "items": [
		//         {
		//             "id": "f1c80ee449d1477a8a423929627b6828",
		//             "meetingNumber": "1700393325",
		//             "title": "Meeting propose",
		//             "agenda": "Meeting propose agenda",
		//             "password": "1922",
		//             "meetingType": "meetingSeries",
		//             "state": "active",
		//             "timezone": "UTC",
		//             "start": "2020-08-11T14:00:00Z",
		//             "end": "2020-08-11T15:00:00Z",
		//             "hostUserId": "Y2lzY29zcGFyazovL3VzL1BFT1BMRS8wZDc1MDUyNi05MjkxLTQ4ODgtYWMxOS1lZGNhNGU5NzVhM2I",
		//             "hostDisplayName": "ladinar ladinar",
		//             "hostEmail": "ladinar@sinergy.co.id",
		//             "hostKey": "737911",
		//             "webLink": "https://sinergyinformasipratama.webex.com/sinergyinformasipratama/j.php?MTID=m229c7a4fc61411f54b72057d709cfb11",
		//             "sipAddress": "1700393325@sinergyinformasipratama.webex.com",
		//             "dialInIpAddress": "210.4.202.4",
		//             "enabledAutoRecordMeeting": true,
		//             "allowAnyUserToBeCoHost": true,
		//             "telephony": {
		//                 "accessCode": "1700393325",
		//                 "callInNumbers": []
		//             }
		//         },
		//         {
		//             "id": "4787d200a6854d82a8c5d13499f84713",
		//             "meetingNumber": "1702633255",
		//             "title": "Meeting propose",
		//             "agenda": "Meeting propose agenda",
		//             "password": "1922",
		//             "meetingType": "meetingSeries",
		//             "state": "inProgress",
		//             "timezone": "UTC",
		//             "start": "2020-08-11T14:00:00Z",
		//             "end": "2020-08-11T15:00:00Z",
		//             "hostUserId": "Y2lzY29zcGFyazovL3VzL1BFT1BMRS8wZDc1MDUyNi05MjkxLTQ4ODgtYWMxOS1lZGNhNGU5NzVhM2I",
		//             "hostDisplayName": "ladinar ladinar",
		//             "hostEmail": "ladinar@sinergy.co.id",
		//             "hostKey": "690327",
		//             "webLink": "https://sinergyinformasipratama.webex.com/sinergyinformasipratama/j.php?MTID=me005b1e6fbf4384678734711cbd7e207",
		//             "sipAddress": "1702633255@sinergyinformasipratama.webex.com",
		//             "dialInIpAddress": "210.4.202.4",
		//             "enabledAutoRecordMeeting": true,
		//             "allowAnyUserToBeCoHost": true,
		//             "telephony": {
		//                 "accessCode": "1702633255",
		//                 "callInNumbers": []
		//             }
		//         },
		//         {
		//             "id": "19051cd482c84b498dfa9d0ccbd3cfff",
		//             "meetingNumber": "1703190789",
		//             "title": "Meeting second",
		//             "agenda": "Meeting propose agenda",
		//             "password": "1922",
		//             "meetingType": "meetingSeries",
		//             "state": "active",
		//             "timezone": "UTC",
		//             "start": "2020-08-11T14:00:00Z",
		//             "end": "2020-08-11T15:00:00Z",
		//             "hostUserId": "Y2lzY29zcGFyazovL3VzL1BFT1BMRS8wZDc1MDUyNi05MjkxLTQ4ODgtYWMxOS1lZGNhNGU5NzVhM2I",
		//             "hostDisplayName": "ladinar ladinar",
		//             "hostEmail": "ladinar@sinergy.co.id",
		//             "hostKey": "109004",
		//             "webLink": "https://sinergyinformasipratama.webex.com/sinergyinformasipratama/j.php?MTID=m51df12cf54fc79cb9ce6adbe9143ee77",
		//             "sipAddress": "1703190789@sinergyinformasipratama.webex.com",
		//             "dialInIpAddress": "210.4.202.4",
		//             "enabledAutoRecordMeeting": true,
		//             "allowAnyUserToBeCoHost": true,
		//             "telephony": {
		//                 "accessCode": "1703190789",
		//                 "callInNumbers": []
		//             }
		//         },
		//         {
		//             "id": "070b0eaa3f7c4c0fb43171dcc54b04f6",
		//             "meetingNumber": "1700355884",
		//             "title": "Meeting Requirement Propose",
		//             "agenda": "Meeting propose agenda",
		//             "password": "1922",
		//             "meetingType": "meetingSeries",
		//             "state": "active",
		//             "timezone": "UTC",
		//             "start": "2020-08-11T14:00:00Z",
		//             "end": "2020-08-11T15:00:00Z",
		//             "hostUserId": "Y2lzY29zcGFyazovL3VzL1BFT1BMRS8wZDc1MDUyNi05MjkxLTQ4ODgtYWMxOS1lZGNhNGU5NzVhM2I",
		//             "hostDisplayName": "ladinar ladinar",
		//             "hostEmail": "ladinar@sinergy.co.id",
		//             "hostKey": "139496",
		//             "webLink": "https://sinergyinformasipratama.webex.com/sinergyinformasipratama/j.php?MTID=m071f52b389f2d89c044f79fef8c277e2",
		//             "sipAddress": "1700355884@sinergyinformasipratama.webex.com",
		//             "dialInIpAddress": "210.4.202.4",
		//             "enabledAutoRecordMeeting": true,
		//             "allowAnyUserToBeCoHost": true,
		//             "telephony": {
		//                 "accessCode": "1700355884",
		//                 "callInNumbers": []
		//             }
		//         },
		//         {
		//             "id": "9cde1b2965d647829a868aabf434c463",
		//             "meetingNumber": "1706663427",
		//             "title": "Meeting Requirement",
		//             "agenda": "Meeting propose agenda",
		//             "password": "1922",
		//             "meetingType": "meetingSeries",
		//             "state": "active",
		//             "timezone": "UTC",
		//             "start": "2020-08-11T14:00:00Z",
		//             "end": "2020-08-11T15:00:00Z",
		//             "hostUserId": "Y2lzY29zcGFyazovL3VzL1BFT1BMRS8wZDc1MDUyNi05MjkxLTQ4ODgtYWMxOS1lZGNhNGU5NzVhM2I",
		//             "hostDisplayName": "ladinar ladinar",
		//             "hostEmail": "ladinar@sinergy.co.id",
		//             "hostKey": "149032",
		//             "webLink": "https://sinergyinformasipratama.webex.com/sinergyinformasipratama/j.php?MTID=m4889267d90692a55dae1471c1fd57b52",
		//             "sipAddress": "1706663427@sinergyinformasipratama.webex.com",
		//             "dialInIpAddress": "210.4.202.4",
		//             "enabledAutoRecordMeeting": true,
		//             "allowAnyUserToBeCoHost": true,
		//             "telephony": {
		//                 "accessCode": "1706663427",
		//                 "callInNumbers": []
		//             }
		//         },
		//         {
		//             "id": "c400bd4db23a4dc09fbb338706edb5e0",
		//             "meetingNumber": "1703360731",
		//             "title": "Meeting Requirement Propose",
		//             "agenda": "Meeting propose agenda",
		//             "password": "1922",
		//             "meetingType": "meetingSeries",
		//             "state": "active",
		//             "timezone": "UTC",
		//             "start": "2020-08-11T14:00:00Z",
		//             "end": "2020-08-11T15:00:00Z",
		//             "hostUserId": "Y2lzY29zcGFyazovL3VzL1BFT1BMRS8wZDc1MDUyNi05MjkxLTQ4ODgtYWMxOS1lZGNhNGU5NzVhM2I",
		//             "hostDisplayName": "ladinar ladinar",
		//             "hostEmail": "ladinar@sinergy.co.id",
		//             "hostKey": "142083",
		//             "webLink": "https://sinergyinformasipratama.webex.com/sinergyinformasipratama/j.php?MTID=me2e97bac7d9101d69c5c3610b20cebd3",
		//             "sipAddress": "1703360731@sinergyinformasipratama.webex.com",
		//             "dialInIpAddress": "210.4.202.4",
		//             "enabledAutoRecordMeeting": true,
		//             "allowAnyUserToBeCoHost": true,
		//             "telephony": {
		//                 "accessCode": "1703360731",
		//                 "callInNumbers": []
		//             }
		//         },
		//         {
		//             "id": "f3ac3b946b834e1dbd800ef681c058a1",
		//             "meetingNumber": "1701143931",
		//             "title": "Meeting",
		//             "agenda": "Meeting propose agenda",
		//             "password": "1922",
		//             "meetingType": "meetingSeries",
		//             "state": "active",
		//             "timezone": "UTC",
		//             "start": "2020-08-12T14:00:00Z",
		//             "end": "2020-08-12T15:00:00Z",
		//             "hostUserId": "Y2lzY29zcGFyazovL3VzL1BFT1BMRS8wZDc1MDUyNi05MjkxLTQ4ODgtYWMxOS1lZGNhNGU5NzVhM2I",
		//             "hostDisplayName": "ladinar ladinar",
		//             "hostEmail": "ladinar@sinergy.co.id",
		//             "hostKey": "838837",
		//             "webLink": "https://sinergyinformasipratama.webex.com/sinergyinformasipratama/j.php?MTID=m9d07575837580b33059ea0e743537549",
		//             "sipAddress": "1701143931@sinergyinformasipratama.webex.com",
		//             "dialInIpAddress": "210.4.202.4",
		//             "enabledAutoRecordMeeting": true,
		//             "allowAnyUserToBeCoHost": true,
		//             "telephony": {
		//                 "accessCode": "1701143931",
		//                 "callInNumbers": []
		//             }
		//         }
		//     ]
		// }';

		// $data = json_decode($data,true)['items'];

		// return $data[sizeof($data)-1];
	}

	public function postGuzzleRequestTest(){
    	$client = new Client();
	    $url = "https://webexapis.com/v1/meetings";

	    $response =  $client->request('POST', $url, [
			'headers' => [
				'Content-Type'     => 'application/json',
				'Authorization'      => 'Bearer NDE3ZThiOWUtYTY3MC00ZjMyLTkzODYtM2MyYjRiYTlmNzUxNzAzMDBlOTUtZGRm_P0A1_0a3c49be-fce4-4450-8609-1ef1499b8df4' 
			],'json' => [
				  "title" => "Meeting",
				  "agenda" => "Meeting propose agenda",
				  "hostDisplayName" => "Moderator",
				  "hostEmail" => "moderator@sinergy.co.id",
				  "password" => "1922",
				  "start" => "2020-08-12 14:00:00",
				  "end" => "2020-08-12 15:00:00",
				  "enabledAutoRecordMeeting" => true,
				  "allowAnyUserToBeCoHost" => true
				
			]
		]);

		return $response->getBody();
    }

    public function tokenTest(){
    	echo "<pre>";
		$scope = 'https://www.googleapis.com/auth/firebase.messaging';
		$credentials = CredentialsLoader::makeCredentials($scope, json_decode(file_get_contents(__DIR__ . '/eod-dev-firebase-adminsdk.json'), true));

		try {
			$url = env('FIREBASE_FCM_URL');
			$client = new Client();
			$response = $client->request('POST', $url, [
				'headers' => [
					'Content-Type'     => 'application/json',
					'Authorization'      => 'Bearer ' . $credentials->fetchAuthToken()['access_token']
				],'json' => [
					"message" => [
						"token" => "c8eTAauXRLKDbrunlGuZOz:APA91bG-jgN25tLapcTZeBtHxG0y_femGvT_qZwhS52rS6Av1cvnpm0ekwsErMJ0OK1IDdklZ8ZmkVkOJyyCZLa5fvXHgbEOZxKJyoAV1vsoZI0AxaQm4dOKKR30TPirJunZY97cY7",
						"data" => [
							"id_user" => "1",
							"fild2" => "asdfasdfasdfasdfasd",
						],
						"notification" => [
							"body" => "This is some test for notification to the Android and IOS Mobile",
							"title" => "Dinar testing",
						]

					]
				]
			]);
		} catch(RequestException $e){
			$error['error'] = $e->getMessage();
			print_r($error);
		}

    	echo "asdfadfa<br>";
		echo $credentials->fetchAuthToken()['access_token'] . "<br>";
		// echo "Status code " . $response->getStatusCode() . "<br>"; 
		echo "</pre>";
	}
}
