var sortable;
$(document).ready(function () {
    var el = document.getElementById('videoList');
    sortable = Sortable.create(el, {
        handle: '.handleOrder',
        animation: 150
    });
});

function saveOrder(){
    var items = sortable.toArray();
    $("#videoOrderStore").val(JSON.stringify(items));
    document.getElementById("videoOrderForm").submit();
}
function checkVideoValidity(URL){
    if(/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/.test(URL)){
        return true;
    }
    alert('Invlaid link. Video must be on youtube');
    event.preventDefault();
    return false;
}