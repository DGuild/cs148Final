<?
session_start();
$debug= false;
include('../data/connect.php');
    include('../query.php');
    include('../inc/validation_functions.php');
    include('../inc/top.php');
    include('../inc/header.php');
    include('../inc/nav.php');

?>
    



<section id="main">

<!-- ###########   meaningful about what this part   ################## -->

<section id="content">
<?php
$category = $_GET['id'];
 $sql = "SELECT * FROM tblNews, tblCategory, tblNewsCategory ";
 $sql .= "WHERE pkNewsID = fkNewsID ";
 $sql .= "AND pkName = fkName ";
 $sql .= "AND fkName ='" . $category . "';";
 $query = sqlSelect($sql);
foreach($query as $query){
    echo "<a href='article.php?id=" . $query['pkNewsId'] . "'><h3>" . $query['fldTitle'] . "</h3></a>";
    echo "<span>" . substr($query['fldDate'], 0, 10) . "</span>";
    echo "<p>" . getExcerpt($query['fldContent'], 30) . "... <a href='article.php?id=" . $query['pkNewsId'] . "'>Continue</a></p>";
 }
 ?>

<!-- ###########                                     ################## -->
</section>
<? include ("footer.php")?>
</section>
</body>
</html>