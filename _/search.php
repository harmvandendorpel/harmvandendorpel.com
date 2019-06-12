<?php
header('Content-type: application/json');
require "shared.inc.php";

$items = getData();

$query = $_GET['q'];

function filter($item) {
  if (preg_match("/$query/i", $item['title'])) {
    return true;
  }

  return false;
}

if (strlen($query) <= 1) {
  $result = array();
} else {
  $result = array_filter($items, 'filter');
}

echo json_encode($result);
// echo levenshtein("harm","hans");
// echo "\n";
// echo levenshtein("xxxxx", "harm");
// echo "\n";
