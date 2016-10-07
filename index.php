<?php include 'template-upper.php'; 
include 'GDS/GDS.php';

function cmp($a, $b)
{
    return strcmp($a->order, $b->order);
}

$video_store = new GDS\Store('Videos');
$homeVideo = $video_store->fetchOne('SELECT * From Videos WHERE IsHomeVideo=@trueTest', ['trueTest' => true]);
$home_image_store = new GDS\Store('HomeImages');
$homeImages = $home_image_store->fetchAll();

usort($homeImages, "cmp");
?>
    <div class="row">
        <div class="col-md-12">
            <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators">
                    <?php
                    $numSlides = count($homeImages);
                    for($i = 0; $i < $numSlides; $i++){
                        echo '<li data-target="#carousel-example-generic" data-slide-to="' . $i . '"';
                        if($i == 0)
                            echo 'class="active"';
                        echo '></li>';
                    }
                    ?>
                </ol>

                <!-- Wrapper for slides -->
                <div class="carousel-inner" role="listbox">
                    <?php
                    for($i = 0; $i < $numSlides; $i++){
                        echo '<div class="item';
                        if($i == 0)
                            echo ' active';
                        echo '"><img src="' . $homeImages[$i]->URL . '"></div>';
                    }
                    ?>
                </div>
                <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="embed-responsive embed-responsive-16by9">
                <embed id="video" src="https://www.youtube-nocookie.com/embed/<?php echo $homeVideo->URL; ?>?theme=light&rel=0&autoplay=0&showinfo=0&origin=https://naprojectwater.com" frameborder="0" allowfullscreen class="embed-responsive-item">
            </div>
        </div>
    </div>
    <div class="row" id="threeWide">
        <div class="row-same-height">
            <div class="col-sm-4 indexGrid col-sm-height col-middle">
                <div class="twitterContainer">
                    <div class="twitterHeader">Twitter</div>
                    <a class="twitter-timeline" data-dnt="true" href="https://twitter.com/naprojectwater" data-widget-id="658412969521364992" data-chrome="noscrollbar noheader nofooter" data-tweet-limit="3" data-border-color="#3cf">Tweets by @naprojectwater</a>
                </div>
            </div>
            <div class="col-sm-4 indexGrid col-sm-height col-middle">
                <div class="totalContainer">
                    <div class="totalHeader">Total Donations</div>

                    <div id="waterDropContainer">

                    </div>
                    <div id="dropTextUpper">$0.00</div>
                    <div id="dropTextMiddle">$15,000.00</div>
                    <div id="dropTextLower"></div>
                </div>
            </div>
            <div class="col-sm-4 indexGrid col-sm-height col-middle">
                <div class="eventContainer">
                    <div class="eventHeader">Upcoming Events</div>
                    <div id="eventData">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'template-lower.php'; ?>