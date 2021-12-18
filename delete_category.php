<?php
    // Code for deleting category by id and redirect to admin page
    echo $_GET['id'];
    require_once('vars.php');
    require_once('functions.php');

    if (isset($_GET['id'])) {
        $query = query_delete_category_by_id($id);
        connect($query);
    }
    header('Location: ' . PG_ADMIN);
?>