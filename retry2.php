<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
require_once "config.php";

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET["username"])) {
    $username1 = $_GET["username"];
} else {
    header("Location: retry.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == "POST"){
$sql = "SELECT sq1,sq2 FROM pinc WHERE username = '$username1'";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $sqv1 = $row["sq1"];
    $sqv2 = $row["sq2"];

    $sqv3 = $_POST['sq1'];
    $sqv4 = $_POST['sq2'];

    if(password_verify($sqv3, $sqv1) && password_verify($sqv4, $sqv2)) {
        $uppercase = preg_match('@[A-Z]@', trim($_POST['pass']));
$lowercase = preg_match('@[a-z]@', trim($_POST['pass']));
$number    = preg_match('@[0-9]@', trim($_POST['pass']));
$specialChars = preg_match('@[^\w]@', trim($_POST['pass']));

if(empty(trim($_POST['pass']))){
    echo '<script type="text/javascript">
            window.onload = function () { alert("Password cannot be blank"); }
        </script>';
}
elseif(strlen(trim($_POST['pass'])) < 8 || !$uppercase || !$lowercase || !$number || !$specialChars){
    
    echo '<script type="text/javascript">
            window.onload = function () { alert("Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character."); }
        </script>';
}

else{
    $pass = trim($_POST['pass']);
$hashed_password = password_hash($pass, PASSWORD_BCRYPT);

$sql = "UPDATE pinc SET password='$hashed_password' WHERE username='$username1'";
if (mysqli_query($conn, $sql)) {
        header("Location: login.php");
} else {
    echo "Error updating password: " . mysqli_error($conn);
}
}

    }
    else{
        echo '<script type="text/javascript">alert("The Answers to the Security Questions are incorrect. Please wait till we redirect.");</script>';
    }


} else {
    header("Location: project.html");
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
    <link rel="stylesheet" href="style3.css">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,700&display=swap" rel="stylesheet">
</head>
<body>
    <main>
        <div class="background">
            <div class="text">
                <h1>Security Questions</h1>
            </div>
            <div class="box">
                <form class="form" method="post" action="">
                    <p><b>Security Question 1 - What was your favourite food as a child?</b></p> 
                    <input type="text" name="sq1" id="sq1" class="sq1" placeholder="Enter answer 1 here" autocomplete="off" required>
                    <p><b>Security Question 2 - What is your favourite colour?</b></p> 
                    <input type="text" name="sq2" id="sq2" class="sq2" placeholder="Enter answer 2 here" autocomplete="off" required>
                    <p><b>New Password - Enter your new password </b></p> 
                    <input type="password" name="pass" id="pass" class="pass" placeholder="Enter new password here" autocomplete="off" required>
                    <input type="submit" class="button" value="Reset">
                </form>
            </div>
        </div>
    </main>
</body>
</html>