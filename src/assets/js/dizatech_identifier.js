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
var page_type = '';
var page = 'default';

$(function () {
    set_page();
});

function set_page() {
    page_type = url.substring(url.lastIndexOf('/') + 1);
    if (typeof page_type != 'undefined'){
        if (page_type === 'default' || page_type === 'register'
            || page_type === 'code' || page_type === 'login'
            || page_type === 'forgot'){
            page = page_type;
        }
    }
}

// login and register handler
$('.create_account').on('click', function (e) {
    e.preventDefault();
    page = 'register';
    change_url('','','/auth/register');
    slide_element('default_page', 'register_page');
});

$('.code_step').on('click', function (e) {
    e.preventDefault();
    page = 'code';
    change_url('','','/auth/code');
    slide_element('register_page', 'code_page');
});

$('.back-btn').on('click', function (e) {
    e.preventDefault();
    switch (page) {
        case 'register':
            page = 'default';
            change_url('','','/auth/default');
            back_slide_element('register_page', 'default_page');
        break;
        case 'code':
            page = 'register';
            change_url('','','/auth/register');
            back_slide_element('code_page', 'register_page');
        break;
        case 'login':
            page = 'default';
            change_url('','','/auth/login');
            back_slide_element('login_page', 'default_page');
        break;
        case 'forgot':
            page = 'default';
            change_url('','','/auth/default');
            back_slide_element('forgot_page', 'default_page');
        break;
        default:
            window.location = '/';
    }
});

function slide_element(hide,show) {
    $('.' + hide).hide('slide', { direction: "right" }, function(){
        $('.' + show).show("slide", { direction: "left" });
    });
}

function back_slide_element(hide,show) {
    $('.' + hide).hide('slide', { direction: "left" }, function(){
        $('.' + show).show("slide", { direction: "right" });
    });
}

function change_url(data,title,url) {
    window.history.pushState(data, title, url);
}
