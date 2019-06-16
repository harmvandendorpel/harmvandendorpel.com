<?php

include('/var/www/mysql.php');
$database = 'harmvandendorpel';

$conn = new mysqli($servername, $username, $password, $database);

function logPageView() {
  global $conn;
  if (array_key_exists('HTTP_REFERER', $_SERVER)) {
    $referer = $_SERVER['HTTP_REFERER'];
  } else {
    $referer = ''; 
  }	  
  $hostname = $_SERVER['REMOTE_ADDR'];
  $url = $_SERVER['REQUEST_URI'];
  $sql = "INSERT INTO logging (host,referer, url) VALUES ('$hostname','$referer','$url')";
  $conn->query($sql);
}

function logSearch($query) {
  global $conn;
  $referer = $_SERVER['HTTP_REFERER'];
  $hostname = $_SERVER['REMOTE_ADDR'];
  $sql = "INSERT INTO searching (host,referer, search) VALUES ('$hostname','$referer','$query')";
  $conn->query($sql);
}

function hits($url) {
  global $conn;
  $sql = "SELECT COUNT(*) AS hits FROM logging WHERE url='$url' GROUP BY url;";
  $result = $conn->query($sql);
  $firstrow = $result->fetch_assoc();

  return $firstrow['hits'];
}

function thisPageHits() {
  return hits($_SERVER['REQUEST_URI']);
}
