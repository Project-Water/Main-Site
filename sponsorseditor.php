<?php include 'checklogged.php'; include 'GDS/GDS.php'; include 'template-upper.php';

function cmpYears($a, $b)
{
    return strcmp(intval($b->value), intval($a->value));
}
function cmpLevels($a, $b){
    return strcmp(intval($a->order), intval($b->order));
}

$sponsor_store = new GDS\Store('Sponsors');
$sponsor_year_store = new GDS\Store('SponsorYears');
$sponsor_level_store = new GDS\Store('SponsorLevels');
    
$years = $sponsor_year_store->fetchAll();
usort($years, "cmpYears");

$levels = $sponsor_level_store->fetchAll();
usort($levels, "cmpLevels");


?>
    <link href='/css/sponsoreditor.css' type="text/css" rel='stylesheet'>

    <h1 class="subPageTitle">Sponsors</h1>

<br>
<div class="col-md-4">
    <div class="fancyForm">
        <div class="formHeader">
            <h3>EDIT YEARS</h3>
        </div>
        
            <div class="text-center">
                <?php
                    foreach($years as $year){
                        echo '<div class="yearSurround"><div class="col-xs-6">' . $year->value . '</div><div class="col-xs-6">';
                        echo '<form method="post" action="/api">';
                        echo '<input type="hidden" name="action" value="deleteSponsorYear">';
                        echo '<input type="hidden" name="toDelete" value="' . $year->getKeyId() . '">';
                        echo '<button type="submit" class="btn btn-danger" style="float:right">Delete</button></form></div></div>';
                    }
                ?>
                <form action="/api" method="post">
                <input type="hidden" name="action" value="addSponsorYear">
                <input type="number" name="year" placeholder="Year">
                <br>
                    <br>
                <input type="submit" value="ADD">
                </form>
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="fancyForm">
        <div class="formHeader">
            <h3>EDIT LEVELS</h3>
        </div>
        
            <div class="text-center">
                <?php
                    foreach($levels as $level){
                        echo '<div class="yearSurround"><div class="col-xs-6">' . $level->name . '</div><div class="col-xs-6">';
                        echo '<form method="post" action="/api">';
                        echo '<input type="hidden" name="action" value="deleteSponsorLevel">';
                        echo '<input type="hidden" name="toDelete" value="' . $level->getKeyId() . '">';
                        echo '<button type="submit" class="btn btn-danger" style="float:right">Delete</button></form></div></div>';
                    }
                ?>
                <form action="/api" method="post">
                <input type="hidden" name="action" value="addSponsorLevel">
                <input type="hidden" name="nextOrder" value="<?php echo count($levels);?>">
                <input type="text" name="level" placeholder="Level Name">
                <br>
                    <br>
                <input type="submit" value="ADD">
                </form>
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="fancyForm">
        <div class="formHeader">
            <h3>EDIT LEVEL ORDER</h3>
        </div>
        <form action="/api" method="post">
            <div class="text-center">
                <input type="hidden" name="action" value="saveGoals">
                
            </div>
            <br>
            <input type="submit" value="SAVE">
        </form>
    </div>
</div>
    <?php include 'template-lower.php'; ?>