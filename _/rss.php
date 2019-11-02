<?php
require "shared.inc.php";

header('Content-Type: application/rss+xml');
date_default_timezone_set("Europe/London");

$content = getContent();

function sortByPubDate($a, $b) {
  return @strtotime($a['pubDate']) < @strtotime($b['pubDate']);
}

uasort($content, 'sortByPubDate');
function rssDate($dateString = null) {
  if ($dateString != null) {
    $date = strtotime($dateString);
  } else {
    $date = time();
  }
  return date('r', $date);
}

echo '<?xml version="1.0" encoding="UTF-8"?>';
?><rss version="2.0"
  xmlns:content="http://purl.org/rss/1.0/modules/content/"
  xmlns:wfw="http://wellformedweb.org/CommentAPI/"
  xmlns:dc="http://purl.org/dc/elements/1.1/"
  xmlns:atom="http://www.w3.org/2005/Atom"
  xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
  xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
  xmlns:media="http://search.yahoo.com/mrss/"
>
  <channel>
    <title>Harm van den Dorpel</title>
    <atom:link href="https://harmvandendorpel.com/feed/" rel="self" type="application/rss+xml" />
    <link>https://harmvandendorpel.com/</link>
    <description></description>
    <lastBuildDate><?php echo rssDate(); ?></lastBuildDate>
    <language>en-US</language>
    <sy:updatePeriod>hourly</sy:updatePeriod>
    <sy:updateFrequency>1</sy:updateFrequency>
    <image>
      <url>https://harmvandendorpel.com/_/favicon.png</url>
      <title>Harm van den Dorpel</title>
      <link>https://harmvandendorpel.com/</link>
    </image>
  <?php
  $max = 20;
  foreach($content as $item) {
    if (@!$item['hideOnIndex'] && !isPrivate($item) && @!isUpcoming($item['date']) && $max > 0) {
      item($item);
      $max--;
    }
  }

	function getImageUrl($item) {
    return $item['meta_image'];
		// if (array_key_exists('parts', $item) && count($item["parts"])) {
		// 	$path = "/img";

		// 	$filename = str_replace(' ', '%20',$item["parts"][0]["filename"]);
		// 	$imgUrl = $path.$filename;
		// 	return $imgUrl;
		// }
		// return null;
	}	
  
  function item($data) {
    if (array_key_exists('link', $data)) {
      $url = $data["link"];
    } else {
      $url = ABSOLUTE_URL.'/'.$data['perma'];
    }

    $image = getImageUrl($data);
    $fullImageUrl  = 'https://harmvandendorpel.com'.getImageUrl($data);
  ?>
      <item>
        <title><?php echo htmlspecialchars($data['title']) ?></title>
        <link><?php echo htmlspecialchars($url); ?></link>
        <pubDate><?php echo rssDate($data['pubDate']); ?></pubDate>
        <dc:creator><![CDATA[Harm van den Dorpel]]></dc:creator>
        <guid isPermaLink="true"><?php echo htmlspecialchars($url); ?></guid>
        <description><![CDATA[<?php echo array_key_exists('descr', $data) ? summary($data['descr']) : ""; ?>]]></description>
        <?php if ($image) { ?>
          <media:thumbnail url="<?php echo $fullImageUrl; ?>" />
          <media:content url="<?php echo $fullImageUrl;?>" medium="image">
            <media:title type="html">image</media:title>
          </media:content>
        <?php } ?>
	    </item>
    <?php } ?>
  </channel>
</rss>
