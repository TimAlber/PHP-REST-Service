


/**
 * 
 * @param {*} href 
 * @param {*} func 
 */
function makeRequest(href, func) {
    
    str = href.split('?');
    action = str[1];
    console.log('Request URL: ' + action);

    httpRequest = new XMLHttpRequest();

    if (!httpRequest) {
      alert('Giving up :( Cannot create an XMLHTTP instance');
      return false;
    }
    console.log('make Request TEST');

    httpRequest.onreadystatechange = function() {
        console.log("Ajax: call alertContents");
        if (httpRequest.readyState === XMLHttpRequest.DONE) {
            console.log("Ajax: readyState: " + httpRequest.status);
            if (httpRequest.status === 200) {
                console.log(httpRequest);
                var response = JSON.parse(httpRequest.responseText);
                var main = document.querySelector('main');
                if (response.isError) {
                    maininnerHTML 
                        = '<div>Upps, da funktioniert etwas nicht</div>'
                        + '<div>' + response.msg + '</div>'
                        + (response.ex ? '<div>' + response.ex + '</div>' : '');  
                } else if (response.isLogin === false) { 
                    isLogin(main, response);
                    console.log("Request without Login: " + response.isLogin);
                }else {
                    func(main, response);
                }
                
            } else {
                alert('There was a problem with the request.');
            }
        }
    }
    httpRequest.open('GET', 'restAPI.php');
    httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    httpRequest.send(action);
}

function showResponseMsg(main, response) {
    console.log(response);
    if (response.isError) {
        main.innerHTML = '<div>Es ist ein Fehler aufgetreten</div>';
    } else {
        main.innerHTML = '<div>Daten erfolgreich versendet</div>';
    }
}