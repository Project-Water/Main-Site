<?php include 'template-upper.php'; ?>
<h1 class="subPageTitle">Videos</h1>
<hr>

<?php

include 'GDS/GDS.php';
$obj_store = new GDS\Store('Videos');
$obj_store->query('SELECT * FROM Videos WHERE IsHomeVideo=@trueTest', ['trueTest' => false]);
function cmp($a, $b)
{
    return strcmp($a->order, $b->order);
}
$result = $obj_store->fetchAll();
usort($result, "cmp");
if (count($result) > 0) {
    foreach($result as $video) {
        echo '<h2 class="videoDescription">' . $video->Title . '</h2>';
        echo '<div class="embed-responsive embed-responsive-16by9">';
        echo '<embed id="video" src="https://www.youtube-nocookie.com/embed/' . $video->URL . '?theme=light&rel=0&autoplay=0&showinfo=0&origin=https://naprojectwater.com" frameborder="0" allowfullscreen class="embed-responsive-item">';
        echo '</div>';
    }
}
include 'template-lower.php'; 
?>