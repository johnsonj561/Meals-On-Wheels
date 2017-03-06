<?php
require_once("connect.php");    //connect to DB

//if session has already been established, re-direct user to appropriate page
if($_SESSION['loggedIn']){
  if(strtolower($_SESSION['username']) == "admin"){
    echo "<meta http-equiv='refresh' content='0; url=../view/admin-landing.php'>";
  }
  else{
     echo "<meta http-equiv='refresh' content='0; url=../view/volunteer-landing.php'>";
  }
}

//else log in user
if(!$_SESSION['loggedIn']){
  $username = strip_tags($_POST['username']);
  $password = strip_tags($_POST['password']);
  $salt = "X1K$6B8";
  $query = "SELECT * FROM Users where Username = '$username';";
  $result = mysqli_query($link, $query);

  //if we have a match! Check the passwords
  if(mysqli_num_rows($result) == 1){
    $row = mysqli_fetch_assoc($result);
    //get hashed value of password to check against database password
    $password = crypt($password, $salt);
    if($password == $row['Password']){
      //log in successful - maintain log in status with $_SESSION
      $_SESSION['userID'] = $row['UserID'];
      $_SESSION['loggedIn'] = true;
      $_SESSION['username'] = $username;

      if(strtolower($username) == "admin"){  //if admin user - re-direct to admin page
        echo "<meta http-equiv='refresh' content='0; url=../view/admin-landing.php'>";
      }
      else{                                 //else re-direct to volunteer page
        echo "<meta http-equiv='refresh' content='0; url=../view/volunteer-landing.php'>";
      }
    }
    else{ //else passwords do not match, return user to home screen
      echo"<meta http-equiv='refresh' content='0; url=../index.php?error=password'>";
    }
    mysqli_free_result($result);
  }
  else{ //else username does not exist - return user to home screen
    echo"<meta http-equiv='refresh' content='0; url=../index.php?error=username'>";
  }
}
?>