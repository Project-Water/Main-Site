<!-- > 


NOTE: THIS IS THE OLD TEAM PAGE> I WANT TO INCLUDE THIS IN A SMALLER "SEE PREVIOUS YEARS" SECTION
		ON THE WEBSITE. PLEASE DO NOT DELETE.

</!-->
<?php
//Twitter consumer key: ***REMOVED***
//Twitter secret: ***REMOVED***

$teams = array(
	
    "2015-2016 Teams" => array(
        "NASH Core Team" => array(
            "Joshua Thomas" => 1380804866,
            "MJ Barton" => 1045293290,
            "Alex Taffe" => 381211050,
            "Gabe Ren" => 496475347
        ),
        "NASH Dodgeball Board" => array(
            "Bailey Daftary" => "daftary_bailey.jpg",
            "Brendan Grzyb" => 2405315112,
            "Jacob Greco" => "greco_jacob.jpg",
            "Kevin Xu" => 2332523996,
            "CJ May" => 2902146421,
            "Kennedy Urban" => "urban-kennedy.jpg",
            "Danny Fujito" => 1024988798,
            "Eric Ricci" => "ricci_eric.jpg",
            "Yara El-Khatib" => 2510451658,
            "Dave Bjorklund" => 2757838540
        ),
        "NAI Core Team" => array(
            "Nikhil Behari" => "photo_template.jpg",
            "Hannah Glasser" => 1674638335,
            "Griffin McVay" => 2576757000, 
            "Margo Weller" => "weller_margo.jpg",
            "Zachary Shuckrow" => "shuckrow_zack.jpg"
        ),
        "Media Team" => array(
            "Alex Taffe" => 381211050,
            "Joshua Thomas" => 1380804866,
            "Shane Mitnick" => 324719303,
            "Julie Chen" => 40125872
        )
    ),
    "2014-2015 Teams" => array(
        "NAI Core Team" => array(
            "Dave Bjorklund" => 2757838540,
            "Ben Cinker" => 1636704312,
            "MJ Barton" => 1045293290,
            "Joshua Thomas" => 1380804866
        ),
        "NAI Dodgeball Board" => array(
            "Bailey Daftary" => "daftary_bailey.jpg",
            "Brendan Grzyb" => 2405315112,
            "Jacob Greco" => "greco_jacob.jpg",
            "Emilie Raymond" => 601340706,
            "Kevin Xu" => 2332523996
        )
    )
);
$ids = [2757838540, 1636704312, 1045293290, 1380804866, 2405315112, 601340706, 2332523996, 381211050, 496475347, 2902146421, 1024988798, 2510451658, 1674638335, 2576757000, 324719303, 40125872];

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

    <h1 style='text-align:center;color:#3cf;font-family: HelveticaNeueCondensedBold;'>Our Teams</h1>

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
        if(!$lastDidEnd)
            echo '</div>';
        $counter = 0;
    }
    echo "<hr>";
}

include 'template-lower.php'; 
?>