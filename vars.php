<?php
    // Define application constants
    define('MM_UPLOAD_PATH', 'images/posts/');
    define('MM_MAX_FILE_SIZE', 5242880);      // 5 Mb
    define('MM_MAX_IMG_WIDTH', 1200);        // 1200 pixels
    define('MM_MAX_IMG_HEIGHT', 1200);       // 1200 pixels
    define('MM_DEFAULT_COVER', 'images/posts/default.jpg');   // default profile photo
    define('IN_HEAD', 'includes/head.html');  // head part path
    define('IN_HEADER', 'includes/header.php');  // header part path
    define('IN_MENU', 'includes/menu.php');  // menu part path
    define('IN_FOOTER', 'includes/footer.html');   // footer path
    define('PG_HOME', 'index.php'); // homepage path
    define('PG_POST', 'post.php'); // post path
    define('PG_ADMIN', 'admin-blog.php'); // admin page path
    define('PG_LOGIN', 'login.php');  // login page path
    define('PG_LOGOUT', 'logout.php');  // logout page path
    define('PG_NEW_POST', 'edit-post.php');  // new-post page path
    define('PG_DEL_CATEGORY', 'delete_category.php');  // delete category path
    define('PG_DEL_POST', 'delete_post.php');  // delete category path
    // Connection data
    define('DB_HOST', 'localhost');
    define('DB_USER', 'zoomag_admin');
    define('DB_PASSWORD', 'blog123');
    define('DB_NAME', 'zoomag_blog');
?>