<?php
// header('Content-type: application/json');
require "shared.inc.php";

$items = getData();

$query = $_GET['q'];

function filter($item) {  
  global $query;
  if (preg_match("/".$query."/i", $item['title'])  > 0) {
    return true;
  }

  return false;
}

if (strlen($query) <= 2) {
  $result = array();
} else {
  $result = array_filter($items, 'filter');
}

echo "\n\n";
echo "$query items.\n\n";
echo count($result);
echo "\n\n";

// echo json_encode($result);
// echo levenshtein("harm","hans");
// echo "\n";
// echo levenshtein("xxxxx", "harm");
// echo "\n";
