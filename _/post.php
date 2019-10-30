<?php

  require('template.php');

  $content = getContent();
  $perma = $_GET['perma'];
  $item = findItem($content, $perma);
  
  if ($item == null) {
    do404($perma, $content);
  }

?><!doctype html>
<html lang="en">
<head><?php
    $title = $item['title'];
    $type = $item['type'] || 'page';
    $isSeries = $item['type'] === 'series';

    $thisPageUrl = "http://harmvandendorpel.com/$perma";

    $description = $item['descr'];
    if ($item["images"] && count($item["images"])) {
      $path = "/img";
      $filename = str_replace(' ', '%20',$item["images"][0]["filename"]);
      $imgUrl = $path.$filename;
    }

    $metaKeywords = $item['tags'];
    $white = $item['white'] == 1;
    if ($item['meta']) {
      $metaDescription = $item['meta'];
    } else {
      $metaDescription = summary($description);
    }
    
    meta($title.' - Harm van den Dorpel', $metaDescription, $imgUrl, $thisPageUrl, $metaKeywords, $white);

    $isUpcoming = isUpcoming($item['date']);

    if (array_key_exists('theme', $item)) {
      $themeClass = 'theme-'.$item['theme'];
    } else {
      $themeClass = 'theme-default';
    }
    ?>
</head>

<body class="<?php echo $themeClass; ?>">

<article itemscope itemtype="http://schema.org/BlogPosting">
    <div class="content">
        <?php if ($item['date']): ?>
            <div class="date">
                <time datetime="<?php echo $item['date']; ?>T00:00+00:00"><?php echo d($item['date']);?></time>
                <?php if ($item['ongoing']) { ?>– ongoing<?php } ?>
            </div>
            <br>
        <?php endif; ?>
        <header>
            <h1 itemprop="headline"><?php echo $title; ?></h1>
        </header>

        <?php if ($description): ?>
            <section itemprop="articleBody">
                <div class="description<?php if (strlen($description) > 850) { echo " description-columns";}?>">
                    <?php echo $description;?>
                </div>
            </section>
        <?php endif; ?>

        <?php if ($item['related']): ?>
            <section>
                <div class="related">
                    <?php related($item['related']); ?>
                </div>
            </section>
        <?php endif; ?>
        
       

        <?php if ($item['seeAlso']): ?>
            <aside>
                <div class="categories">
                    <?php seeAlso($item['seeAlso']); ?>
                </div>
            </aside>
        <?php endif ?>
        
    </div>
    <?php foreach ($item['parts'] as $part): ?>
        <?php process_part($part); ?>
    <?php endforeach; ?>

    <div class="index-content">
        <div class="index-index">
            <div class="index-index-list" id="list-default">
            <?php // foreach($series_items as $item) item($item); ?>
            </div>
        </div>
    </div>

    <?php if ($item['cat']): ?>
            <footer>
                <div class="categories">
                    <?php cats($item['cat'], $isUpcoming); ?>
                </div>
            </footer>
    <?php endif ?>
</article>
<?php backButton(true); ?>
<?php script(); ?>
</body>
</html>

<?php

function cats($cats, $isUpcoming) {
    $catArr = explode(',', $cats);

    if ($isUpcoming) {
        $catArr[] = 'upcoming';
    }

    echo "List all from ";
    $first = true;
    foreach($catArr as $cat) {
        $cat = trim($cat);
        if (!$first) echo ' or ';
        echo "<a rel='category' href='".ABSOLUTE_URL."/list/".rawurlencode($cat)."'>$cat</a>";

        $first = false;
    }
}

function findItem($content, $perma) {
    foreach($content as $item) {
        if (strtolower($item['perma']) == strtolower($perma) && !isPrivate($item) ) {
            return $item;
        }
    }
    return null;
}

// function gatherSeriesItems($perma) {
//     global $content;
//     $result = [];
    
//     for ($i = 0; $i < count($content); $i++) {
//       $item = $content[$i];

//       if ($item['series'] === $perma && !isPrivate($item) && $item['type'] !== 'series') {
//         $result[] = $item;
//       }
//     }

//     return $result;
//   }

//   function gatherSeriesWorks($perma) {
//     global $content;
//     $result = [];
    
//     for ($i = 0; $i < count($content); $i++) {
//       $item = $content[$i];
//       $imagesData = $item['parts'];
//       $images = $imagesData;
//       for ($j = 0; $j < count($images); $j++) {
//         $image = $images[$j];
//         $filename = $image['filename'];
//         if ($image['series'] === $perma || $item['perma'] === $perma) {
//           $result[] = array(
//             'perma' => $item['perma'],
//             'type' => 'work',
//             'image' => "/img$filename",
//             'caption' => $image['caption']
//           );
//         }
//       }
//     }

//     return $result;
//   }

function images($content) {
  echo '<div class="images" id="pics">';
    
  if (count($content)) {
    foreach ($content as $image) {
        if ($image['caption']) {
            $alt = $image['caption'];
        } else {
            $alt = $item['title'];
        }
        if ($image['link']) {
            $link = $image['link'];
        } else {
            $link = null;
        }
        image($image, $alt, $link);
    }
  }
  echo "</div>";
}

function related($related) {
    echo "<ul>";
    foreach($related as $relItem) {
        echo "<li>$relItem</li>";
    }
    echo "</ul>";
}


function seeAlso($seeAlso) {
    echo "See also:";
    echo "<ul>";
    foreach($seeAlso as $item) {
        echo "<li>$item</li>";
    }
    echo "</ul>";
}

function image($image, $alt, $link) {
    $fullFilename = '/img/' . $image['filename'];
    list($width, $height) = getimagesize('.' . $fullFilename);

    $url = ABSOLUTE_URL . $fullFilename;
    if ($image['orientation']) {
        $class = $image['orientation'];
    } else {
        $class = $width > $height ? 'landscape' : 'portrait';
    }

    ?>
    <figure class='main-image <?php echo $class; ?>' id='<?php echo $image['filename']?>'>
    <?php if ($link) { ?><a href='<?php echo $link; ?>' target='_blank'><?php } ?>
    <img alt='<?php echo $alt; ?>' src='<?php echo($url); ?>'>
    <?php if ($link) { ?></a><?php } ?>
    <figcaption><?php echo $image['caption']; ?></figcaption>
    </figure><?php
}


function process_part($part) {
  switch ($part['type']) {
    case 'thumbs':
      show_thumbs($part);
      break;

    case 'list':      
      images($part['content']);
      break;
  }
}

function show_thumbs($thumbs) {
  $content = $thumbs['content'];
  $show_captions = $thumbs['captions'];
  echo '<div class="index-thumbs">';
  
  foreach ($content as $thumb)  {
    $filename = $thumb['filename'];
    $link = "/img/$filename";
    $data = array(
      title => $thumb['caption'],
      link => '',
      image => $link
    );
    thumb($data, $show_captions);
  }

  echo '</div>';
}