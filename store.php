<?php


$username = $password = $confirm_password = $website = $username1 = $id = $website1 = "";
$username_err = $password_err = $confirm_password_err = $website_err = $username1_err = $websiteandusername_err = "";

session_start();

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !==true)
{
    header("location: login.php");
}

error_reporting(E_ERROR | E_WARNING | E_PARSE);
require_once "config.php";

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == "POST"){
  $username = $_SESSION['username'];
  $website1 = $_POST['website'];


if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$website1)) {
  $website_err = "Website cannot be blank";
    echo '<script type="text/javascript">

        window.onload = function () { alert("Website format incorrect. Please enter the website in right format."); }

    </script>';
}


  if(empty(trim($_POST["website"]))){
    $website_err = "Website cannot be blank";
    echo '<script type="text/javascript">

        window.onload = function () { alert("Website field cannot be empty. Please enter the website."); }

    </script>';
}
else if(empty(trim($_POST["username1"]))){
  $username1_err = "Username cannot be blank";
  echo '<script type="text/javascript">

      window.onload = function () { alert("Username cannot be empty. Please enter the username."); }

  </script>';
}


else{
  $sql = "SELECT * FROM retrieve WHERE website = ? AND username1 = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $param_website, $param_username1);

        $param_website = trim($_POST['website']);
        $param_username1 = trim($_POST['username1']);

        if(mysqli_stmt_execute($stmt)){
            mysqli_stmt_store_result($stmt);
            if(mysqli_stmt_num_rows($stmt) > 0) {
                $websiteandusername_err = "This username already has an entry for this website.";
                echo '<script type="text/javascript">
                window.onload = function () { alert("This username already has an entry for this website. Please use a different username to continue."); }
                </script>';
            }
            else{
                $username1 = trim($_POST['username1']);
                $website = trim($_POST['website']);
            }
        }
        else{
            echo "Something went wrong";
            echo '<script type="text/javascript">
            window.onload = function () { alert("Something went wrong. Please try again later."); }
            </script>';
        }
    }
    mysqli_stmt_close($stmt);
}
  $uppercase = preg_match('@[A-Z]@', trim($_POST['password']));
  $lowercase = preg_match('@[a-z]@', trim($_POST['password']));
  $number    = preg_match('@[0-9]@', trim($_POST['password']));
  $specialChars = preg_match('@[^\w]@', trim($_POST['password']));
  
  
  if(empty(trim($_POST['password']))){
      $password_err = "Password cannot be blank";
      echo '<script type="text/javascript">
              window.onload = function () { alert("Password cannot be blank"); }
          </script>';
  }
  elseif(strlen(trim($_POST['password'])) < 8 || !$uppercase || !$lowercase || !$number || !$specialChars){
      $password_err = "Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.";
      echo '<script type="text/javascript">
              window.onload = function () { alert("Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character."); }
          </script>';
  }
  
  else{
      $password = trim($_POST['password']);
  }
  
  if(trim($_POST['password']) !=  trim($_POST['confirm_password'])){
      $password_err = "Passwords should match";
      echo '<script type="text/javascript">
              window.onload = function () { alert("Passwords should match"); }
          </script>';
  }

$ciphering = "AES-128-CTR";
$iv_length = openssl_cipher_iv_length($ciphering);
$options = 0;
$encryption_iv = '1234567891011121';
$encryption_key = "HeapsV";
$encryption = openssl_encrypt($password, $ciphering, $encryption_key, $options, $encryption_iv);

  if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($website_err) && empty($username1_err) && empty($websiteandusername_err))
  {
      $sql = "INSERT INTO retrieve (username, website, username1, password) VALUES (?, ?, ?, ?)";
      $stmt = mysqli_prepare($conn, $sql);
      if ($stmt)
      {
          mysqli_stmt_bind_param($stmt, "ssss", $param_username, $param_website, $param_username1, $param_password);
  
          $param_username = $username;
          $param_website = $website;
          $param_username1 = $username1;
          $param_password = $encryption;
  
          if (mysqli_stmt_execute($stmt))
          {
              header("location: welcome.php");
          }
          else{
              echo "Something went wrong... cannot redirect!";
              echo '<script type="text/javascript">
              window.onload = function () { alert("Something went wrong... cannot redirect!"); }
          </script>';
          }
      
      mysqli_stmt_close($stmt);
  }
  }
  mysqli_close($conn);
  
}

?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
body {
  font-family: Arial, Helvetica, sans-serif;
  background-image:url(green.jpg);
  background-repeat: no-repeat;
  background-size:cover;
}
* {
  box-sizing: border-box;
}

.container {
  padding: 25px;
  background-color: white;
}

input[type=text], input[type=password] , input[type=username]{
  width: 100%;
  padding: 15px;
  margin: 5px 0 22px 0;
  display: inline-block;
  border: none;
  background: #f1f1f1;
}

input[type=text]:focus, input[type=password]:focus {
  background-color: #ddd;
  outline: none;
}

hr {
  border: 1px solid #f1f1f1;
  margin-bottom: 25px;
}

.registerbtn {
  background-color: #118570 ;
  color: white;
  padding: 16px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 50%;
  opacity: 0.9;
}

.registerbtn:hover {
  opacity: 1;
}

a {
  color: dodgerblue;
}

.signin {
  background-color: #f1f1f1;
  text-align: left;
}
</style>

</head>
<body  style="padding-left: 200px;padding-right: 200px;">
    <br><br><br><br>
<form method="post" action="">
  <div class="container">
    <h1>Store. <?php echo "Hi, ". $_SESSION['username']?></h1>
    <hr>
    <label for="inputEmail4"><b>Website</b></label><br>
    <input type="text" class="form-control" placeholder="Enter Website Name" name="website" id="website" autocomplete="off" required>
<br>
    <label for="username"><b>Username</b></label><br>
    <input type="username" class="form-control" placeholder="Enter Username" name="username1" id="username1" autocomplete="off" required>
<br>
    <label for="psw"><b>Password (It must contain atleast 8 characters long 1 symbol, 1 numeric and a Capital Letter)</b></label><br>
    <input type="password" class="form-control" placeholder="Enter Password" name="password" id="password" autocomplete="off" required>
<br>
    <label for="psw-repeat"><b>Repeat Password</b></label><br>
    <input type="password" class="form-control" placeholder="Repeat Password" name="confirm_password" id="psw-repeat" autocomplete="off" required>
<br>
   <center> <input type="submit" value="Submit" class="registerbtn" id="submit"></center>
  </div>

</form>
<br>
<br>
<Br>
</body>
</html>