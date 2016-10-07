<?php include 'checklogged.php'; include 'GDS/GDS.php'; include 'template-upper.php';
$obj_store = new GDS\Store('HomeImages');
?>
    <link href='/css/homeimageeditor.css' type="text/css" rel='stylesheet'>

    <h1 class="subPageTitle">Home Images</h1>

    <div class="list-group" id="imageList">
        
        <?php
        
            function cmp($a, $b)
            {
                return strcmp($a->order, $b->order);
            }
	        $result = $obj_store->fetchAll();
            usort($result, "cmp");
            if (count($result) > 0) {
                foreach($result as $image) {
                    echo '<div class="list-group-item" data-id="' . $image->getKeyId() .'"><img class="handleOrder" src="/img/reorder.svg">';
                    echo '<img src="' . $image->thumbURL . '" class="imagethumb">';
                    echo '&nbsp;&nbsp;';
                    echo $image->name;
                    echo '<form action="/api" method="post"><input type="hidden" name="action" value="deleteImage">';
                    echo '<input type="hidden" name="toDelete" value="' . $image->getKeyId() . '">';
                    echo '<input type="submit" style="float:right;margin-top: -30px;" class="btn btn-danger" value="Delete"></form></div>';
                }
            }
        ?>
    </div>
<button class="fancyButton" onclick="saveOrder()">Save Order</button>

<form action="/api" method="post" id="imageOrderForm">
    <input type="hidden" name="action" value="updateImageOrder">
    <input type="hidden" name="order" value="" id="imageOrderStore">
</form>

<br>
    <div class="imageForm">
        <div class="formHeader">
            <h3>ADD IMAGE</h3>
        </div>
        <div class="text-center"><strong>Valid image types are PNG, JPG, SVG, GIF, and WEBP</strong></div>
        <div class="text-center">Images should be 1263x421 pixels. Differently sized images will be cropped</div>
        <form action="/api" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="addImage">
            <input type="hidden" name="nextOrder" value="<?php echo count($result);?>">
            <input name="images[]" type="file" multiple="multiple">
            <br>
            <input type="submit" value="ADD">
        </form>
    </div>

    <?php include 'template-lower.php'; ?>