<?php
    header("Content-type:text/html;charset=\"utf-8\"");
    $previsionTiempo = ""; $error="";
    if (array_key_exists('ciudad',$_GET))
    {
        $urlContents = file_get_contents("http://api.openweathermap.org/data/2.5/weather?q=".urlencode($_GET['ciudad'])."&appid=38d051ed302f82af6de229d8b5c83cb5&lang=es");

        
        $array = json_decode($urlContents,true);

        $previsionTiempo = "El tiempo en ".$_GET['ciudad']." es actualmente '".$array['weather'][0]['description']."'";
        $temperatura=$array['main']['temp']-273;
        $previsionTiempo .= ".La temperatura es ".intval($temperatura)."&deg;C";
        $tempMin = $array['main']['temp_min'];
        $tempMax = $array['main']['temp_max'];
        $previsionTiempo .=" oscilando entre ".intval($tempMin)."&deg;C de mínima y ".intval($tempMax)."&deg;C de máxima.";

    }
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <!-- Required meta tags always come first -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css" integrity="sha384-y3tfxAZXuh4HwSYylfB+J125MxIs6mR5FOHamPBG064zB+AFeWH94NdvaCBm8qnd" crossorigin="anonymous">
    <title>¿Qué tiempo hace?</title>
      <style type="text/css">
        html { 
              background: url(background.jpg) no-repeat center center fixed; 
              -webkit-background-size: cover;
              -moz-background-size: cover;
              -o-background-size: cover;
              background-size: cover;
            }
        body {
              background: none;
        }
        .container {
            text-align:center;
            margin-top:250px;
            width:450px;
        }
          input {
              margin: 20px 0;
          }
          
          #previsionTiempo {
              margin-top: 30px;
          }
      </style>
  </head>
  <body>
    <div class="container">
        <h1>¿Qué tiempo hace?</h1>
        <form>
          <fieldset class="form-group">
            <label for="ciudad">Introduce el nombre de una ciudad:</label>
            <input type="ciudad" class="form-control" id="ciudad" name="ciudad" placeholder="Por ej. Londres, Tokyo" value="<?php 
                if (array_key_exists('ciudad',$_GET)) {
                    echo $_GET['ciudad'];} ?>">
          
          </fieldset>

          <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
        <div id="previsionTiempo">
            <?php
                if ($previsionTiempo)
                {
                    echo '<div class="alert alert-success" role="alert">'.$previsionTiempo.'</div>';
                }
            ?>
        </div>
    </div>
    <!-- jQuery first, then Bootstrap JS. -->
    
  </body>
</html>