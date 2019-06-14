<?php require('template.php'); ?><!doctype html>
<html>
<head>
    <?php
        $cat = $_GET['cat'];
        $content = filterIndex(getContent(), strtolower($cat));
        $title = ucfirst($cat)." - Harm van den Dorpel";
        $description = ""; // 'pessimism is weak';
        $thisPageUrl = ABSOLUTE_URL."/list/$cat";
        $keywords = "list,$cat,harm van den dorpel,work";
        $list_meta_pic = getListMetaPic($content);

        meta($title, $description, $list_meta_pic, $thisPageUrl, $keywords);

        if ($cat === 'recent') {
            $textOnly = true;
        } else {
            $textOnly = false;
        }
    ?>
    <script>
        if (window.localStorage) {
            window.localStorage.setItem('category', "<?php echo $cat; ?>")
        }
    </script>
</head>

<body itemscope itemtype="http://schema.org/WebPage">
<div class="content">
    <h1><?php echo ucfirst($cat) ;?></h1>
    <ul style="list-style: none; padding:0; margin: 0; float:left;margin-bottom: 36px;width: 100%;"><?php
        
        foreach($content as $item) {
            echo indexItem($item, $textOnly);
        }

    ?></ul>
</div>

<?php backButton(false); ?>
<?php script(); ?>
<?php footer(false); ?>

</body>
</html>
