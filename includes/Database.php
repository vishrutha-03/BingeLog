<?php


define('HOST', 'localhost');
define('USER_NAME', 'root');
define('PASSWORD', '');
define('DB_NAME', 'bingelog');
//start class
class Database
{
    public $connection;
    public function __construct()
    {
        $this->open_db_connection();
    }
     public function last_insert_id(){
    return mysqli_insert_id($this->connection);}
    public function open_db_connection()// to create connection to the database
    {
        $this->connection = new mysqli(HOST, USER_NAME, PASSWORD, DB_NAME);
        if (mysqli_connect_errno()) {
            die("Database connection failed: " . mysqli_connect_error() . " (" . mysqli_connect_errno() . ")"); // die keyword is used to top execution of the script and display an error message as soon as php encounters error. 
        }
    }
    // executing sqp query
    public function query($sql)
    {
        $result = $this->connection->query($sql);// variable result that has the result of this query
        if (!$result) {
            die("Query failed: " . $this->connection->error); // if the query fails,{ no result} it will stop the execution and display an error message
        }
        return $result;
    }
    //fetching list of data from sql result
    public function fetch_array($result)
    {
        if($result->num_rows > 0){// if result has some value /rows 
            while ($row = $result->fetch_assoc()) { // fetch_assoc() method is used to fetch a result row as an associative array. fetches associated data
                $result_set[] = $row; // store the fetched row in the result_set array
            }
            return $result_set; // return the result_set array containing all fetched rows
        }
    }
    //fetching siugle row of data from sql result
    public function fetch_row($result)
    {
        if ($result->num_rows > 0) { // if result has some value /rows 
            return $result->fetch_assoc(); // fetch_assoc() method is used to fetch a result row as an associative array. fetches associated data
        }
    }
    //checks proper format of data
    public function escape_value($value)
    {
        return $this->connection->real_escape_string($value); // real_escape_string() method is used to escape special characters in a string for use in an SQL statement, taking into account the current character set of the connection.
    }
    //close the connection
    public function close_connection()
    {
        if (isset($this->connection)) {
            $this->connection->close(); // close the database connection
        }
    }

}
// create an instance of the Database class
$database = new Database();//(capital d is class name and small d is object name)