function onAddClient() {
  if (!validateFirstName()) {
    displayFormErrors("name");
    return false;
  } else if(!validateLastName()){
    displayFormErrors("name");
    return false;
  } else if (!validateTelephone()) {
    displayFormErrors("telephone");
    return false;
  } else if(!validateAddress()){
    displayFormErrors("address");
    return false;
  } else if (!validateCity()){
    displayFormErrors("city");
    return false;
  } else if(!validateZip()){
    displayFormErrors("zip");
    return false;
  } else if(!validateLocation()){
    displayFormErrors("location");
    return false;
  }
  return true;
}

function validateFirstName() {
  var e = document.getElementById("firstName").value;
  if (e.length < 2 || e.length > 30) {
    return false;
  } else {
    return true;
  }
}

function validateLastName() {
  var e = document.getElementById("lastName").value;
  if (e.length < 2 || e.length > 30) {
    return false;
  } else {
    return true;
  }
}

function validateTelephone() {
  var e = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
  var t = document.getElementById("phone").value;
  if (t.match(e)) {
    return true;
  } else {
    return false;
  }
}

function validateAddress(){
  var e = document.getElementById("address").value;
  if(e.length < 5 || e.length > 64){
    return false;
  }
  else{
    return true;
  }
}

function validateCity(){
  var e = document.getElementById("city").value;
  if(e.length < 4 || e.length > 30){
    return false;
  }
  else{
    return true;
  }
}

function validateZip(){
  var e = document.getElementById("zip").value;
  if(!isNumber(e) || e.length != 5){
    return false;
  }
  else{
    return true;
  }
}

function validateLocation(){
  var longitude = document.getElementById("longitude").value;
  if(!isNumber(longitude) || longitude.length < 8 || longitude.length > 12 || !validateLongitudeRange(longitude)){
    return false;
  }
  var latitude = document.getElementByID("latitude").value;
  if(!isNumber(latitude) || latitude.length < 8 || latitude.length > 12 || !validateLatitudeRange(latitude)){
    return false;
  }
  else{
    return true;
  }
}



function isNumber(e) {
  return !isNaN(parseFloat(e)) && isFinite(e);
}

function validateLatitudeRange(e)
{
    if(e < 26.050153 || e > 27.212657)
        return false;
    else
        return true;
}

function validateLongitudeRange(e)
{
    if(e < -80.797088 || e > -79.942901)
        return false;
    else
        return true;
}

function displayFormErrors(e) {
  var t = document.getElementById("errorElement");
  switch (e) {
    case "name":
      t.innerHTML = "Name must be between 2 - 30 characters.";
      break;
    case "telephone":
      t.innerHTML = "Please provide a valid telephone number.";
      break;
    case "address":
      t.innerHTML = "Please provide a valid address.";
      break;
    case "city":
      t.innerHTML = "Please provide a valid city.";
      break;
    case "zip":
      t.innerHTML = "Please provide a 5 digit zip code.";
      break;
    case "location":
      t.innerHTML = "Verify that your latitude and longitude are correct<br>Provide between 8 and 10 Significant Digits and make sure it is within our delivery range.";
      break;
    default:
      correctionsElement.innerHTML = "";
      break;
  }
}
