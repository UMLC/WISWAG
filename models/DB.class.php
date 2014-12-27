<?php

// namespace lets you have multiple classes named the same thing
// as long as they are in different namespaces
// for our purposes the namespace will always be the same as the folder name
namespace models;

// A class is a blueprint for an object
// when we create an object from this class using '$db = new DB()'
// we'll be able to use any of the 'public' functions definend here

class DB {

    // variables in a class are difined as 'public' or 'private'
    // we won't have access to the private variables in the class's resulting object
    // $db = new DB(); echo $db->user;
    // would return an error because 'user' is a private variable
    private $user = "UMLC_data";
    private $pass = "UMLC_data";
    private $host = "localhost";
    private $base = "UMLC_data";
    private $conn = false;

    // '__construct' is a 'magic' function because we don't have to run the function
    // whenever an object is created from a class, the class's '__construct' function
    // is run automatically, in this case a connection to the database is created
    public function __construct() {
        // here we can access private variables because this code is inside the class.
        // inside a class, '$this' refers to the object that will be created from this
        // class
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->base);
    }

    // now we'll include a function to run a query on the database so we can run
    // $db = new DB(); $db->query("SELECT * FROM teachers ORDER BY name")
    // or any other qeury
    public function query($sql) {
        $result = $this->conn->query($sql);
        // query returns false when an error occurs
        if ($result === false) {
            // let the user know the query failed, and show them the query
            echo "Query Failed: " . $sql;
            // query returns true if successful and no actual results are expected
            // this is the case with UPDATE
        } else if ($result === true) {
            if ($this->conn->insert_id) {
                // if the query worked ($result === true) and it was an INSERT query
                // it would have set a new 'insert_id' because we are inserting into
                // a table with an autoincrement field, if this is the case
                // return the number, because it is useful
                return $this->conn->insert_id;
            }
            // when a function returns true, we can assume everything worked right
            // here we return true because at this point the query was successful
            // and it was a query that doesn't return any useful results
            return true;
        } else {
            // this else will handle anything that was not handled above, in this case
            // it will handle the case where real results are returned by the query
            // like when we use SELECT
            $results = $result->fetch_all();
            // fetch_all gets an array of all the rows and fields returned as a 
            // result of the query
            return $results;
        }
        // if none of these cases were triggered, something went wrong
        return false;
        // returning a value from a function works like this
        // $variable = functionName();
        // now $variable holds whatever value was 'return'ed from functionName()
    }
    
    // __destruct is another magic function, it is called when someone wants
    // to delete the object created from the class
    public function __destruct() {
        // before the object is deleted, close the MySQL connection it was using
        $this->conn->close();
    }

}
