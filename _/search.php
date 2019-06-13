<?php
header('Content-type: application/json');
require "template.php";

$items = getData();
$query = $_GET['q'];
main($query, $items);

function main($query, $items) { 
  $result = search($query, $items);
  echo json_encode(
    array(
      items => $result,
      html => searchContent($result)
    )
  );
}
