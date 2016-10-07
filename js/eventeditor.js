$("#eventStartTime").datetimepicker({
    inline: true,
    timepickerScrollbar: false,
    step: 30
});

function setEventValue() {
    $("#eventStartTime").val($("#eventStartTime").datetimepicker('getValue'));

}