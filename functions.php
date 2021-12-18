<?php
    /* General functions */

    // Function for database connection and query
    function connect($query) {
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $result = mysqli_query($dbc, $query);
        mysqli_close($dbc);
        return $result;
    }

    // Function for getting all categories (array)
    function get_categories_array($data) : array {
        $categories_arr = array();
        while ($row = mysqli_fetch_array($data)) {
            $categories_arr[$row['id']] = $row['name'];
        }
        return $categories_arr;
    }

    function get_post_by_id($id) {
        if(!empty($id)) {
            $query = query_get_post_by_id($id);
            return connect($query);
        } else {
            return '';
        }
    }

    // Function for pagination
    function generate_page_links ($cur_page, $num_pages) : string {
        $page_links = '<div class="paginator-block p-3"><nav aria-label="Pagination" class="d-flex align-items-baseline">'.
            '<ul class="pagination m-0">';

        // If it is not a first page than creating previous page link
        if ($cur_page > 1) {
            $page_links .= '<li class="page-item"><a class="page-link" href="' . $_SERVER['PHP_SELF'] . '?page=' . ($cur_page - 1) . '"><</a></li>';
        } else {
            $page_links .= '<li class="page-item disabled"><a class="page-link" href="#"><</a></li>';
        }

        // Creating page links
        for ($i = 1; $i <= $num_pages; $i++) {
            if ($cur_page == $i) {
                $page_links .= '<li class="page-item disabled"><a class="page-link fw-bold" href="' . $_SERVER['PHP_SELF'] . '?page=' . $i . '">' . $i . '</a></li>';
            } else {
                $page_links .= '<li class="page-item"><a class="page-link" href="' . $_SERVER['PHP_SELF'] . '?page=' . $i . '">' . $i . '</a></li>';
            }
        }

        // If it is not a last page than creating next page link
        if ($cur_page < $num_pages) {
            $page_links .= '<li class="page-item"><a class="page-link" href="' . $_SERVER['PHP_SELF'] . '?page=' . ($cur_page + 1) . '">></a></li>';
        } else {
            $page_links .= '<li class="page-item disabled"><a class="page-link" href="#">></a></li>';
        }

        // Ending page links
        $page_links .= '</ul><p class="ms-auto">Page ' . $cur_page . ' of ' . $num_pages . '</p></nav></div>';

        return $page_links;
    }

    // Function for getting a query part of serach words conditions
    function generate_where_user_search ($user_search) : string {
        // Extracting search criteria into an array
        $clean_search = str_replace(',', ' ', $user_search);
        $search_words = explode(' ', $clean_search);
        $final_search_words = array();
        if (count($search_words) > 0) {
            foreach ($search_words as $word) {
                $final_search_words[] = $word;
            }
        }

        // Creating WHERE condition list
        $where_list = array();
        if (count($final_search_words) > 0) {
            foreach ($final_search_words as $word) {
                $where_list[] = " p.post LIKE '%$word%' OR p.title LIKE '%$word%'";
            }
        }
        return $where_clause = implode(' OR ', $where_list);
    }

    /* Queries */

    // Categories

    function query_get_all_categories() : string {
        return "SELECT id, name FROM categories";
    }

    function query_delete_category_by_id($id) : string {
        return "DELETE FROM categories WHERE id = '" . $id . "' LIMIT 1";
    }

    function query_update_category_by_id($id, $new_name) : string {
        return "UPDATE categories SET name='$new_name' WHERE id='$id'";
    }

    function query_insert_new_category_by_name($name) : string {
        return "INSERT INTO categories (name) VALUES ('" . $name . "')";
    }

    // Posts

    function query_get_all_posts() : string {
        return "SELECT p.id, p.title, p.post, p.post_date, p.cover, p.category_id, c.name, u.username FROM posts AS p INNER JOIN categories AS c ".
            "ON p.category_id = c.id INNER JOIN users AS u ON u.id = p.user_id";
    }

    function query_get_post_by_id($id) : string {
        return "SELECT p.id, p.title, p.post, p.post_date, p.cover, p.category_id, c.name, u.username FROM posts AS p INNER JOIN categories AS c ".
            "ON p.category_id = c.id INNER JOIN users AS u ON u.id = p.user_id WHERE p.id = '$id' LIMIT 1";
    }

    function query_delete_post_by_id($id) : string {
        return "DELETE FROM posts WHERE id = '" . $id . "' LIMIT 1";
    }

    function query_update_post_with_new_cover($post_id, $title, $post_date, $post, $cover, $category_id) :string {
        return "UPDATE posts SET title='$title', post_date='$post_date', post='$post', cover='$cover', category_id='$category_id' WHERE id='" . $post_id . "'";
    }

    function query_update_post_without_cover($post_id, $title, $post_date, $post, $category_id) :string {
        return "UPDATE posts SET title='$title', post_date='$post_date', post='$post', category_id='$category_id' WHERE id='" . $post_id . "'";
    }

    function query_insert_post_with_new_cover($user_id, $title, $post_date, $post, $cover, $category_id) :string {
        return "INSERT INTO posts (user_id, title, post_date, post, cover, category_id) VALUES ('$user_id', '$title', '$post_date', '$post', '$cover', '$category_id')";
    }

    function query_insert_post_without_cover($user_id, $title, $post_date, $post, $category_id) :string {
        return "INSERT INTO posts (user_id, title, post_date, post, cover, category_id) VALUES ('$user_id', '$title', '$post_date', '$post', '$category_id')";
    }

    // Users

    function query_login($user_username, $user_password) : string {
        return "SELECT id, username FROM users WHERE username = '$user_username' AND password = SHA('$user_password')";
    }

    function query_get_user_by_username($username) : string {
        return "SELECT * FROM users WHERE username = '$username'";
    }

    function query_insert_new_user($username, $password) : string {
        return "INSERT INTO users (username, password) VALUES ('$username', SHA('$password'))";
    }
?>
