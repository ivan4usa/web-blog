<?php
    // Loading all constants and functions
    require_once('vars.php');
    require_once ('functions.php');

    // Start the session_start
    session_start();

    // Defence page
    require_once('defence.php');
?>

<!doctype html>
<html lang="en">
<head>
    <?php include(IN_HEAD); ?>
    <title>Web Blog - Home Page</title>
</head>
<body>
<?php
    include(IN_HEADER);
    // Data for pagination (part 1)
    $cur_page = isset($_GET['page'])? $_GET['page'] : 1;
    $results_per_page = 10;
    $skip = (($cur_page - 1) * $results_per_page);

    // Build query
    $query = query_get_all_posts();
    $query .= " WHERE p.user_id = '" . $_SESSION['user_id'] . "'";
    if (isset($_GET['filter'])) {
        $query .= " AND p.category_id = '" . $_GET['filter'] . "'";
    }

    // Get number of all rows of posts
    $data = connect($query);
    $records_number = mysqli_num_rows($data);

    // Adding sorting order and limit to query
    $query .= " ORDER BY p.post_date DESC";
    $query .= " LIMIT $skip, $results_per_page";

    // Get posts data
    $data = connect($query);

    // Data for pagination (part 2)
    $num_pages = ceil($records_number / $results_per_page);

    // Statistics data
    $last_post_date = '';

    // Get categories from database
    $query = query_get_all_categories();
    $cat_data = connect($query);
    $categories_arr = get_categories_array($cat_data);

    if (isset($_POST['submit']) && trim($_POST['category_name']) && !in_array($_POST['category_name'], $categories_arr)) {
        $query = query_insert_new_category_by_name($_POST['category_name']);
        connect($query);
        unset($_POST);
    }
?>

<main>
    <div class="container-xl m-auto flex-direction-column justify-content-space-around block">
        <div class="row block-row">
            <div class="col-12 col-md-8 mt-2 content">
                <div class="d-flex align-items-center bg-2 p-2 mb-2">
                    <h2 class="fs-3 text-white fw-bold ms-2 my-0">Posts</h2>
                    <a class="ms-auto text-white text-decoration-none fs-5 d-flex align-items-center btn py-0 btn-warning" href="<?php echo PG_NEW_POST; ?>">
                        <i class="fas fa-plus-circle me-2"></i>New Post
                    </a>
                </div>
                <table class="table table-striped table-hover align-middle">
                    <thead class="bg-warning text-white fs-5">
                    <tr>
                        <th class="d-none d-sm-block text-center">Image</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Title</th>
                        <th class="text-center">Category</th>
                        <th class="text-center">Edit</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        // Build table of posts
                        if ($data && mysqli_num_rows($data) > 0) {
                            while ($row = mysqli_fetch_array($data)) {
                                if (!isset($first_row)) {
                                    $last_post_date = date('M d, Y', strtotime($row['post_date']));
                                    $first_row = true;
                                }
                                echo '<tr>';
                                echo '<td class="d-none d-sm-block"><img src="' . MM_UPLOAD_PATH . $row['cover'] . '" alt="image post" class="text-center" height="50"></tdclass>';
                                echo '<td class="text-center">' . date('M d, Y', strtotime($row['post_date'])) . '</td>';
                                echo '<td>' . $row['title'] . '</td>';
                                echo '<td>' . $row['name'] . '</td>';
                                echo '<td class="text-nowrap text-center"><a href="' . PG_NEW_POST . '?id=' . $row['id'] .'" class="text-decoration-none me-3"><i class="far fa-edit text-dark"></i>' .
                                    '</a><a href="' . PG_DEL_POST . '?id=' . $row['id'] . '&amp;cover=' . $row['cover'] . '" class="text-decoration-none"><i class="fas fa-trash-alt text-danger"></i></a></td>';
                            }
                        } else {
                            echo '<div class="d-flex justify-content-around my-3"><p class="fs-3 text-warning m-0">No Data</p></div>';
                        }
                    ?>
                    </tbody>
                </table>
                <?php
                    // Add pagination
                    if ($num_pages > 1) {
                        echo generate_page_links($cur_page, $num_pages);
                    }
                ?>
            </div>
            <div class="col-12 col-md-4 mt-2 bg-light">
                <div class="d-flex align-items-center bg-warning bg-gradient p-2 mb-2 justify-content-center">
                    <h2 class="fs-3 fw-bold m-0 text-white">Adminpanel</h2>
                </div>
                <table class="table align-middle">
                    <tr class="bg-2 text-white text-center fs-5"><th colspan="2">Statistics</th></tr>
                    <tr><td>Category selected:</td><td class="text-center"><?php echo isset($_GET['filter'])? $categories_arr[$_GET['filter']] : 'All'; ?></td></tr>
                    <tr><td>Posts in the database:</td><td class="text-center"><?php echo $records_number; ?></td></tr>
                    <tr><td>Last post created:</td><td class="text-center"><?php echo $last_post_date; ?></td></tr>
                </table>
                <table class="table align-middle">
                    <tr class="bg-2 text-white text-center fs-5"><th colspan="2">Categories</th></tr>
                    <?php
                    $cat_edit = '';  //  variable to identify the type of page (edit or create a post)
                    if (isset($_GET['cat_edit'])) {
                        $cat_edit = $_GET['cat_edit'];
                    } else {
                        $show_add_category_form = true;
                    }
                    // Build list of categories
                    foreach ($categories_arr as $key => $category_name) {
                        if (isset($_GET['filter']) && $_GET['filter'] == $key) {
                            $selected_class = 'class="bg-warning fw-bold"';
                            $selected_link = PG_ADMIN;
                        } else {
                            $selected_class = '';
                            $selected_link = PG_ADMIN . '?filter=' . $key;
                        }
                        if ($key == $cat_edit) {
                            echo '<tr><form action="edit-category.php" method="post"><td>' .
                                '<input type="text" class="form-control form-control-sm" name="category_new_name" value="'.$category_name.'"></td>' .
                                '<td><button type="submit" class="btn btn-sm btn-success" value="'.$key.'" name="category_edit_id"><i class="fas fa-check text-white"></i></button>' .
                                '<button type="submit" class="btn btn-sm btn-danger" value="cancel" name="category_edit_id"><i class="fas fa-times text-white"></i></button></td></form></tr>';
                        } else {
                            echo '<tr class="flex-nowrap"><td ' . $selected_class . '><a href="' . $selected_link . '" class="d-block text-decoration-none">' . $category_name. '</a></td>';
                            echo '<td><a href="' . PG_ADMIN . '?cat_edit=' . $key . '" class="text-decoration-none me-3"><i class="far fa-edit text-dark"></i>' .
                                '</a><a href="' . PG_DEL_CATEGORY . '?id=' . $key . '" class="text-decoration-none"><i class="fas fa-trash-alt text-danger"></i></a></td></tr>';
                        }
                    }
                    // Condition for showing the form of category addition
                    if (isset($show_add_category_form)) :
                        ?>
                        <tr>
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                <td><input class="form-control form-control-sm my-2" type="text" name="category_name"></td>
                                <td><button class="btn btn-sm btn-warning text-white text-nowrap" type="submit" name="submit"><i class="fas fa-plus me-2"></i>Add</button></td>
                            </form>
                        </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
</main>
<?php include(IN_FOOTER) ?>
</body>
</html>
