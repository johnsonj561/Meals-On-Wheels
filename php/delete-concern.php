<?php
require_once('connect.php');
//get the ConcernID to be deleted
if(isset($_GET['ConcernID'])){
  $concernID = $_GET['ConcernID'];
}
//else re-direct and notify user if ConcernID not available
else{
  echo"<meta http-equiv='refresh' content='0; url=../view/view-concerns.php?message=delete-error'>";
}

$query = "DELETE FROM Concerns WHERE ConcernID = '$concernID';";
$result = mysqli_query($link, $query);
//notify user of result
if($result){
  echo"<meta http-equiv='refresh' content='0; url=../view/view-concerns.php?message=delete-success'>";
}
else{
  echo"<meta http-equiv='refresh' content='0; url=../view/view-concerns.php?message=delete-error'>";
}
?>