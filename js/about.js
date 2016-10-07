 $.getJSON("json/goal.json", function (data) {
            var total = parseFloat(data["total"]);
            $("#amountRaised").text("$" + total.toFixed(2));
 });