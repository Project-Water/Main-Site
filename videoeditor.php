<?php include 'checklogged.php'; include 'GDS/GDS.php'; include 'template-upper.php';
$obj_store = new GDS\Store('Videos');
$homeVideo = $obj_store->fetchOne('SELECT * From Videos WHERE IsHomeVideo=@trueTest', ['trueTest' => true]);
if($homeVideo != null)
    $homeVideoURL = 'https://youtu.be/' . $homeVideo->URL;
else
    $homeVideoURL = '';
?>
    <link href='/css/videoeditor.css' type="text/css" rel='stylesheet'>

    <h1 class="subPageTitle">Videos</h1>

    <h3 class="text-center">Home Page</h3>
    <br>
    <div class="videoForm">
        <div class="formHeader">
            <h3>HOME VIDEO</h3>
        </div>
        <form action="/api" method="post" onSubmit="checkVideoValidity(document.getElementById('homeVideoURL').value)">
            <input type="hidden" name="action" value="changeHomeVideo">
            <input type="text" id="homeVideoURL" name="URL" placeholder="URL" value="<?php echo $homeVideoURL; ?>">
            <br>
            <input type="submit" value="SAVE">
        </form>
    </div>

    <hr>
    <h3 class="text-center">Video Tab</h3>

    <div class="list-group" id="videoList">
        
        <?php
        
            function cmp($a, $b)
            {
                return strcmp($a->order, $b->order);
            }
        
            $obj_store->query('SELECT * FROM Videos WHERE IsHomeVideo=@trueTest', ['trueTest' => false]);
	        $result = $obj_store->fetchAll();
            usort($result, "cmp");
            if (count($result) > 0) {
                foreach($result as $video) {
                    echo '<div class="list-group-item" data-id="' . $video->getKeyId() .'"><img class="handleOrder" src="/img/reorder.svg"><strong>';
                    echo $video->Title;
                    echo '</strong><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                    $tempURL = 'https://youtu.be/' . $video->URL;
                    echo '<a href="' . $tempURL . '">' . $tempURL . '</a>';
                    echo '<form action="/api" method="post"><input type="hidden" name="action" value="deleteVideo">';
                    echo '<input type="hidden" name="toDelete" value="' . $video->getKeyId() . '">';
                    echo '<input type="submit" style="float:right;margin-top: -36px;" class="btn btn-danger" value="Delete"></form></div>';
                }
            }
        ?>
    </div>
<button class="fancyButton" onclick="saveOrder()">Save Order</button>

<form action="/api" method="post" id="videoOrderForm">
    <input type="hidden" name="action" value="updateVideoOrder">
    <input type="hidden" name="order" value="" id="videoOrderStore">
</form>

<br>
    <div class="videoForm">
        <div class="formHeader">
            <h3>ADD VIDEO</h3>
        </div>
        <form action="/api" method="post" onSubmit="checkVideoValidity(document.getElementById('newVideoURL').value)">
            <input type="hidden" name="action" value="addVideo">
            <input type="hidden" name="nextOrder" value="<?php echo count($result);?>">
            <input type="text" name="Title" placeholder="Title">
            <input type="text" name="URL" placeholder="URL" id="newVideoURL">
            <br>
            <input type="submit" value="ADD">
        </form>
    </div>

    <?php include 'template-lower.php'; ?>