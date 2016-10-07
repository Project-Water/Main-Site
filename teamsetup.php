<?php
include 'GDS/GDS.php';

$obj_store = new GDS\Store('Computers');

function generateCode()
{
	$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$randomString = '';
	for ($i = 0; $i < 5; $i++) {
		$randomString.= $characters[rand(0, strlen($characters) - 1) ];
	}
	return $randomString;
}
if($_SERVER['REQUEST_METHOD'] == "POST"){

    if($_POST["operation"] == "add"){
        $computer = new GDS\Entity();
        $computer->code = generateCode();
        $computer->teams = json_encode(["teams"=>[]]);
        $computer->court = $_POST["courtName"];
        $computer->winner = "";
        $obj_store->upsert($computer);
    }
    else if($_POST["operation"] == "pushNextTeam"){
        $computerToPush = $_POST["code"];
        $computer = $obj_store->fetchOne('SELECT * FROM Computers WHERE code=@code', ['code' => $computerToPush]);
        $teams = json_decode($computer->teams, true);
        array_shift($teams["teams"]);
        $computer->teams = json_encode($teams);
        $obj_store->upsert($computer);
        
        $memcache = new Memcache;
        $memcache->set('' . $computerToPush, $computer, 900); //keep in the cache for 15 mins
    }
    else if($_POST["operation"] == "addTeam"){
        $computerToPush = $_POST["code"];
        $computer = $obj_store->fetchOne('SELECT * FROM Computers WHERE code=@code', ['code' => $computerToPush]);
        $teams = json_decode($computer->teams, true);
        array_push($teams["teams"], $_POST["teamName"]);
        $computer->teams = json_encode($teams);
        $obj_store->upsert($computer);
        
        $memcache = new Memcache;
        $memcache->set('' . $computerToPush, $computer, 900); //keep in the cache for 15 mins
    }
    else if($_POST["operation"] == "delete"){
        $computerToDelete = $_POST["code"];
        $computer = $obj_store->fetchOne('SELECT * FROM Computers WHERE code=@code', ['code' => $computerToDelete]);
        $obj_store->delete($computer);
    }
    else if($_POST["operation"] == "announceWinner"){
        $obj_store->query('SELECT * FROM Computers');
        $result = $obj_store->fetchAll();
        $memcache = new Memcache;
        foreach($result as $computer) {
            $computer->winner = $_POST["winner"];
            $memcache->set('' . $computer->code, $computer, 900);
            $obj_store->upsert($computer);
        }
    }
    sleep(1);
    header('Location: /teamsetup');
}
else{
    // attempt to query the database for the user
	$obj_store->query('SELECT * FROM Computers');
	$result = $obj_store->fetchAll();
	if (count($result) > 0) {
		// well we have some lessons, iterate through them and start dumping them into an array
        ?>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <table border="1" style="border-collapse: collapse;width:75%;">
        <tr>
            <td><b>Code</b></td>
            <td><b>Court Name</b></td>
            <td><b>Teams</b></td>
            <td><b>Add Team</b></td>
            <td><b>Next Team</b></td>
            <td><b>Delete</b></td>
        </tr>
        <?php
		foreach($result as $computer) {
            echo "<tr>";
			echo "<td>" . $computer->code . "</td>";
            echo "<td>" . $computer->court . "</td>";
            echo "<td>";
            if(json_decode($computer->teams, true)["teams"] != null)
                foreach(json_decode($computer->teams, true)["teams"] as $team)
                    echo $team . ",";
            echo "</td>";
            echo "<td>" . '<form action="teamsetup" method="post"><input type="hidden" name="operation" value="addTeam"><input type="hidden" name="code" value="' . $computer->code . '"><input type="text" name="teamName"><input type="submit" value="Add Team"></form>' . "</td>";
            echo "<td>" . '<form action="teamsetup" method="post"><input type="hidden" name="operation" value="pushNextTeam"><input type="hidden" name="code" value="' . $computer->code . '"><input type="submit" value="Next"></form>' . "</td>";
            echo "<td>" . '<form action="teamsetup" method="post"><input type="hidden" name="operation" value="delete"><input type="hidden" name="code" value="' . $computer->code . '"><input type="submit" value="Delete"></form>' . "</td>";
            echo "</tr>";
		}
        ?>
    </table>
    <?php
	}
}
?>
        <form action="teamsetup" method="post">
            <input type="hidden" name="operation" value="announceWinner">
            <input type="text" name="winner" placeholder="Winner">
            <input type="submit" value="Announce">
        </form>
        <br>
        <form action="teamsetup" method="post">
            <input type="hidden" name="operation" value="add">
            <input type="text" name="courtName" placeholder="Court Name">
            <input type="submit" value="Add Computer">
        </form>

        <script src="js/jquery-1.11.2.min.js"></script>
        <script src="js/bootstrap.min.js" data-no-instant></script>