<?php
//This form will allow users to edit articles that already exist, and will allow the admin to delete articles
session_start();
$debug = false;

    include('../data/connect.php');
    include('../query.php');
    include('../inc/validation_functions.php');
    include('../inc/top.php');
    include('../inc/header.php');
    include('../inc/nav.php');
    
    if(isset($_POST['btnSubmit'])){
        
        //cleans the form entries
        $title = htmlentities($_POST['txtTitle']);
        $content = nl2br(htmlentities($_POST['txtContent']));
        
        //Checks for empty or invalid data
        if (empty($title)) { 
        $errorMsg[] = "Oh no! You need a title!"; 
        $titleERROR = true; 
        } else { 
        $valid = verifyAlphaNum($title); /* test for non-valid  data */ 
        if (!$valid) { 
            $errorMsg[] = "I'm sorry, the title you entered is not valid. Please only put letters and numbers in the title."; 
            $titleERROR = true; 
          } 
        }
        
        if (empty($content)) { 
        $errorMsg[] = "Oh no! We couldn't accept your edit - there wasn't any news story!"; 
        $contentERROR = true; 
        } //else { 
        //$valid = verifyAlphaNum($content); /* test for non-valid  data */ 
        //if (!$valid) { 
            //$errorMsg[] = "I'm sorry, the Last Name you entered is not valid."; 
            //$contentERROR = true; 
          //} 
        //}
        
        //echo the errors
        if($errorMsg){
            foreach($errorMsg as $error) echo "<p>" . $error . "<p>";
        }
        
        if (empty($errorMsg)){
            //submits the changes to database
            $sql = "UPDATE tblNews SET fldTitle = '" . $title . "', fldContent = '" . $content . "' WHERE pkNewsId =" . $_GET['id'] . ";";
            sqlUpdate($sql);
            echo "<h3>Thanks for the Update!</h3>";
        }
    }//end submit
    
    //Retrieves the data on initial page load
    $sql = "SELECT * FROM tblNews WHERE pkNewsId='" . $_GET['id'] . "';";
    $query = sqlSelect($sql);
    foreach($query as $query){

if($_SESSION['login'] != 1){
    echo '<h3>Please <a href="userlogin.php">login</a> to edit this story</h3>';
}else{

$content = str_replace("<br />", "", $query['fldContent']);
    
echo '<form action="' . $_SERVER['PHP_SELF'] . '?id=' .  $_GET['id'] . '" method="post" id="frmRegister" enctype="multipart/form-data">
    
    
 
 <fieldset>               
                    <label for="txtTitle" class="required">Title</label>
                    <input type="text" id="txtTitle" name="txtTitle" value="' . $query['fldTitle'] . '" tabindex="288"
                            size="25" maxlength="60" placeholder="Enter your Title"  onfocus="this.select()" />
                </fieldset>    
    <fieldset>
         <label for="txtContent" class="required">Content</label><br>
                    <textarea id="txtContent" name="txtContent"  tabindex="288"
                            size="25" maxlength="60000" placeholder="Enter your Content" onfocus="this.select()" >' . html_entity_decode($content) . '</textarea>
    </fieldset>
   

<fieldset class="buttons"> 
                    <input type="submit" id="btnSubmit" name="btnSubmit" value="Post" tabindex="991" class="button"> 
                    <input type="reset" id="butReset" name="butReset" value="Reset Form" tabindex="993" class="button" onclick="reSetForm()" > 
     </fieldset>     
</form>';
if($_SESSION['email'] === 'admin@bealguild.com'){
echo '<a href="delete.php?id=' . $_GET['id'] . '">Delete</a>';
}
                        
    }
    }
?>