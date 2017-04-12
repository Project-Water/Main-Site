function StringSet() {
    var setObj = {},
    val = {};

    this.contains = function(str) {
        return setObj[str] === val;
    };

    this.add = function(str) {
        if (!this.contains(str))
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



var school = "";
var gameLocation = "";
var data;

var sortedTeams;

function goFullScreen() {
    var elem = document.getElementById("bodyElement");
    if (elem.requestFullscreen) {
        elem.requestFullscreen();
    } else if (elem.msRequestFullscreen) {
        elem.msRequestFullscreen();
    } else if (elem.mozRequestFullScreen) {
        elem.mozRequestFullScreen();
    } else if (elem.webkitRequestFullscreen) {
        elem.webkitRequestFullscreen();
    }
}

// Array Remove - By John Resig (MIT Licensed)
Array.prototype.remove = function(from, to) {
  var rest = this.slice((to || from) + 1 || this.length);
  this.length = from < 0 ? this.length + from : from;
  return this.push.apply(this, rest);
};


function updateDisplay() {
    var teams = data["teams"];
    var events = [];

    for (var i = 0; i < data.length; i++) {
        if(data[i]['location'] == gameLocation)
            events.push(data[i]);
    }

    events.sort(function(a, b) {
        var time1 = a['time'];
        var time2 = b['time'];

        var hour1 = time1.substring(0, 2);
        if (hour1.charAt(1) == ":")
            hour1 = hour1.substring(0, 1);

        var hour2 = time2.substring(0, 2);
        if (hour2.charAt(1) == ":")
            hour2 = hour2.substring(0, 1);

        hour1 = parseInt(hour1);
        hour2 = parseInt(hour2);

        var mins1 = parseInt(time1.slice(-2));
        var mins2 = parseInt(time2.slice(-2));

        var date1 = new Date();
        var date2 = new Date();

        if(hour1 < 7)
            date1.setHours(hour1 + 12);
        else
            date1.setHours(hour1);

        if(hour2 < 7)
            date2.setHours(hour2 + 12);
        else
            date2.setHours(hour2);

        date1.setMinutes(mins1);
        date2.setMinutes(mins2);

        return date1 - date2;
    });
    //remove duplicates
    for(var i = 0; i < events.length; i++){
        for(var j = i + 1; j < events.length; j++){
            if(events[i]['time'] == events[j]['time'] && events[i]['competitor'] == events[j]['team'] && events[i]['team'] == events[j]['competitor']){
                events.remove(i);
                break;
            }
        }
    }

    //prune back
    var currentTime = new Date();
    for (var i = 0; i < events.length; i++){
        var time = events[i]['time'];
        var savedHour = time.substring(0, 2);
        if (savedHour.charAt(1) == ":")
            savedHour = savedHour.substring(0, 1);
        var savedMin = time.slice(-2);
        var newDate = new Date();
        if(savedHour == 12)
          newDate.setHours(12);
        else if(savedHour >= 7 && savedHour < 12)
          newDate.setHours(parseInt(savedHour));
        else
          newDate.setHours(parseInt(savedHour) + 12);
        newDate.setMinutes(parseInt(savedMin));
        if(currentTime.getTime() - newDate.getTime() > 5 * 60 * 1000){
            events.remove(i);
            i--;
        }
    }
    for (var i = 0; i < events.length && i < 3; i++) {
        switch (i) {
            case 0:
            $("#nowPlaying").text(events[i]['team'] + ' vs ' + events[i]['competitor']);
            break;
            case 1:
            $("#nextUp").text(events[i]['team'] + ' vs ' + events[i]['competitor']);
            break;
            case 2:
            $("#nextNextUp").text(events[i]['team'] + ' vs ' + events[i]['competitor']);
            break;
        }
    }



    if (data.length < 3) {
        $("#nextNextUp").text("");
        if (data.length < 2) {
            $("#nextUp").text("");
            if (data.length < 1)
                $("#nowPlaying").text("");
        }
    }
}

function activate(){
    if($("#schoolPicker").val() == "School" || $("#locationPicker").val() == "Location"){
        alert("You stupid jackass, you need to select a school and a location. We can't read your mind");
        return;
    }
    $('#codeModal').modal('hide');
    $("#courtNum").text(gameLocation);
    var teamData = firebase.database().ref('/schedule/' + school);
    teamData.on('value', function(snapshot) {
        data = snapshot.val();
    });
    updateDisplay();
    window.setTimeout(function() {
        updateDisplay();
    }, 15000);
}

$(document).ready(function() {
    $('#codeModal').modal('show');
    $("#schoolPicker").on('change', function() {
        school = this.value;
        $("#locationPicker").prop( "disabled", true );
        if(school != "School"){
            return firebase.database().ref('/schedule/' + school).once('value').then(function(snapshot) {
                data = snapshot.val();
                var locations = new StringSet();
                for (var i = 0; i < data.length; i++) { 
                    locations.add(data[i]['location']);
                }

                var locationPickerHTML = '<option value="Location">Pick a location</option>';
                var locationArray = locations.values().sort();
                for (var i = 0; i < locationArray.length; i++) { 
                    locationPickerHTML += `<option value="${locationArray[i]}">${locationArray[i]}</option>`;
                }
                $("#locationPicker").html(locationPickerHTML);
                $("#locationPicker").prop( "disabled", false );
            });
        }
    });
    $("#locationPicker").on('change', function() {
        gameLocation = this.value;
    });
});