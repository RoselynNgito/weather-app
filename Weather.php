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
h2{
  text-align: center;
  margin-top: 5px;
  margin-bottom: 5px;
}
input{
  width: 350px;
  padding: 5px;
}
#table-container {
            margin: 20px auto;
            width: 80%;
            
        }
        table {
            border-collapse: collapse;
            width: 80%;
            margin-left: 50px;
            margin-bottom: 50px;
           
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            color: black;
        }
        th {
            background-color: #f2f2f2;
        }
        img {
            width: 40px;
            height: 40px;
        }
        #current-info {
            margin: 20px auto;
            width: 80%;
            text-align: center;
        }
</style>



  </head>
  <body>
    <div class="container">
    <h1>Weather Forecast</h1>
    <form action="" method="POST">
        <label for="city">Enter City Name:</label>
        <input type="text" name="city" id="city" placeholder="city name eg: nairobi " required><br><br>
        <button type="submit" name="submit" class="btn btn-success">Get forecast</button><br>
         
        </form>
    </div>

    
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Process the form submission
        if (isset($_POST['city'])) {
            $city = urlencode($_POST['city']);
            $apiKey = '6134fabc0c4a23e6eac7bddae4047f9a';
            $apiUrl = "https://api.openweathermap.org/data/2.5/forecast?q=$city,ke&appid=$apiKey&cnt=40";
            
            // Fetch weather data from API
            $response = file_get_contents($apiUrl);
            $data = json_decode($response, true);
            
            if ($data && isset($data['list'])) {
                // Filter out the data for the next five days starting from tomorrow
                $nextFiveDaysData = array();
                $tomorrow = date('Y-m-d', strtotime('+1 day'));

                foreach ($data['list'] as $dayData) {
                    $date = date('Y-m-d', $dayData['dt']);
                    if ($date >= $tomorrow) {
                        $nextFiveDaysData[$date][] = $dayData;
                    }
                }

                // Display current weather information
                $currentWeather = $data['list'][0];
                $currentDayOfWeek = date('l', $currentWeather['dt']);
                $currentTemperature = round($currentWeather['main']['temp'] - 273.15, 2);
                $currentHumidity = $currentWeather['main']['humidity'];
                $currentWindSpeed = $currentWeather['wind']['speed'];
                $currentWeatherIcon = "http://openweathermap.org/img/wn/{$currentWeather['weather'][0]['icon']}.png";

                echo "<div id='current-info'>";
                echo "<h2>Current Weather for " . $data['city']['name'] . "</h2>";
                echo "<p>Day: $currentDayOfWeek</p>";
                echo "<p>Temperature: $currentTemperature °C</p>";
                echo "<p>Humidity: $currentHumidity %</p>";
                echo "<p>Wind Speed: $currentWindSpeed m/s</p>";
                echo "<img src='$currentWeatherIcon' alt='Current Weather Icon'>";
                echo "</div>";

                // Display weather forecast table
                echo "<div id='table-container'>";
                echo "<h2>Weather forecast for " . $data['city']['name'] . "</h2>";
                echo "<table>";
                echo "<tr><th>Day</th><th>Average Temperature (°C)</th><th>Humidity (%)</th><th>Wind Speed (m/s)</th><th>Weather</th></tr>";

                foreach ($nextFiveDaysData as $date => $dailyData) {
                    $dayOfWeek = date('l', strtotime($date));
                    $temperatureSum = 0;
                    $humiditySum = 0;
                    $windSpeedSum = 0;

                    foreach ($dailyData as $dayData) {
                        $temperatureSum += ($dayData['main']['temp'] - 273.15);
                        $humiditySum += $dayData['main']['humidity'];
                        $windSpeedSum += $dayData['wind']['speed'];
                    }

                    $averageTemperature = round($temperatureSum / count($dailyData), 2);
                    $averageHumidity = round($humiditySum / count($dailyData), 2);
                    $averageWindSpeed = round($windSpeedSum / count($dailyData), 2);
                    $weatherIcon = "http://openweathermap.org/img/wn/{$dailyData[0]['weather'][0]['icon']}.png";

                    echo "<tr>";
                    echo "<td>{$dayOfWeek}</td>";
                    echo "<td>{$averageTemperature}</td>";
                    echo "<td>{$averageHumidity}</td>";
                    echo "<td>{$averageWindSpeed}</td>";
                    echo "<td><img src='{$weatherIcon}' alt='Weather Icon'></td>";
                    echo "</tr>";
                }

                echo "</table>";
                echo "</div>";
            } else {
                echo "Error: Weather data not available.";
            }
        }
    }
    ?>
  </body>
</html>