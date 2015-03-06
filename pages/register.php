<?php
    $debug = false;

    include('../data/connect.php');
    include('../query.php');
    include('../inc/validation_functions.php');
    include('../inc/top.php');
    include('../inc/header.php');
    include('../inc/nav.php');
    
    //
    //If the form has been submitted, do the following code
    //
    if(isset($_POST['btnSubmit'])){
        if($debug) echo "you submitted the form!";
        
        $baseURL = $_SERVER['PHP_SELF'];
        
        //Cleans the entered information
        
        $email = htmlentities($_POST['txtEmail']) ;
        $firstName = htmlentities($_POST['txtFName']) ;
        $lastName = htmlentities($_POST['txtLName']);
        $password = $_POST['txtPassword'];
        
        //Checks for invalid data
        
        //$errorMsg[] = "";//Having this declared seems to be messing with the empty checks below
        
        if (empty($email)) { 
        $errorMsg[] = "Please enter your Email Address"; 
        $emailERROR = true; 
        } else { 
        $valid = verifyEmail($email); /* test for non-valid  data */ 
        if (!$valid) { 
            $errorMsg[] = "I'm sorry, the email address you entered is not valid."; 
            $emailERROR = true; 
          } 
        }
        
        if (empty($firstName)) { 
        $errorMsg[] = "Please enter your First Name"; 
        $firstNameERROR = true; 
        } else { 
        $valid = verifyAlphaNum($firstName); /* test for non-valid  data */ 
        if (!$valid) { 
            $errorMsg[] = "I'm sorry, the First Name you entered is not valid."; 
            $firstNameERROR = true; 
          } 
        }
        
        if (empty($lastName)) { 
        $errorMsg[] = "Please enter your Last Name"; 
        $lastNameERROR = true; 
        } else { 
        $valid = verifyAlphaNum($lastName); /* test for non-valid  data */ 
        if (!$valid) { 
            $errorMsg[] = "I'm sorry, the Last Name you entered is not valid."; 
            $lastNameERROR = true; 
          } 
        }
        
        if (empty($password)) { 
        $errorMsg[] = "Please enter a password"; 
        $passwordERROR = true; 
        }
        
        if($errorMsg){
            foreach($errorMsg as $error) echo "<p>" . $error . "<p>";
        }
        
        if(!$errorMsg){
            $success = true;
            if($debug) echo "Form is valid";
            
            //submits the data
            $sql = 'INSERT INTO tblUser SET pkEmail="' . $email . '", fldFirstName="' .$firstName . '", fldLastName="' . $lastName . '", fldPassword="' . $password . '";';
            sqlInsert($sql);

            //Send the email to confirm
            $to = $email;
            $message = '<p>Thank you for registering with Matt and Drew\'s News! Click below to confirm your membership</p>' ;
            $message .= '<a href="www.uvm.edu/' . $baseURL . '?email=' . $email . '&firstName=' . $firstName . '&lastName=' . $lastName . '">Confirm!</a><br>';
            $message .= 'www.uvm.edu/' . $baseURL . '?email=' . $email . '&firstName=' . $firstName . '&lastName=' . $lastName;
            $subject = "Confirmation: Matt and Drew's News";
            include_once '../inc/mailMessage.php';
            $mailed = sendMail($email, $subject, $message);
            if ($debug) echo $message;
            echo 'Your registration succeeded, and a confirmation message has been sent to your email';
        }else{
            if($debug) echo "There are error messages?";
        }
        
        
    }//end submit
?>

<?php
//The following code determines what is displayed on register.php
//If the user is arriving to register, GET will be empty, so the form will display
//If they have been directed here via email, GET will NOT be empty, so the confirmation message will appear
    if(empty($_GET) AND $success != true){
    echo '<form method="post" action="register.php">
        <label for="txtEmail">Email: </label>
        <input type="text" id="txtEmail" name="txtEmail" value="' . $email . '">
        <br>
        <label for="txtFName">First Name: </label>
        <input type="text" id="txtFName" name="txtFName" value="' . $firstName . '">
        <br>
        <label for="txtLName">Last Name: </label>
        <input type="text" id="txtLName" name="txtLName" value="' . $lastName . '">
        <br>
        <label for="txtPassword">Password: </label>
        <input type="password" id="txtPassword" name="txtPassword" value="' . $password . '">
        <br>
        <input type="submit" value="submit" name="btnSubmit" id="btnSubmit">
    </form>';
    }else{
        try { 
            $db->beginTransaction(); 
            
            $sql = 'SELECT * FROM tblUser WHERE pkEmail = "' . $_GET['email'] . '" AND fldFirstName="' .$_GET['firstName'] . '" AND fldLastName="' . $_GET['lastName'] . '";'; 
            $stmt = $db->prepare($sql); 
            if ($debug) print "<p>sql: ". $sql; 
        
            $stmt->execute(); 

            // all sql statements are done so lets commit to our changes 
            $dataEntered = $db->commit();
            
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $count = $stmt->rowCount();
            //if the GET info corresponds to 1 database row (i.e, is an actual user's info) we will update their confirmation status
            if($count === 1){
                try { 
                $db->beginTransaction(); 

                $sqlU = 'UPDATE tblUser SET fldConfirmed=1 WHERE pkEmail="' . $_GET['email'] . '";'; 
                $stmt = $db->prepare($sqlU); 
                if ($debug) print "<p>sql: ". $sqlU; 

                $stmt->execute(); 

                $dataEntered = $db->commit();
                
                echo "<h2>Thanks for confirming your membership!</h2>";
                echo "<a href='home.php'>Return to Home</a>";
                } catch (PDOExecption $e) { 
                    $db->rollback(); 
                    if ($debug) print "Error!: " . $e->getMessage() . "</br>"; 
                    $errorMsg[] = "There was a problem confirming your registration."; 
                } 
            }
        } catch (PDOExecption $e) { 
            $db->rollback(); 
            if ($debug) print "Error!: " . $e->getMessage() . "</br>"; 
            $errorMsg[] = "There was a problem with accepting your data please."; 
        }
        
    }
?>