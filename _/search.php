<?php
header('Content-type: application/json');
require "template.php";

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
    (
      contains($query, $item['title']) ||
      contains($query, $item['descr']) ||
      contains($query, $item['cat']) ||
      contains($query, $item['location']) ||
      contains($query, $item['tags']) ||
      hasImageCaption($query, $item
    )
    ) &&
    $item['private'] !== true
  ) {
    return true;
  }

  return false;
}

function formatItem($item) {
  // if ($item['link']) {
  //   $link = $item['link'];
  //   $external = true;
  // } else {
  //   $link = '/'.$item['perma'];
  //   $external = false;
  // }

  return $item;
  // return array(
  //   title => $item['title'],
  //   link => $link,
  //   external => $external,
  //   date => $item['date'],
  //   location => $item['location'],
  //   type => 'item'
  // );
}

function content($items) {
  $result = '';
  foreach($items as $item) $result .= indexItem($item, true, null, false);
  return $result;
}

function main() {
  global $query, $items;
  if (strlen($query) < 1) {
    $result = array();
  } else {
    $result_assoc = array_map(
      'formatItem', 
      array_filter(
        $items, 'filter'
      )
    );
    $result = array();
    foreach ($result_assoc as $key => $value) {
      $result[] = $value;
    }

    $result = array_slice($result, 0, 12);
  }

  echo json_encode(
    array(
      items => $result,
      html => content($result)
    )
  );
}

main();