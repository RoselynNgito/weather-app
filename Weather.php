<?php
     if(array_key_exists('submit', $_GET)){
       
      if(!$_GET['city'])
      {
        $error="Sorry, Your Input Field is empty";
      
      }
    }

?>





<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Weather App</title>

<style>
   body{
        margin:0px;
        padding:0px;
        box-sizing: border-box;
        background-image: url(C:\Users\User\Documents\Weather App\weather pictures\back.jpeg);
   }
.container{
        text-align: center;
        justify-content: center;
        align-items: center;
        width: 440px;
}
h1{
  margin-top: 150px;
}
input{
  width: 350px;
  padding: 5px;
}
</style>



  </head>
  <body>
    <div class="container">
      <h1>Search Global weather</h1>
      <form action="" method="GET">
         <p><label for="city">ENTER YOUR CITY</label></p>
         <p><input type="text" name="city" id="city"placeholder="City Name"></p>
         <button type="submit" name="submit" class="btn btn-success">Submit Now</button>
         <div class="output">
               <?php echo $error; ?>
         </div>
        </form>
    </div>
    
  </body>
</html>