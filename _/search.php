<?php
header('Content-type: application/json');
require "shared.inc.php";

$items = getData();

$query = $_GET['q'];

function contains($query, $field) {
  return preg_match("/".$query."/i", $field) > 0;
}

function filter($item) {  
  global $query;
  if (
    contains($query, $item['title']) ||
    contains($query, $item['descr']) ||
    contains($query, $item['cat']) ||
    contains($query, $item['tags']) 
  ) {
    return true;
  }

  return false;
}

function formatItem($item) {
  return array(
    $title => $item['title']
  );
}

if (strlen($query) <= 2) {
  $result = array();
} else {
  $result = array_map(
    'formatItem', array_filter(
      $items, 'filter'
    )
  );
}

echo json_encode($result);

// echo json_encode($result);
// echo levenshtein("harm","hans");
// echo "\n";
// echo levenshtein("xxxxx", "harm");
// echo "\n";
