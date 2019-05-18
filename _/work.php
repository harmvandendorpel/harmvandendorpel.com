<?php
  require('template.php');
?><!doctype html>
<html lang="en" class="index-index-index">
<head>
<?php
  $perma = $_GET['perma'];
  // $content = getWork($perma);
  $title = 'work title';
  $description = 'work description';
  $keywords = 'work keywords';

  meta($title, $description, $imgUrl, 'https://harmvandendorpel.com/', $keywords);
?>
</head>
  <body>work</body>
</html>