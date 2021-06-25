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

// start init pages

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

// end init pages

// login and register handler
$('.create_account').on('click', function (e) {
    e.preventDefault();
    page = 'register';
    change_url('','','/auth/register');
    slide_element('default_page', 'register_page');
});

$('.code_step').on('click', function (e) {
    e.preventDefault();
    sendCode($('.register_mobile').val());
});

$('.confirm_sms_code').on('click', function (e) {
    e.preventDefault();
    if ($('.register_mobile').val() == null){
        page = 'register';
        change_url('','','/auth/register');
        back_slide_element('code_page', 'register_page');
        alertify.error('لطفا شماره موبایل خود را دوباره وارد کنید.');
    }else {
        confirmCode($('.register_mobile').val(),$('.user_input_code').val());
    }
});

function after_send_code() {
    switch (page) {
        case 'register':
            page = 'code';
            change_url('','','/auth/code');
            slide_element('register_page', 'code_page');
            break;
    }
}

function after_confirm_code() {

}

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

// start helper functions

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

function hide_error_messages(){
    $('.form-group')
        .find('.invalid-feedback')
        .addClass('d-none')
        .find('strong').text('');
    $('.form-group')
        .find('.is-invalid')
        .removeClass('is-invalid');
}

function show_error_messages(res){
    let response = res;
    $('.form-group')
        .find('.invalid-feedback')
        .addClass('d-none')
        .find('strong').text('');
    $('.form-group').find('.is-invalid')
        .removeClass('is-invalid');
    if (response.status === 422) {
        for( const field_name in response.responseJSON.errors ){
            if(response.responseJSON.errors[field_name]) {
                let target = $('[name=' + field_name + ']');
                target.addClass('is-invalid');
                target.closest('.form-group')

                    .find('.invalid-feedback')
                    .removeClass('d-none')
                    .find('strong').text(response.responseJSON.errors[field_name]);
                [].forEach.call(document.querySelectorAll("input[type='file']"),
                    function(input) {
                        input = $(input);
                        if(input.data('name') === field_name){
                            input.addClass('is-invalid');
                            input.closest('.custom-file')
                                .find('.invalid-feedback')
                                .removeClass('d-none')
                                .find('strong').text(response.responseJSON.errors[field_name]);
                        }
                    });
            }
        }
    }
}

function sendCode(mobile_field) {
    Swal.fire({
        title: 'در حال اجرای درخواست',
        icon: 'info',
        allowEscapeKey: false,
        allowOutsideClick: false,
    });
    Swal.showLoading();
    $.ajax({
        type: "post",
        url: baseUrl + '/auth/send/code',
        dataType: 'json',
        data: {
            'mobile': mobile_field
        },
        success: function (response) {
            hide_error_messages();
            Swal.close();
            if (response.status == 200){
                alertify.success(response.message);
                after_send_code();
            }else {
                alertify.error(response.message);
            }
        },
        error: function (response) {
            show_error_messages(response);
            Swal.close();
            alertify.error('لطفا خطاهای فرم را بررسی کنید.');
        }
    });
}

function confirmCode(mobile_field,code_field) {
    Swal.fire({
        title: 'در حال اجرای درخواست',
        icon: 'info',
        allowEscapeKey: false,
        allowOutsideClick: false,
    });
    Swal.showLoading();
    $.ajax({
        type: "post",
        url: baseUrl + '/auth/confirm/code',
        dataType: 'json',
        data: {
            'mobile': mobile_field,
            'code': code_field
        },
        success: function (response) {
            hide_error_messages();
            Swal.close();
            if (response.status == 200){
                alertify.success(response.message);
                after_confirm_code();
            }else {
                alertify.error(response.message);
            }
        },
        error: function (response) {
            show_error_messages(response);
            Swal.close();
            alertify.error('لطفا خطاهای فرم را بررسی کنید.');
        }
    });
}

// end helper functions
