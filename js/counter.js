function createCounter() {

    $('.countDownNASH').countdown({
        date: "February 20, 2017 8:00:00",
        render: function (data) {

            var el = $(this.el);
            el.empty()
                .append(this.leadingZeros(data.days, 1) + "&nbsp;days,&nbsp;")
                .append(this.leadingZeros(data.hours, 1) + "&nbsp;hours, ")
                .append(this.leadingZeros(data.min, 1) + "&nbsp;mins,&nbsp;")
                .append(this.leadingZeros(data.sec, 1) + "&nbsp;secs");
        },
        onEnd: function () {
            $(".countDownNASH").css("display", "none");
            $(".registerButtonNASH").css("display", "block");
            $(".countDownTitle").css("display", "none");
        }
    });
}
if (typeof InstantClick !== 'undefined')
    InstantClick.on('change', createCounter());
$(document).ready(function () {
    createCounter()
});