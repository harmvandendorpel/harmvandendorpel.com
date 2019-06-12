<?php
require 'shared.inc.php';

error_reporting(E_ERROR | E_PARSE);

function meta($title, $metaDescription, $metaImg=null, $thisPageUrl, $metaKeywords, $white = false) { ?>
    <meta charset="UTF-8">
    <title><?php echo $title; ?></title>
    <meta content='<?php echo ABSOLUTE_URL; ?>/_/favicon.png' property='og:image'>
    <meta content='<?php echo ABSOLUTE_URL; ?>/_/favicon.png' property='twitter:image'>
    <link href="<?php echo ABSOLUTE_URL; ?>/_/favicon.png" rel='shortcut icon'>
    <link href="<?php echo ABSOLUTE_URL; ?>/_/favicon.png" rel="icon">
    <link href="<?php echo ABSOLUTE_URL; ?>/_/favicon.png" rel="apple-touch-icon" />
    <link href="<?php echo ABSOLUTE_URL; ?>/_/favicon.png" rel="apple-touch-icon" sizes="76x76" />
    <link href="<?php echo ABSOLUTE_URL; ?>/_/favicon.png" rel="apple-touch-icon" sizes="120x120" />
    <link href="<?php echo ABSOLUTE_URL; ?>/_/favicon.png" rel="apple-touch-icon" sizes="152x152" />
    <link href="<?php echo ABSOLUTE_URL; ?>/_/favicon.png" rel="apple-touch-icon" sizes="180x180" />
    <link href="<?php echo ABSOLUTE_URL; ?>/_/favicon.png" rel="icon" sizes="192x192" />
    <link href="<?php echo ABSOLUTE_URL; ?>/_/favicon.png" rel="icon" sizes="128x128" />
    <link rel="canonical" href="<?php echo $thisPageUrl; ?>">
    <meta name="description" content="<?php echo $metaDescription; ?>" >
    <meta name="keywords" content="<?php echo $metaKeywords; ?>" >
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=4.0, user-scalable=yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black" >

    <meta property="fb:app_id" content="717770564991882">
    <meta property="twitter:title" content="<?php echo $title; ?>">
    <meta property="og:title" content="<?php echo $title; ?>">
    <meta property="og:description" content="<?php echo $metaDescription; ?>">
    <meta property="twitter:description" content="<?php echo $metaDescription; ?>">
    <meta property="og:url" content="<?php echo $thisPageUrl; ?>">
    <meta property="twitter:url" content="<?php echo $thisPageUrl; ?>">
    <meta property="og:site_name" content="Harm van den Dorpel">
    <meta property="og:type" content="article">
    <meta property="og:locale" content="en_US">
    <?php if ($metaImg): ?>
    <meta property="og:image" content="<?php echo ABSOLUTE_URL.$metaImg; ?>">
    <meta property="twitter:image" content="<?php echo ABSOLUTE_URL.$metaImg; ?>">
    <meta property="twitter:image:src" content="<?php echo ABSOLUTE_URL.$metaImg; ?>">
    <?php endif;?>
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:site" content="@harmvddorpel">
    <meta property="twitter:creator" content="@harmvddorpel">
    <meta name="theme-color" content="#efefef">
    <meta content='#efefef' name='msapplication-TileColor'>

    <?php
    if (!$metaKeywords) {
        $metaKeywords = "";
    }

    $keywordArr = explode(',', $metaKeywords);
    $keywordArr[] = 'art';
    $keywordArr[] = 'language';
    $keywordArr[] = 'technology';

    $keywordArr = array_unique($keywordArr);
    $keywordArr = array_map("trim", $keywordArr);

    foreach($keywordArr  as $tag) {
        echo "<meta property=\"article:tag\" content=\"$tag\">\n";
    }
    ?>

    <meta name="keywords" content="<?php echo implode(', ', $keywordArr); ?>">
    <link rel="alternate" type="application/rss+xml" title="Harm van den Dorpel RSS Feed" href="https://harmvandendorpel.com/feed/" />
    <link href="<?php echo ABSOLUTE_URL; ?>/_/css/harmvandendorpel.css?a=25483598<?php // echo mt_rand(0, 10000000000000); ?>" rel="stylesheet">
    <?php
    if ($white) {
        echo "<style>body{background-color:white;} .back-button {background-color:white !important}  .floating-about a, .floating-top-left-nav a {background-color:white !important}</style>";
    }
    ?>
<?php
}

function backButton() { ?>
    <div class="floating-top-left-nav">
        <a rel="nofollow" href="/" class="back-button" id='btn-back-button'>index</a>
    </div>

    <script>
    if(window.location.hash && window.location.hash !== '#pics') {
        var button = document.getElementById('btn-back-button');
        var cat = window.location.hash.substring(1);
        button.href = '/list/' + cat;
        button.innerHTML = decodeURIComponent(cat);
    }
    </script>
<?php
}

