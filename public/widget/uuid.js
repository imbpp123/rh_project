(function() {
    let element = document.getElementById("pixelElement");
    let pixelScript = document.getElementById('pixelScript');
    if (!element || !pixelScript) {
        return;
    }

    let uuid = pixelScript.src.match(/([^\/]+)(?=\.\w+$)/)[0];
    let xhr = new XMLHttpRequest();
    let data = [];

    function loadAverageScore() {
        if (xhr.status === 200) {
            element.innerHTML = "Average score for hotel '" + data[0].name + "': " + xhr.responseText;
        }
    }
    function loadHotel() {
        if (xhr.status === 200) {
            data = JSON.parse(xhr.responseText)
            if (data.length !== 1) {
                return;
            }
            xhr.onload = loadAverageScore;
            xhr.open('GET', '/api/v2/hotel/' + data[0].id + '/average');
            xhr.send();
        }
    }
    xhr.onload = loadHotel;
    xhr.open('GET', '/api/v2/hotel/?uuid=' + uuid);
    xhr.send();
})();