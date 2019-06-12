<?php
header('Content-type: application/json');
require "shared.inc.php";

$items = getData();

$query = $_GET['q'];

function contains($query, $field) {
  return preg_match("/".$query."/i", $field) > 0;
}

function hasImageCaption($query, $item) {
  if ($item['images']) {
    if ($item['images']['filenames']) {
      $images = $item['images']['filenames'];
      for ($i = 0; $i < count($images); $i++) {
        $image = $images[$i];
        if (contains($query, $image["caption"])) {
          return true;
        }
      }
    }
  }
  return false;
}

function filter($item) {  
  global $query;
  if (
    contains($query, $item['title']) ||
    contains($query, $item['descr']) ||
    contains($query, $item['cat']) ||
    contains($query, $item['tags']) ||
    hasImageCaption($query, $item)
  ) {
    return true;
  }

  return false;
}

function formatItem($item) {
  if ($item['link']) {
    $link = $item['link'];
  } else {
    $link = '/'.$item['perma'];
  }

  return array(
    $title => $item['title'],
    $link => $link
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
