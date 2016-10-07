<?php include 'checklogged.php'; include 'GDS/GDS.php'; include 'template-upper.php'; ?>
    <link href="/css/tournaments.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/css/jquery.datetimepicker.css" />

    <h1 class="subPageTitle">Tournaments</h1>


    <?php
$obj_store = new GDS\Store('Tournaments');
$data = $obj_store->fetchAll();
if($data == null)
    echo '<div class="text-center">No tournaments</div>';
else{
    echo '<table class="modernTable">';
    echo '<thead><tr><th><span>Name</span></th><th><span>Promo Video</span></th><th><span>Registration</span></th><th><span>Registration Open</span></th><th><span>Registration Date</span></th><th><span>Tournament Date</span></th><th><span>Edit</span></th><th><span>Teams</span></th><th><span>Referees</span></th><th><span>Delete</span></th></tr></thead>';
    echo '<tbody>';
    foreach($data as $tournament){
        echo '<tr>';
        echo '<td>' . $tournament->Name . '</td>';
        echo '<td><a href="' . $tournament->PromoVideoUrl . '">' . $tournament->PromoVideoUrl . '</a></td>';
        echo '<td><a href="' . $tournament->RegistrationURL . '">' . $tournament->RegistrationURL . '</a></td>';
        echo '<td>';
        if($tournament->RegistrationIsOpen)
            echo 'Yes';
        else
            echo 'No';
        echo '</td>';
        echo '<td>' . date("F j, Y<\b\\r>g:i A", strtotime($tournament->RegistrationDate)) . '</td>';
        echo '<td>' . date("F j, Y<\b\\r>g:i A", strtotime($tournament->TournamentDate)) . '</td>';
        
        echo '<td><button type="submit" style="background-color:#3cf;color:white;border-color:white;" class="btn btn-default" onclick="editTournament(' . $tournament->getKeyId() .')">Edit</button></td>';
        
        echo '<td><button type="submit" style="background-color:#3cf;color:white;border-color:white;" class="btn btn-default" onclick="editTournamentTeams(' . $tournament->getKeyId() .')">Edit Teams</button></td>';
        
        echo '<td><button type="submit" style="background-color:#3cf;color:white;border-color:white;" class="btn btn-default" data-toggle="modal" data-target="#refModal">Edit Referees</button></td>';
        
        
        echo '<td>';
        echo '<form method="post" action="/api" onSubmit="if(!confirm(\'Are you sure you want to delete this tournament? All associated teams and referees will be deleted\')){return false;}">';
        echo '<input type="hidden" name="action" value="deleteTournament">';
        echo '<input type="hidden" name="toDelete" value="' . $tournament->getKeyId() . '">';
        echo '<button type="submit" class="btn btn-danger">Delete</button></form></td>';
        
        
    }
    echo '</tbody></table>';
}
?>
        <br>
        <br>
        <button class="fancyButton" onclick="newTournament()">Create Tournament</button>

        <div class="modal fade" id="tournamentModal" tabindex="-1" role="dialog" aria-labelledby="tournamentModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="tournamentModalLabel">Create New Tournament</h4>
                    </div>
                    <div class="modal-body">
                        <div class="loader" style="display:none" id="tournamentLoader">Loading...</div>
                        <form action="/api" method="post" id="tournamentForm">
                            <input type="hidden" name="action" value="addTournament" id="tournamentFormAction">
                            <input type="hidden" name="toUpdate" value="" id="tournamentFormUpdateID">

                            <h4>School Name</h4>
                            <input type="text" placeholder="School" name="Name" id="schoolName">

                            <h4>Promo Video URL</h4>
                            <input type="text" placeholder="URL" name="PromoVideoUrl" id="PromoVideoUrl">

                            <h4>Registration Form URL</h4>
                            <input type="text" placeholder="URL" name="RegistrationURL" id="RegistrationURL">

                            <h4>Show on website</h4>
                            <input type="radio" name="showOnSite" value="Yes" checked id="showRadioButton"> Yes &nbsp;
                            <input type="radio" name="showOnSite" value="No" id="hideRadioButton"> No
                            <br>

                            <h4>Theme</h4>
                            <input type="radio" name="theme" value="Dark" checked id="darkThemeRadioButton"> Night &nbsp;
                            <input type="radio" name="theme" value="Light" id="lightThemeRadioButton"> Day
                            <br>
                            
                            <h4>Number of Courts</h4>
                            <input type="number" name="NumCourts" min="1" value="1" id="numCourts">

                            <!--<h4>Scoreboard Image</h4>
                            <input type="file" placeholder="URL">-->

                            <h4>Registration Start Time</h4>
                            <input type="text" id="registrationStartDatePicker" name="RegistrationDate">

                            <h4>Tournament Start Time</h4>
                            <input type="text" id="tournamentStartDatePicker" name="TournamentDate">
                            
                            <h4>Tournament Details</h4>
                            <textarea name="tournamentDescription" id="tournamentDescription" rows="10" cols="80" placeholder="Enter Tournament Details Here">
                            </textarea>
                        </form>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="submitTournament()">Save</button>
                    </div>
                </div>
            </div>
        </div>

<div class="modal fade" id="refModal" tabindex="-1" role="dialog" aria-labelledby="refModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="refModalLabel">Edit referees</h4>
                    </div>
                    <div class="modal-body">
                        
                        <form action="/api" method="post" id="refForm">
                            <input type="hidden" name="action" value="updateRefs">
                            <input type="hidden" name="tournamentKey" value="">
                        </form>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="submitTournament()">Save</button>
                    </div>
                </div>
            </div>
        </div>

<div class="modal fade" id="teamModal" tabindex="-1" role="dialog" aria-labelledby="teamModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="teamModalLabel">Edit teams</h4>
                    </div>
                    <div class="modal-body">
                        <div class="loader" style="display:none" id="teamLoader">Loading...</div>
                        <div id="teamList">
                            
                        </div>
                        <br>
                        <div class="fancyForm" id="teamForm">
                            <div class="formHeader">
                                <h3>ADD TEAM</h3>
                            </div>
                            <form action="/api" method="post">
                                <input type="hidden" name="action" value="addTeam">
                                <input type="hidden" name="TournamentKey" value="" id="teamTournamentKey">
                                <input type="text" name="name" placeholder="Team Name"><br>
                                <input type="submit" value="SUBMIT">
                            </form>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>


        <?php include 'template-lower.php'; ?>