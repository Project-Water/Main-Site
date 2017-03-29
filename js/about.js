 $.getJSON("/api?action=getGoal", function (data) {
            var total = parseFloat(data["total"]);
            $("#amountRaised").text("$" + total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
 });