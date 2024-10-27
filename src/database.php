<?php

$servername = "db";
$username = "root";
$password = "CoolPassword";
$dbname = "weather";

try {
//    //Connect and check the connection
$conn = new mysqli($servername, $username, $password, $dbname);
echo 'test';
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
    
//$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
//$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$tableCheck = "SHOW TABLES LIKE 'report'";
$tableChecked = $conn->prepare($tableCheck);
//echo 'test';
$tableChecked->execute();
$tableChecked->store_result();

if($tableChecked->num_rows == 0){
    $createTable = "CREATE TABLE `report` (`temp` int(3) NOT NULL,`wind` int(3) NOT NULL,`rain` varchar(10) NOT NULL,`date` date NOT NULL)";
    $createdTable = $conn->prepare($createTable);
    $createdTable->execute();
    $createdTable->store_result();
}

$dataCheck = "SELECT date FROM `report`";
$dataChecked = $conn->prepare($dataCheck);
$dataChecked->execute();

//if(!isset($data['date'])) {
if(!$dataChecked->num_rows == 0){
    $insert = "INSERT INTO `report` (`temp`, `wind`, `rain`, `date`) VALUES(12, 9, 'Clouds', '2024-10-11'),"
            . "(11, 9, 'Clouds', '2024-10-12'),(9, 9, 'Clouds', '2024-10-13'),(10, 9, 'Clouds', '2024-10-14'),(13, 8, 'Clouds', '2024-10-15')";
    $tableInsert = $conn->prepare($insert);
    $tableInsert->execute();
    $tableInsert->store_result();
}

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
//// set parameters and execute
//$temp = 10.05;
//$wind = 8.5;
//$rain = false;                 
//$date = "2024-10-14 12:00:00";
//
//// prepare and bind
//$stmt = $conn->prepare("INSERT INTO report (temp, wind, rain, date) VALUES (?, ?, ?, ?)");
//$stmt->bind_param("ssss", $temp, $wind, $rain, $date);
//$stmt->execute();
} catch(PDOException $e) {
  echo "Error: " . $e->getMessage();
}
?>
