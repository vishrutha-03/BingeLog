<?php

$ds = DIRECTORY_SEPARATOR;
$base_dir = realpath(dirname(__FILE__) . $ds . '..') . $ds;

require_once($base_dir . 'includes' . $ds . 'Database.php');
require_once($base_dir . 'includes' . $ds . 'Bcrypt.php');// its a way of hashing passwords 

class User {

    private $table = 'user';

    public $id;
    public $name;
    public $email;
    public $password;
    public $profpic;
    public $createdat;

    private $db;

    // constructor
    public function __construct()
    {
        $this->db = new Database();
    }

    //check if email is unique or not
    public function check_unique_email($email){
        global $database;
        $this->email = trim(htmlspecialchars(strip_tags($email)));
        $sql = "SELECT id FROM $this->table WHERE email = '".$database->escape_value($this->email)."'";
        $result = $database->query($sql);//running sql command
        $user_id = $database->fetch_row($result); // fetching the result of this query
        return empty($user_id) ? true : false; // if user_id is empty return true (email is unique) otherwise return false (email is not unique)
    }
    // validate params
    public function validate_params($value){
        return !empty($value);
    }
    public function last_insert_id(){
    return mysqli_insert_id($this->connection);}
    // register user
    public function register_user(){

        $database = $this->db;

        // sanitize
        $this->name = trim(htmlspecialchars(strip_tags($this->name))); 
        $this->email = trim(htmlspecialchars(strip_tags($this->email)));
        $this->password = trim(htmlspecialchars(strip_tags( Bcrypt::hashPassword($this->password) ))); // Hash the password using the Bcrypt class before sanitizing it
        $this->profpic = trim(htmlspecialchars(strip_tags($this->profpic)));
        $this->createdat = trim(htmlspecialchars(strip_tags($this->createdat)));

        // query
        $sql = "INSERT INTO $this->table 
                (name, email, password, profpic, createdat) 
                VALUES (
                '" . $database->escape_value($this->name) . "',
                '" . $database->escape_value($this->email) . "',
                '" . $database->escape_value($this->password) . "',
                '" . $database->escape_value($this->profpic) . "',
                '" . $database->escape_value($this->createdat) . "'
                )";

        $user_saved = $database->query($sql);

        if($user_saved){
            return $database->last_insert_id(); // now this will work
        } else {
            return false;
        }
    }
    //login function 
    public function login(){
        global $database;
        $this->email=trim(htmlspecialchars(strip_tags($this->email)));
        $this->password=trim(htmlspecialchars(strip_tags($this->password)));
        $sql="SELECT * FROM $this->table WHERE email='".$database->escape_value($this->email)."'";
        $result=$database->query($sql);//running sql command
        $user_data=$database->fetch_row($result); // fetching the result of this query
        if(empty($user_data)){
            return "User not found";}
        else{
            if(Bcrypt::checkPassword($this->password, $user_data['password'])){
                unset($user_data['password']);// to remove password from the user data array before returning it in the response
                return $user_data;
            }
            else{
                return "Incorrect password";
            }
    }
}

}
// create object
$user = new User();