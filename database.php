<?php

$servername = "localhost";
$username = "root";
$password = "CoolPa$$word";
$dbname = "weather";

try {
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// prepare and bind
$stmt = $conn->prepare("INSERT INTO report (temp, wind, rain, date) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $temp, $wind, $rain, $date);

// set parameters and execute
$temp = 10.05;
$wind = 8.5;
$rain = false;                 
$date = "2024-10-14 12:00:00";

$stmt->execute();
} catch(PDOException $e) {
  echo "Error: " . $e->getMessage();
}
?>
