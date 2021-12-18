<?php
    // Loading all constants
    require_once('vars.php');
    require_once('functions.php');

    // Clear the error message
    $error_msg = "";

    // Try to signup
    if (isset($_POST['submit'])) {
        // Connect to the database
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        // Grab the profile data from the POST
        $username = mysqli_real_escape_string($dbc, trim($_POST['username']));
        $password1 = mysqli_real_escape_string($dbc, trim($_POST['password1']));
        $password2 = mysqli_real_escape_string($dbc, trim($_POST['password2']));
        mysqli_close($dbc);

        if (!empty($username) && !empty($password1) && !empty($password2) && ($password1 == $password2)) {
            // Make sure someone isn't already registered using this username
            $query = query_get_user_by_username($username);
            $data = connect($query);
            if (mysqli_num_rows($data) == 0) {
                // The username is unique, so insert the data into the database
                $query = query_insert_new_user($username, $password1);
                connect($query);

                // Redirect to login page with success message
                $error_msg = 'Your new account has been successfully created. Now just log in.';
                header('Location: ' . PG_LOGIN . '?msg=' .$error_msg);
            }
            else {
                // An account already exists for this username, so display an error message
                $error_msg  = "An account already exists for this username. Please use a different address.";
                $username = "";
            }
        }
        else {
            $error_msg = "You must enter all of the sign-up data, including the desired password twice.";
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
    <title>Web Blog - Sign Up</title>
</head>
<body class="bg-1">
<div class="container mt-5">

    <form class="mx-auto my-5 p-3 bg-2 form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">

        <fieldset>
            <a href="<?php echo PG_HOME; ?>" class="text-decoration-none d-inline-flex bg-dark bg-gradient rounded-circle p-4 logo">
                <i class="fas fa-blog fs-1 text-white"></i>
            </a>
            <h1 class="fs-2 mb-4 text-center text-white">Sign Up</h1>
            <?php
            // Show any error messages if exists
            if (!empty($error_msg)) {
                echo '<p class="text-white text-center"><i class="fas fa-exclamation-triangle me-2"></i>' . $error_msg . '</p>';
            }
            ?>
            <div class="input-group row mx-0 my-3">
                <span class="col-5 input-group-text bg-warning text-white">Username:</span>
                <input class="col-7 form-control" type="text" name="username" value="<?php if (!empty($username)) echo $username; ?>">
            </div>

            <div class="input-group row mx-0 my-3">
                <span class="col-5 input-group-text bg-warning text-white">Password:</span>
                <input class="col-7 form-control" type="password" id="password1" name="password1">
            </div>

            <div class="input-group row mx-0 my-3">
                <span class="col-5 input-group-text bg-warning text-white">Password (retype):</span>
                <input class="col-7 form-control" type="password" id="password2" name="password2">
            </div>
        </fieldset>
        <div class="d-flex justify-content-between align-items-center my-5">
            <input class="btn btn-warning px-4" type="submit" value="Sign Up" name="submit">
            <span class="text-white">or</span>
            <a class="btn btn-outline-warning px-4" href="login.php">Log In</a>
        </div>
        <p class="text-light d-flex justify-content-center m-0">&copy Created by &nbsp<a href="https://ivan4usa.com" target="_blank" class="text-warning">Ivan Pol</a></p>
    </form>
</div>
</body>
</html>
