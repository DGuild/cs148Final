<?php 
session_start();

$debug = false;

    include('../data/connect.php');
    include('../query.php');
    include('../inc/validation_functions.php');
    include('../inc/top.php');
    include('../inc/header.php');
    include('../inc/nav.php');
    
    
if ($debug) print "<p>DEBUG MODE IS ON</p>"; 


//############################################################################# 
// set all form variables to their default value on the form. for testing i set 

$Email = "";
$Password = "";

// $email = ""; 
//############################################################################# 
//  
// flags for errors 

$EmailERROR= false;
$PasswordERROR= false;

/*
$UsernameERROR = false;
$PasswordERROR = false;
 */


//############################################################################# 
//   


//----------------------------------------------------------------------------- 
//  
// Checking to see if the form's been submitted. if not we just skip this whole  
// section and display the form 
//  
//############################################################################# 
// minor security check 

if (isset($_POST["btnSubmit"])) { 
    $_SESSION['login']= "0";
    $fromPage = getenv("http_referer"); 
/*
    if ($debug) {
        print "<p>From: " . $fromPage . " should match "; 
        print "<p>Your: " . $yourURL; 
    }
    if ($fromPage != $yourURL) { 
        die("<p>Sorry you cannot access this page. Security breach detected and reported.</p>"); 
    } 
*/ 
    
    $Email = htmlentities($_POST["txtEmail"], ENT_QUOTES, "UTF-8");
    $Password = htmlentities($_POST["txtPassword"], ENT_QUOTES, "UTF-8");
    $errorMsg = array(); 
     
    
    if (empty($Email)) { 
        $errorMsg[] = "Please enter your Email"; 
        $EmailERROR = true; 
    } else { 
        $valid = verifyEmail($Email); /* test for non-valid  data */ 
        if (!$valid) { 
            $errorMsg[] = "I'm sorry, the email address you entered is not valid."; 
            $EmailERROR = true; 
        } 
    } 
    if (empty($Password)) { 
        $errorMsg[] = "Please enter your Password"; 
        $PasswordERROR = true; 
    } else { 
        $valid = verifyText($Password); /* test for non-valid  data */ 
        if (!$valid) { 
           $errorMsg[] = "I'm sorry, the Password you entered is not valid."; 
            $PasswordERROR = true; 
        } 
}
   $sql = "SELECT * FROM tblUser WHERE pkEmail='" . $Email . "' AND fldPassword='" . $Password . "';";
   // $sql = "SELECT * FROM tblUser WHERE pkEmail='" . $Email . "'and fldPassword='" . $Password . "';"; // and fldConfirmed=1
    
    if ($debug) print($sql);
    
    
    
    
   
    $stmt = $db->prepare($sql); 
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $firstName = $result['fldFirstName'];

    $CorrectEmail = $result["pkEmail"]; 
    if ($debug) print ($CorrectEmail);
    // set logged in   
    if ($CorrectEmail !=""){
        $_SESSION['login']="1";
        $_SESSION['email']=$Email;
        $_SESSION['user'] = $firstName;
       //$_SESSION['number']=$NumberID;
    }
 
    if($debug) print($_SESSION['login']);
    if($debug) print($_SESSION['user']);
    if($debug) print($_SESSION['number']);
 
}
    
   
?>

    

<body id="home">
<section id ="main">
    <section id='login'>
    </section>
<!-- ###########   comment to separate source code    ################## -->
<section id ="topping">
<header>
</header>

<!-- ###########   comment should have something     ################## -->
<nav>
</nav>
</section>

<!-- ###########   meaningful about what this part   ################## -->

<section id="content">

   <?php if (isset($_POST["btnSubmit"]) AND empty($errorMsg)) { 
            print "<h2>Your login has "; 

            if ($_SESSION['login'] == 1) { 
                echo "Succeeded ";
            } else{
                echo "Not Succeeded, try again";
            }

            
        } else { 



            print '<div id="errors">'; 
        
            if ($errorMsg) { 
                echo "<ol>\n"; 
                foreach ($errorMsg as $err) { 
                    echo "<li>" . $err . "</li>\n"; 
                } 
                echo "</ol>\n"; 
            } 
        }
            print '</div>'; 
            
            if ($_SESSION['login'] == 1) {
                echo "<a href='home.php'>Click here to go home</a>";
            }
        
            ?>
<?php
if($_SESSION['login'] != 1){
        echo '<p>You can log in here!</p>
            <form action="userlogin.php" method="post" id="frmRegister" enctype="multipart/form-data">


             <fieldset>               
                                <label for="txtEmail" class="required">Email</label>
                                <input type="text" id="txtEmail" name="txtEmail" value="' . $Email . '" tabindex="288"
                                        size="25" maxlength="60" placeholder="Enter your Email"  onfocus="this.select()" />
                            </fieldset>    
                <fieldset>
                     <label for="txtPassword" class="required">Password</label>
                                <input type="password" id="txtPassword" name="txtPassword" value="' . $Password . '" tabindex="288"
                                        size="25" maxlength="60" placeholder="Enter your Password"  onfocus="this.select()" />
                </fieldset>

                <fieldset class="buttons"> 
                                <input type="submit" id="btnSubmit" name="btnSubmit" value="Log In" tabindex="991" class="button"> 
                                <input type="reset" id="butReset" name="butReset" value="Reset Form" tabindex="993" class="button" onclick="reSetForm()" > 
                 </fieldset>     
            </form>
        <p>Don\'t have an account? <a href="register.php">Register here</a></p>';
}
?>

<!-- ###########  search for  html5 article vs section  ################## -->

<!-- ###########   is all about                      ################## -->
<aside id="rightColumn">
</aside>

<!-- ###########                                     ################## -->
</section>


</section>
</body>
</html>