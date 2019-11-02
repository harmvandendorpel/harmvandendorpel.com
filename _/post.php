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
    $type = $item['kind'] ? $item['kind'].': ' : '';
    $title = $type.$item['title'];

    $thisPageUrl = "http://harmvandendorpel.com/$perma";

    $description = $item['descr'];
    // if ($item["images"] && count($item["images"])) {
    //   $path = "/img";
    //   $filename = str_replace(' ', '%20',$item["images"][0]["filename"]);
    //   $imgUrl = $path.$filename;
    // }
    $imgUrl = $item['meta_image'];

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
                <?php if ($item['location']): ?>
                    <span style='font-size:16px;'><?php echo $item['location'];?>, </span>
                <?php endif; ?>
                <time datetime="<?php echo $item['date']; ?>T00:00+00:00">
                  <?php echo d($item['date']);?>
                  <?php if ($item['ongoing']) { ?>– ongoing<?php } ?>
                </time>
                
            </div>
            <br>
        <?php endif; ?>
        <header>
            <h1 itemprop="headline"><?php echo $title; ?></h1>
        </header>

        <?php if ($description): ?>
            <section itemprop="articleBody">
                <div class="description<?php if (strlen($description) > 680) { echo " description-columns";}?>">
                    <?php echo $description;?>
                </div>
            </section>
        <?php endif; ?>

        <?php if ($item['seeAlso']): ?>
            <aside>
                <div class="categories" style='margin-bottom: 50px;'>
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



    <?php if ($item['related']): ?>
    <div class="related-items-container">
        <div class='related-item-intro'>
            <?php echo $item['related']['caption']; ?>
        </div>
        <?php foreach ($item['related']['content'] as $related): ?>
            <?php $related_item = findItem($content, $related['perma']); ?>
            <a class='related-item' href='/<?php echo $related['perma']; ?>'>
                <h1 style='margin:0' class='related-item-title'><?php echo $related_item['title']; ?></h1>
                <?php if($related_item['location']): ?>
                    <span class='related-item-location' style='font-size:16px;'><?php echo $related_item['location']; ?></span>
                <?php endif; ?>
            </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (false && $item['cat']): ?>
            <footer>
                <div class="categories">
                    <?php cats($item['cat'], $isUpcoming); ?>
                </div>
            </footer>
    <?php endif ?>
</article>
<?php backButton(true); ?>
<?php script(); ?>

<?php viewer(); ?>
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
    <img
        alt='<?php echo $alt; ?>'
        src='<?php echo($url); ?>'
        data-viewer-item='<?php echo($url); ?>'
        data-viewer-caption='<?php echo $image['caption'];?>'
    >
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
  echo '<div class="index-thumbs">';
  
  foreach ($content as $thumb)  {
    $filename = $thumb['filename'];
    $link = "/img/$filename";
    $data = array(
      title => $thumb['caption'],
      link => '',
      image => $link
    );
    thumb($data);
  }

  echo '</div>';
}