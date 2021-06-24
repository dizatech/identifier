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

var url = window.location.href.replace(/\/$/, '');
var page_type = url.substring(url.lastIndexOf('/') + 1);
var page = 'default';

$(function () {
    if (typeof page_type != 'undefined'){
        if (page_type === 'default' || page_type === 'register'
            || page_type === 'code' || page_type === 'login'
            || page_type === 'forgot'){
            page = page_type;
        }
    }
});

// login and register handler
$('.create_account').on('click', function (e) {
    e.preventDefault();
    page = 'register';
    window.history.pushState('', '', '/auth/register');
    $('.default_page').hide('slide', { direction: "right" }, function(){
        $('.register_page').show("slide", { direction: "left" });
    });
});

$('.code_step').on('click', function (e) {
    e.preventDefault();
    page = 'code';
    window.history.pushState('', '', '/auth/code');
    $('.register_page').hide('slide', { direction: "right" }, function(){
        $('.code_page').show("slide", { direction: "left" });
    });
});

$('.back-btn').on('click', function (e) {
    e.preventDefault();
    switch (page) {
        case 'register':
            page = 'default';
            $('.register_page').hide('slide' , { direction: "left" }, function(){
                $('.default_page').show('slide', { direction: "right" })
            });
            window.history.pushState('', '', '/auth/default');
        break;
        case 'code':
            page = 'register';
            $('.code_page').hide('slide' , { direction: "left" }, function(){
                $('.register_page').show('slide', { direction: "right" })
            });
            window.history.pushState('', '', '/auth/register');
        break;
        case 'login':
            page = 'default';
            $('.login_page').hide('slide' , { direction: "left" }, function(){
                $('.default_page').show('slide', { direction: "right" })
            });
            window.history.pushState('', '', '/auth/login');
        break;
        case 'forgot':
            page = 'default';
            $('.forgot_page').hide('slide' , { direction: "left" }, function(){
                $('.default_page').show('slide', { direction: "right" })
            });
            window.history.pushState('', '', '/auth/default');
        break;
        default:
            window.location = '/';
    }
});
