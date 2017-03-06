<?php
require_once('connect.php');

//flag used to represent successful registration and valid username
$isValidPassword = false;
$isValidUsername = false;

//make sure username isn't already being used
//set $isValid to false if username is not valid
$username = strip_tags($_POST['username']);
if(validateUsername($username, $link)){
  $isValidUsername = true;
}

//crypt password to create hash for safe DB storage
$salt = "X1K$6B8";
$password1 = strip_tags($_POST['password1']);
$password2 = strip_tags($_POST['password2']);
$password1 = crypt($password1, $salt);
$password2 = crypt($password2, $salt);

//make sure passwords match
if(validatePasswords($password1, $password2)){
  $isValidPassword = true;
}

//If username is valid and passwords match - update database!
if($isValidUsername && $isValidPassword){
  //collect user info
  $firstName = strip_tags($_POST['firstName']);
  $lastName = strip_tags($_POST['lastName']);
  $email = strip_tags($_POST['email']);
  $phoneNumber = strip_tags($_POST['phoneNumber']);
  //Add entry to Users table
  $query = "INSERT INTO Users
              (Username, Password) 
              VALUES
              ('$username', '$password1');";
  mysqli_query($link, $query);

  //get ID that was generated for this new user
  $query = "SELECT UserID FROM Users WHERE Username = '$username';";
  $result = mysqli_query($link, $query);
  $row = mysqli_fetch_assoc($result);
  $userID = $row['UserID'];
  mysqli_free_result($result);

  //Add entry to UserInfo table
  $query = "INSERT INTO UserInfo
            (UserID, FirstName, LastName, Email, Phone)
            VALUES
            ('$userID', '$firstName', '$lastName', '$email', '$phoneNumber');";
  mysqli_query($link, $query);  
}


//create appropriate user message - dependant on registration status 
if(!$isValidUsername){
  $html = "<h2 class='intro-text text-center'>Registration Failed</h2>
          <hr>
          <p class='text-center'>Unfortunately the Username you selected is already in use.
          <a href='../view/register.php'>Please Select A Different Username</a>.</p>";
}
else if(!$isValidPassword){
  $html = "<h2 class='intro-text text-center'>Registration Failed</h2>
          <hr>
          <p class='text-center'>Your passwords do not match.
          <a href='../view/register.php'>Please Try Again</a>.</p>";
}
else{
  $html = "<h2 class='intro-text text-center'>Registration Successful</h2>
          <hr>
          <h4 class='text-center'><a href='../index.php'>Click To Log In</a></h4>";
}
mysqli_close($link);


//returns true if username does not already exist
//queries database to determine if any rows exist with this username
function validateUsername($username, $link){
  $query = "SELECT UserID FROM Users WHERE Username = '$username'";
  $result = mysqli_query($link, $query);
  if(mysqli_num_rows($result) > 0){     //if row exists with user name, user must select new name
      return false;
    }
    else{                                //else username is valid
      return true;
    }
  mysqli_free_result($result);
}


//returns true if both passwords match
function validatePasswords($password1, $password2){
  if($password1 == $password2){
    return true;
  }
  else{
    return false;
  }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Registration Status</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/custom-css.css" rel="stylesheet">
  </head>
  <body>
    <?php require_once('../template/navbar.html'); ?>
    <div class="container">
      <div class="row">
        <div class="box">
          <div class="col-lg-12">
            <hr>
            <?php echo $html; ?>
          </div>
          <br>
        </div>
      </div>
    </div>
    <?php require_once('../template/footer.html'); ?>
  </body>
</html>