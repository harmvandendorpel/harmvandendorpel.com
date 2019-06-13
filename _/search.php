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

function makeLink($item) {
  return $item['link'] ? $item['link'] : '/'.$item['perma'];
}
function searchTitle($query, $item, &$result) {  
  $pos = stripos($item['title'], $query);
  if ($pos !== FALSE) {
    
    $result[] = array(
      title => $item['title'],
      from => $pos,
      to => $pos + strlen($query),
      link => makeLink($item),
      type => 'title'
    );
    return true;
  }
  return false;
}

function searchCategory($query, $item, &$result) {  
  $categories = explode(',', $item['cat']);
  for ($i = 0; $i < count($categories); $i++) {
    $category = trim($categories[$i]);
    $pos = stripos($category, $query);
    if ($pos !== FALSE) {
      $result[] = array(
        title => $item['title'],
        text => $category,
        from => $pos,
        to => $pos + strlen($query),
        link => makeLink($item),
        type => 'category'
      );
      return true;
    }
  }
  return false;
}

function searchTags($query, $item, &$result) {  
  $categories = explode(',', $item['tags']);
  for ($i = 0; $i < count($categories); $i++) {
    $category = trim($categories[$i]);
    $pos = stripos($category, $query);
    if ($pos !== FALSE) {
      $result[] = array(
        title => $item['title'],
        from => $pos,
        to => $pos + strlen($query),
        link => makeLink($item),
        type => 'tag'
      );
      return true;
    }
  }
  return false;
}

function searchImages($query, $item, &$result) {  
  if ($item['images']) {
    if ($item['images']['filenames']) {
      $images = $item['images']['filenames'];
      $path = $item['images']['path'];
      for ($i = 0; $i < count($images); $i++) {
        $image = $images[$i];
        $caption = $image['caption'];
        $pos = stripos($caption, $query);
        if ($pos !== FALSE) {
          $result[] = array(
            title => $item['title'],
            text => $caption,
            from => $pos,
            to => $pos + strlen($query),
            link => makeLink($item),
            type => 'image',
            filename => '/img'.$path.$image['filename']
          );
          return true;
        }
      }
    }
  }
  return false;
}

function searchDescr($query, $item, &$result) {  
  $sentences = preg_split('/[.,?]+/', strip_tags($item['descr']));
  for ($i = 0; $i < count($sentences); $i++) {
    $sentence = trim($sentences[$i]);
    $pos = stripos($sentence, $query);
    if ($pos !== FALSE) {
      $result[] = array(
        title => $item['title'],
        text => $sentence,
        from => $pos,
        to => $pos + strlen($query),
        link => makeLink($item),
        type => 'descr'
      );
      return true;
    }
  }  
  return false;
}

function content($items) {
  $result = '';
  foreach($items as $item) $result .= indexItem($item, true, null, false);
  return $result;
}

function main() {
  global $query, $items;
  $result = array();

  if (strlen($query) > 1) {
    for ($i = 0; $i < count($items); $i++) {
      $item = $items[$i];
      if ($item['private'] === TRUE) continue;
      searchTitle($query, $item, $result) ||
      searchImages($query, $item, $result) ||
      searchDescr($query, $item, $result) ||
      searchTags($query, $item, $result) ||
      searchCategory($query, $item, $result);
    }

    $result = array_slice($result, 0, 12);
  }

  echo json_encode(
    array(
      items => $result,
      //html => content($result)
    )
  );
}

main();