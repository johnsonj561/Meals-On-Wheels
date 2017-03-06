<?php 
require_once('../php/connect.php'); 
require_once('../php/route-calculator.php'); 

//get current userid from SESSION
if(isset($_SESSION['userID'])){
  $userID = $_SESSION['userID'];
}
//else re-direct to home page to prompt logging in
else{
  echo"<meta http-equiv='refresh' content='0; url=../php/logout.php'>"; 
}

//get the route from SESSION
if(isset($_SESSION['route']))
{
    $route       = $_SESSION['route'];
    $routeLength = count($route);
    if($routeLength >= 3)
    {
        $waypointList = array();
        $source      = $route[0];
        $destination = $route[$routeLength - 1];
        $url         = "https://maps.googleapis.com/maps/api/directions/json?origin=$source&destination=$destination&waypoints=optimize:true|";
        $finalUrl    = "https://www.google.com/maps/dir/";

        //add the waypoints to the request url
        for($i = 1; $i < $routeLength - 1; $i++)
        { 
            $url .= $route[$i];
            $url .= "|";
        }
        $result = file_get_contents($url);
        $data   = json_decode($result, true);
        for ($i = 0; $i < $routeLength - 2; $i++) {
          array_push($waypointList, $route[$data["routes"][0]["waypoint_order"][$i] + 1]);
        }
        //$waypointList should now contain the waypoints in the correct order.
        //generate our Url to direct the user to their map.
        $finalUrl .= $route[0];
        $finalUrl .= "/";
        for ($i = 0; $i < count($waypointList); $i++) 
        {
          $finalUrl .= $waypointList[$i];
          $finalUrl .= "/";
        }
        $finalUrl .= $route[count($waypointList) + 1];
        $finalUrl .= "/data=!3m1!4b1!4m4!4m3!2m1!2b1!3e0";
        header('Location: ' . $finalUrl, false, 302);
    }
    //not enough locations in the route, direct user to route planning page.
    else
    {
        header('Location: volunteer-plan-route.php', false, 302);
    }
    
}
//otherwise, direct the user to the route planning page.
else
{
    header('Location: volunteer-plan-route.php', false, 302);
}
?>