function script() {
    ?>
    <script src="/_/js/jquery-1.11.1.min.js"></script>
<?php
}

function upcomingString($dateString) {
  $item_date = new DateTime($dateString);
  $item_year = date_format($item_date, 'Y');
  $item_month = date_format($item_date, 'M');
  return $item_month; // .' '.$item_year;
}

function do404($perma, $content) {
    http_response_code(301);
    $rating = array();
    foreach ($content as $item) {
      $b = strtolower($item['perma']);
	  $l = levenshtein($perma, $b);
	  $rating[$b] = $l;
    }
    asort($rating);
    reset($rating);
    $target = key($rating);
    $redirect_url = "https://harmvandendorpel.com/$target";
    // echo $redirect_url;
    header("Location: $redirect_url");
    die();
}

function indexItem($content, $textOnly = false, $cat=null, $forceImage=false) {
  if ($content['link']) {
    $url = $content['link'];
    $targetBlank = true;
  } else {
    $url = ABSOLUTE_URL.'/'.$content['perma'];
    if ($cat) {
    $url .= '#' . $cat;
    }
    $targetBlank = false;
  }
  $isUpcoming = isUpcoming($content['date']);

  $caption = $content['title'];
//   if ($isUpcoming && false) {
//     $caption .= ' ('.upcomingString($content['date']).')';
//   }

  $asImage = $forceImage || $content['images'] && $content["indexPic"] == 'true' && !$textOnly;
  ?>
  <li class="item <?php echo $asImage ? 'index-item-image': ''; ?>" id="item-<?php echo $content['perma'];?>" style="width: 100%;">
      <a href="<?php echo $url; ?>" rel="bookmark" <?php if ($targetBlank) {echo "target='_blank'";}?>><?php if ($asImage) {
          indexImage($content, $isUpcoming);
      } else {
          echo $caption;
      }
      ?></a>
      
      <?php
        $date = d($content['date']);
        if (!$asImage && !$content['location']) {
          echo "<div class='list-item-date'>$date</div>";
        }

        if ($content['location'] && !$asImage) {
            $location = $content['location'];   
            echo "<div class='list-item-date'>&nbsp;&nbsp;&mdash;&nbsp;&nbsp;$date</div>";
            echo "<div style='float: right;' class='location'>$location</div>";
        }
      ?>
  </li>
<?php
}

function footer($showMailinglist=true) {
    ?><script>
        $(document).ready(function () {
            $('*[data-link]').each(function () {
                $(this).bind('click touch', function () {
                    location.href = $(this).data().link;
                });
            });
        });
    </script><?php
}

function indexImage($content, $isUpcoming = false) {
    $images = $content['images'];
    $path = $images['path'];
    if (!$path) {
      $path = '/';
    }

    for ($i=0; $i < count($images['filenames']);$i++) {
      $image = $images['filenames'][$i];
      if ($image['index']) {
        $firstPic = $image;
      }
    }

    if (!$firstPic) {
      $firstPic = $images['filenames'][0];
    }

    $fullFilename = '/img'.$path.$firstPic['filename'];
    list($width, $height) = getimagesize('.'.$fullFilename);

    $class = $width > $height ? 'landscape' : 'portrait';

    $url = ABSOLUTE_URL.'/img'.$path.rawurlencode($firstPic['filename']);
    $caption = $content['title'];
    if ($isUpcoming) {
    	$caption .= ' (upcoming)';
    }

    if ($content['location']) {
        $location = $content['location'];
        $date = d($content['date']);
        $figCaption = "<div><div class='image-caption'>$caption</div><div class='list-item-date'>&nbsp;&nbsp;&mdash;&nbsp;&nbsp;$date</div><div style='float:right;' class='location'>$location</div></div>";
    } else { 
        $figCaption = "<div>$caption</div>";
    }
    
    ?>
    <figure class='main-image <?php echo $class;?>'>
        <img alt='<?php echo $caption;?>' src='<?php echo $url; ?>'>
        <figcaption><?php echo $figCaption;?></figcaption>
    </figure>
<?php
}

function d($s) {
    $m = array(
        1 => 'January',
        2 => 'February',
        3 => 'March',
        4 => 'April',
        5 => 'May',
        6 => 'June',
        7 => 'July',
        8 => 'August',
        9 => 'September',
        10 => 'October',
        11 => 'November',
        12 => 'December',
    );

    $date = new DateTime($s);

    // $month = $date->format('M');
    // $arr = explode('-', $s);
    // $monthIndex = (int)trim($arr[1]);

    if (isUpcoming($s)) {
        $result = $result = $date->format('n F Y'). ' (upcoming)';
    } else {
        $result = $result = $date->format('F Y'); // $m[$monthIndex].' '. $arr[0];
    }
    return $result;
}

