	// ref: http://stackoverflow.com/a/1293163/2343
    // This will parse a delimited string into an array of
    // arrays. The default delimiter is the comma, but this
    // can be overriden in the second argument.
    function CSVToArray( strData, strDelimiter ){
        // Check to see if the delimiter is defined. If not,
        // then default to comma.
        strDelimiter = (strDelimiter || ",");

        // Create a regular expression to parse the CSV values.
        var objPattern = new RegExp(
        	(
                // Delimiters.
                "(\\" + strDelimiter + "|\\r?\\n|\\r|^)" +

                // Quoted fields.
                "(?:\"([^\"]*(?:\"\"[^\"]*)*)\"|" +

                // Standard fields.
                "([^\"\\" + strDelimiter + "\\r\\n]*))"
                ),
        	"gi"
        	);


        // Create an array to hold our data. Give the array
        // a default empty first row.
        var arrData = [[]];

        // Create an array to hold our individual pattern
        // matching groups.
        var arrMatches = null;


        // Keep looping over the regular expression matches
        // until we can no longer find a match.
        while (arrMatches = objPattern.exec( strData )){

            // Get the delimiter that was found.
            var strMatchedDelimiter = arrMatches[ 1 ];

            // Check to see if the given delimiter has a length
            // (is not the start of string) and if it matches
            // field delimiter. If id does not, then we know
            // that this delimiter is a row delimiter.
            if (
            	strMatchedDelimiter.length &&
            	strMatchedDelimiter !== strDelimiter
            	){

                // Since we have reached a new row of data,
                // add an empty row to our data array.
                arrData.push( [] );

            }

            var strMatchedValue;

            // Now that we have our delimiter out of the way,
            // let's check to see which kind of value we
            // captured (quoted or unquoted).
            if (arrMatches[ 2 ]){

                // We found a quoted value. When we capture
                // this value, unescape any double quotes.
                strMatchedValue = arrMatches[ 2 ].replace(
                	new RegExp( "\"\"", "g" ),
                	"\""
                	);

            } else {

                // We found a non-quoted value.
                strMatchedValue = arrMatches[ 3 ];

            }


            // Now that we have our value string, let's add
            // it to the data array.
            arrData[ arrData.length - 1 ].push( strMatchedValue );
        }

        // Return the parsed data.
        return( arrData );
    }

    function guid() {
    	function s4() {
    		return Math.floor((1 + Math.random()) * 0x10000)
    		.toString(16)
    		.substring(1);
    	}
    	return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
    	s4() + '-' + s4() + s4() + s4();
    }

    function StringSet() {
    	var setObj = {}, val = {};

    	this.contains = function(str) {
    		return setObj[str] === val;
    	};

    	this.add = function(str) {
    		if(!this.contains(str))
    			setObj[str] = val;
    	};

    	this.remove = function(str) {
    		delete setObj[str];
    	};

    	this.values = function() {
    		var values = [];
    		for (var i in setObj) {
    			if (setObj[i] === val) {
    				values.push(i);
    			}
    		}
    		return values;
    	};
    }

    var school = "NASH";


    function writeUserData() {

    	if(confirm('Are you sure you want to prefill? All previous tournament details will be overwritten')){
    		var file = document.getElementById('csvFile');

    		if(file.files.length == 1)
    		{
    			var reader = new FileReader();

    			reader.onload = function(e)
    			{
    				var data = CSVToArray(e.target.result);
    				var databaseArray = [];
    				for(var i = 1; i < data.length; i++){
    					databaseArray.push({
    						team: data[i][0],
    						time: data[i][1],
    						competitor: data[i][2],
    						location: data[i][3],
    						id: guid()
    					})
    				}
    				firebase.database().ref('schedule/' + school).set(databaseArray);
    			};
    			reader.readAsBinaryString(file.files[0]);
    		}
    		else if(file.files.length == 0){
    			alert("You moron you need to actually choose a file");
    		}
    		else{
    			alert("You moron, using multiple files in this context doesn't even make sense");
    		}
    	}
    }


    function defer(method) {
    	if (window.jQuery)
    		method();
    	else
    		setTimeout(function() { defer(method) }, 50);
    }

    var data;

    function updateTable(snapshot){
    	var tableData = "";
    	var teamNameString = "";
    	var locationsString = "";
    	var teamNames = new StringSet();
    	var locations = new StringSet();
    	data = snapshot.val();
    	if(data != null){
    		for(var i = 0; i < data.length; i++){
    			tableData += "<tr>";
    			tableData += `<td>${data[i]["team"]}</td>`;
    			tableData += `<td>${data[i]["time"]}</td>`;
    			tableData += `<td>${data[i]["competitor"]}</td>`;
    			tableData += `<td>${data[i]["location"]}</td>`;
    			tableData += `<td>${data[i]["id"]}</td>`;
    			tableData += `<td><button type="button" class="btn btn-primary">Edit</button></td>`;
    			tableData += `<td><button type="button" class="btn btn-danger" onclick="deleteTeam('${data[i]["id"]}')">Delete</button></td>`;
    			tableData += "</tr>";

    			teamNames.add(data[i]["team"]);
    			teamNames.add(data[i]["competitor"]);
    			locations.add(data[i]["location"]);
    		}
    		for(var i = 0; i < teamNames.values().length; i++){
    			teamNameString += `<option value="${teamNames.values()[i]}">${teamNames.values()[i]}</option>`;
    		}
    		for(var i = 0; i < locations.values().length; i++){
    			locationsString += `<option value="${locations.values()[i]}">${locations.values()[i]}</option>`;
    		}
    	}
    	teamNameString += '<option value="Other">Other</option>';
    	locationsString += '<option value="Other">Other</option>';

    	console.log("update");
    	if(snapshot == null || snapshot.val() == null){
    		tableData = "";
    		teamNameString = '<option value="Other">Other</option>';
    		locationsString = '<option value="Other">Other</option>';
    	}

    	$("#teamSelect").html(teamNameString);
    	$("#competitorSelect").html(teamNameString);
    	$("#locationSelect").html(locationsString);

    	$("#teamData tbody").html(tableData);
    }

    defer(function () {

    	$("#teamSelect").on('change', function() {
    		if(this.value == "Other")
    			$("#teamOther").css('display', 'inline');
    		else
    			$("#teamOther").css('display', 'none');
    	});
    	$("#competitorSelect").on('change', function() {
    		if(this.value == "Other")
    			$("#competitorOther").css('display', 'inline');
    		else
    			$("#competitorOther").css('display', 'none');
    	});
    	$("#locationSelect").on('change', function() {
    		if(this.value == "Other")
    			$("#locationOther").css('display', 'inline');
    		else
    			$("#locationOther").css('display', 'none');
    	});

    	var tableData = firebase.database().ref('/schedule/'  + school);
    	tableData.on('value', function(snapshot) {
    		updateTable(snapshot);
    	});

    	return firebase.database().ref('/schedule/' + school).once('value').then(function(snapshot) {
    		updateTable(snapshot);
    	});
    });

    function addTeam(){
    	var team = $("#teamSelect").val();
    	if(team == "Other")
    		team = $("#teamOther").val();
    	var time = $("#timeField").val();
    	var location = $("#locationSelect").val();
    	if(location == "Other")
    		location = $("#locationOther").val();
    	var competitor = $("#competitorSelect").val();
    	if(competitor == "Other")
    		competitor = $("#competitorOther").val();


    	if(competitor == team){
    		alert("You stupid idiot, a team can't face themselves");
    		return;
    	}
    	if(!time.replace(/\s/g, '').length || time == ""){
			alert("Schedules rely on something called time. Somehow you're dumb enough to have forgotten the time field. How do you feel now?");
    		return;
    	}
    	if(time.replace(new RegExp('^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$'), '').length){
    		alert("You incompetent oaf, how do you manage to type in a time incorrectly? It's literally just HH:MM, how complicated is that?!");
    		return;
    	}
    	time += " " + $("#ampmSelector").val();

    	if(!team.replace(/\s/g, '').length || team == ""){
    		alert("Hey dingus, you need to actually assign the team a name");
    		return;
    	}
    	if(!competitor.replace(/\s/g, '').length || competitor == ""){
    		alert("Yes, this makes sense. A team can just play against no one.");
    		return;
    	}
    	if(!location.replace(/\s/g, '').length || location == ""){
    		alert("Nah, this team doesn't actually need to know where to play. How about we just put them on Mars or somewhere in Timbuktu");
    		return;
    	}

    	data.push({
    		team: team,
    		time: time,
    		competitor: competitor,
    		location: location,
    		id: guid()
    	})
    	firebase.database().ref('/schedule/' + school).update(data);
    	$('#addTeamModal').modal('hide');

    	$("#teamSelect").selectedIndex = 0;
    	$("#timeField").val("");
    	$("#locationSelect").selectedIndex = 0;
    	$("#competitorSelect").selectedIndex = 0;
    	$("#teamOther").val("");
    	$("#locationOther").val("");
    	$("#competitorOther").val("");
    }

    // Array Remove - By John Resig (MIT Licensed)
    Array.prototype.remove = function(from, to) {
    	var rest = this.slice((to || from) + 1 || this.length);
    	this.length = from < 0 ? this.length + from : from;
    	return this.push.apply(this, rest);
    };
    function deleteTeam(id){
    	var i;
    	for(i = 0; i < data.length; i++){
    		if(data[i]['id'] == id){
    			data.remove(i)
    			break;
    		}
    	}
    	firebase.database().ref('/schedule/' + school).set(data);
    }

    function changeSchools(schoolName){
    	school = schoolName;
    	if(schoolName == 'NASH'){
    		$("#naiButton").removeClass('active');
    		$("#naiButton").removeClass('btn-primary');
    		$("#naiButton").addClass('btn-default');

    		$("#nashButton").addClass('active');
    		$("#nashButton").addClass('btn-primary');
    		$("#nashButton").removeClass('btn-default');
    	}
    	else if(schoolName == 'NAI'){
    		$("#nashButton").removeClass('active');
    		$("#nashButton").removeClass('btn-primary');
    		$("#nashButton").addClass('btn-default');

    		$("#naiButton").addClass('active');
    		$("#naiButton").addClass('btn-primary');
    		$("#naiButton").removeClass('btn-default');
    	}
    	return firebase.database().ref('/schedule/' + school).once('value').then(function(snapshot) {
    		updateTable(snapshot);
    	}).catch(function(error) {
    		updateTable(null);
    	});
    }

    function presentAddTeamDialogue(){

    }
