<?php
    // Code for deleting post by id and redirect to admin page
    require_once('vars.php');
    require_once('functions.php');
    // Delete posts cover if exists
    if ($_GET['cover']) {
        @unlink(MM_UPLOAD_PATH . $_GET['cover']);
    }
    // Delete post
    if ($_GET['id']) {
        $query = query_delete_post_by_id($_GET['id']);
        connect($query);
    }
    // Redirect to Admin Page
    header('Location: ' . PG_ADMIN);
?>