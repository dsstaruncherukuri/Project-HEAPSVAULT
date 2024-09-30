<?php
require_once "config.php";

$username = $password = $confirm_password = $email = $sq1 = $sq2 = $pin1 = $pin2 = $pin3 = "";
$username_err = $password_err = $confirm_password_err = $email_err = $sq1_error = $sq2_error = $pinx_error = $spin_err = "";

if ($_SERVER['REQUEST_METHOD'] == "POST"){

    if(empty(trim($_POST["username"]))){
        $username_err = "Username cannot be blank";
        echo '<script type="text/javascript">

            window.onload = function () { alert("Username cannot be empty. Please enter the username."); }

        </script>';
    }
    else{
        $sql = "SELECT id FROM pinc WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if($stmt)
        {
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            $param_username = trim($_POST['username']);

            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1)
                {
                    $username_err = "This username is already taken"; 
                    echo '<script type="text/javascript">
            window.onload = function () { alert("Username already taken. Enter another username to continue."); }
        </script>';
                }
                else{
                    $username = trim($_POST['username']);
                }
            }
            else{
                echo "Something went wrong";
                echo '<script type="text/javascript">
            window.onload = function () { alert("Something went wrong. Please try later."); }
        </script>';
            }
        }
    mysqli_stmt_close($stmt);
}


