<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Project/PHP/PHPProject.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" href="style.css" media="screen">
    </head>
    <body>
        <?php
            $url = "http://api.openweathermap.org/data/2.5/forecast?lat=52.15&lon=4.15&units=metric&appid=9f07cc011ced1f0237895b3312734d31";
             $json    = file_get_contents( $url );
             $data    = json_decode( $json, true );
             $data['city']['name'];
             // var_dump($data );
//             print_r($test = $data['list']['0']);
            foreach ( $data['list'] as $day => $value ) {
                echo 'Max temperature for day ' . $day
                . ' will be ' . $value['main']['temp'] . '<br />';
                echo '<img src="http://openweathermap.org/img/w/' . $value['weather'][0]['icon'] . '.png"
                            class="weather-icon" />';
    
            }
        ?>
    </body>
</html>
