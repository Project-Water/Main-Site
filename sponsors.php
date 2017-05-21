<?php include 'template-upper.php'; 
class Sponsor{
    public $name;
    public $url;
    public $image;
    function __construct($sponsorName, $sponsorURL, $sponsorImage) {
        $this->name = $sponsorName;
        $this->url = $sponsorURL;
        $this->image = $sponsorImage;
    }
    
    function getHTML(){
        $html = '<div class="col-md-3 col-xs-6 sponsorCol">';
        $html .= '<a class="thumbnail down" href="';
        $html .= $this->url;
        $html .= '" target="_blank">';

        $html .= '<img src="img/sponsors/';
        $html .= $this->image;
        $html .= '" alt="';
        $html .= $this->name;
        $html .= ' logo">';

        $html .= '<div class="caption"><h5>';
        $html .= $this->name;
        $html .= '</h5></div>';

        $html .= '</a>';

        $html .= '</div>';
        return $html;
        
    }
}

$sponsors = array(
    "2017" => array(
        "Gold" => array(
            new Sponsor("Graeter's Ice Cream", "https://www.graeters.com/", "graeters.jpg"),
            new Sponsor("Monte Cello's","http://www.montecellos.com/","monte_cello.png")
        ),
        "Silver" => array(
            new Sponsor("Just for Marketing Industries","http://justformarketing.com/","just_for_marketing.png"),
	        new Sponsor("Pennsylvania Financial Group","http://www.pfgpgh.com/","penn_financial_group.png"),
            new Sponsor("Chick-fil-A Ross Park Mall", "http://www.chick-fil-a.com", "Chick-fil-A_Logo.svg"),
            new Sponsor("Strassburger McKenna Gutnick & Gefsky", "http://www.smgglaw.com/", "Strassburger-Mckenna-Gutnick-Gefsky-Attorneys-at-Law.svg")
        ),
        "Bronze" => array(
            new Sponsor("Brandy Varrati State Farm Insurance Agency", "https://www.statefarm.com/agent/US/PA/Allison-Park/Brandy-Varrati-4CJY24RSVAK","logo-statefarm.png"),
            new Sponsor("Crossfit City of Bridges", "https://www.crossfitcityofbridges.com/home.html", "crossfit_cob.jpg"),
            new Sponsor("Dimond Electric", "", ""),
            new Sponsor("Ditka's Restaurant", "https://www.ditkasrestaurants.com/", "ditkas.png"),
            new Sponsor("Moe's Southwest Grill", "https://www.moes.com/", "moes.png"),
            new Sponsor("Peoples Gas", "https://www.peoples-gas.com/", "peoples_gas.gif"),
            new Sponsor("Pizza Hut", "https://www.pizzahut.com/", "pizza_hut.svg"),
            new Sponsor("Scoumis Dentistry", "", ""),
            new Sponsor("Sheetz", "https://www.sheetz.com/", "sheetz_logo.png"),
            new Sponsor("Speaker of the House Mike Turzai","http://www.repturzai.com/","turzai.svg"),
            new Sponsor("Total Learning Center", "http://www.totallearningcenter.com","total-learning-center.png"),
            new Sponsor("Vincent Lighting", "http://www.vls.com/pittsburgh-lighting-office", "vincent_lighting.png")
        )
    ),
    "2016" => array(
        "Gold" => array(
            new Sponsor("Peace Love and Little Donuts", "http://www.peaceloveandlittledonuts.com", "peace-love-little-donuts.svg"),
            new Sponsor("Chick-fil-A Ross Park Mall", "http://www.chick-fil-a.com", "Chick-fil-A_Logo.svg"),
            new Sponsor("Holliday Fenolgio Fowler, L.P.","https://www.hfflp.com/","holliday-fenoglio-fowler.svg"),
            new Sponsor("Blank Extreme Entertainment LLC", "http://blankextremeentertainment.com", "blank-extreme.svg"),
            new Sponsor("Monte Cello's","http://www.montecellos.com/","monte_cello.png"),
            new Sponsor("Strassburger Mckenna Gutnick & Gefsky Attorneys at Law", "http://www.smgglaw.com", "Strassburger-Mckenna-Gutnick-Gefsky-Attorneys-at-Law.svg"),
            new Sponsor("Just for Marketing Industries","http://justformarketing.com/","just_for_marketing.png")
        ),
        "Silver" => array(
            new Sponsor("Dirt Doctors Cleaning Services, LLC", "http://dirtdoctorscleaning.com/", "dirt-doctors-pittsburgh-best-cleaners.png"),
            new Sponsor("Speaker of the House Mike Turzai","http://www.repturzai.com/","turzai.svg"),
            new Sponsor("Heritage Valley Health System","http://www.heritagevalley.org/","herritage_valley.jpg"),
            new Sponsor("Sir Pizza", "http://sirpizza-pittsburgh.com", "sir-pizza.svg"),
            new Sponsor("Total Learning Center", "http://www.totallearningcenter.com","total-learning-center.png"),
            new Sponsor("Rita's Italian Ice Wexford", "http://www.ritasice.com", "Rita_s_Ice.svg"),
            new Sponsor("Soergel Orchards", "http://soergels.com", "soergel.png"),
            new Sponsor("Jones Day International Law Firm", "http://www.jonesday.com", "logo.gif"),
            new Sponsor("K & R Insurance: Home, Auto, Business, Life", "http://www.krinsllc.com", "kr.png"),
            new Sponsor("Peppi’s Old Tyme Sandwich Shoppe", "http://peppisubs.com", "peppis-logo.svg")
        )
    ),
    "2015" => array(
        "All Sponsors" => array(
            new Sponsor("Pizza Hut", "http://www.pizzahut.com/", "pizza_hut.svg"),
            new Sponsor("Monte Cello's","http://www.montecellos.com/","monte_cello.png"),
            new Sponsor("Bellissimo's","http://www.gobellissimos.com/","bellissimo.png"),
            new Sponsor("Heritage Valley Health System","http://www.heritagevalley.org/","herritage_valley.jpg"),
            new Sponsor("Holliday Fenolgio Fowler, L.P.","https://www.hfflp.com/","holliday-fenoglio-fowler.svg"),
            new Sponsor("Just for Marketing Industries","http://justformarketing.com/","just_for_marketing.png"),
            new Sponsor("La Felice Pizza & Pasta","http://pizzalafelice.com","la-felice.png")
        )
    )
);

