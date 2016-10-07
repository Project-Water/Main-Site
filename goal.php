<?php include 'checklogged.php'; include 'GDS/GDS.php'; include 'template-upper.php';
$obj_store = new GDS\Store('Goals');
$currentMoney = $obj_store->fetchOne('SELECT * From Goals WHERE name=@objName', ['objName' => 'currentMoney']);
$goalMoney = $obj_store->fetchOne('SELECT * From Goals WHERE name=@objName', ['objName' => 'goalMoney']);
?>
    <link href='/css/goal.css' type="text/css" rel='stylesheet'>

    <h1 class="subPageTitle">Goal</h1>

<br>
    <div class="goalForm">
        <div class="formHeader">
            <h3>EDIT GOALS</h3>
        </div>
        <form action="/api" method="post">
            <div class="text-center">
                <input type="hidden" name="action" value="saveGoals">
                Current Money: <input type="number" name="currentMoney" value="<?php echo $currentMoney->value;?>">
                <br>
                Goal Money: <input type="number" name="goalMoney" value="<?php echo $goalMoney->value;?>">
            </div>
            <br>
            <input type="submit" value="SAVE">
        </form>
    </div>

    <?php include 'template-lower.php'; ?>