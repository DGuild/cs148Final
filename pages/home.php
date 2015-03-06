<?php
session_start();
//This will be the home page
$debug = false;

    include('../data/connect.php');
    include('../query.php');
    include('../inc/validation_functions.php');
    include('../inc/top.php');
    include('../inc/header.php');
    include('../inc/nav.php');
    
    
    $sql = "SELECT * FROM tblNews ORDER BY pkNewsId DESC LIMIT 9;";
    $query = sqlSelect($sql);
    foreach($query as $query){
        echo '<div class="news-box">';
        echo "<a href='article.php?id=" . $query['pkNewsId'] . "'><h3>" . $query['fldTitle'] . "</h3></a>";
        echo "<span>" . substr($query['fldDate'], 0, 10) . "</span>";
        echo "<p>" . getExcerpt($query['fldContent'], 15) . "... <a href='article.php?id=" . $query['pkNewsId'] . "'>Continue</a></p>";
        echo '</div>';
    }
?>