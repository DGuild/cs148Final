<?php 
session_start();

    include('../data/connect.php');
    include('../query.php');
    include('../inc/validation_functions.php');
    include('../inc/top.php');
    include('../inc/header.php');
    include('../inc/nav.php');


$debug = false; 
if ($debug) print "<p>DEBUG MODE IS ON</p>";

//Intialize the variables that represent submitted data 

$Title = "";
$Content = "";

// error-checking variables 

$TitleERROR= false;
$ContentERROR= false;
$ChkERROR= false;



//If the user has press the submit button, the page should
// 1. Sanitize data
// 2. Check to make sure there IS data (not empty)
// 3. Ensure data is valid
// 4. Submit the data to database
//    4.1 In News table
//    4.2 And in NewCategory table

if (isset($_POST["btnSubmit"])) {
    
//////1. Sanitize the data/////////////////////////////////////////////////////
    
    function clean($elem)
    {
        if(!is_array($elem))
            $elem = htmlentities($elem,ENT_QUOTES,"UTF-8");
        else
            foreach ($elem as $key => $value)
                $elem[$key] = clean($value);
        return $elem;
     }
     if(isset($_GET)) $_CLEAN['GET'] = clean($_GET);
     if(isset($_POST)) $_CLEAN['POST'] = clean($_POST);

    $Title = htmlentities($_POST["txtTitle"], ENT_QUOTES, "UTF-8"); 
    $Content= nl2br(htmlentities($_POST["txtContent"], ENT_QUOTES, "UTF-8"));
   

/////2. Check for valid data////////////////////////////////////////////////////

    $errorMsg = array(); 
    
    if (empty($Title)) { 
        $errorMsg[] = "Please enter your Title"; 
        $TitleERROR = true; 
    } else { 
        $valid = verifyText($Title); /* test for non-valid  data */ 
        if (!$valid) { 
            $errorMsg[] = "I'm sorry, the Title you entered is not valid."; 
            $TitleERROR = true; 
        } 
    } 
     if (empty($Content)) { 
        $errorMsg[] = "Please enter Content"; 
        $ContentERROR = true; 
    } 
    if  ($_SESSION['user']=== ""){
        $errorMsg[]= "Please Log in";
    }
    
    
////4.Submit to the database////////////////////////////////////////////////////
    if (!$errorMsg) { 
    
        if ($debug) print "<p>Form is valid</p>";
        $sql = 'INSERT INTO tblNews SET fldTitle="'.$Title.'", fldContent="'.$Content.'", fkEmail="'.$_SESSION['email'].'";';
        $entry = sqlInsert($sql);
        echo '<br>';
        
///////4.2Below code is used to input checkbox data into database///////////////
        
        function strContains($string){
            if(strstr($string, 'chk') != false){
                return true;
            }
        }
        
        $arrayKeys = array_keys($_POST);
        $chkArray = array_filter($arrayKeys, "strContains");
        foreach($chkArray as $chk){
            $sqlCat = 'INSERT INTO tblNewsCategory SET fkName="' . substr($chk, 3) . '", fkNewsId=' . $entry . ';';
            sqlInsert($sqlCat);
        }
     }
}
   
?>

<section id ="main">
<section id="content">

 <?php 
    print '<div id="errors">'; 
    if (isset($_POST["btnSubmit"]) AND empty($errorMsg)) { 
       print "<h2>Your news has "; 

       echo "been submitted</h2>"; 

    } 

    if ($errorMsg) { 
        echo "<ol>\n"; 
        foreach ($errorMsg as $err) { 
            echo "<li>" . $err . "</li>\n"; 
        } 
            
            echo "</ol>\n"; }
            print '</div>';
            
?>

<?php 
if($_SESSION['login'] != 1){
    echo '<h3>Please <a href="userlogin.php">login</a> to submit news</h3>';
}else{


    echo '<p>Post your news here!</p>
            <form action="submit.php" method="post" id="frmRegister" enctype="multipart/form-data">

                <fieldset>               
                    <label for="txtTitle" class="required">Title</label>
                    <input type="text" id="txtTitle" name="txtTitle" value="' . $Title . '" tabindex="288"
                            size="25" maxlength="60" placeholder="Enter your Title" /><br>

                    <label for="txtContent" class="required">Content</label><br>
                    <textarea id="txtContent" name="txtContent" value="' . $Content . '" tabindex="288"
                            size="25" maxlength="60000" placeholder="Enter your Content" />' . $Content . '</textarea><br>';
            
                $getCats = "SELECT * FROM tblCategory;";
                $categories = sqlSelect($getCats);
                echo "<label>Categories(choose all that apply):</label><br>";
                foreach ($categories as $category){
                    echo '<input type="checkbox" id=chk' . $category['pkName'] . '" name="chk' . $category['pkName'] . '" value="' . $category['pkName'] . '"';
                    if(in_array($category['pkName'], $_POST)){
                           echo 'checked="checked"';
}
                     echo '"><label for="chk' . $category['pkName'] . '">' . $category['pkName'] . '</label><br>';
                }
                    

        

                echo '</fieldset>
                <fieldset class="buttons"> 
                    <input type="submit" id="btnSubmit" name="btnSubmit" value="Post" tabindex="991" class="button"> 
                    <input type="reset" id="butReset" name="butReset" value="Reset Form" tabindex="993" class="button" onclick="reSetForm();" > 
                </fieldset>     
            </form>';
}
?>

</section>
<? include ("../inc/footer.php")?>
</section>
</body>
</html>