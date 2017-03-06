<?php
require_once('connect.php');
$firstName = strip_tags($_POST['firstName']);
$lastName = strip_tags($_POST['lastName']);
$phone = strip_tags($_POST['phone']);
$address = strip_tags($_POST['address']);
$city = strip_tags($_POST['city']);
$zip = strip_tags($_POST['zip']);
$longitude = strip_tags($_POST['longitude']);
$latitude = strip_tags($_POST['latitude']);
$UserID = $_POST['UserID'];


//Update UserInfo table with new user information
  $query = "UPDATE Clients SET 
          FirstName = '$firstName',
          LastName = '$lastName',
          Phone = '$phone',
          Address = '$address',
          City = '$city',
          Zip = '$zip',
          Latitude = '$latitude',
          Longitude = '$longitude'
          WHERE ClientID = $UserID;";
$result = mysqli_query($link, $query);

if($result){  //if query worked, notify user
    echo "<meta http-equiv='refresh' content='0; url=../view/edit-recipients.php?message=clientupdated'>";
}
else{
    echo"<meta http-equiv='refresh' content='0; url=../view/update-recipient.php?message=error'>";
}
mysqli_free_result($result);
?>