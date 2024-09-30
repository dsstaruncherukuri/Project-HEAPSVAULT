<?php

session_start();

if(!isset($_SESSION['loggedin']) || !isset($_SESSION['sended']) || $_SESSION['loggedin'] !==true)
{
    header("location: login.php");
}

error_reporting(E_ERROR | E_WARNING | E_PARSE);
require_once "config.php";

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
$username = $_SESSION["username"];
$sql = "SELECT pin2 FROM pinc WHERE username = '$username'";
$result = mysqli_query($conn, $sql);
if ($result && mysqli_num_rows($result) > 0) {
  $row = mysqli_fetch_assoc($result);
  $cpin = $row["pin2"];
  $decryption_iv = '1234567891011121';
  $ciphering = "AES-128-CTR";
  $iv_length = openssl_cipher_iv_length($ciphering);
  $options = 0;
  $decryption_key = "HeapsV";
  $decryption=openssl_decrypt ($cpin, $ciphering,$decryption_key, $options, $decryption_iv);

}
else{
  echo '<script type="text/javascript">
  window.onload = function () { alert("Something went wrong. Please wait till we resolve the issue."); }
</script>';
}
$received = $decryption;

?>
<!DOCTYPE html>
<html>
<body style="background-color:black" >
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<center><div id="demo"style="font-size:6cm; color: white;font-family: 'Courier New', monospace;">PIN : </div></center>

<script>
window.onload = typeWriter;

var i = 0;
var txt = "<?php echo $received; ?>";
var speed = 1200;
function typeWriter() {
  if (i < txt.length) {
    document.getElementById("demo").innerHTML += txt.charAt(i);
    i++;
    setTimeout(typeWriter, speed);
  }
}
</script>

</body>
</html>