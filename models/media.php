<?php

$ds = DIRECTORY_SEPARATOR; // Defines a constant for the directory separator (e.g., '/' on Unix, '\' on Windows)
$base_dir = realpath(dirname(__FILE__).ds . '..') . $ds; // Gets the absolute path of the current directory and appends the directory separator two dots mean one folder up (unix) bingelog

require_once($base_dir . 'includes' . $ds . 'Database.php'); // Includes the Database.php file from the includes directory

class Media {
    private $table='media'; // Defines a private variable to hold the name of the media table
    public $id;
    public $title;
    public $type;
    public $genre;
    public $year;
    public $rating;
    public $description;
    public $image;
    //constructor
    public function __construct()
    {
        $this->db = new Database(); // Create a new instance of the Database class and assign it to the $db variable
    }

    public function validate_params($value){//validate the parameters if they exist
      if (empty($value)) {
        return false; // Return false if the value is empty
      }
      else {
        return true; // Return true if the value is not empty
    }}
    //saving new data in database
    public function register_media(){
      global $database; // Access the global $database variable
      $this->title= trim(htmlspecialchars(strip_tags($this->title))); 
      //id automaticallu created 
      //strip tags removes all tags in html xml and php, html special chars converts special characters to html entities, trim removes whitespace from the beginning and end of a string
      $this->type= trim(htmlspecialchars(strip_tags($this->type)));
      $this->genre= trim(htmlspecialchars(strip_tags($this->genre)));
      $this->year= trim(htmlspecialchars(strip_tags($this->year)));
      $this->rating= trim(htmlspecialchars(strip_tags($this->rating)));
      $this->description= trim(htmlspecialchars(strip_tags($this->description)));
      $this->image= trim(htmlspecialchars(strip_tags($this->image)));
      $sql = "INSERT INTO $this->table (title, type, genre, year, rating, description, image) VALUES ("".$database->escape_value($this->title)."", 
      "".$database->escape_value($this->type)."",
      "".$database->escape_value($this->genre)."",
      "".$database->escape_value($this->year)."",
      "".$database->escape_value($this->rating)."",
      "".$database->escape_value($this->description)."",
      "".$database->escape_value($this->image)."")";// SQL query to insert a new media record into the database, using the escaped values of the media properties
      $media_saved = $database->query($sql); // Execute the SQL query using the query method of the Database class and store the result in the $media_saved variable
      if($media_saved){ // Check if the media was saved successfully
        return $database->last_insert_id(); // Return the ID of the newly inserted media record using the last_insert_id method of the Database class
      }
      else {
        return false; // Return false if there was an error saving the media
      }
      

    }
}
$media=new Media(); // Create a new instance of the Media class and assign it to the $media variable

