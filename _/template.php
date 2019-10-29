<?php
require 'shared.inc.php';
// logPageView();
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

    $cssCaching = ENVIRONMENT === 'development'
      ? mt_rand(0, 10000000000000)
      : '2e434234234';
    ?>

    <meta name="keywords" content="<?php echo implode(', ', $keywordArr); ?>">
    <link rel="alternate" type="application/rss+xml" title="Harm van den Dorpel RSS Feed" href="https://harmvandendorpel.com/feed/" />
    <link rel="stylesheet" href="<?php echo ABSOLUTE_URL?>/_/css/agipo.css" />
    <link href="<?php echo ABSOLUTE_URL; ?>/_/css/harmvandendorpel.css?cache=<?php echo $cssCaching ?>" rel="stylesheet">
    <?php
    if ($white) {
        echo "<style>body{background-color:white;} .back-button {background-color:white !important}  .floating-about a, .floating-top-left-nav a {background-color:white !important}</style>";
    }
    ?>
<?php
}

function backButton($useCategory) { ?>
    <div class="floating-top-left-nav">
        <a rel="nofollow" href="/" class="back-button" id='btn-back-button'>index</a>
    </div>
    
    <?php if ($useCategory) { ?>
    <script>
      // if (window.localStorage && window.localStorage.getItem('category')) {
      //   const button = document.getElementById('btn-back-button');
      //   const cat = window.localStorage.getItem('category');
      //   button.href = '/list/' + cat;
      //   button.innerHTML = decodeURIComponent(cat);        
      // }
    </script>
    <?php } ?>
    
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

function thumb($item) {
  ?>
    <div class='thumb-container'>
      <a href='<?php echo $item['link']; ?>' class='thumb-image-link'>
        <div class='index-thumb-item'>
          <div class='thumb-item-image' style='background-image: url(<?php echo $item['image'] ?>);'></div>
        </div>
        <div style='width: 100%; text-align:center;'><?php echo $item['title']; ?></div>
      </a>
    </div>
  <?php
}

function item($data) {
  $images = $data['parts'];
  $path = $images['path'];
  $filenames = $images;
  $filename = $filenames[0]['filename'];

  for ($i = 0; $i < count($filenames); $i++) {
    $image = $filenames[$i];
    if ($image['indexPic']) {
      $filename = $image['filename'];
    }
  }

  $imgUrl = "/img$path$filename";

  // list($width, $height) = getimagesize('.' . $imgUrl);
  
  ?>
    <div class="image-wide-index" style='position: relative;'>
      <a href='/<?php echo $data['perma']; ?>'>

        <div class='image-container-proportional'>
          <div class='image-loading-watermark'>
            <span class='image-inner-proportional'></span>
          </div>
          <div class='image-proportional' style='background-image: url(<?php echo $imgUrl; ?>);'>
            <span class='image-inner-proportional'></span>
          </div>
        </div>

        <div class='index-header-container'><h1 style='background-color: white; display: inline; padding: 10px 15px;'><?php echo $data['title']; ?></h1></div>
      </a>
    </div>
  <?php
}


function do404($perma, $content) {
    http_response_code(307);
    $rating = array();
    foreach ($content as $item) {
      $b = strtolower($item['perma']);
	  $l = levenshtein($perma, $b);
	  $rating[$b] = $l;
    }
    asort($rating);
    reset($rating);
    $target = key($rating);
    $redirect_url = ABSOLUTE_URL.'/'.$target;
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
    $targetBlank = false;
  }
  $isUpcoming = isUpcoming($content['date']);

  $caption = $content['title'];

  $asImage = $forceImage || $content['parts'] && $content["indexPic"] == 'true' && !$textOnly;
  $result = '';
  
  $extraClass = $asImage ? 'index-item-image': '';
  $id = 'item-'.$content['perma'];
  $result .= "<li class='item $extraClass' id='$id' style='width: 100%;'>";
  $target = $targetBlank ? "target='_blank'": '';

  $result .= "<a href='$url' rel='bookmark' $target>";
  
  $result .= $asImage ? indexImage($content, $isUpcoming) : $caption;
  $result .='</a>';
  $date = d($content['date']);

  if (!$asImage && !$content['location']) {
    $result .= "<div class='list-item-date'>$date</div>";
  } 

  if ($content['location'] && !$asImage) {
    $location = $content['location'];   
    $result .= "<div class='list-item-date'>&nbsp;&nbsp;&mdash;&nbsp;&nbsp;$date</div>";
    $result .= "<div style='float: right;' class='location'>$location</div>";
  }  
  $result .= '</li>';
  return $result;
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
    $images = $content['parts'];
    $path = $images['path'];
    if (!$path) {
      $path = '/';
    }

    for ($i=0; $i < count($images);$i++) {
      $image = $images[$i];
      if ($image['index']) {
        $firstPic = $image;
      }
    }

    if (!$firstPic) {
      $firstPic = $images[0];
    }

    $fullFilename = '/img'.$path.$firstPic['filename'];
    list($width, $height) = getimagesize('.'.$fullFilename);

    $class = $width > $height ? 'landscape' : 'portrait';

    $url = ABSOLUTE_URL.'/img'.$path.$firstPic['filename'];
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
    
    $result = '';
    $result .= "<figure class='main-image $class'>";
    $result .= "<img alt='$caption' src='$url'>";
    $result .= "<figcaption>$figCaption</figcaption>";
    $result .= "</figure>";
    return $result;
}

