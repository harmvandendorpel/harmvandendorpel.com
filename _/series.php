<?php require('template.php'); ?><!doctype html>
<html>
<head>
<?php
  $perma = $_GET['series'];
  $series = getSeries($perma);
  
  $title = $series['title'];
  $description = 'description';
  $this_page_url = '/series/xxx';
  $list_meta_pic = 'images.jpg';
  $keywords = 'test';

  meta($title, $description, $list_meta_pic, $this_page_url, $keywords);

  $content = $json_data['content'];
  $pages = gatherSeriesItems($perma);
  $works = gatherSeriesWorks($perma);

  
?>
</head>
<body itemscope itemtype="http://schema.org/WebPage">

</body>
</html>