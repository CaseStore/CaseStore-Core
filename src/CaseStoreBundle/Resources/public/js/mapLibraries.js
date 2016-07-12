var mapObject;

function goToPostcode() {
    $('#PostCodeSearchLoading').show();
    $('#PostCodeSearchMessage').hide();
    $.ajax({url: '/data/postcode/' + encodeURIComponent($('#PostCode').val()) })
        .done(function(data) {

            $('#PostCodeSearchLoading').hide();
            if (data.result) {
                mapObject.setView([data.lat, data.lng], 16);
            } else {
                $('#PostCodeSearchMessage').html('Can not find that postcode!').show();
            }
        });
}

function startMap(divId) {
    mapObject = L.map(divId).setView([57.04199, -3.55957], 6);

    L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(mapObject);
}

