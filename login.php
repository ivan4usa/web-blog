<?php
    // Loading all constants
    require_once('vars.php');
    require_once('functions.php');

    // Start the session_start
    session_start();

    // Clear the error message or get it from $_GET global variable
    if (isset($_GET['msg'])) {
        $error_msg = $_GET['msg'];
    } else {
        $error_msg = "";
    }

    // If the user isn't logged in, try to log them in
    if (!isset($_SESSION['user_id'])) {
        if (isset($_POST['submit'])) {
            // Connect to the database
            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

            // Grab the user-entered log-in data
            $user_username = mysqli_real_escape_string($dbc, trim($_POST['username']));
            $user_password = mysqli_real_escape_string($dbc, trim($_POST['password']));
            mysqli_close($dbc);

            // Trying login
            if (!empty($user_username) && !empty($user_password)) {
                // Look up the username and password in the database
                $query = query_login($user_username, $user_password);
                $data = connect($query);

                if (mysqli_num_rows($data) == 1) {
                    // The log-in is OK so set the user ID and username session vars (and cookies), and redirect to the home page
                    $row = mysqli_fetch_array($data);
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['username'] = $row['username'];
                    setcookie('user_id', $row['id'], time() + (60 * 60 * 24 * 30));    // expires in 30 days
                    setcookie('username', $row['username'], time() + (60 * 60 * 24 * 30));  // expires in 30 days
                }
                else {
                    // The username/password are incorrect so set an error message
                    $error_msg = 'Sorry, you must enter a valid username and password to log in.';
                }
            }
            else {
                // The username/password weren't entered so set an error message
                $error_msg = 'Sorry, you must enter your username and password to log in.';
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <?php
        // Including head data
        include(IN_HEAD);
    ?>
    <title>Web Blog - Login</title>
</head>
<body class="bg-1">
<div class="container mt-5">
    <form class="mx-auto my-5 p-3 bg-2 form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <a href="<?php echo PG_HOME; ?>" class="text-decoration-none d-inline-flex bg-dark bg-gradient rounded-circle p-4 logo">
            <i class="fas fa-blog fs-1 text-white"></i>
        </a>
        <h1 class="fs-2 mb-4 text-center text-white">Log In</h1>
        <?php
        // If the session var is empty, show any error message and the log-in form; otherwise confirm the log-in
        if (empty($_SESSION['user_id'])) {
        if(!empty($error_msg)) {
            echo '<p class="text-white text-center"><i class="fas fa-exclamation-triangle me-2"></i>' . $error_msg . '</p>';
        }
        ?>
        <div class="input-group my-3">
            <span class="input-group-text bg-warning"><i class="far fa-user-circle text-white"></i></span>
            <input class="form-control" type="text" name="username" value="<?php if (!empty($user_username)) echo $user_username; ?>">
        </div>

        <div class="input-group my-3">
            <span class="input-group-text bg-warning"><i class="fas fa-key text-white"></i></span>
            <input class="form-control" type="password" name="password">
        </div>
        <div class="d-flex justify-content-between align-items-center my-5">
            <input class="btn btn-warning px-4" type="submit" value="Log In" name="submit">
            <span class="text-white">or</span>
            <a class="btn btn-outline-warning px-4" href="signup.php">Sign Up</a>
        </div>
        <p class="text-light d-flex justify-content-center m-0">&copy Created by &nbsp<a href="https://ivan4usa.com" target="_blank" class="text-warning">Ivan Pol</a></p>
    </form>

    <?php
    }
    else {
        // Redirect to Previous page or to home page
        if (isset($_SESSION['current_page']) && !empty($_SESSION['current_page'])) {
            header('Location: ' . $_SESSION['current_page']);
        } else {
            header('Location: ' . PG_ADMIN);
        }
    }
    ?>
</div>
</body>
</html>
