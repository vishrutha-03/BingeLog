<?php

header('Access-Control-Allow-Origin: *');//check starting part of domain( local host./ allow all origins to send requests if u write local host only that can send req)
header('Content-type: application/json');
header('Access-Control-Allow-Method: POST');
header('Access-Control-Allow-Headers: Origin, Content-Type, Accept');// used to handle preflight requests, which are sent by the browser before making the actual request to check if the server allows the requested method and headers. This header specifies that the server allows requests from any origin, with content type and accept headers.
include_once('../../models/user.php'); // Include the user.php file from the models directory, which contains the User class definition( samre ad requireonce)

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if($user->validate_params($_POST['name'])) { // Check if the request method is POST and validate the required parameters (name, email, password) using the validate_params method of the User class
    $user->name = $_POST['name'];}
  else{
    echo json_encode(array('success'=>0,'message'=>'Name is required'));
    die();
  } 
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
  if($user->validate_params($_POST['createdat'])) {
    $user->createdat =date('Y-m-d H:i:s');}
  else{
    echo json_encode(array('success'=>0,'message'=>'Created at is required'));
    die();
  }
  if ($user->check_unique_email($user->email)) { // Check if the email provided by the user is unique using the check_unique_email method of the User class
    // proceed with registration
  }
  else {
    http_response_code(400); // If the email is not unique return a 400 Bad Request response
    echo json_encode(array('success'=>0,'message'=>'Email already exists')); // If the email is not unique return a JSON response with failure status and message
    die();
    
  }
  //img is a file so code is diff
  $user_images_folder='../../assets/user_images/'; // Define the folder path where user profile pictures will be stored
  if(!is_dir($user_images_folder)){ // Check if the user images folder exists
    mkdir($user_images_folder); // If the folder does not exist create
  }
  //checking if img is there, if not fine if its there save img
  if(isset($_FILES['profpic'])) { // Check if a profile picture file is uploaded and there are no errors
    $file_name = $_FILES['profpic']['name']; // Get the org file path of the uploaded profile picture
    $file_tmp = $_FILES['profpic']['tmp_name']; // Get the temp file name of the uploaded profile picture
    $extension = end(explode('.', $file_name)); // Extract the file extension from the original file name
    $new_file_name = $user->email . "_profile." . $extension; // Create a new file name for the profile picture using the user's email and the original file extension
    move_uploaded_file($file_tmp, $user_images_folder ."/" . $new_file_name); // Move the uploaded file from the temp location to the user images
    //  folder with the new file name
    $user->profpic = 'user_images/' . $new_file_name; // Set the profpic property of the User object to the new file name
  } else {
    $user->profpic = null; // If no profile picture is uploaded set the profpic property to null
  }
  
  if ($id=$user->register_user()) { // Call the register_user method of the User class to save the user data in the database and check if it was successful
    echo json_encode(array('success'=>1,'message'=>'User registered successfully','id'=>$id)); // If the user was registered successfully return a JSON response with success status, message, and the ID of the newly registered user
  }
  else {
   http_response_code(500); // If there was an error registering the user return a 500 Internal Server Error response
   echo json_encode(array('success'=>0,'message'=>'Internal sever error')); // Return a JSON response with failure status and message
  }
}
else{
  die(header('HTTP/1.0 405 Method Not Allowed'));// If the request method is not POST, return a 405 Method Not Allowed response and terminate the script
}