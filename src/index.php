<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" href="style.css" media="screen">
    </head>
    <body>
        
         <?php   
         require_once './db.php';
//         require_once '../.env';
         echo getenv('PASSWORD_FILE_PATH');
            // Read the database connection parameters from environment variables
            $db_host = getenv('DB_HOST');
            $db_name = getenv('DB_NAME');
            $db_user = getenv('DB_USER');
//            $db_pass = getenv('DB_PASSWORD');
            // Read the password file path from an environment variable
            
            $password_file_path = getenv('PASSWORD_FILE_PATH');
            if (!$password_file_path || !file_exists($password_file_path)) {
                echo "Error: Password file does not exist at the specified path.";
            }
            
            // Read the password from the file
            $db_pass = trim(file_get_contents($password_file_path));
            echo "Connecting to database with:<br>";
            echo "Host: $db_host<br>";
            echo "Database: $db_name<br>";
            echo "User: $db_user<br>";
            //Connect and check the connection
            $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } 
            //make api call and save data
            $url = "http://api.openweathermap.org/data/2.5/forecast?lat=52.15&lon=4.15&units=metric&appid=9f07cc011ced1f0237895b3312734d31";
            $json    = file_get_contents( $url );
            $data    = json_decode( $json, true );
            $data['city']['name'];
            
            //loop through data 
            foreach ( $data['list'] as $day => $value ) {
                // get one datapoint from each day instead of every 3 hours
                if (str_ends_with($value['dt_txt'], '12:00:00')) {
                    //check for duplicates
                    $stmt = $conn->prepare($duplicates);
                    if (!$stmt) {
                        die("Prepare failed: " . $conn->error);
                    }
                    $stmt->bind_param("s", substr($value['dt_txt'],0,10));
                    $stmt->execute();
                    $stmt->store_result();
                    $count = $stmt->num_rows;
                    $stmt->fetch();
                    $stmt->close();
                    if ($count == "0") {
                        //insert the data 
                        $insert_stmt = $conn->prepare($sql);
                        if (!$insert_stmt) {
                            die("Prepare failed: " . $conn->error);
                        }
                   
                        $insert_stmt->bind_param("ddss", 
                            $value['main']['temp_min'], 
                            $value['wind']['speed'], 
                            $value['weather'][0]['main'], 
                            $value['dt_txt']
                        );
                        
                        if ($insert_stmt->execute()) {
//                            echo "Record inserted successfully.<br>";
                        } else {
                            echo "Error inserting record: " . $insert_stmt->error . "<br>";
                        }
                        
                        $insert_stmt->close();
                    } else {
//                        echo "Record already exists for date: " . htmlspecialchars($value['dt_txt']) . "<br>";
                    }
                    //display the data / text
                    echo 'Max temperature for day ' . $value['dt_txt'] . ' will be ' . $value['main']['temp_max'];
                    echo ' Min temperature are ' . $value['main']['temp_min'];
                    echo ' Temperature feels like ' . $value['main']['feels_like'];
                    echo '<img src="http://openweathermap.org/img/w/' . $value['weather'][0]['icon'] . '.png" class="weather-icon" />'.'</br>';
                    
                    
                }
            }
            //get average temp for the second to last 5 days
            $stmt = $conn->prepare($averagetemppast);
            if (!$stmt) {
                die("Prepare failed: " . $conn->error);
            }
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_all(MYSQLI_ASSOC); // Fetching all rows as associative array
            $temps = array();
            foreach ($data as $row) {
                $temps[] = $row['temp'];
            }
            $avTemp = array_sum($temps)/count($temps);
            $stmt->close();
            //get average temp for the last 5 days
           
            $stmt2 = $conn->prepare($averagetempfut);
            if (!$stmt2) {
                die("Prepare failed: " . $conn->error);
            }
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            $data2 = $result2->fetch_all(MYSQLI_ASSOC); // Fetching all rows as associative array
            $temps2 = array();
            foreach ($data2 as $row) {
                $temps2[] = $row['temp'];
            }
            $avTempFuture = array_sum($temps2)/count($temps2);
            $stmt2->close();
            
            echo 'Average temp past: '.$avTemp.' and Future:'.$avTempFuture;
            if($avTemp > $avTempFuture){
                $showimage = './duck.jpg';
            } else {
                $showimage = './images.jpg';
            }
            $conn->close();
            if($value['main']['temp_max'] > 40){
                $tempImage = './max.jpg';
            }
            else if($value['main']['temp_min'] < 10){
                $tempImage = './min.jpg';
            } else {                
                $tempImage = './decent.jpg';
            }
           
        ?>
        <img class = "img" src="<?php echo $showimage; ?>" />
        <?php  echo "temperate is"; ?>
        <img class = "img" src="<?php echo $tempImage; ?>" />
        <?php 
        if($value['wind']['speed'] > 10){
            $windImage = './hurricane.jpg';
        } else {
            $windImage = './nowind.jpg';
        }
        echo 'The wind strength is: ';
        ?>
         <img class = "img" src="<?php echo $windImage; ?>" />
         <?php
            if($value['weather'][0]['main'] == 'Rain'){
                $rainImage = './rain.jpg';
            } else {
                $rainImage = './norain.jpg';
            }
            echo 'The rain is :';
         ?>
         <img class = "img" src="<?php echo $rainImage; ?>" />
    </body>
</html>
