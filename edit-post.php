<?php
    // Loading all constants
    require_once('vars.php');
    require_once('functions.php');

    // Start the session_start
    session_start();

    // Defence page
    require_once('defence.php');

    // Clear the error message or get it from $_GET global variable
    if (isset($_GET['msg'])) {
        $error_msg = $_GET['msg'];
    } else {
        $error_msg = "";
    }
?>

<!doctype html>
<html lang="en">
<head>
    <?php include(IN_HEAD); ?>
    <script src="https://cdn.tiny.cloud/1/sfm6cle65ve7y2hlb1rclz253mfrvdxuiuwkdhhcrzi1nb4h/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <title>Web Blog - Home Page</title>
</head>
<body>
<?php
    // Get array of categories
    $query = query_get_all_categories();
    $data = connect($query);
    $categories_arr = get_categories_array($data);

    // Get post by id
    if (isset($_GET['id']) || isset($_POST['post_id'])) {
        $post_id = isset($_GET['id'])? $_GET['id'] : $_POST['post_id'];

        if (isset($_GET['id'])) {
            $query = query_get_post_by_id($_GET['id']);
            $post_data = connect($query);

            if ($post_data && mysqli_num_rows($post_data) > 0) {
                $row = mysqli_fetch_array($post_data);
                $post_title = $row['title'];
                $post_category = $row['category_id'];
                $post_text = $row['post'];
                $post_cover = $row['cover'];
            }
        }
    } else {
        $post_id = '';
    }

    // Starting activities on clicking submit button
    if (isset($_POST['submit'])) {
        // Grab the profile data from the POST
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $category = $_POST['category']? mysqli_real_escape_string($dbc, trim($_POST['category'])) : '';
        $title = $_POST['title']? mysqli_real_escape_string($dbc, trim($_POST['title'])) : '';
        $post_date = date('Y-m-d-h-i-s');
        $post = $_POST['post']? mysqli_real_escape_string($dbc, trim($_POST['post'])) : '';
        $old_picture = $_POST['old_picture']? mysqli_real_escape_string($dbc, trim($_POST['old_picture'])) : '';
        $new_picture = $_FILES['new_picture']? mysqli_real_escape_string($dbc, trim($_FILES['new_picture']['name'])) : '';
        $new_picture_type = $_FILES['new_picture']? $_FILES['new_picture']['type'] : '';
        $new_picture_size = $_FILES['new_picture']? $_FILES['new_picture']['size'] : '';
        if (!empty($new_picture)) {
            list($new_picture_width, $new_picture_height) = getimagesize($_FILES['new_picture']['tmp_name']);
        }
        mysqli_close($dbc);
        $error = false;

        // Validate and move the uploaded picture file, if necessary
        if (!empty($new_picture)) {
            if ((($new_picture_type == 'image/gif') || ($new_picture_type == 'image/jpeg') || ($new_picture_type == 'image/pjpeg') ||
                    ($new_picture_type == 'image/png')) && ($new_picture_size > 0) && ($new_picture_size <= MM_MAX_FILE_SIZE) &&
                ($new_picture_width <= MM_MAX_IMG_WIDTH) && ($new_picture_height <= MM_MAX_IMG_HEIGHT)) {
                if ($_FILES['new_picture']['error'] == 0) {
                    // Move the file to the target upload folder
                    $target = MM_UPLOAD_PATH . basename($new_picture);
                    if (move_uploaded_file($_FILES['new_picture']['tmp_name'], $target)) {
                        // The new picture file move was successful, now make sure any old picture is deleted
                        if (!empty($old_picture) && ($old_picture != $new_picture)) {
                            @unlink(MM_UPLOAD_PATH . $old_picture);
                        }
                    } else {
                        // The new picture file move failed, so delete the temporary file and set the error flag
                        @unlink($_FILES['new_picture']['tmp_name']);
                        $error = true;
                        $error_msg = 'Sorry, there was a problem uploading your picture.';
                    }
                }
            } else {
                // The new picture file is not valid, so delete the temporary file and set the error flag
                @unlink($_FILES['new_picture']['tmp_name']);
                $error = true;
                $error_msg = 'Your picture must be a GIF, JPEG, or PNG image file no greater than ' . (MM_MAX_FILE_SIZE / 1024) .
                    ' KB and ' . MM_MAX_IMG_WIDTH . 'x' . MM_MAX_IMG_HEIGHT . ' pixels in size.';
            }
        }

        if (!$error) {
            // Update or Insert the profile data in the database
            if (!empty($category) && !empty($title) && !empty($post)) {
                // Build query
                if (!empty($post_id)) {
                    // Only set the picture column if there is a new picture
                    if (!empty($new_picture)) {
                        $query = query_update_post_with_new_cover($post_id, $title, $post_date, $post, $new_picture, $category);
                    } else {
                        $query = query_update_post_without_cover($post_id, $title, $post_date, $post, $category);
                    }
                } else {
                    // Only set the picture column if there is a new picture
                    if (!empty($new_picture)) {
                        $query = query_insert_post_with_new_cover($_SESSION['user_id'], $title, $post_date, $post, $new_picture, $category);
                    } else {
                        $query = query_insert_post_without_cover($_SESSION['user_id'], $title, $post_date, $post, $category);
                    }
                }
                // Run query
                connect($query);

                // Confirm success with the user
                if (!empty($post_id)) {
                    $success_msg = 'The post has been successfully changed.';
                } else {
                    $success_msg = 'New post has been successfully created.';
                }
                $category = '';
                $title = '';
                $post = '';
                $old_picture = '';
                $new_picture = '';
                $post_id = '';
            } else {
                $error_msg = 'You must enter all of the profile data (the picture is optional).';
            }
        }
    }
