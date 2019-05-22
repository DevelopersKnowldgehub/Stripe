<?php
/*@Author : knowledgeHub*/
/*@Date Created : XXX*/
/*@Description :   you can fetch more details or save the json in db so that you can see the response.*/

if(isset($_REQUEST['action']))
{

if($_REQUEST['action']=='pending'){
$input = @file_get_contents('php://input');
$event_json = json_decode($input);
// Do something with $event_json
$transaction_id=$event_json->data->object->id;

/*$obj->test_webhook($conn,$transaction_id,json_encode($event_json),'pending');*/ 
/*Use your Function. This is demo function so that you can store the response in DB and check the response*/

// Return a response to acknowledge receipt of the event
http_response_code(200);

}

if($_REQUEST['action']=='success'){
$input = @file_get_contents('php://input');
$event_json = json_decode($input);
$transaction_id=$event_json->data->object->id;
// Do something with $event_json
/*$obj->test_webhook($conn,$transaction_id,json_encode($event_json),'successful');*/
/*Use your Function. This is demo function so that you can store the response in DB and check the response*/

// Return a response to acknowledge receipt of the event
http_response_code(200);
}

if($_REQUEST['action']=='failed'){
$input = @file_get_contents('php://input');
$event_json = json_decode($input);
// Do something with $event_json
$transaction_id=$event_json->data->object->id;
/*$obj->test_webhook($conn,$transaction_id,json_encode($event_json),'failed');*/
/*Use your Function. This is demo function so that you can store the response in DB and check the response*/

// Return a response to acknowledge receipt of the event
http_response_code(200);
}

}

?>