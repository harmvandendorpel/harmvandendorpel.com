<?php
header('Content-type: application/json');
require "shared.inc.php";

$items = getData();

function filter($item) {
  return true;
}

$result = array_filter($items, 'filter');

echo json_encode($result);

// echo levenshtein("harm","hans");
// echo "\n";
// echo levenshtein("xxxxx", "harm");
// echo "\n";
