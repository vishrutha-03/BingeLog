<?php

header('Access-Control-Allow-Origin: *');//check starting part of domain( local host./ allow all origins to send requests if u write local host only that can send req)
header('Content-type: application/json');
header('Access-Control-Allow-Method: POST');
header('Access-Control-Allow-Headers: Origin, Content-Type, Accept');// used to handle preflight requests, which are sent by the browser before making the actual request to check if the server allows the requested method and headers. This header specifies that the server allows requests from any origin, with content type and accept headers.
include_once('../../models/user.php'); // Include the user.php file from the models directory, which contains the User class definition( samre ad requireonce)

if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
  if($user->validate_params($_POST['email'])) {
    $user->email = $_POST['email'];}
  else{
    echo json_encode(array('success'=>0,'message'=>'Email is required'));
    die();
  }
  if($user->validate_params($_POST['password'])) {
    $user->password = $_POST['password'];}
  else{
    echo json_encode(array('success'=>0,'message'=>'Password is required'));
    die();
  }
  $user_data = $user->login();
  if (gettype($user_data)=="string") {
    http_response_code(404);// either one of the string statements password not found email not dound as happened and henece its a string
    echo json_encode(array('success'=>0,'message'=>$user_data)); // if its a string then it will be either user not found or incorrect password and hence we will return that message in the response
  }
  else{
    echo json_encode(array('success'=>1,'message'=>'Login successful','user' => $user_data));
  }


}
else{
  die(header('HTTP/1.0 405 Method Not Allowed'));// If the request method is not POST, return a 405 Method Not Allowed response and terminate the script
}