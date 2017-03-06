<?php
require_once('connect.php');

$email = $_POST['email'];
if(validateEmail($email, $link)){
  //Provided email is valid, give user a new random password
  //create a new password and email it to user
  $newPassword  = randomPassword();
  $api_user     = "pnsiTmi2Ig";
  $api_key      = "adKn5GzXyjC13381";
  $url          = 'https://api.sendgrid.com';
  $emailMessage = 
    "<table class='form-email-message'>
      <tr>
        <td>Your new password: $newPassword</td>
      </tr>
      <tr>
        <td>Please login and change your password, your temporary password is not secure.</td>
      </tr>
      <tr>
        <td><a href='meals-on-wheels-routing.mybluemix.net/'>Click to Login</a></td>
      </tr>
    </table>";
   $params = array(
        'api_user'   => $api_user,
        'api_key'    => $api_key,
        'to'         => $email,
        'subject'    => "Meals on Wheels Password Recovery",
        'html'       => 'test',
        'text'       => 'test',
        'from'       => "5ff44547-e92f-4cfd-a482-4880c57735cf@appdirect.com",
   );
   $request = $url.'api/mail.send.json';

  //update database with the new password!
  $salt = "X1K$6B8";
  $hashedPassword = crypt($newPassword, $salt);
  $query = "UPDATE Users, UserInfo SET Password = '$hashedPassword' WHERE 
            Users.UserID = UserInfo.UserID AND
            UserInfo.Email = '$email'";
  $result = mysqli_query($link, $query);
  if(!$result){ //if database update failed - alert user to try again
    echo"<meta http-equiv='refresh' content='0; url=../view/forgot-password.php?error=new-password-failed'>";
  }
  else
  {
    //The database has been updated, inform the user.
    // Generate curl request
    $session = curl_init($request);
    // Tell curl to use HTTP POST
    curl_setopt ($session, CURLOPT_POST, true);
    // Tell curl that this is the body of the POST
    curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
    // Tell curl not to return headers, but do return the response
    curl_setopt($session, CURLOPT_HEADER, false);
    // Tell PHP not use SSLv3
    curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_SSLv3);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

    // obtain response
    $response = curl_exec($session);
    curl_close($session);

    // print everything out
    print_r($response);

  }
}
else{ //alert user of invalid email
  echo"<meta http-equiv='refresh' content='0; url=../view/forgot-password.php?error=invalid-email'>";
}

/** *******************************************************************
 * Validates a user's email when they request a recovery.             *
 * Queries the user database to see if a user with that email exists. *
 * @param email The email we want to validate                         *
 * @param link The pointer to the database connection                 *
 * @return True if the email is valid, false otherwise.               *
 **********************************************************************/
function validateEmail($email, $link){
  $query = "SELECT UserID FROM UserInfo WHERE Email = '$email'";
  $result = mysqli_query($link, $query);
  if(mysqli_num_rows($result) > 0){     //if row exists with email, it is valid
    return true;
  }
  else{                                //else email is invalid
    return false;
  }
  mysqli_free_result($result);
}

/** *******************************************************************
 * Generate a psuedorandom password non-securely                      *
 * Uses the provided $alphabet to generate an eight-character string. *
 * Good enough for demo usage, not good enough for production code.   *
 * @return A psuedorandom eight-character string                      *
 **********************************************************************/
function randomPassword() {
    $alphabet    = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass        = array();
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n      = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
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
    <title>Volunteer Controls</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/custom-css.css" rel="stylesheet">
  </head>
  <body>
    <?php require_once('../template/navbar.html'); ?>
    <!-- Page Content -->
    <div class="container">
      <h1 class="intro-text text-center">Password Updated</h1>
      <h3 class="intro-text text-center">Please check your email for your updated password</h3>
      <div class="col-med-12">
        <div class="col-md-3 text-center"></div>
        <div class="col-md-3 text-center">
          <div class="btn-border">
            <a class="btn btn-primary btn-lg" href="../index.php">Login</a>
            <p class="btn-description">Return to home page and login using your new password</p>
          </div>
        </div>
        <div class="col-md-3 text-center">
          <div class="btn-border">
            <a class="btn btn-primary btn-lg" target="_blank"
               href="http://mealsonwheelspalmbeaches.org/contact/">Contact Us</a>
            <p class="btn-description">For technical support if you continue to have trouble signing in</p>
          </div>
        </div>
        <div class="col-md-3 text-center"></div>
      </div>



    </div>
    <hr>
    </div>
  <?php require_once('../template/footer.html'); ?>
  <script src = "../js/validate-registration.js"></script>
  </body>
</html>
