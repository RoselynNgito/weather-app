<?php
     if(array_key_exists('submit', $_GET)){
       
      if(!$_GET['city']){
        $error="Your input field is empty";
      }
      if ($_GET['city']) {
        $apiData = file_get_contents("https://api.openweathermap.org/data/2.5/weather?q=".
        $_GET['city'].",ke&appid=6134fabc0c4a23e6eac7bddae4047f9a");
        $weatherArray = json_decode($apiData, true);
        if ($weatherArray['cod'] == 200) {
           //K = C -273.15
        $tempCelsius = $weatherArray['main']['temp'] -273;
        $weather ="<b>" .$weatherArray['name'].", ".$weatherArray['sys']['country']." :
        ".intval($tempCelsius). "&deg;C</b> <br>";
       $weather .="<b>Weather Condition : </b>" .$weatherArray['weather']['0']
       ['description']."<br>";
       $weather .="<b>Humidity : </b>" .$weatherArray['main']['humidity'].
       "hPa<br> ";
       $weather .="<b>Wind Speed : </b>" .$weatherArray['wind']['speed'].
       "meter/sec<br> ";
       $weatherIconCode = $weatherArray['weather'][0]['icon'];
       $weatherIconUrl = "http://openweathermap.org/img/wn/{$weatherIconCode}.png";
       $weather .= "<img src=\"$weatherIconUrl\" alt=\"weather Icon\" >";
        }
        else{
          $error ="Your city name is not valid";
        }
        
      }
    }

?>





<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">


    <title>Weather App</title>

<style>
   body{
        margin:0px;
        padding:0px;
        box-sizing: border-box;
        background-size: cover; 
        background-image:url(https://wallpapers.com/images/hd/fine-weather-landscape-iq9k6ubn8w9yhhkc.jpg);
        color: white;
        font-size: large;
        
        background-attachment: fixed;

   }
.container{
       
        text-align: center;
        justify-content: center;
        align-items: center;
        width: 440px;
}
h1{
  font-weight: 700;
  
}
input{
  width: 350px;
  padding: 5px;
}
</style>



  </head>
  <body>
    <div class="container">
      <h1>Search Countrywide Weather</h1>
      <form action="" method="GET">
         <p><label for="city">ENTER YOUR CITY</label></p>
         <p><input type="text" name="city" id="city"placeholder="City Name"></p>
         <button type="submit" name="submit" class="btn btn-success">Submit Now</button>
         <div class="output">
               <?php 
              if (!empty($weather)) {
                echo '<div class="alert alert-success" role="alert">
                '. $weather.'
                </div>';
              } 
              if (!empty($error)){
                echo '<div class="alert alert-dark" role="alert">
                '. ($error="Your input field is empty").'               </div>';

              }
               
                ?>
         </div> 
        </form>
    </div>
    
  </body>
</html>