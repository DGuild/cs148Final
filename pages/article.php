<?php
session_start();
//This page will display specific articles
$debug = false;

    include('../data/connect.php');
    include('../query.php');
    include('../inc/validation_functions.php');
    include('../inc/top.php');
    include('../inc/header.php');
    include('../inc/nav.php');
    
    //Retrieves individual article and displays it
    $sql = "SELECT * FROM tblNews WHERE pkNewsId='" . $_GET['id'] . "';";
    $query = sqlSelect($sql);
    foreach ($query as $query){ //Had to use foreach to get it to display, even though it is just one per article page
        echo "<h2>" . $query['fldTitle'] . "</h2>";
        echo "<span>" . substr($query['fldDate'], 0, 10) . "</span>";
        echo "<p>" . html_entity_decode($query['fldContent']) . "</p>";
        echo "<a href='edit.php?id=" . $query['pkNewsId'] . "'>(edit)</a>";//may want to only let users see edit link
    }
?>