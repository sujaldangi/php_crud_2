<?php
$servername = "localhost";
$username = "root";
$password = "";
// connect to my php admin
$conn = mysqli_connect($servername, $username, $password);
// check if the connection was successfull
if (!$conn) {
    die("" . mysqli_connect_error());
} else {
    echo '<div class="alert alert-success alert-dismissible fade showfade show" role="alert"><strong>Connected to database</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
}
$query = 'CREATE DATABASE IF NOT EXISTS user_data ';
$result = mysqli_query($conn, $query);
if (!$result) {
    die('' . mysqli_error($conn));
}
$query = 'CREATE TABLE IF NOT EXISTS `user_data`.`information` (`Id` INT(10) PRIMARY KEY AUTO_INCREMENT ,`username` VARCHAR(50) NOT NULL , `first_name` VARCHAR(50) NOT NULL , `last_name` VARCHAR(50) NOT NULL , `city` VARCHAR(50) NOT NULL , `state` VARCHAR(50) NOT NULL , `zip` INT(10) NOT NULL, `date-time` DATETIME NOT NULL , UNIQUE KEY (`username`)) ENGINE = InnoDB; ';
$result = mysqli_query($conn, $query);
if (!$result) {
    die('' . mysqli_error($conn));
}
// check if the form method was post
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // create table in the database
    // check if the post method is sent with element named insert
    if (isset($_POST['insert'])) {
        // collect the input form data
        $first_name = $_POST["fname"];
        $last_name = $_POST["lname"];
        $user_name = $_POST["username"];
        $city = $_POST["city"];
        $state = $_POST["state"];
        $zip = $_POST["zip"];
        // select query to check if the user already exists
        $check_sql = "SELECT * FROM `user_data`.`information` WHERE `username` = '$user_name'";
        $check_result = mysqli_query($conn, $check_sql);
        $numer_row = mysqli_num_rows($check_result);
        if ($numer_row > 0) {
            echo '<div class="alert alert-warning alert-dismissible fade show" role="alert"><strong>Username already exists</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        } else {
            $insert_sql = "INSERT INTO `user_data`.`information` (`username`, `first_name`,`last_name`,`city`,`state`,`zip`,`date-time`) VALUES ('$user_name', '$first_name', '$last_name', '$city', '$state', '$zip', NOW())";
            $insert_result = mysqli_query($conn, $insert_sql);
            if (!$insert_result) {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>An error occurred:</strong> ' . mysqli_error($conn) . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            }
        }
    }
    // check if the post method is sent with element named delete
    if (isset($_POST['delete'])) {
        $first_name = $_POST["fname"];
        $last_name = $_POST["lname"];
        $user_name = $_POST["username"];
        $city = $_POST["city"];
        $state = $_POST["state"];
        $zip = $_POST["zip"];
        $delete_username = $_POST['delete_username'];
        $delete_sql = "DELETE FROM `user_data`.`information` WHERE `username`='$delete_username'";
        $delete_result = mysqli_query($conn, $delete_sql);
        if (!$delete_result) {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>An error occurred while deleting:</strong> ' . mysqli_error($conn) . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        } else {
            echo '<div class="alert alert-success fade show" role="alert"><strong>User deleted successfully</strong></div>';
        }

        header("Location: index.php");
    }
    // check if the post method is sent with element named update
    if (isset($_POST['update'])) {

        $edited_fname = $_POST['edited_fname'];
        $edited_lname = $_POST['edited_lname'];
        $edited_city = $_POST['edited_city'];
        $edited_state = $_POST['edited_state'];
        $edited_zip = $_POST['edited_zip'];
        $edit_username = $_POST['edited_username'];
        $update_query = "UPDATE `user_data`.`information` SET
                        `first_name`='$edited_fname',
                        `last_name`='$edited_lname',
                        `city`='$edited_city',
                        `state`='$edited_state',
                        `zip`='$edited_zip',
                        `date-time`=NOW()
                        WHERE `username`='$edit_username'";
        $update_result = mysqli_query($conn, $update_query);
        if (!$update_result) {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>An error occurred while updating:</strong> ' . mysqli_error($conn) . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        } else {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>User updated successfully</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            echo '<script>window.opener.location.reload();</script>';
        }
    }
}
?>
<!DOCTYPE html>

<head>
    <title>CRUD</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-color: beige;
        }
    </style>
    <!-- js function to confirm user deletion -->
    <script>
        function confirmDelete() {
            return confirm('Are you sure you want to delete this user?');
        }
    </script>
</head>

<body>
    <!-- button that directs to the aduser webpage -->
    <h1>&nbspUSER DATA</h1>
    <a href="./adduser.php" class="btn btn-dark">Add new user</a>
    <?php
    // table creation to show the data in the database
    echo "<table class='table'><thead><tr><th scope='col'>Id</th><th scope='col'>Username</th><th scope='col'>First_name</th><th scope='col'>Last_name</th><th scope='col'>City</th><th scope='col'>State</th><th scope='col'>Zip</th><th scope='col'>date-time</th><th scope='col'>Action</th></tr></thead>";
    $sql = 'SELECT * FROM `user_data`.`information`';
    $result = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($result);
    if ($num > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $i = $row["Id"];
            $u = $row["username"];
            $f = $row["first_name"];
            $l = $row["last_name"];
            $c = $row["city"];
            $s = $row["state"];
            $z = $row["zip"];
            $d = $row["date-time"];
            echo "<tbody><tr><td>$i</td><td>$u</td><td>$f</td><td>$l</td><td>$c</td><td>$s</td><td>$z</td><td>$d</td><td>
                        <form method='post' style='display: inline;' onsubmit='return confirmDelete();'>
                            <input type='hidden' name='delete_username' value='$u'>
                            <button type='submit' name='delete'>Del</button>
                        </form>
                        <form method='post' style='display: inline;' action='edit.php' >
                            <input type='hidden' name='edit_username' value='$u'>
                            <button type='submit' name='edit'>Edit</button>
                        </form>
    </td></tr>";
        }
    }
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>