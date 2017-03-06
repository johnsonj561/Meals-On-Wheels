<?php

header('Content-type: text/plain');

//This needs to be filled in once the SQL Database service is added to the Bluemix project
define('DB_HOST', 'localhost');
define('DB_NAME', 'Meals.On.Wheels.Routing');
define('DB_USER','root');
define('DB_PASSWORD','ROOT');

//Create a connection to the database
$connectiontoDB = mysql_connect(DB_HOST,DB_USER,DB_PASSWORD) or die("Failed to connect to MySQL: " . mysql_error());
$db=mysql_select_db(DB_NAME,$connectionToDB) or die("Failed to connect to MySQL: " . mysql_error());

function signIn()
{
	session_start();
	if(!empty($_POST['username'])) 
	{
		//Hash the password for security purpose. bcrypt automatically salts
		//the password. how nice of them. using the default problem cost of '10'
		$hash = password_hash($_POST['password'], PASSWORD_BCRYPT)

		//Generate a query to get the user
		$query = mysql_query("SELECT * FROM Users where username = '$_POST[username]' AND password = '$hash'") or die(mysql_error());
		$row = mysql_fetch_array($query) or die(mysql_error());
		
		//This statement should always evaluate to true becuase we should
		//make sure the fields are filled in using our Javascript
		//before we POST the user's input.
		if(!empty($row['username']) AND !empty($row['password']))
		{
			if(password_verify($hash, $row['password']))
			{
				$_SESSION['username'] = $row['password'];
			//Successful login, redirect user to the post-login page.
			}
			else
			{
				//Invalid username or password, inform user
			}
			
		}
	}

}
	//Submitting the form should reload the page so that this code executes.
	//We will redirect users after a successful login.
	if(isset($_POST['submit']))
	{
		signIn();
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
    <title>Meals on Wheels Route Planning</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/custom-css.css" rel="stylesheet">
  </head>
  <body>
    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a href="index.php" class="img-logo pull-left"><img src="img/mealsonwheels-logo-55x55.png"/></a>
          <a class="navbar-brand" href="#">Meals on Wheels Routing</a>
        </div>
        <div class="collapse navbar-collapse pull-right" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <li>
              <a href="http://mealsonwheelspalmbeaches.org/contact/" target="_blank">Contact Meals on Wheels</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Page Content -->
    <div class="container">
      <h1 class="intro-text text-center">Palm Beach County Meals on Wheels</h1>
      <h2 class="intro-text text-center">Delivery Services</h2>
      <hr>
      <div class="sign-in-border">
        <form role="form" action="php/sign-in-landing.php" method = "post">
          <div class="row">
            <div class="col-lg-12">
              <div class="col-lg-2"></div>

              
                <div class="form-group col-lg-3">
                  <label>Username</label>
                  <input autofocus type="text" class="form-control" name = "username" id = "username" required>
                </div>
                <div class="form-group col-lg-3">
                  <label>Password</label>
                  <input type="password" class="form-control" name = "password" id = "password">
                </div>


              <div class="form-group col-lg-2 text-center">
                <button type="submit" class="btn btn-primary btn-sign-in">Sign In</button>
              </div>
              <div class="col-lg-2"></div>
              <div class="col-lg-12 text-center">
                <h4 class="register-text"><a href="view/register.php">Register Here</a> | <a href="#">Forgot Password?</a></h4>
              </div>
            </div>
          </div>
        </form>
      </div>
      <hr>
    </div>
    <footer>
      <div class="row">
        <div class="col-lg-12">
          <p class="text-center">Copyright 2015 &copy; DLT2 Designs, Florida Atlantic University</p>
        </div>
      </div>
    </footer>
    </div>
  <!-- /.container -->
  <!-- jQuery -->
  <script src="js/jquery.js"></script>
  <!-- Bootstrap Core JavaScript -->
  <script src="js/bootstrap.min.js"></script>
  </body>

</html>
