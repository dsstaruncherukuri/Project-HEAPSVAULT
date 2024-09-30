<?php

session_start();

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !==true)
{
    header("location: login.php");
}
?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <title>Strengthener</title>
    <link rel="stylesheet" href="style7.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css"/>
  </head>
<body>
  <form method="POST" action="">
  <div class="container">
    <u><center><h1>Strengthener and Strength Checker</h1></center></u>
    <br><br>
    <div class="input-box">
      <h2>Password (Previous)</h2><BR>
      <i class="fas fa-eye-slash show_hide"></i>
      <input spellcheck="false" type="password" id="pass" placeholder="Enter password">
    </div>
    <div class="indicator">
      <div class="icon-text">
        <i class="fas fa-exclamation-circle error_icon"></i>
        <h6 class="text"></h6>
      </div>
    </div>
    <br><br>
    <div class="input-box">
      <h2>Strengthened Password</h2><BR>
      <input spellcheck="false" id="pass1" name="pass1" class="pass1" type="password" readonly>

    </div>
    <div class="indicator">
      <div class="icon-text">
        <i class="fas fa-exclamation-circle error_icon"></i>
        <h6 class="text"></h6>
      </div>
    </div>
    <br>
    <input type="submit" name="button1" class="button button4" value="Strengthen">
    <br>
    <input type="button" class="button button4" name="button2" id="button2" onclick="myFunction()" value="Copy">
  </div>
</form>


<script src="scriptchk.js"></script>
<script>


  

function strengthenPassword() {

var inputPass = document.getElementById("pass");

if (inputPass.value === "") {
    window.alert("Please enter a password to strengthen.");
  }
else{
var symbols = {
  "a": "@",
  "e": "3",
  "i": "!",
  "o": "0",
  "s": "$",
  "t": "7"
};


var newPassword = inputPass.value.toLowerCase().replace(/[aeiost]/g, function(match) {
  return symbols[match];
});


newPassword += Math.floor(Math.random() * 2) + 1;
newPassword += Math.floor(Math.random() * 2) + 1;


var letters = newPassword.split("");
var randomIndex1 = "1";
var randomIndex2 = Math.floor(Math.random() * letters.length);
letters[randomIndex1] = letters[randomIndex1].toUpperCase();
letters[randomIndex2] = letters[randomIndex2].toUpperCase();
newPassword = letters.join("");


var alphabet = "abcdefghijklmnopqrstuvwxyz";
var randomIndex = Math.floor(Math.random() * alphabet.length);
var randomLetter = alphabet.charAt(randomIndex);
newPassword = randomLetter + newPassword + randomLetter;


document.getElementById("pass1").value = newPassword;
}
}

document.querySelector(".button4").addEventListener("click", function(event) {
event.preventDefault();
strengthenPassword();
});



function myFunction() {
  var copyText = document.getElementById("pass1");

  
  copyText.select();
  copyText.setSelectionRange(0, 99999);

   
   if((copyText.value).length<1){
      alert("Nothing to copy.");
   }

else if((copyText.value).length>=1){
  navigator.clipboard.writeText(copyText.value);
  alert("Copied the Strengthened Password.");
}
};



</script>
</body>
</html>
