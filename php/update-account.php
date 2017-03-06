<?php
require_once('connect.php');

//obtain username from $_SESSION variable
if((isset($_SESSION['username']) && $_SESSION['username'] != -1)){
  $username = $_SESSION['username'];
}
else{ //else username wasn't stored and user must log in again
  echo"<meta http-equiv='refresh' content='0; url=../index.php?error=session-ended'>"; 
}

//obtain userinfo from form
$firstName = strip_tags($_POST['firstName']);
$lastName = strip_tags($_POST['lastName']);
$email = strip_tags($_POST['email']);
$phoneNumber = strip_tags($_POST['phoneNumber']);
$password1 = strip_tags($_POST['password1']);
$password2 = strip_tags($_POST['password2']);

//crypt password to create hash for safe DB storage
$salt = "X1K$6B8";
$password1 = crypt($password1, $salt);
$password2 = crypt($password2, $salt);

//make sure passwords match
if(validatePasswords($password1, $password2)){
  $isValidPassword = true;



  //Update UserInfo table with new user information
  $query = "UPDATE UserInfo, Users SET 
          FirstName = '$firstName',
          LastName = '$lastName',
          Email = '$email',
          Phone = '$phoneNumber'
          WHERE UserInfo.UserID = Users.UserID
          AND Users.Username = '$username';";
  $result1 = mysqli_query($link, $query);

  //Update User table with new password
  $query = "UPDATE Users SET
          Password = '$password1'
          WHERE Username = '$username';";
  $result2 = mysqli_query($link, $query);
}

//if updates failed, alert user to try again
if(!result1 || !$result2){
  echo"<meta http-equiv='refresh' content='0; url=../view/update-account.php?error=update-failed'>";
}
//else updates were successful, re-direct user to landing page
else{
  echo"<meta http-equiv='refresh' content='0; url=../view/volunteer-landing.php?error=null'>";
}

//verify valid passwords match
//returns true if both passwords match
function validatePasswords($password1, $password2){
  if($password1 == $password2){
    return true;
  }
  else{
    return false;
  }
}

