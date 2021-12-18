<?php
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
    <title>Web Blog - Post</title>
</head>
<body>

<?php
    include(IN_HEADER);

    // Getting post
    isset($_GET['post_id'])? $post_id = $_GET['post_id'] : $post_id = '';
    $data = get_post_by_id($post_id);
    if (!empty($data)) {
        while ($row = mysqli_fetch_array($data)) {
            $post_title = $row['title'];
            $category_name = $row['name'];
            $post_date = date('M d, Y', strtotime($row['post_date']));
            $post_text = $row['post'];
            $post_cover = $row['cover'];
            $post_author = $row['username'];
        }
    }
?>
<main>
    <div class="container-xl m-auto flex-direction-column justify-content-space-around block">
        <div class="row block-row">
            <div class="col-12 col-md-4 order-2 px-0 mt-2 bg-light">
                <h2 class="h4 p-2 m-0 bg-dark bg-gradient text-white">About post</h2>
                <nav class="mb-2">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item fs-5"><b>Title: </b><?php echo isset($post_title)? $post_title : ''; ?></li>
                        <li class="list-group-item fs-5"><b>Topic: </b><?php echo isset($category_name)? $category_name : ''; ?></li>
                        <li class="list-group-item fs-5"><b>Author: </b><?php echo isset($post_author)? $post_author : ''; ?></li>
                        <li class="list-group-item fs-5"><b>Date: </b><?php echo isset($post_date)? $post_date : ''; ?></li>
                        <li class="list-group-item fs-5"><b>Length: </b><?php echo isset($post_text)? iconv_strlen($post_text) : '0'; ?></li>
                        <li class="list-group-item fs-5"><b>ID in database: </b><?php echo isset($post_id)? $post_id : ''; ?></li>
                    </ul>
                </nav>
            </div>
            <div class="col-12 col-md-8 order-1 px-0 my-2 content">
                <div class="px-3">
                    <h1 class="display-5 text-weight-bold text-capitalize"><?php echo isset($post_title)? $post_title : 'No Title'?></h1>
                    <div class="d-flex">
                        <em><?php echo isset($category_name)? $category_name : 'No Category'?></em>
                        <p class="ms-auto"><em><?php echo isset($post_date)? $post_date : 'No Date'?></em></p>
                    </div>
                    <img src="<?php echo isset($post_cover)? MM_UPLOAD_PATH . '/' . $post_cover : MM_DEFAULT_COVER ?>" alt="cover" class="img-fluid">
                    <p class="fs-5 pt-2"><?php echo isset($post_text)? $post_text : 'No Post'?></p>
                    <a href="<?php echo PG_HOME?>" class="btn btn-info d-blog text-white py-0 my-4">Back to Home Page</a>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include(IN_FOOTER) ?>
</body>
</html>