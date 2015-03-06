<?php
     if ($_SESSION['login']==="1"){
     echo "<p>Welcome ". ($_SESSION['email']) . "</p>";
}
    else{
    echo "You are not logged in <br>";
    echo "<a href='home.php'>Click here to Login!</a>";
    echo "<a href='reg.php'> Click here to Register!</a>";
        
}
?>
