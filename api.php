<?php

use google\appengine\api\cloud_storage\CloudStorageTools;

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $action = $_GET['action'];
    
    if($action == 'getTournamentDetails'){
        include 'checklogged.php';
        include 'GDS/GDS.php';
        $obj_store = new GDS\Store('Tournaments');
        $tournament = $obj_store->fetchById($_GET['tournamentKey']);
        $tournamentArr = array(
				    'Name' => $tournament->Name,
				    'Key' => $tournament->getKeyId(),
                    'NumCourts' => $tournament->NumCourts,
                    'PromoVideoUrl' => $tournament->PromoVideoUrl,
                    'RegistrationDate' => $tournament->RegistrationDate,
                    'RegistrationIsOpen' => $tournament->RegistrationIsOpen,
                    'RegistrationURL' => $tournament->RegistrationURL,
                    'Theme' => $tournament->Theme,
                    'TournamentDate' => $tournament->TournamentDate,
                    'TournamentDescription' => $tournament->TournamentDescription
			     );
        echo json_encode($tournamentArr, JSON_HEX_QUOT | JSON_HEX_TAG);
    }
    else if($action == 'getTournaments'){
        include 'checklogged.php';
        include 'GDS/GDS.php';
        $obj_store = new GDS\Store('Tournaments');
        $result = $obj_store->fetchAll();
        $tournaments = array();
        if(count($result) > 0){
            foreach($result as $tournament) {
			     $tournaments[] = array(
				    'Name' => $tournament->Name,
				    'Key' => $tournament->getKeyId()
			     );
		    }
            echo json_encode($tournaments);
        }
        else{
            echo '[]';
        }
    }
    else if($action == 'getGoal'){
        include 'GDS/GDS.php';
        $obj_store = new GDS\Store('Goals');
        $currentMoney = $obj_store->fetchOne('SELECT * From Goals WHERE name=@objName', ['objName' => 'currentMoney']);
        if($currentMoney == null){
            $currentMoney = new GDS\Entity();
            $currentMoney->name = 'currentMoney';
            $currentMoney->value = '0';
        }
        $goalMoney = $obj_store->fetchOne('SELECT * From Goals WHERE name=@objName', ['objName' => 'goalMoney']);
        if($goalMoney == null){
            $goalMoney = new GDS\Entity();
            $goalMoney->name = 'goalMoney';
            $goalMoney->value = '0';
        }
        echo json_encode(array(
            'total'=>$currentMoney->value,
            'goal'=>$goalMoney->value
        ));
    }
    else if($action == 'getEvents'){
        include 'GDS/GDS.php';
        $obj_store = new GDS\Store('Events');
        $result = $obj_store->fetchAll();
        $events = array('events'=>array());
        if(count($result) > 0){
            foreach($result as $event) {
			     $events['events'][] = array(
				    'title' => $event->title,
                    'date' => $event->eventDate,
                    'details' => $event->description,
                    'location' => $event->location,
			     );
		    }
            if(count($result) < 2){
                for($i = count($result); $i < 2; $i++){
                    $events['events'][] = array(
                        'title' => 'TBA',
                        'date' => 'TBA',
                        'details' => 'Upcoming events TBA',
                        'location' => 'TBA',
                     );
                }
            }
            echo json_encode($events);
        }
        else{
            $events = array('events'=>array());
            for($i = count($result); $i < 2; $i++){
                $events['events'][] = array(
                    'title' => 'TBA',
                    'date' => 'TBA',
                    'details' => 'Upcoming events TBA',
                    'location' => 'TBA',
                 );
            }
            echo json_encode($events);
        }  
    }
    else if($action == 'getTournamentTeams'){
        include 'checklogged.php';
        include 'GDS/GDS.php';
        $obj_store = new GDS\Store('Teams');
        $obj_store->query('SELECT * FROM Teams WHERE TournamentKey=@id', ['id' => $_GET['tournamentKey']]);
	    $result = $obj_store->fetchAll();
        $teams = array();
        foreach($result as $team){
            $teams[] = array(
                'name' => $team->name,
                'id' => $team->getKeyId(),
            );
        }
        echo json_encode($teams);
    }
    else if($action == 'getTournamentRefs'){
        include 'checklogged.php';
        include 'GDS/GDS.php';
        
    }
    else if($action == 'getSVGImage'){
        header('Content-Type: image/svg+xml');
        echo file_get_contents(urldecode($_GET['url']));
    }
    else{
        echo "Unsupported method";
    }
}
else if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $action = $_POST['action'];
    
    if($action == 'addAdmin'){
        include 'checklogged.php';
        include 'GDS/GDS.php';
        $obj_store = new GDS\Store('Administrators');
        $admin = new GDS\Entity();
        $admin->CanBeDeleted = true;
        $admin->Email = $_POST['email'];
        $admin->Name = $_POST['name'];
        $admin->DateAdded = new DateTime();
        $obj_store->upsert($admin);
        header('Location: /admin/administrators');
    }
    else if($action == 'deleteAdmin'){
        include 'checklogged.php';
        include 'GDS/GDS.php';
        $obj_store = new GDS\Store('Administrators');
        $admin = $obj_store->fetchById($_POST['toDelete']);
        if(!$admin->CanBeDeleted){
            header('Location: /admin/administrators');
            die();
        }
        $obj_store->delete($admin);
        header('Location: /admin/administrators');
    }
    else if($action == 'addTournament'){
        include 'checklogged.php';
        include 'GDS/GDS.php';
        $obj_store = new GDS\Store('Tournaments');
        $tournament = new GDS\Entity();
        $tournament->Name = $_POST['Name'];
        $tournament->PromoVideoUrl = $_POST['PromoVideoUrl'];
        $tournament->RegistrationURL = $_POST['RegistrationURL'];
        if($_POST['showOnSite'] == 'Yes')
            $tournament->RegistrationIsOpen = true;
        else
            $tournament->RegistrationIsOpen = false;
        $tournament->Theme = $_POST['theme'];
        $tournament->NumCourts = $_POST['NumCourts'];
        $tournament->RegistrationDate = substr($_POST['RegistrationDate'], 0, -15);
        $tournament->TournamentDate = substr($_POST['TournamentDate'], 0, -15);
        $tournament->TournamentDescription = $_POST['tournamentDescription'];
        $obj_store->upsert($tournament);
        header('Location: /admin/tournaments');
    }
    else if($action == 'updateTournament'){
        include 'checklogged.php';
        include 'GDS/GDS.php';
        $obj_store = new GDS\Store('Tournaments');
        $tournament = $obj_store->fetchById($_POST['toUpdate']);
        $tournament->Name = $_POST['Name'];
        $tournament->PromoVideoUrl = $_POST['PromoVideoUrl'];
        $tournament->RegistrationURL = $_POST['RegistrationURL'];
        if($_POST['showOnSite'] == 'Yes')
            $tournament->RegistrationIsOpen = true;
        else
            $tournament->RegistrationIsOpen = false;
        $tournament->Theme = $_POST['theme'];
        $tournament->NumCourts = $_POST['NumCourts'];
        $tournament->RegistrationDate = substr($_POST['RegistrationDate'], 0, -15);
        $tournament->TournamentDate = substr($_POST['TournamentDate'], 0, -15);
        $tournament->TournamentDescription = $_POST['tournamentDescription'];
        $obj_store->upsert($tournament);
        header('Location: /admin/tournaments');
    }
    else if($action == 'deleteTournament'){
        include 'checklogged.php';
        include 'GDS/GDS.php';
        $obj_store = new GDS\Store('Tournaments');
        $tournament = $obj_store->fetchById($_POST['toDelete']);
        $obj_store->delete($tournament);
        $obj_store = new GDS\Store('Teams');
        $obj_store->query('SELECT * FROM Teams WHERE TournamentKey=@key', ['key' => $_POST['toDelete']]);
	    $result = $obj_store->fetchAll();
        foreach($result as $team)
            $obj_store->delete($team);
        $obj_store = new GDS\Store('Referees');
        $obj_store->query('SELECT * FROM Referees WHERE TournamentKey=@key', ['key' => $_POST['toDelete']]);
	    $result = $obj_store->fetchAll();
        foreach($result as $ref)
            $obj_store->delete($ref);
        header('Location: /admin/tournaments');
    }
    else if($action == 'addTeam'){
        include 'checklogged.php';
        include 'GDS/GDS.php';
        $tournament_store = new GDS\Store('Tournaments');
        $obj_store = new GDS\Store('Teams');
        $team = new GDS\Entity();
        $team->TournamentKey = $_POST['TournamentKey'];
        $team->name = $_POST['name'];
        $team->Ties = 0;
        $team->Wins = 0;
        $team->Losses = 0;
        $obj_store->upsert($team);
        header('Location: /admin/tournaments');
    }
    else if($action == 'deleteTeam'){
        include 'checklogged.php';
        include 'GDS/GDS.php';
        $obj_store = new GDS\Store('Teams');
        $team = $obj_store->fetchById($_POST['toDelete']);
        $obj_store->delete($team);
        header('Location: /admin/tournaments');
    }
    else if($action == 'addRef'){
        include 'checklogged.php';
        include 'GDS/GDS.php';
        $obj_store = new GDS\Store('Referees');
        $ref = new GDS\Entity();
        $ref->TournamentKey = $_POST['TournamentKey'];
        $obj_store->upsert($ref);
    }
    else if($action == 'updateRefs'){
        
    }
    else if($action == 'changeHomeVideo'){
        include 'checklogged.php';
        include 'GDS/GDS.php';
        $obj_store = new GDS\Store('Videos');
        $homeVideo = $obj_store->fetchOne('SELECT * From Videos WHERE IsHomeVideo=@trueTest', ['trueTest' => true]);
        if($homeVideo == null){
            $homeVideo = new GDS\Entity();
            $homeVideo->Title = '';
            $homeVideo->IsHomeVideo = true;
        }
        $url = $_POST['URL'];
	   preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $url, $matches);
        $homeVideo->URL = $matches[1];
        $obj_store->upsert($homeVideo);
        header('Location: /admin/videos');
    }
    else if($action == 'addVideo'){
        include 'checklogged.php';
        include 'GDS/GDS.php';
        $obj_store = new GDS\Store('Videos');
        $video = new GDS\Entity();
        $video->Title = $_POST['Title'];
        $video->IsHomeVideo = false;
        $url = $_POST['URL'];
	   preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $url, $matches);
        $video->URL = $matches[1];
        $video->order = intval($_POST['nextOrder']);
        $obj_store->upsert($video);
        header('Location: /admin/videos');
    }
    else if($action == 'deleteVideo'){
        include 'checklogged.php';
        include 'GDS/GDS.php';
        $obj_store = new GDS\Store('Videos');
        $video = $obj_store->fetchById($_POST['toDelete']);
        if($video->IsHomeVideo){
            header('Location: /admin/videos');
            die();
        }
        $obj_store->delete($video);
        header('Location: /admin/videos');
    }
    else if($action == 'updateVideoOrder'){
        include 'checklogged.php';
        include 'GDS/GDS.php';
        $obj_store = new GDS\Store('Videos');
        $order = json_decode($_POST['order'], true);
        for($i = 0; $i < count($order); $i++){
            $video = $obj_store->fetchById($order[$i]);
            $video->order = $i;
            $obj_store->upsert($video);
        }
        header('Location: /admin/videos');
    }
    else if($action == 'addImage'){
        include 'checklogged.php';
        include 'GDS/GDS.php';
        $obj_store = new GDS\Store('HomeImages');
        $bucket = CloudStorageTools::getDefaultGoogleStorageBucketName();
        $root_path = 'gs://' . $bucket . '/' . $_SERVER["REQUEST_ID_HASH"] . '/';
        $i = intval($_POST['nextOrder']);
        
        $public_urls = [];
        foreach($_FILES['images']['name'] as $idx => $name) {
            if ($_FILES['images']['type'][$idx] === 'image/jpeg' || $_FILES['images']['type'][$idx] === 'image/png' || $_FILES['images']['type'][$idx] === 'image/gif' || $_FILES['images']['type'][$idx] === 'image/webp' || $_FILES['images']['type'][$idx] === 'image/svg+xml') {
                $original = $root_path . 'original/' . $name;
                move_uploaded_file($_FILES['images']['tmp_name'][$idx], $original);
                
                
                if($_FILES['images']['type'][$idx] === 'image/svg+xml'){
                    $public_urls[] = [
                        'name' => $name,
                        'original' => '/api?action=getSVGImage&url=' . $original,
                        'thumb' => '/api?action=getSVGImage&url=' . $original,
                        'location' => $original
                    ];
                }
                else
                    $public_urls[] = [
                        'name' => $name,
                        'original' => CloudStorageTools::getImageServingUrl($original, ['size' => 1263, 'secure_url' => true]),
                        'thumb' => CloudStorageTools::getImageServingUrl($original, ['size' => 150, 'secure_url' => true]),
                        'location' => $original
                    ];
              }
        }
        foreach($public_urls as $urls){
            $image = new GDS\Entity();
            $image->order = $i;
            $image->URL = $urls['original'];
            $image->thumbURL = $urls['thumb'];
            $image->name = $urls['name'];
            $image->location = $urls['location'];
            $obj_store->upsert($image);
            $i++;
        }
        header('Location: /admin/homeimages');
    }
    else if($action == 'updateImageOrder'){
        include 'checklogged.php';
        include 'GDS/GDS.php';
        $obj_store = new GDS\Store('HomeImages');
        $order = json_decode($_POST['order'], true);
        for($i = 0; $i < count($order); $i++){
            $image = $obj_store->fetchById($order[$i]);
            $image->order = $i;
            $obj_store->upsert($image);
        }
        header('Location: /admin/homeimages');
    }
    else if($action == 'deleteImage'){
        include 'checklogged.php';
        include 'GDS/GDS.php';
        $obj_store = new GDS\Store('HomeImages');
        $image = $obj_store->fetchById($_POST['toDelete']);
        unlink($image->location);
        $obj_store->delete($image);
        header('Location: /admin/homeimages');
    }
    else if($action == 'saveGoals'){
        include 'checklogged.php';
        include 'GDS/GDS.php';
        $obj_store = new GDS\Store('Goals');
        $currentMoney = $obj_store->fetchOne('SELECT * From Goals WHERE name=@objName', ['objName' => 'currentMoney']);
        if($currentMoney == null){
            $currentMoney = new GDS\Entity();
            $currentMoney->name = 'currentMoney';
        }
        $currentMoney->value = $_POST['currentMoney'];
        $obj_store->upsert($currentMoney);
        $goalMoney = $obj_store->fetchOne('SELECT * From Goals WHERE name=@objName', ['objName' => 'goalMoney']);
        if($goalMoney == null){
            $goalMoney = new GDS\Entity();
            $goalMoney->name = 'goalMoney';
        }
        $goalMoney->value = $_POST['goalMoney'];
        $obj_store->upsert($goalMoney);
        header('Location: /admin/goal');
    }
    else if($action == 'addEvent'){
        include 'checklogged.php';
        include 'GDS/GDS.php';
        $obj_store = new GDS\Store('Events');
        $event = new GDS\Entity();
        $event->title = $_POST['title'];
        $event->description = $_POST['description'];
        $event->eventDate = $_POST['eventDate'];
        $event->location = $_POST['location'];
        $obj_store->upsert($event);
        header('Location: /admin/events');
    }
    else if($action == 'deleteEvent'){
        include 'checklogged.php';
        include 'GDS/GDS.php';
        $obj_store = new GDS\Store('Events');
        $event = $obj_store->fetchById($_POST['toDelete']);
        $obj_store->delete($event);
        header('Location: /admin/events');
    }
    else if($action == 'addSponsorYear'){
        include 'checklogged.php';
        include 'GDS/GDS.php';
        $obj_store = new GDS\Store('SponsorYears');
        $year = new GDS\Entity();
        $year->value = $_POST['year'];
        $obj_store->upsert($year);
        header('Location: /admin/sponsors');
    }
    else if($action == 'deleteSponsorYear'){
        include 'checklogged.php';
        include 'GDS/GDS.php';
        $obj_store = new GDS\Store('SponsorYears');
        $year = $obj_store->fetchById($_POST['toDelete']);
        $obj_store->delete($year);
        header('Location: /admin/sponsors');
    }
    else if($action == 'addSponsorLevel'){
        include 'checklogged.php';
        include 'GDS/GDS.php';
        $obj_store = new GDS\Store('SponsorLevels');
        $level = new GDS\Entity();
        $level->name = $_POST['level'];
        $level->order = $_POST['nextOrder'];
        $obj_store->upsert($level);
        header('Location: /admin/sponsors');
    }
    else if($action == 'deleteSponsorLevel'){
        include 'checklogged.php';
        include 'GDS/GDS.php';
        $obj_store = new GDS\Store('SponsorLevels');
        $level = $obj_store->fetchById($_POST['toDelete']);
        $obj_store->delete($level);
        header('Location: /admin/sponsors');
    }
    else{
        echo "Unsupported method";
    }
}
else{
    echo "Unsupported method";
}

?>