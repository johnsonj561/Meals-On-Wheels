<?php
require_once('connect.php');

/** *******************************************************************
 * Fetches the coordinates of a delivery point.                       *
 * @param client The ClientID of the client we want to fetch.         *
 * @return The Lat,Long for the provided client.                      *
 **********************************************************************/
function getCoordinates($client, $link)
{
    //fetch the coordinates from the table
    $query     = "SELECT Latitude,Longitude FROM Clients WHERE ClientID='$client';";
    $result    =  mysqli_query($link, $query);
    $row       =  mysqli_fetch_assoc($result);
	$latitude  =  $row['Latitude'];
    $longitude =  $row['Longitude'];

    return       "$latitude,$longitude";

}

/** *******************************************************************
 * Calculates the distance between two points.                        *
 * @param coordinatesOfSource Lat,Long of origin.                     *
 * @param coordinatesOfDestination Lat,Long of destination.           *
 * @return The distance, in miles, between the two points.            *
 **********************************************************************/
function calculateDistance($coordinatesOfSource, $coordinatesOfDestination){
    //Coordinates should come in the following format:
    //Latitude,Longitude ex - [55.5555,44.4444]

    //I'm leaving the API key here just in case, but apparently using the API key
    //causes the request to be denied.
    $apiKey = "AIzaSyC0w2m0WrMJBQAhAA0t9XXsA6Vpea0DF1Y";

    //Send a request to the Google API to find the distance between two points
    //This route finding will avoid tolls.
    $url = "http://maps.googleapis.com/maps/api/directions/json?" .
           "origin=" . urlencode("$coordinatesOfSource") .
           "&destination=" . urlencode("$coordinatesOfDestination") .
           "&avoid=tolls";

    $jsonResults = file_get_contents($url);

    $data = json_decode($jsonResults, TRUE);

    //Return the distance of the route in miles if the request was successful,
    //otherwise return the status. You can check if the returned value is a number
    //or not to know if the request succeeded outside of this function.
    if($data['status']=="OK")
        return $data['routes'][0]['legs'][0]['distance']['value'] * 0.00062;
    else
        return $data['status'];

}	
