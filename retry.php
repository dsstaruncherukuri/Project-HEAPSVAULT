<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
require_once "config.php";

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == "POST"){
    $username = $_POST["username"];

    $sql = "SELECT * FROM pinc WHERE username='$username'";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        header("Location: retry2.php?username=".urlencode($username));
        exit();
    } else {
        echo '<script type="text/javascript">
                window.onload = function () { alert("Username does not Exist. Please recheck and enter."); }
            </script>';
    }
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset</title>
    <link rel="stylesheet" href="retry-style.css">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,700&display=swap" rel="stylesheet">
</head>
<body>
    <main>
        <div class="background">
            <div class="text">
                <h1>Reset</h1>
            </div>
            <div class="box">
                <form id="foam" class="form" method="post" action="">
                    <input type="text" name="username" id="username" class="username" placeholder="Username" autocomplete="off" required>
                    <input type="submit" class="button" value="Reset">
                </form>
            </div>
        </div>
    </main>
</body>
</html>