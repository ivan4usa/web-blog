<aside>
    <h2 class="h4 p-2 m-0 bg-dark bg-gradient text-white">Categories</h2>
    <nav class="mb-2">
        <ul class="list-group">
            <?php
                // Loading all constants and functions
                require_once('vars.php');
                require_once('functions.php');

                // Get all categories from database
                $menu_query = query_get_all_categories();
                $menu_data = connect($menu_query);
                $categories_arr = get_categories_array($menu_data);  // array with all categories

                // Building list from array of categories
                foreach ($categories_arr as $key => $category_name) {
                    if (isset($_GET['filter']) && $_GET['filter'] == $key) {
                        $selected_class = 'bg-warning fw-bold';
                        $selected_link = $_SERVER['PHP_SELF'];
                    } else {
                        $selected_class = '';
                        $selected_link = $_SERVER['PHP_SELF'] . '?filter=' . $key;
                    }
                    ?>
                    <li class="list-group-item list-group-item-action <?php echo $selected_class;?>"><a
                                class="d-block text-decoration-none"<?php echo ' " href="' . $selected_link . '">' . $category_name; ?></a></li>
                    <?php
                }
            ?>
        </ul>
    </nav>
    <h2 class="h4 p-2 m-0 bg-dark bg-gradient text-white">Search Post</h2>
    <form  class="mb-3" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="d-flex mt-2">
            <input type="text" class="w-100" name="user_search" value="<?php echo isset($_POST['user_search'])? $_POST['user_search'] : ''?>">
            <input type="submit" class="btn btn-sm btn-info text-white" value="Search">
        </div>
        <?php if (isset($_POST['user_search'])) : ?>
        <div class="d-flex my-3 text-dark justify-content-between align-items-center">
            <p class="m-0">Posts found: <?php echo isset($_SESSION['records_number'])? $_SESSION['records_number'] : '';?></p>
            <button type="submit" name="search_reset" class="text-decoration-none btn btn-sm btn-info text-white"><i class="fas fa-times me-2"></i>Clear filter</button>
        </div>
        <?php endif; ?>
    </form>
</aside>