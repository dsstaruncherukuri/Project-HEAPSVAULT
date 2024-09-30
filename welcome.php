<?php

session_start();

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !==true)
{
    header("location: login.php");
}

require_once "config.php";

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == "POST"){
  $username = $_SESSION["username"];
  $name = $_POST["name"];
  $email = $_POST["email"];
  $subject = $_POST["subject"];
  $comment = $_POST["comment"];

  $sql = "INSERT INTO query (username, name, email, subject, comment) VALUES ('$username', '$name', '$email', '$subject', '$comment')";

  if (mysqli_query($conn, $sql)) {
  echo '<script type="text/javascript">
                window.onload = function () { alert("Submitted Successfully."); }
            </script>';
} else {
  echo '<script type="text/javascript">
                window.onload = function () { alert("Something went wrong, retry after sometime."); }
            </script>';
}
mysqli_close($conn);
}


?>


<!DOCTYPE html>
<html>
<head>
<title> Welcome</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body>

<div class="w3-top">
  <div class="w3-bar w3-white w3-wide w3-padding w3-card">
    <a href="#home" class="w3-bar-item w3-button"><b>HEAPS</b> Vault</a>
    <div class="w3-right w3-hide-small">
      <a class="w3-bar-item w3-button" href="logout.php">Logout</a>
      <a class="w3-bar-item w3-button" href="pin.php">PIN</a>
      <a class="w3-bar-item w3-button" href="use.html">How to use?</a>
      <a class="w3-bar-item w3-button" href="#"> <img src="https://img.icons8.com/metro/26/000000/guest-male.png"> <?php echo "Hi, ". $_SESSION['username']?></a>
    </div>
  </div>
</div>

<header class="w3-display-container w3-content w3-wide" style="max-width:1500px;" id="home">
  <img class="w3-image" src="architect.jpg" alt="Architecture" width="1500" height="800">
  <div class="w3-display-middle w3-margin-top w3-center">
    <h1 class="w3-xxlarge w3-text-white"><span class="w3-padding w3-black w3-opacity-min"><b>HEAPS</b></span> <span class="w3-hide-small w3-text-light-grey">Vault</span></h1>
  </div>
</header>

<div class="w3-content w3-padding" style="max-width:1564px">

  <div class="w3-container w3-padding-32" id="projects">
    <h3 class="w3-border-bottom w3-border-black w3-padding-16">Social Media</h3>
  </div>

  <div class="w3-row-padding">
    <div class="w3-col l3 m6 w3-margin-bottom">
      <div class="w3-display-container">

       <a href="https://www.google.com/"> <img src="Insta - Copy.jpg" alt="House" style="width:100%"></a>
      </div>
    </div>
    <div class="w3-col l3 m6 w3-margin-bottom">
      <div class="w3-display-container">
       
        <a href="https://www.google.com/"> <img src="Tele - Copy.jpg" alt="House" style="width:100%"></a>
      </div>
    </div>
    <div class="w3-col l3 m6 w3-margin-bottom">
      <div class="w3-display-container">
        
      <a href="https://www.google.com/"><img src="Twit.jpg" alt="House" style="width:100%"></a>
      </div>
    </div>
    <div class="w3-col l3 m6 w3-margin-bottom">
      <div class="w3-display-container">
        
      <a href="https://www.google.com/"> <img src="Disco.jpg" alt="House" style="width:100%"></a>
      </div>
    </div>
  </div>


  <div class="w3-container w3-padding-32" id="about">
    <h3 class="w3-border-bottom w3-border-dark-grey w3-padding-16">Functionalities</h3>
    <p>HEAPSVAULT - Highly Encrypted Arbitrary Password Setup VAULT. This is a Security Web-Application that helps users in Generating, Retrieving Arbitrary (Random) Passwords. This also helps the user in determining whether their passwords are Easy or Hard to crack by determing the degree of strength of the password. This also ensures that the passwords used by the user are encrypted using the best encryption method there is.
    </p>
  </div>

  <div class="w3-row-padding w3-grayscale">
    <div class="w3-col l3 m6 w3-margin-bottom">
      <img src="untitled-desi1gn-1-63f726c02f183.png" alt="John" style="width:100%">
      <h3>Generate Random Passwords</h3>
      <p class="w3-opacity">Step One</p>
      <p>Generating Arbitrary Passwords can be helpful as they are harder to crack even when they are Bruteforced.</p>
      <p><button onclick="window.location.href='generate.php'" class="w3-button w3-light-grey w3-block">Generate</button></p>
    </div>
    <div class="w3-col l3 m6 w3-margin-bottom">
      <img src="untitled-desi1gn-1-63f727c9324c3.png" alt="Jane" style="width:100%">
      <h3>Store Passwords</h3>
      <p class="w3-opacity">Step Two</p>
      <p>Store your passwords and also Secure them with the best Hashing and Encryption Techniques currently used.</p>
      <p><button onclick="window.location.href='store.php'" class="w3-button w3-light-grey w3-block">Store</button></p>
    </div>
    <div class="w3-col l3 m6 w3-margin-bottom">
      <img src="untitled-desi1gn-1-63f728905b229.png" alt="Mike" style="width:100%">
      <h3>Retrieve Passwords</h3>
      <p class="w3-opacity">Step Three</p>
      <p>Retrieve your stored passwords that are previously generated and stored or directly stored in your vault.</p>
      <p><button button onclick="window.location.href='gateway.php'" class="w3-button w3-light-grey w3-block">Retrieve</button></p>
    </div>
    <div class="w3-col l3 m6 w3-margin-bottom">
      <img src="untitled-desi1gn-1-63f72a8b23166.png" alt="Dan" style="width:100%">
      <h3>Strengthen Passwords</h3>
      <p class="w3-opacity">Step Four</p>
      <p>Strengthen your current passwords in all domains so that they are harder to crack by all hacking methods (Mainly Bruteforcing).</p>
      <p><button onclick="window.location.href='check.php'" class="w3-button w3-light-grey w3-block">Strengthen</button></p>
    </div>
  </div>

  <div class="w3-container w3-padding-32" id="contact">
    <h3 class="w3-border-bottom w3-border-black w3-padding-16">Contact</h3>
    <p>Please fill and submit the form below if you are having any issues or queries. Response will be sent to your mail dierctly.</p>
    <form method="POST">
      <input class="w3-input w3-section w3-border" type="text" name="name" id="name" placeholder="Name" required>
      <input class="w3-input w3-section w3-border" type="text" name="email" id="email" placeholder="Email" required>
      <input class="w3-input w3-section w3-border" type="text" name="subject" id="subject" placeholder="Subject" required>
      <input class="w3-input w3-section w3-border" type="text" name="comment" id="comment" placeholder="Comment" required>
      <button class="w3-button w3-black w3-section" type="submit">
        <i class="fa fa-paper-plane"></i> SUBMIT
      </button>
    </form>
  </div>
  
<div class="w3-container">
  <img src="https://c.files.bbci.co.uk/16321/production/_96131909_gettyimages-513189256-1.jpg"  width="1500" height="800">
</div>


</div>

<br>
<br>
<footer class="w3-center w3-black w3-padding-16">
  <p>Powered by <a href="project.html" target="_blank" class="w3-hover-text-green">HEAPSVault</a></p>
</footer>

</body>
</html>
