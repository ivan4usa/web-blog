<?php
    // Code for editing selected category
    require_once('vars.php');
    require_once('functions.php');
    // Update category
    if (isset($_POST['category_edit_id']) && isset($_POST['category_new_name'])) {
        if ($_POST['category_edit_id'] != 'cancel' && is_numeric($_POST['category_edit_id']) && !empty(trim($_POST['category_new_name']))) {
            $query = query_update_category_by_id($_POST['category_edit_id'], $_POST['category_new_name']);
            connect($query);
        }
    }
    // Redirect to Admin Page
    header('Location: ' . PG_ADMIN);
?>