<?php require('template.php'); ?><!doctype html>
<html lang="en" class="index-index-index">
<head>
<?php
  $searchQuery = $_GET['q'];
  $content = filterFeatured();
  $title = 'Harm van den Dorpel';
  $description = 'Remember what it looked like before it meant anything?';
  $keywords = 'art,artist,education,decentralisation,aesthetics,learning,algorithms,platforms,developer,berlin,programming,sculpture,collage';

  meta($title, $description, $imgUrl, 'https://harmvandendorpel.com/', $keywords);
?>

<style>

.index-index-list {
  margin: 0;
  padding: 0;
  list-style: none;
  margin-bottom: 36px;
  width: 100%;
  display:block;
}

#list-default {
  display: block;
}

.image-wide-index {
  margin-bottom: 60px;
}

.image-wide-index img {
  max-width: 100%
}

.index-thumbs {
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: center;
  margin-top: 100px;
  margin-bottom: 100px;
}

.index-thumb-item {
  width: 15vw;
  height: 15vw;
  box-sizing: border-box;
  padding: calc(0.2vw + 10px);
}

.thumb-container {
  position: relative;
}

.thumb-item-image {
  background-size: contain;
  width: 100%;
  height: 100%;
  background-repeat: no-repeat;
  background-position: 50% 50%;
}

</style>
</head>
<?php

function item($data) {
  $images = $data['images'];
  $path = $images['path'];
  $filenames = $images['filenames'];
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

?>
<body itemscope itemtype="http://schema.org/WebPage">

<style>
  
  .image-loading-watermark {
    background-image: url(/img/raster.png);
    width: 100%;
    background-size: cover;
    position: absolute;
    background-position: 50% 50%;
    background-repeat: no-repeat;
    padding-bottom: 58%;
    left: 0;
    top: 0;
  }

  .image-container-proportional {
    width: 100%;
    position:relative;
  }

  .image-proportional {    
    left: 0;
    width: 100%;
    background-size: cover;
    position: relative;
    background-position: 50% 50%;
    background-repeat: no-repeat;
    padding-bottom: 58%;
    top: 0;
  }

  .image-inner-proportional {
    width: 100%;
    height: 100%;
    position: absolute;
    left: 0;
    display: block;
  }

  .index-content {
    max-width: 1024px;
    margin: auto;
  }


  .thumb-image-link {
    border: none;
  }

</style>
<div class="index-thumbs">
  <?php main_thumbs(); ?>
</div>
<div class="index-content">
  <div class="index-index">
    <div class="index-index-list" id="list-default">
      <?php foreach($content as $item) item($item); ?>
    </div>
  </div>
</div>


<div class="floating-logo floating-logo-deli" data-link="/deli-near-info"></div>
<a href='https://left.gallery' target='_blank'><div class="floating-logo floating-logo-left"></div></a>
<div class="floating-logo floating-logo-death" data-link="/death-imitates-language"></div>
<div class="floating-logo floating-logo-dissociations" data-link="http://dissociations.com/804"></div>
<div class="floating-logo floating-logo-tokens" data-link="https://tokens.harmvandendorpel.com/"></div>

<?php script(); ?>
<?php footer(); ?>

<?php

function main_thumbs () {
  $index_data = getIndexData();
  foreach($index_data as $item) {
    ?>
    <div class='thumb-container'>
      <a href='<?php echo $item['link']; ?>' class='thumb-image-link'>
        <div
          class='index-thumb-item'      
        >
          <div class='thumb-item-image' style='background-image: url(<?php echo $item['image'] ?>);'></div>
        </div>
        <div style='width: 100%; text-align:center;'><?php echo $item['title']; ?></div>
      </a>
    </div>
    <?php
  }
  ?></div><?php
} ?>

<div class="mailing-list">
  <div id="mc_embed_signup">
    <form action="//harmvandendorpel.us14.list-manage.com/subscribe/post?u=ef8e7956615c468f0fbb12530&amp;id=289c6d67a9" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
      <div id="mc_embed_signup_scroll">
        <div class="mc-field-group">
          <input type="email" value="" placeholder='subscribe to mailing list' name="EMAIL" class="required email" id="mce-EMAIL">
        </div>

        <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_ef8e7956615c468f0fbb12530_289c6d67a9" tabindex="-1" value=""></div>
        <input type="submit" value="ok" name="subscribe" id="mc-embedded-subscribe" class="button">

        <div id="mce-responses" class="clear">
          <div class="response" id="mce-error-response" style="display:none"></div>
          <div class="response" id="mce-success-response" style="display:none"></div>
        </div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
      </div>
      <br>
      <div class="mailing-info">
        Please enter your email here if you want to be informed about my work, upcoming exhibitions, and software releases.<br><br>You will receive at most one email per month.
      </div>
      <br>
      <div class="btn-close">&times;</div>
    </form>
  </div>
</div>
<script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script>
<script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
<script src="/_/js/index.js?nocache=a1ruy23"></script>
</body>
</html>
