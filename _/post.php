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

    $thisPageUrl = "http://harmvandendorpel.com/$perma";

    $description = $item['descr'];
    if ($item["images"] && count($item["images"]["filenames"])) {
        $path = "/img";
        if ($item["images"]["path"]) {
            $path .= $item["images"]["path"];
        } else {
            $path = $path."/";
        }
        $filename = str_replace(' ', '%20',$item["images"]["filenames"][0]["filename"]);
        $imgUrl = $path.$filename;
    }
    $metaKeywords = $item['tags'];
    $white = $item['white'] == 1;
    if ($item['meta']) {
      $metaDescription = $item['meta'];
    } else {
      $metaDescription = summary($description);
    }

    echo "<!-- $metaDescription -->";
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
            </div>
            <br>
        <?php endif; ?>
        <header>
            <h1 itemprop="headline"><?php echo $title; ?></h1>
        </header>

        <?php if ($description): ?>
            <section itemprop="articleBody">
                <div class="description">
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

        <?php if ($item['images']['filenames']): ?>
            <div class="images" id='pics'>
                <?php images($item); ?>
            </div>
        <?php endif; ?>

        <?php if ($item['seeAlso']): ?>
            <aside>
                <div class="categories">
                    <?php seeAlso($item['seeAlso']); ?>
                </div>
            </aside>
        <?php endif ?>

        <?php if ($item['cat']): ?>
            <footer>
                <div class="categories">
                    <?php cats($item['cat'], $isUpcoming); ?>
                </div>
            </footer>
        <?php endif ?>
    </div>
</article>
<?php backButton(); ?>
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
        if (strtolower($item['perma']) == strtolower($perma)) {
            return $item;
        }
    }
    return null;
}

function images($item) {
    $path = $item['images']['path'];
    if (count($item['images']['filenames'])) {
        foreach ($item['images']['filenames'] as $image) {
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
            image($image, $path, $alt, $link);
        }
    }
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

function image($image, $path, $alt, $link) {
    if (!$path) {
        $path = '/';
    }

    $fullFilename = '/img' . $path . $image['filename'];
    list($width, $height) = getimagesize('.' . $fullFilename);

    $url = ABSOLUTE_URL . '/img' . $path . rawurlencode($image['filename']);
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
