<?php
//db connection parameters
$databaseName = "ad_512dfb1d063e0d2"; 
$databaseHostname = "us-cdbr-iron-east-03.cleardb.net"; 
$databasePort = "3306";
$username = "xxx"; 
$password = "xxx";

$link = mysqli_connect($databaseHostname, $username, $password, $databaseName);

if($link)
{
	//connection successful
}
else
{
	echo "Connection to database failed.";
	echo mysql_error($link);
}

session_start();

  if(!isset($_SESSION['loggedIn'])){
    $_SESSION['loggedIn'] = false;
  }
  if(!isset($_SESSION['userID'])){
    $_SESSION['userID'] = -1;
  }
  if(!isset($_SESSION['username'])){
    $_SESSION['username'] = -1;
  }
?>
