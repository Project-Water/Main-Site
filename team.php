<?php
//Twitter consumer key: ***REMOVED***
//Twitter secret: ***REMOVED***

$teams = array(
	"NASH Team" => array(
		"Team Directors" => array(
			"Nikhil Behari, Logistics Director" => "behari.jpg",
			"Jack Kenna, Publicity Director" => "kenna.jpg",
			"Griffin McVay, Mission Director" => "mcvay.jpg",
			"Margo Weller, Mission Director" => "weller.jpg",
			"Luke Trueman, Players' Union Leader" => "trueman.jpg"
		),
		"Team Members" => array(
			"Jess Barry, Mission Team" => "barry.jpg",
			"Sam Buirge, Mission Team" => "buirge.jpg",
			"Ryan Earle, Mission Team" => "earle.jpg",
			"Jenna Edelmann, Mission Team" => "edelmann.jpg",
			"Naomi Heisstand, Mission Team" => "heisstand.jpg",
			"Breanna Jones, Publicity Team" => "jones.jpg",
			"Christopher Lee, Logistics Team" => "lee.jpg",
			"Valerie Malachin, Mission Team" => "malachin.jpg",
			"Ritika Nagpal, Mission Team" => "nagpal.jpg",
			"Jayne Simon, Mission Team" => "simon.jpg",
			"Zach Trdinich, Publicity Team" => "trdinich.jpg",
			"Luke Turkovich, Logistics Team" => "turkovich.jpg",
			"Jon Van Kirk, Mission Team" => "vankirk.jpg",
			"Jeremiah Zemet, Mission Team" => "zemet.jpg"
		)
			
	),
	
	"NAI Team" => array(
		"Team Directors" => array(
			"Grace Welsh, NAI Director" => "",
			"RJ Swanson, NAI Director" => ""
		),
		"Team Members" => array(
			"Andrew Baierl, Logistics Team" => "",
			"Grace Baierl, Mission Team" => "",
			"Meghna Behari, Logistics Team" => "",
			"John Catanzaro, Logistics Team" => "",
			"Charlie Deible, Logistics Team" => "",
			"Patrick Fenlon, Logistics Team" => "",
			"Josh Galecki, Logistics Team" => "",
			"Andrew Johnson, Logistics Team" => "",
			"Ana Key, Logistics Team" => "",
			"Angelina Lowe, Logistics Team" => "",
			"Jake Mellinger, Player's Union" => "",
			"Jacob Pan, Publicity Team" => "",
			"Natalie Shoup, Player's Union" => "",
			"Andrew Solman, Logistics Team" => "",
			"Lydia Thomas, Player's Union" => "",
			"Rose Timmer, Mission Team" => "",
			"Grace Waldee, Mission Team" => ""			
		)
	)
);
$ids = [2757838540, 1636704312, 1045293290, 1380804866, 2405315112, 601340706, 2332523996, 381211050, 496475347, 2902146421, 1024988798, 2510451658, 2402095411, 1674638335, 2576757000, 324719303, 40125872];

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

    <h1 style='text-align:center;color:#3cf;font-family: HelveticaNeueCondensedBold;'>2017-2018 NA Project Water Teams</h1>

    <?php

$counter = 0;
$lastDidEnd = false;
foreach($teams as $year=>$yearTeams){
    echo "<h2 style='text-align:center;text-decoration:underline'>" . $year . "</h2>";
    foreach($yearTeams as $teamName=>$people) {
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
                echo 'img/team/2017_team/' . $id;
            echo '" style="border-radius:5px;"><div style="margin-top:-23px;">';
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
        if(!$lastDidEnd)
            echo '</div>';
        $counter = 0;
    }
    echo "<hr>";
}

echo "<center>Special thanks to <a href='http://www.kayceeorwigphotography.com' target='_blank'>Kaycee Orwig</a> for taking our team pics!</center>";
echo "<center>Check out all of our past year's NA Project Water teams <a href='http://www.naprojectwater.com/allteams'>here!</a></center>";
include 'template-lower.php'; 
?>
