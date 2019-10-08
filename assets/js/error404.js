var seconds = 10;

let secondPassed = function(){
    $('#countdown').html(seconds);
    if (seconds <= 0){
        window.location.href = $('#countdown').attr('href');
    }else{
        seconds--;
    }
}

window.setInterval(
    function(){secondPassed()}
    ,1000
);

