// Config and Headers
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(function () {
    $('[data-toggle="tooltip"]').tooltip();
});

// Plugins
window.Swal = require('sweetalert2');

window.alertify = require('alertifyjs/build/alertify.min');

require('lity/dist/lity.min');

require('jquery-ui/ui/effects/effect-slide');

var page = 'default';

// login and register handler
$('.create_account').on('click', function (e) {
    e.preventDefault();
    page = 'register';
    // $('.register_page').show("slide");
    // $('.default_page').hide("slide");
    // $('.login_page').hide("slide");
    // $('.forgot_page').hide("slide");

    $('.default_page').hide('slide', function(){
        $('.register_page').show("slide");
    });
});

$('.back-btn').on('click', function (e) {
    e.preventDefault();
    if (page !== 'default'){
        page = 'default';
        $('.register_page').hide('slide', function(){
            $('.default_page').show('slide')
        });
        // $('.default_page').show("slide", { direction: "left" });
        // $('.login_page').hide("slide", { direction: "right" });
        // $('.register_page').hide("slide", { direction: "right" });
        // $('.forgot_page').hide("slide", { direction: "right" });
    }else {
        window.location = '/';
    }
});

var boxWidth = $(".box").width();
$(".slide-left").click(function(){
    $(".box").animate({
        width: 0
    });
});
$(".slide-right").click(function(){
    $(".box").animate({
        width: boxWidth
    });
});
