var sortable;
$(document).ready(function () {
    var el = document.getElementById('imageList');
    sortable = Sortable.create(el, {
        handle: '.handleOrder',
        animation: 150
    });
});

function saveOrder(){
    var items = sortable.toArray();
    $("#imageOrderStore").val(JSON.stringify(items));
    document.getElementById("imageOrderForm").submit();
}