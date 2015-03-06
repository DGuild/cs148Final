<?php
/* The purpose of this file to establish the connection between your php page
 * and the database. On successfull completion you will have a variable $db 
 * that is your database connection ready to use.
 */

$url = $_SERVER['PHP_SELF'];

if($debug){
        echo $url;
}

$explode = explode('/', $url);


//if it's Drew's, do this
if (in_array('~dguild',$explode)){
    include("/usr/local/uvm-inc/dguild.inc");

    $databaseName="DGUILD_final";        

    $dsn = 'mysql:host=webdb.uvm.edu;dbname=';
}
//if it's Matt's, do this
if (in_array('~mbeal',$explode)){
    $db_A_username = 'mbeal_admin';
    $db_A_password = '4jLRcrdzqPmXId3t';

    $databaseName="MBEAL_NEWS";   

    $dsn = 'mysql:host=webdb.uvm.edu;dbname=';
}
function dbConnect($dbName){
    global $db, $dsn, $db_A_username, $db_A_password;

    if (!$db) $db = new PDO($dsn . $dbName, $db_A_username, $db_A_password); 
        if (!$db) {
          return 0;
        } else {
          return $db;
        }
} 

// create the PDO object
try {
    global $databaseName;
    $db=dbConnect($databaseName);
    if($debug) echo '<p>A You are connected to the database!</p>';
} catch (PDOException $e) {
    $error_message = $e->getMessage();
    if($debug) echo "<p>An error occurred while connecting to the database: $error_message </p>";
}

?>