function d($s) {
    $date = new DateTime($s);

    if (isUpcoming($s)) {
        $result = $result = $date->format('j F Y'). ' (upcoming)';
    } else {
        $result = $result = $date->format('F Y');
    }
    return $result;
}


function searchItem($item) {
    $link = $item['link'];
    $title = $item['title'];
    $text = $item['text'];
    $from = $item['from'];
    $before = "<em>";
    $to = $item['to'] + strlen($before);
    $caption = substr_replace($text, "<em>", $from, 0);
    $caption = substr_replace($caption, "</em>", $to, 0);
    if ($item['parts']) {
      $images = $item['parts'];
      $imageHtml = '<div class="search-item-thumbs-container">';
      for ($i = 0; $i < count($images); $i++) {
        $image = $images[$i];
        $imageHtml .= "<img onclick='location.href=\"".$link."\"' loading='lazy' class='search-item-thumbnail' src='".$image."' />";
      }
      $imageHtml .= '</div>';
    } else {
      $imageHtml = '';
    }
    return "
      <li class='item'>
        <a href='$link'>$title</a><div style='float:right;'>$caption</div>
        $imageHtml
      </li>
    ";
  }
  
  function searchContent($items) {
    $result = '';
    foreach($items as $item) $result .= searchItem($item);
    return $result;
  }

  function contains($query, $field) {
    return preg_match("/".$query."/i", $field) > 0;
  }
  
  function makeLink($item) {
    return $item['link'] ? $item['link'] : '/'.$item['perma'];
  }
  
  function searchTitle($query, $item, &$result) {  
    $pos = stripos($item['title'], $query);
    if ($pos !== FALSE) {
            
      $searchItem = array(
        title => $item['title'],
        text => $item['title'],
        from => $pos,
        to => $pos + strlen($query),
        link => makeLink($item),
        type => 'title'
      );

      if ($item['parts']) {
        $images = $item['parts'];
        $path = $images['path'] ? $images['path'] : '/';

        for ($i = 0; $i < min(4, count($images)); $i++) {
          $firstImage = $images[$i];
          $imageUrl = '/thumb'.$path.$firstImage['filename'];
          $searchItem['parts'][] = $imageUrl;
        }
      }

      $result[] = $searchItem;
      return true;
    }
    return false;
  }
  
  function searchLocation($query, $item, &$result) {  
    if ($item['location']) {
      $pos = stripos($item['location'], $query);
      if ($pos !== FALSE) {
        
        $result[] = array(
          title => $item['title'],
          text => $item['location'],
          from => $pos,
          to => $pos + strlen($query),
          link => makeLink($item),
          type => 'location'
        );
        return true;
      }
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
      $tag = trim($categories[$i]);
      $pos = stripos($tag, $query);
      if ($pos !== FALSE) {
        $result[] = array(
          title => $item['title'],
          text => $tag,
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
    if ($item['parts']) {
      if ($item['parts']) {
        $images = $item['parts'];
        $path = $item['parts']['path'];
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
              images => array('/thumb'.$path.$image['filename'])
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
  
  function search($query, $items) {
    $result = array();
  
    if (strlen($query) > 0) {
      for ($i = 0; $i < count($items); $i++) {
        $item = $items[$i];
        if ($item['private'] === TRUE) continue;
        searchTitle($query, $item, $result) ||
        searchImages($query, $item, $result) ||
        searchDescr($query, $item, $result) ||
        searchTags($query, $item, $result) ||
        searchLocation($query, $item, $result) ||
        searchCategory($query, $item, $result);
      }
    }
    return $result;
  }
  