?>



<h1 class="subPageTitle">Sponsors</h1>
<hr>

<article class="subPageArticle">
    <p>
        &nbsp;&nbsp;&nbsp;&nbsp;As with any large-scale event, Project Water needs sponsorships from local businesses who give back to their communities. As a sponsor, your logo will be on this page, and can be displayed at the upcoming dodgeball tournament. Any financial or product sponsorship helps. If you’d like to partner with us as North Allegheny works to provide African villages with clean water, please contact us at <a href="mailto:sponsor@naprojectwater.com">sponsor@naprojectwater.com</a>.
    </p>
    <div class="sponsors">
		

        
        <?php
			echo '<hr>';
            foreach($sponsors as $year=>$levels){
                echo '<h2 class="text-center"><u>' . $year . '</u></h3>';
                foreach($levels as $level=>$sponsorList){
                    echo '<br><br><h3 class="text-center">' . $level . '</h2>';
                    $tempCounter = 1;
                    $totalIterations = 0;
                    foreach($sponsorList as $sponsor){
                        if ($tempCounter == 1)
                            echo '<div class="row sponsorRow">';
                        echo $sponsor->getHTML();
                        $tempCounter++;
                        
                        if (($totalIterations == count($sponsorList) - 1) && (count($sponsorList) % 4 != 0)) {
                            for ($i = 0; $i < (4 - (count($sponsorList) % 4)); $i++) {
                                echo '<div class="col-md-3 col-xs-6 sponsorCol">';
                                echo '<a class="thumbnail down" href="#" data-toggle="modal" data-target="#sponsorModal">';
                                echo '<img src="img/sponsors/place-holder.png" alt="Place Holder">';
                                echo '<div class="caption"><h5>Your logo could be here!</h5></div>';
                                echo '</a>';
                                echo '</div>';
                            }
                        }
                        
                        if ($tempCounter == 5 || $totalIterations == count($sponsorList) - 1) {
                            echo '</div>';
                            $tempCounter = 1;
                        }
                        $totalIterations += 1;
                    }
                }
                echo '<br><hr>';
            }
        ?>
        
        
    </div>
</article>


<div class="modal fade" id="sponsorModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Sponsors</h4>
            </div>
            <div class="modal-body">
                To become a sponsor, please email us at <a href="mailto:sponsor@naprojectwater.com">sponsor@naprojectwater.com</a>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php include 'template-lower.php'; ?>