$(document).ready(function () {
    $("#waterDropContainer").load("img/waterdrop.svg", function () {
        $.getJSON("/api?action=getGoal", function (data) {
            var total = parseFloat(data["total"]);
            var goal = parseFloat(data["goal"]);
            var percent = (100 - ((total / goal) * 100)) + "%";

            var date = new Date();
            var textUpper = "$" + total.toFixed(2) + "/";
            var textMiddle = "$" + goal.toFixed(2);
            var textLower = "Last updated: " + (date.getMonth() + 1) + "/" + date.getDate() + "/" + date.getFullYear();

            $("#dropTextUpper").text(textUpper);
            $("#dropTextMiddle").text(textMiddle);
            $("#dropTextLower").text(textLower);


            $("#upperWaterLimit").attr("offset", percent);
            $("#lowerWaterLimit").attr("offset", percent);
        });
    });
    $.getJSON("/api?action=getEvents", function (data) {
        var temp = "";
        var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sept", "Oct", "Nov", "Dec"];
        for (var i = 0; i < data["events"].length; i++) {
            var date = new Date(data["events"][i]["date"]);
            temp += '<div class="row eventRow">';
            temp += '<div class="col-xs-4 calDateHolder">';
            temp += '<div class="calDate">';
            if (isNaN(date.getDay()))
                temp += '<h2 class="calDateDay">Date</h2><br><h4 class="calDateMonth">TBA</h4>';
            else
                temp += '<h2 class="calDateDay">' + date.getDate() + '</h2>' + '<br><h4 class="calDateMonth">' + monthNames[date.getMonth()] + "</h4>";
            temp += '</div>';
            var hour = date.getHours();
            var hourModifier = 'AM';
            if(hour >= 13){
                hour -= 12;
                hourModifier = 'PM';
            }
            if(isNaN(hour)){
                temp += '<br><div class="calDateTime">Date TBA</div>';
            }
            else{
                if (date.getMinutes() == 0){
                    temp += '<br><div class="calDateTime">' + hour + ':00' + hourModifier + '</div>';
                }
                else{
                    temp += '<br><div class="calDateTime">' + hour + ':' + date.getMinutes() + hourModifier + '</div>';
                }
            }
            temp += '</div>';
            temp += '<div class="col-xs-8 calDetailsHolder">';
            temp += '<h2 class="calDateTitle">' + data["events"][i]["title"] + '</h2>';
            temp += '<p class="calDateDescription">' + data["events"][i]["details"] + '</p>';
            temp += '</div>';
            temp += '</div>'
        }
        $("#eventData").html(temp);
    });
    var carousel = document.getElementById("carousel-example-generic");
    Hammer(carousel).on("swipeleft", function (event) {
        $("#carousel-example-generic").carousel('next');
    });
    Hammer(carousel).on("swiperight", function (event) {
        $("#carousel-example-generic").carousel('prev');
    });
});