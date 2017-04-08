<?php
include 'checklogged.php';
include 'template-upper.php';
?>
<style>
    /* a wrapper for the paginatior */
    .btn-group-wrap {
        text-align: center;
    }

    div.btn-group {
        margin: 0 auto; 
        text-align: center;
        width: inherit;
        display: inline-block;
    }

    a {
        float: left;   
    }

</style>
<div class="modal fade" tabindex="-1" role="dialog" id="addTeamModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add Team</h4>
    </div>
    <div class="modal-body">
        <form id="addTeamForm">
            Team:<br>
            <select id="teamSelect"><option value="Other">Other</option></select><input type="text" id="teamOther" placeholder="Other" style="display: none"><br>
            Time:<br>
            <input type="text" name="timeField" id="timeField"><br>
            Competitor:<br>
            <select id="competitorSelect"><option value="Other">Other</option></select> <input type="text" id="competitorOther" placeholder="Other" style="display: none"><br>
            Location:<br>
            <select id="locationSelect"><option value="Other">Other</option></select><input type="text" id="locationOther" placeholder="Other" style="display: none"><br>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="addTeam()">Add Team</button>
    </div>
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<br>
<div class="btn-group-wrap">
    <div class="btn-group btn-group-lg" role="group" aria-label="...">
        <button type="button" class="btn btn-primary active" id="nashButton" onclick="changeSchools('NASH')">NASH</button>
        <button type="button" class="btn btn-default" id="naiButton" onclick="changeSchools('NAI')">NAI</button>
    </div>
</div>
<br>
<br>
<div class="table-responsive">
<table border="1" style="border-collapse: collapse;width:75%;display: block;" id="teamData" class="table">
    <thead>
        <tr>
            <td><b>Team</b></td>
            <td><b>Time</b></td>
            <td><b>Competitor</b></td>
            <td><b>Location</b></td>
            <td><b>id</b></td>
            <td><b>Edit</b></td>
            <td><b>Delete</b></td>
        </tr>
    </thead>
    <tbody>
    </tbody>

</table>
</div>

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary btn-lg" onclick="addTeamButtonPressed()">
  Add Item
</button>


<hr>
<h3>Prefill</h3>
<form action="teamsetup" method="post">
    <input type="file" name="dataFile" accept=".csv" id="csvFile">
    <button type="button" onclick="writeUserData()">Enter</button>
</form>

<script src="https://www.gstatic.com/firebasejs/3.7.4/firebase.js"></script>
<script>
          // Initialize Firebase
          var config = {
            apiKey: "AIzaSyAJqS8YxLjKQcXLeoWdH4rtHEyxdH7lrEU",
            authDomain: "lunar-caster-87118.firebaseapp.com",
            databaseURL: "https://lunar-caster-87118.firebaseio.com",
            projectId: "lunar-caster-87118",
            storageBucket: "lunar-caster-87118.appspot.com",
            messagingSenderId: "472703596562"
        };
        firebase.initializeApp(config);
    </script>
    <script src="/js/teamSetup.js"></script>
    <script src="js/jquery-1.11.2.min.js"></script>
    <script src="js/bootstrap.min.js" data-no-instant></script>
    <?php include 'template-lower.php';?>