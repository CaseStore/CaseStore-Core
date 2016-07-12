var mapObject;

function goToPostcode() {
    $.ajax({url: '/data/postcode/' + encodeURIComponent($('#PostCode').val()) })
        .done(function(data) {

            if (data.result) {
                mapObject.setView([data.lat, data.lng], 16);
            }
        });
}

function startMap(divId) {
    mapObject = L.map(divId).setView([57.04199, -3.55957], 6);

    L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(mapObject);
}

