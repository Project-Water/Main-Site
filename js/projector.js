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
var team = "";
var data;

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



function updateDisplay() {
    $.getJSON("projectorapi?code=" + code, function (data) {
        var teams = data["teams"];
        $("#courtNum").text(data["court"]);
        for (var i = 0; i < teams.length; i++) {
            switch (i) {
                case 0:
                $("#nowPlaying").text(teams[i]);
                break;
                case 1:
                $("#nextUp").text(teams[i]);
                break;
                case 2:
                $("#nextNextUp").text(teams[i]);
                break;
            }
        }


        
    })

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

}

$(document).ready(function() {
    $('#codeModal').modal('show');
    $("#schoolPicker").on('change', function() {
        console.log("test");
        school = this.value;
        if(school == "School")
            $("#locationPicker").prop( "disabled", true );
        else{
            return firebase.database().ref('/schedule/' + school).once('value').then(function(snapshot) {
                data = snapshot.val();
                console.log(data);
            });
        }
    });
});