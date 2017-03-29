<?php
//Twitter consumer key: ***REMOVED***
//Twitter secret: ***REMOVED***

$teams = array(
	"" => array(
		"NASH Leadership Team" => array(
			"Joshua Thomas" => 1380804866,
			"Nikhil Behari" => "behari_nikhil.jpg",
			"Shane Mitnick" => 324719303,
			"Bailey Daftary" => "daftary_bailey.jpg",
			"Brendan Grzyb" => "photo_template.jpg",
			"CJ May" => 2902146421,
			"Danny Fujito" => 1024988798,
			"Griffin McVay" => 2576757000,
			"Hannah Glasser" => 1674638335,
			"Jack Kenna" => 902832746,
			"Jacob Greco" => "greco_jacob.jpg",
			"Julie Chen" => 40125872,
			"Kennedy Urban" => "urban-kennedy.jpg",
			"Kevin Xu" => 2332523996,
			"Margo Weller" => "weller_margo.jpg",
			"MJ Barton" => 1045293290,
			"Owen Leonard" => "photo_template.jpg",
			"Sean Bartholomew" => "bartholomew_sean.jpg",
			"Zach Shuckrow" => "shuckrow_zack.jpg"
		),
        "NAI Leadership Team" => array(
            "Andrew Ziegler" => "andrew_ziegler.jpg",
            "Zach Trdinich" => "photo_template.jpg",
            "Emmett Gwaltney" => "photo_template.jpg",
            "Christopher Lee" => "photo_template.jpg",
            "John Ehling" => "photo_template.jpg",
            "Kristen Chomos" => 1066521937,
            "Grace Walsh" => "photo_template.jpg",
            "RJ Swanson" => "photo_template.jpg",
            "Olivia Cress" => "Olivia_Kress.jpeg",
            "Morgan McConnell" => "photo_template.jpg",
            "Luke Turkovich" => "luke.jpg",
            "Zach Eilig" => "zach_eilig.jpeg"
        ),
		"PW Players Union" => array()
			
	)
);
$ids = [2757838540, 1636704312, 1045293290, 1380804866, 601340706, 2332523996, 381211050, 496475347, 2902146421, 1024988798, 2510451658, 1674638335, 2576757000, 324719303, 40125872, 2402095411, 902832746, 1066521937, 2405315112];
//$twitterResults = performPost("https://api.twitter.com/1.1/users/lookup.json", array("user_id" => implode(",",user_id)));

//url is string, data is array
function performPost($url,$data,$bearer_token){
    // use key 'http' even if you send the request to https://...
    $options = array(
                     'http' => array(
                                     'header'  => 'Authorization: Bearer '.$bearer_token,
                                     'method'  => 'POST',
                                     'content' => http_build_query($data),
                                     ),
                     );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    return $result;
}

function getTwitterFromAPI(){
    global $ids;
    $app_key = '***REMOVED***';
    $app_token = '***REMOVED***';
    //These are our constants.
    $api_base = 'https://api.twitter.com/';
    $bearer_token_creds = base64_encode($app_key.':'.$app_token);
    //Get a bearer token.
    $opts = array(
      'http'=>array(
        'method' => 'POST',
        'header' => 'Authorization: Basic '.$bearer_token_creds."\r\n".
                   'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
        'content' => 'grant_type=client_credentials'
      )
    );
    $context = stream_context_create($opts);
    $json = file_get_contents($api_base.'oauth2/token',false,$context);
    $result = json_decode($json,true);
    if (!is_array($result) || !isset($result['token_type']) || !isset($result['access_token'])) {
      die("Something went wrong. This isn't a valid array: ".$json);
    }
    if ($result['token_type'] !== "bearer") {
      die("Invalid token type. Twitter says we need to make sure this is a bearer.");
    }
    //Set our bearer token. Now issued, this won't ever* change unless it's invalidated by a call to /oauth2/invalidate_token.
    //*probably - it's not documentated that it'll ever change.
    $bearer_token = $result['access_token'];
    //Try a twitter API request now.
    $opts = array(
      'http'=>array(
        'method' => 'GET',
        'header' => 'Authorization: Bearer '.$bearer_token
      )
    );
    $context = stream_context_create($opts);
    $options = array(
        'user_id' => implode(',',$ids)
    );
    $json = performPost($api_base.'1.1/users/lookup.json',$options, $bearer_token);
    return $json;
}


//save the data and retrieve from memcache if we can to speed up operations
$memcache = new Memcache;
$data = $memcache->get("people");
if($data === false){
    $data = getTwitterFromAPI();
    $memcache->set("people", $data, 86400);
}
$twitterResults = json_decode($data, true);


function getIDLocation($id){
    global $ids, $twitterResults;
    $count = 0;
    foreach($ids as $number){
        if($twitterResults[$count]["id"] == $id){
            return $count;
        }
        $count++;
    }
}
    

include 'template-upper.php';
?>

    <h1 style='text-align:center;color:#3cf;font-family: HelveticaNeueCondensedBold;'>2016-2017 Team</h1>

    <?php

$counter = 0;
$lastDidEnd = false;
foreach($teams as $year=>$yearTeams){
    echo "<h2 style='text-align:center;text-decoration:underline'>" . $year . "</h2>";
    foreach($yearTeams as $teamName=>$people) {
		echo "<br><br>";
        echo "<h3 style='text-align:center;'>" . $teamName . "</h3>";
        foreach($people as $name=>$id){
            if($counter == 0){
                echo '<div class="row">';
                $lastDidEnd = false;
            }
            $counter++;
            echo '<div class="col-md-3 col-xs-6"><div class="thumbnail down"><img src="';
            $intTest = (int)$id;
            if(gettype($id) == "integer" || $intTest != 0)
                echo str_replace("_normal", "", $twitterResults[getIDLocation($id)]["profile_image_url_https"]);
            else
                echo 'img/team/' . $id;
            echo '" style="border-radius:5px;"><div style="margin-top:-15px;">';
            if(gettype($id) == "integer" || $intTest != 0)
                echo '<a href="https://twitter.com/' . $twitterResults[getIDLocation($id)]["screen_name"] . '">' . $name . '</a>';
            else
                echo $name;
            echo "</div></div></div>";
            if($counter == 4){
                echo '</div>';
                $counter = 0;
                $lastDidEnd = true;
            }
        }
        if(!$lastDidEnd){
			if($teamName === 'PW Players Union'){
			echo "Andrew Turzai, Ben Cinker, Brooke Anderson, Caleb Karsh, Cam Sunseri, CJ Bates, Devin Bluemling, Ethan Maenza, Jack Kairys, Jack Lehew, Jack Barber, Jason Stiefater, Jenna Kolano, Jenna Risacher, Katie Flanders, Katie Walzer, Liam Nobbs, Logan Glace, Maria Cataline, Mark Puntil, Morgan Rutan, Noah Frank, Sam Neal, Sean Atwater";
		    echo "<hr>";
			}
            echo '</div>';
		}
        $counter = 0;
    }
	

	
    echo '<h3 class="text-center">See Past Years\' Teams</h3>';
    echo '<div class="donateButton"><a href="allteams" target="_blank"><div class="donateButtonText">ALL TEAMS</div></a></div><br>';
	
}

include 'template-lower.php'; 
?>