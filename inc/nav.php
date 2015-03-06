<nav>
    <ul>
        <?php
            $getCats = "SELECT * FROM tblCategory;";
            $categories = sqlSelect($getCats);
            foreach($categories as $category){
                echo '<li><a href="categories.php?id=' . $category['pkName'] . '" class="nav-item">' . $category['pkName'] . '</a></li>';
            }
        ?>
    </ul>
</nav>