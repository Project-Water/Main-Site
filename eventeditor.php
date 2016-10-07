<?php include 'checklogged.php'; include 'GDS/GDS.php'; include 'template-upper.php'; ?>
    <link href="/css/eventeditor.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="/css/jquery.datetimepicker.css" />

    <h1 class="subPageTitle">Events</h1>

    <?php
$obj_store = new GDS\Store('Events');
$data = $obj_store->fetchAll();
if($data == null)
    echo "No events";
else{
    echo '<table id="eventTable">';
    echo '<thead><tr><th><span>Title</span></th><th><span>Date</span></th><th><span>Location</span></th><th><span>Description</span></th><th><span>Delete</span></th></tr></thead>';
    echo '<tbody>';
    foreach($data as $event){
        echo '<tr>';
        echo '<td>' . $event->title . '</td>';
        echo '<td>' . $event->eventDate . '</td>';
        echo '<td>' . $event->location . '</td>';
        echo '<td>' . $event->description . '</td>';
        echo '<td>';
        echo '<form method="post" action="/api">';
        echo '<input type="hidden" name="action" value="deleteEvent">';
        echo '<input type="hidden" name="toDelete" value="' . $event->getKeyId() . '">';
        echo '<button type="submit" class="btn btn-danger">Delete</button></form></td>';
        
    }
    echo '</tbody></table>';
}
?>


        <div id="eventForm">
            <div class="formHeader">
                <h3>ADD EVENT</h3>
            </div>
            <form action="/api" method="post">
                <div class="text-center">Event Start Time<br>
                <input type="text" id="eventStartTime" name="eventDate"></div>
                <input type="hidden" name="action" value="addEvent">
                <input type="text" name="title" placeholder="Title"><br>
                <input type="text" name="description" placeholder="Description"><br>
                <input type="text" name="location" placeholder="Location"><br>
                <input type="submit" value="SUBMIT" onsubmit="setEventValue()">
            </form>
        </div>


        <?php include 'template-lower.php'; ?>