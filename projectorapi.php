<?php
include 'GDS/GDS.php';

$code = $_GET['code'];

$memcache = new Memcache;
$data = $memcache->get($code);

if ($data === false) {
    $obj_store = new GDS\Store('Computers');
	$data = $obj_store->fetchOne('SELECT * FROM Computers WHERE code=@code',['code'=>$code]);
	if ($data !== null) 
        $memcache->set('' . $code, $data, 900); //keep in the cache for 15 mins
}


if ($data == null) 
    echo json_encode(['status' => 'fail', 'message' => 'Code not found']);
else{
    echo json_encode(["teams"=>json_decode($data->teams,true)["teams"], 'court'=>$data->court, 'winner'=>$data->winner]);
}
?>