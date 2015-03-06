<header>
    <div>
        <?php
        if($_SESSION['login'] != 1){
            echo '<a href="../pages/userlogin.php" class="header-login">Login!</a>';
            
        }else{
            echo '<span class="header-welcome">Welcome ' . $_SESSION['user'] . '</span>';
            echo '<a href="../pages/submit.php" class="header-submit">Submit some news!</a>';
            echo '<a href="logout.php" class="header-logout">Logout</a>';
        }
        ?>
    </div>
    <a href="home.php"><h1>Matt and Drew's World News</h1></a>
</header>