if(empty(trim($_POST["email"]))){
  $email_err = "Email cannot be blank";
  echo '<script type="text/javascript">

      window.onload = function () { alert("Email cannot be empty. Please enter the email."); }

  </script>';
}
else{
  $sql = "SELECT id FROM pinc WHERE email = ?";
  $stmt = mysqli_prepare($conn, $sql);
  if($stmt)
  {
      mysqli_stmt_bind_param($stmt, "s", $param_email);

      $param_email = trim($_POST['email']);

      if(mysqli_stmt_execute($stmt)){
          mysqli_stmt_store_result($stmt);
          if(mysqli_stmt_num_rows($stmt) == 1)
          {
              $username_err = "This email is already taken"; 
              echo '<script type="text/javascript">
      window.onload = function () { alert("Email already taken. Enter another email to continue."); }
  </script>';
          }
          else{
              $email = trim($_POST['email']);
          }
      }
      else{
          echo "Something went wrong";
          echo '<script type="text/javascript">
      window.onload = function () { alert("Something went wrong. Please try later."); }
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


if(empty(trim($_POST["sq1"]))){
    $sq1_err = "Security Question 1 cannot be blank";
    echo '<script type="text/javascript">

        window.onload = function () { alert("Security Question cannot be empty. Please enter answer to the Security Question."); }

    </script>';
}
else{
    $sq1 = trim($_POST['sq1']);
}

if(empty(trim($_POST["sq2"]))){
    $sq2_err = "Security Question 2 cannot be blank";
    echo '<script type="text/javascript">

        window.onload = function () { alert("Security Question cannot be empty. Please enter answer to the Security Question."); }

    </script>';
}
else{
    $sq2 = trim($_POST['sq2']);
}


if(empty(trim($_POST["pin1"]))){
    $pin1_err = "PIN cannot be blank";
    echo '<script type="text/javascript">

        window.onload = function () { alert("PIN cannot be empty. Please enter the PIN."); }

    </script>';
}
else if(!is_numeric($_POST["pin1"])){
    $pin1_err = "PIN must be a number.";
    echo '<script type="text/javascript">

        window.onload = function () { alert("PIN must be a number."); }

    </script>';
}
else if(trim(strlen($_POST['pin1']))< 6 || strlen(trim($_POST['pin1'])) > 6 ){
    $pin1_err = "PIN size must be exaclty 6 digits.";
    echo '<script type="text/javascript">

        window.onload = function () { alert("PIN size must be exaclty 6 digits."); }

    </script>';
}
else{
    $pin1 = $_POST['pin1'];
}


$randomNumber = mt_rand(1000, 9999);
$ciphering = "AES-128-CTR";
$iv_length = openssl_cipher_iv_length($ciphering);
$options = 0;
$encryption_iv = '1234567891011121';
$encryption_key = "HeapsV";
$encryption = openssl_encrypt($randomNumber, $ciphering, $encryption_key, $options, $encryption_iv);

$pin4= $randomNumber.$pin1;
if(empty($pin1_err)){
    $sql = "SELECT id FROM pinc WHERE pin3 = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if($stmt)
    {
        mysqli_stmt_bind_param($stmt, "s", $param_pin3);
  
        $param_pin3 = $pin4;
  
        if(mysqli_stmt_execute($stmt)){
            mysqli_stmt_store_result($stmt);
            if(mysqli_stmt_num_rows($stmt) == 1)
            { 
                $spin_err = "PIN is not very unique.";
                echo '<script type="text/javascript">
        window.onload = function () { alert("Pin already taken please enter another one."); }
    </script>';
            }
            else{
                $pin3 = $pin4;
            }
        }
        else{
            echo "Something went wrong";
            echo '<script type="text/javascript">
        window.onload = function () { alert("Something went wrong. Please try later."); }
    </script>';
        }
    }
  
  
  mysqli_stmt_close($stmt);
  }
  else{
    echo '<script type="text/javascript">

    window.onload = function () { alert("Pin Size must be 6 Digits exactly."); }

</script>';
  }






if(empty($username_err) && empty($password_err) && empty($confirm_password_err) &&empty($email_err) &&empty($sq1_err) &&empty($sq2_err) &&empty($pin1_err) &&empty($pin2_err))
{
    $sql = "INSERT INTO pinc (username, password, email, sq1, sq2, pin1, pin2, pin3) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt)
    {
        mysqli_stmt_bind_param($stmt, "ssssssss", $param_username, $param_password, $param_email, $param_sq1, $param_sq2, $param_pin1, $param_pin2, $param_pin3);

        $param_username = $username;
        $param_email = $email;
        $param_sq1 = password_hash($sq1, PASSWORD_DEFAULT);
        $param_sq2 = password_hash($sq2, PASSWORD_DEFAULT);
        $param_pin1 = password_hash($pin1,PASSWORD_DEFAULT);
        $param_pin2 = $encryption;
        $param_pin3 = password_hash($pin3, PASSWORD_DEFAULT);
        $param_password = password_hash($password, PASSWORD_DEFAULT);

        if (mysqli_stmt_execute($stmt))
        {
            header("location: login.php");
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
  background-image:url('https://images.unsplash.com/photo-1597773150796-e5c14ebecbf5?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8NXx8Ymx1ZSUyMGFic3RyYWN0fGVufDB8fDB8fA%3D%3D&w=1000&q=80');
}
* {
  box-sizing: border-box;
}

.container {
  padding: 25px;
  background-color: white;
}

input[type=text], input[type=password] , input[type=email]{
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
  background-color: rgb(7, 108, 139) ;
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
    <br><br><br>
<form method="post" action="">
  <div class="container">
    <h1>Register</h1>
    <hr>
    <label for="inputEmail4"><b>Username</b></label><br>
    <input type="text" class="form-control" placeholder="Enter Username" name="username" id="username" autocomplete="off" required>
<br>
    <label for="email"><b>Email</b></label><br>
    <input type="email" class="form-control" placeholder="Enter Email" name="email" id="email" autocomplete="off" required>
<br>
    <label for="psw"><b>Password (It must contain atleast 8 characters long 1 symbol, 1 numeric and a Capital Letter)</b></label><br>
    <input type="password" class="form-control" placeholder="Enter Password" name="password" id="password" autocomplete="off" required>
<br>
    <label for="psw-repeat"><b>Repeat Password</b></label><br>
    <input type="password" class="form-control" placeholder="Repeat Password" name="confirm_password" id="psw-repeat" autocomplete="off" required>
<br>
    <label for="sq1"><b>Security Question 1 - What was your favourite food as a child?</b></label><br>
    <input type="text" class="form-control" placeholder="Security Question 1" name="sq1" id="sq1" autocomplete="off" required>
<br>
    <label for="sq2"><b>Security Question 2 - What is your favourite colour?</b></label><br>
    <input type="text" class="form-control" placeholder="Security Question 2" name="sq2" id="sq2" autocomplete="off" required>
<br>
    <label for="pin"><b>PIN (Personal Identification Number) of length 6 digits</b></label><br>
    <input type="text" class="form-control" placeholder="PIN (Personal Identification Number)" name="pin1" id="pin1" autocomplete="off" required>
<br>
   <center> <input type="submit" value="Submit" class="registerbtn" id="submit"></center>
  </div>
  
  <div class="container signin">
    <center><p>Already have an account? <a href="login.php">Sign in</a>.</p></center>
  </div>
</form>
<br>
<br>
<Br>
</body>
</html>
