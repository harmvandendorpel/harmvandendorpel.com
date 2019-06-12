<?php
header('Content-type: application/json');
require "shared.inc.php";

$items = getData();

$query = $_GET['q'];

function filter($item) {  
  global $query;
  if (
    preg_match("/".$query."/i", $item['title'])  > 0 ||
    preg_match("/".$query."/i", $item['descr'])  > 0
  ) {
    return true;
  }

  return false;
}

if (strlen($query) <= 2) {
  $result = array();
} else {
  $result = array_filter($items, 'filter');
}

echo json_encode($result);

// echo json_encode($result);
// echo levenshtein("harm","hans");
// echo "\n";
// echo levenshtein("xxxxx", "harm");
// echo "\n";
