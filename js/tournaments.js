$("#tournamentStartDatePicker").datetimepicker({
    inline: true,
    timepickerScrollbar: false,
    step: 30
});
$("#registrationStartDatePicker").datetimepicker({
    inline: true,
    timepickerScrollbar: false,
    step: 30
});

function submitTournament() {
    $("#registrationStartDatePicker").val($("#registrationStartDatePicker").datetimepicker('getValue'));
    
    $("#tournamentDescription").val(CKEDITOR.instances.tournamentDescription.getData());
    $("#tournamentStartDatePicker").val($("#tournamentStartDatePicker").datetimepicker('getValue'));
    document.getElementById("tournamentForm").submit();

}

$(document).ready(function () {
    CKEDITOR.replace('tournamentDescription');
});

function editTournament(id){
    $('#tournamentForm').css('display','none');
    $('#tournamentLoader').css('display','block');
    $('#tournamentModal').modal();
    $('#tournamentModalLabel').text('Edit Tournament');
    $('#tournamentFormAction').val('updateTournament');
    $('#tournamentFormUpdateID').val(id);
    $.getJSON('/api?action=getTournamentDetails&tournamentKey=' + id, function (data) {
        $("#schoolName").val(data['Name']);
        $('#PromoVideoUrl').val(data['PromoVideoUrl']);
        $('#RegistrationURL').val(data['RegistrationURL']);
        if(data['RegistrationIsOpen'])
            $('#showRadioButton').prop('checked',true);
        else
            $('#hideRadioButton').prop('checked',true);
        if(data['Theme'] == 'Dark')
            $('#darkThemeRadioButton').prop('checked',true);
        else
            $('#lightThemeRadioButton').prop('checked',true);
        $('#numCourts').val(data['NumCourts']);
        $('#registrationStartDatePicker').val(data['RegistrationDate']);
        $('#tournamentStartDatePicker').val(data['TournamentDate']);
        $('#tournamentForm').css('display','block');
        $('#tournamentLoader').css('display','none');
        CKEDITOR.instances.tournamentDescription.setData(data['TournamentDescription']);
    });
}

function editTournamentTeams(id){
    $('#teamForm').css('display','none');
    $('#teamLoader').css('display','block');
    $('#teamList').html('');
    $('#teamList').css('display','none');
    $('#teamModal').modal();
    $('#teamTournamentKey').val(id);
    $.getJSON('/api?action=getTournamentTeams&tournamentKey=' + id, function (data) {
        var teamHTML = '';
        for(var i=0; i < data.length; i++){
            teamHTML += data[i].name;
        
            teamHTML += '<form method="post" action="/api">';
            teamHTML += '<input type="hidden" name="action" value="deleteTeam">';
            teamHTML += '<input type="hidden" name="toDelete" value="' + data[i].id + '">';
            teamHTML += '<button type="submit" class="btn btn-danger" style="float:right;margin-top:-24px">Delete</button></form>';
            
            
            teamHTML += '<hr>';
        }
        $('#teamList').html(teamHTML);
        $('#teamForm').css('display','block');
        $('#teamLoader').css('display','none');
        $('#teamList').css('display','block');
    });
}

function newTournament(){
    $('#tournamentForm').css('display','block');
    $('#tournamentLoader').css('display','none');
    $('#tournamentModal').modal();
    $('#tournamentModalLabel').text('Create New Tournament');
    $("#schoolName").val('');
    $('#PromoVideoUrl').val('');
    $('#RegistrationURL').val('');
    $('#showRadioButton').prop('checked',true);
    $('#darkThemeRadioButton').prop('checked',true);
    $('#numCourts').val('1');
    $('#registrationStartDatePicker').datetimepicker('reset');
    $('#tournamentStartDatePicker').datetimepicker('reset');
    $('#tournamentFormAction').val('addTournament');
    CKEDITOR.instances.tournamentDescription.setData('');
}