<?php require('template.php'); ?><!doctype html>
<html lang="en" class="index-index-index">
<head>
<?php
    $searchQuery = $_GET['q'];
    echo "<!--".thisPageHits()." -->";
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
  float: left;
  width: 100%;
  display:block;
}

#list-search-results em {
  font-weight: 600;
  font-style: normal;
}

#list-default {
    <?php if ($searchQuery) { ?>
        display: none;
    <?php } else { ?>
        display: block;
    <?php } ?>
}


#list-search-results {
    <?php if ($searchQuery) { ?>
        display: block;
    <?php } else { ?>
        display: none;
    <?php } ?>
}


</style>
</head>

<body itemscope itemtype="http://schema.org/WebPage">
<div class="content">
    <div class="index-index">
        <input
            type="text"
            style="width: 100%; margin-bottom: 36px; padding: 5px; box-sizing: border-box;"
            id="searchBox"
            placeholder="Search"    
            autofocus    
            value="<?php echo $searchQuery ? htmlspecialchars($searchQuery) : ""; ?>"
        />
        <div class="index-index-list" id="list-default">
            <ul style='float:left; width: 100%;'>
                <?php foreach($content as $item) echo indexItem($item, true, $cat, false); ?>
            </ul>
            
            <ul style="clear: both;" class='list-columns'>
                <li class="item"><a href="/list/exhibitions">All exhibitions</a></li>
                <li class="item"><a href="/list/upcoming">Upcoming</a></li>
                <li class="item" style='margin-bottom:0;'><a href="/list/recent">Recent</a></li>
                <li class="item" style="margin-top: 72px;" ><a href="/list/software">Software</a></li>
                <li class="item" ><a href="https://www.are.na/harm-van-den-dorpel/drawings-qkfaoxldau0" target="_blank">Drawings</a></li>
                <li class="item" ><a href="/list/writing">Writing</a></li>
            </ul>
            <ul class='list-columns list-columns-right'>
                <li class="item" ><a href="#" class="btn-mailinglist">Mailinglist</a></li>
                <li class="item" ><a href="/about">Contact</a></li>
            </ul>
        </div>

        <ul class="index-index-list" id="list-search-results">
        <?php 
            if ($searchQuery) {
                $query = htmlspecialchars($searchQuery);
                $items = getData();
                $result = search($query, $items);
                echo searchContent($result);
            }
        
        ?>
        </ul>
    </div>

    <!-- div class="floating-logo floating-logo-deli" data-link="/deli-near-info"></div -->
    <a href='https://left.gallery' target='_blank'><div class="floating-logo floating-logo-left"></div></a>
    <div class="floating-logo floating-logo-death" data-link="/death-imitates-language"></div>
    <div class="floating-logo floating-logo-dissociations" data-link="http://dissociations.com/804"></div>
    <div class="floating-logo floating-logo-tokens" data-link="https://tokens.harmvandendorpel.com/"></div>
    <?php script(); ?>
    <?php footer(); ?>
</div>

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
<script>

const searchCache = {}

function search(value) {  
  const url = '/_/search.php?q=' + value
  
  return new Promise(resolve => {
    if (value in searchCache) {
        resolve(searchCache[value])
    } else {
        $.get(url, function (result) {            
            searchCache[value] = result
            resolve(result)
        })
    }
  })
}

const input = document.getElementById('searchBox')

function renderResults(result) {
    const query = input.value  
    if (query.length > 0) {
        $searchResults = $("#list-search-results")
        $searchResults.html(result.html)
        $searchResults.show()
        $("#list-default").hide();
    } else {
        $("#list-search-results").hide();
        $("#list-default").show();
    }
}

function updateSearch() {
  const query = input.value
  const url = query.length ? "/search/" + query : "/";
  if (window.localStorage) {
      window.localStorage.setItem('search', query)
  }
  window.history.pushState({}, query, url);
  if (query.length > 0) {
    $searchResults = $("#list-search-results")
    $searchResults.html('')
  }
  search(query).then(() => {
    renderResults(searchCache[query])
    $('.floating-logo').fadeOut()
  })
}

input.addEventListener('keyup', updateSearch)
window.localStorage.removeItem('category')

<?php if ($searchQuery) { ?>
    if (window.localStorage) {
        window.localStorage.setItem('search', '<?php echo htmlspecialchars($searchQuery);?>')
    }
<?php } ?>


const searchBox = document.getElementById('searchBox')
const searchLength = searchBox.value.length
searchBox.setSelectionRange(searchLength, searchLength)

</script>

</body>
</html>
