<?php
	ini_set('display_errors', '1');
    // Loading all constants
    require_once('vars.php');
    require_once('functions.php');

    // Start the session_start
    session_start();

    // If the session vars aren't set, try to set them with a cookie
    if (!isset($_SESSION['user_id'])) {
        if (isset($_COOKIE['user_id']) && isset($_COOKIE['username'])) {
            $_SESSION['user_id'] = $_COOKIE['user_id'];
            $_SESSION['username'] = $_COOKIE['username'];
        }
    }
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

    // Reset search filter
    if (isset($_POST['search_reset']) || (isset($_POST['user_search']) && trim($_POST['user_search']) == '')) {
        unset($_POST['user_search'], $_POST['search_reset']);
    }

    // Data for pagination (part 1)
    $cur_page = isset($_GET['page'])? $_GET['page'] : 1;
    $results_per_page = 5;
    $skip = (($cur_page - 1) * $results_per_page);

    // Connect to database and get posts data
    $query = query_get_all_posts();
    if (isset($_GET['filter'])) {
        $query .= " WHERE p.category_id = '" . $_GET['filter'] . "'";
    }

    // If is set search words
    if (isset($_POST['user_search'])) {
        $user_search = $_POST['user_search'];
        $where_user_search = generate_where_user_search($user_search);
        $query .= ' WHERE' . $where_user_search;
    }

    // Data for pagination (part 2)
    $data = connect($query);
    $records_number = mysqli_num_rows($data);
    $_SESSION['records_number'] = $records_number;
    $num_pages = ceil($records_number / $results_per_page);

    // Adding sorting order and limit to query
    $query .= " ORDER BY p.post_date DESC";
    $query .= " LIMIT $skip, $results_per_page";

    // Get posts data
    $data = connect($query);

?>
<main>
    <div class="container-xl m-auto flex-direction-column justify-content-space-around block">
        <div class="row block-row">
            <div class="col-12 col-md-4 order-2 mt-2 bg-light">
                <?php include(IN_MENU); ?>
            </div>
            <div class="col-12 col-md-8 order-1 my-2 content">
                <div class="posts-block flex-column">
                    <?php
                    while ($row = mysqli_fetch_array($data)) {
                        $category_link = $_SERVER['PHP_SELF'] . '?filter=' . $row['category_id'];
                    ?>
                    <article class="px-3 mb-3">
                        <a href="<?php echo PG_POST . '?post_id=' . $row['id']; ?>"><h3 class="display-6 text-weight-bold text-capitalize m-0 p-0"><?php echo $row['title']; ?></h3></a>
                        <div class="d-flex">
                            <a class="ms-1" href="<?php echo $category_link; ?>"><?php echo $row['name']; ?></a>
                            <p class="ms-auto"><em><?php echo date('M d, Y', strtotime($row['post_date'])); ?></em></p>
                        </div>
                        <div class="row bg-light">
                            <div class="col-12 col-md-6 col-lg-4 m-auto mb-3 py-0">
                                <img class="img-fluid" src="<?php echo $row['cover']? MM_UPLOAD_PATH . '/' . $row['cover'] : MM_DEFAULT_COVER; ?>" alt="cover">
                            </div>

                            <div class="col-12 col-md-6 col-lg-8 d-flex p-0">
                                <div class="me-2">
                                    <?php echo substr($row['post'], 0, 300) . '...'; ?>
                                </div>
                                <a href="<?php echo PG_POST . '?post_id=' . $row['id']; ?>" class="btn btn-info d-blog py-0 text-white fs-1 d-flex align-items-center"><i class="fas fa-angle-right"></i></a>
                            </div>
                        </div>
                    </article>
                    <?php
                    }
                    ?>
                </div>
                <?php
                // Add pagination
                if ($num_pages > 1) {
                    echo generate_page_links($cur_page, $num_pages);
                }
                ?>
            </div>
        </div>
    </div>
</main>
<?php include(IN_FOOTER); ?>
</body>
</html>