?>

<header class="bg-dark bg-gradient">
    <div class="container-xl h-100 d-flex align-items-center">
        <a class="d-flex align-items-center text-decoration-none" href="index.php"><i class="fas fa-blog fs-2 text-white"></i><h1 class="display-5 ms-2 my-0 text-white">My Blog</h1></a>
        <a class="d-flex align-items-center btn ms-auto text-warning" href="<?php echo PG_ADMIN; ?>"><i class="fas fa-user-cog me-2"></i>Admin</a>
        <a class="d-flex align-items-center btn text-warning" href="<?php echo PG_LOGOUT; ?>"><i class="fas fa-sign-in-alt me-2"></i>Log Out</a>
    </div>
</header>
<main>
    <div class="container-xl m-auto">
        <form class="mx-auto my-2 bg-2" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
            <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MM_MAX_FILE_SIZE; ?>" />
            <fieldset>
                <legend class="fs-3 mb-5 text-center text-white bg-warning">
                    <?php if (!empty($post_id)) : ?>
                    Edit Post
                    <?php else : ?>
                    New Post
                    <?php endif; ?>
                </legend>
                <?php
                    // Show any error messages
                    if (!empty($error_msg)) {
                        echo '<p class="p-3 border text-center text-white fs-3"><i class="fas fa-exclamation-triangle me-1"></i>' . $error_msg . '</p>';
                    } elseif (isset($success_msg) && !empty($success_msg)) {
                        echo '<p class="p-3 border text-center text-white fs-3"><i class="fas fa-check-circle me-1"></i>' . $success_msg . '</p>';
                    }
                ?>
                <div class="p-3">
                    <h2 class="text-white fs-3">Category:</h2>
                    <select class="form-select mb-3 fs-5" aria-label="Select category" name="category">
                        <option selected>Select category</option>
                        <?php
                            foreach ($categories_arr as $arr_key => $arr_value) {
                                if (isset($category) && $category == $arr_key) {
                                    $selected = 'selected';
                                } elseif (isset($post_category) && ($arr_key == $post_category)) {
                                    $selected = 'selected';
                                } else {
                                    $selected = '';
                                }
                                echo '<option value="' .  $arr_key . '" ' . $selected . '>' . $arr_value . '</option>';
                            }
                        ?>
                    </select>

                    <div class="mb-3">
                        <label for="title" class="form-label text-white fs-3 mb-2">Title of post:</label>
                        <input type="text" class="form-control fs-5" id="title" placeholder="Enter Title" name="title"
                               value="<?php echo !empty($title)? $title : (isset($post_title)? $post_title : ''); ?>">
                    </div>

                    <input type="hidden" name="old_picture" value="<?php echo !empty($old_picture)? $old_picture : (isset($post_cover)? $post_cover : ''); ?>" />
                    <input type="hidden" name="post_id" value="<?php echo isset($post_id)? $post_id : '' ?>">

                    <div class="mb-3">
                        <label for="new_picture" class="form-label text-white fs-3 mb-2">Cover Image:</label>
                        <input type="file" class="form-control fs-5" id="new_picture" name="new_picture">
                        <?php if (!empty($old_picture)) {
                            echo '<img class="img-fluid my-2" src="' . MM_UPLOAD_PATH . $old_picture . '" alt="Post Picture" />';
                        } elseif (isset($post_cover)) {
                            echo '<img class="img-fluid my-2" src="' . MM_UPLOAD_PATH . $post_cover . '" alt="Post Picture" />';
                        } else {
                            echo '';
                        } ?>
                    </div>

                    <h2 class="text-white fs-3">Post:</h2>
                    <div class="mb-3 bg-light">
                        <textarea class="form-control bg-light fs-5" id="post" rows="5" name="post">
                            <?php echo !empty($post)? $post : (isset($post_text)? $post_text : ''); ?>
                        </textarea>
                    </div>
                </div>


            </fieldset>
            <div class="d-flex justify-content-around align-items-center my-5 p-3">
                <input class="btn btn-warning px-5 fs-5" type="submit" value="Submit" name="submit">
                <input class="btn btn-outline-warning px-5 fs-5" type="submit" value="Reset" name="reset">
                <a class="btn btn-outline-warning px-5 fs-5" href="<?php echo PG_ADMIN; ?>">To Home</a>
            </div>
        </form>
    </div>
</main>
<script>
    tinymce.init({
        selector: 'textarea',
        plugins: 'a11ychecker advcode casechange formatpainter linkchecker autolink lists checklist media mediaembed pageembed permanentpen powerpaste table advtable tinycomments tinymcespellchecker',
        toolbar: 'a11ycheck addcomment showcomments casechange checklist code formatpainter pageembed permanentpen table',
        toolbar_mode: 'floating',
        tinycomments_mode: 'embedded',
        tinycomments_author: 'Author name',
    });
</script>
<?php include(IN_FOOTER); ?>
</body>
</html>