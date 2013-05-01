function afficheDate () {
    date = new Date();
    var hours = date.toLocaleString();
    $("#date_zone").html(hours);
    setTimeout("afficheDate()",1000);
}

setTimeout("afficheDate()",0);