<?php
session_start();
include('../data/connect.php');
include('../query.php');
$sql = 'DELETE FROM tblNewsCategory WHERE fkNewsId = ' . $_GET['id'] . '; ';
$sql .= 'DELETE FROM tblNews WHERE pkNewsId = ' . $_GET['id'] . ';';
sqlInsert($sql);
echo '<p>Successfully deleted. <a href="home.php">Go home</a></p>';
?>