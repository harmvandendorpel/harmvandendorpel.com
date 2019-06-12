<?php

require 'log.inc.php';
const ABSOLUTE_URL = 'https://harmvandendorpel.com';
// const ABSOLUTE_URL = '';
date_default_timezone_set('Europe/Berlin');

function isUpcoming($s) {
  $date = new DateTime($s); //DateTime::createFromFormat('Y-m-d', $s);
  $today = new DateTime();
  return ($today < $date);
}

function getContent() {
  function sortDesc($a, $b) {
    return @strtotime($a['date']) < @strtotime($b['date']);
  }

    function sortAsc($a, $b) {
    return strtotime($a['date']) > strtotime($b['date']);
  }

  function filterUpcomingLambda($item) {
    if (!array_key_exists('date', $item)) return false;
    return isUpcoming($item['date']);
  }

  function filterArchive($item) {
    if (!array_key_exists('date', $item)) return true;	
    return !isUpcoming($item['date']);
  }

  $data = json_decode(file_get_contents("work.json"), true)['content'];

  $upcoming = array_filter($data, "filterUpcomingLambda");
  $archive = array_filter($data, "filterArchive");

  uasort($archive, 'sortDesc');
  uasort($upcoming, 'sortAsc');
  return array_merge($upcoming, $archive);
}

function summary($input) {
  $metaDescription = strip_tags($input);
  $metaDescription = explode('. ', $metaDescription)[0];
  $metaDescription = explode(', ', $metaDescription)[0];
  $metaDescription = explode('<br>', $metaDescription)[0];
  if (strlen($metaDescription) > 140) {
    $metaDescription = explode(' ', $metaDescription);

    while(strlen(join(' ', $metaDescription)) > 140) {
        array_pop($metaDescription);
    }
    $metaDescription[] = '...';
    return join(' ', $metaDescription);
  } else {
    return $metaDescription;
  }
  $metaDescription = join(' ', $metaDescription);
  return $metaDescription;
}

function getListMetaPic($content) {
    for ($i=0; $i < count($content); $i++) {
        if (!array_key_exists($content[$i], 'images')) {
            $images = $content[$i]['images'];
            if (count($images['filenames']) > 0) {
                return '/img'.$images['path'].$images['filenames'][0]['filename'];
            }
        }
    }

    return null;
}

function isPrivate($item) {
    if (isset($item['private'])) {
        return $item['private'] == 1;
    }
    return false;
}

function filterUpcoming($content) {
    $result = array();
    foreach($content as $item) {
        if (isUpcoming($item['date']) && !isPrivate($item)) {
            $result[] = $item;
        }
    }
    return $result;
}

function filterRecent($content) {
    $result = array();
    foreach($content as $item) {
        if (!isUpcoming($item['date']) && !isPrivate($item)) {
            $result[] = $item;
            if (count($result) >= 10) {
                return $result;
            }
        }
    }
    return $result;
}

function filterIndex($content, $cat) {
    $cat = trim(strtolower($cat));

    if ($cat === 'upcoming') {
        return filterUpcoming($content);
    }
    
    if ($cat === 'recent') {
        return filterRecent($content);
    }

    $result = array();
    foreach($content as $item) {
        $catArr = explode(',', (strtolower($item['cat'])));
        $catArr = array_map('trim', $catArr);

        $isUpcoming = isUpcoming($item['date']);
        if (
            in_array($cat, $catArr) &&
            !isPrivate($item) &&
            !array_key_exists('disabled', $item) &&
            (!$isUpcoming || $cat !== 'exhibitions')) {
            $result[] = $item;
        }
    }
    return $result;
}

function filterFeatured() {
    $content = getContent();

    $result = array();
    foreach($content as $item) {
        if ( $item['featured']) { // $item['featured'] &&
            $result[] = $item;
        }
    }
    return $result;
}
