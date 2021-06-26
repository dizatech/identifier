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
            || page_type === 'forgot' || page_type === 'login_code'
            || page_type === 'not_registered'){
            page = page_type;
        }
        if(page_type.length === 11){
            send_otp($('.otp_timer'));
            page = 'code';
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

$('.account_login').on('click', function (e) {
    e.preventDefault();
    checkUser($('.username_input').val());
});

$('.code_step').on('click', function (e) {
    e.preventDefault();
    let mobile_num = $('.register_mobile').val();
    $('.mobile_num').html('(' + mobile_num + ')');
    sendCode(mobile_num);
});

$('.confirm_sms_code').on('click', function (e) {
    let register_mobile = '';
    let register_code = '';
    if (page === 'login'){
        register_mobile = $('.username_input').val();
        register_code = $('.login_input_code').val();
    }else if(page === 'code') {
        register_mobile = $('.register_mobile').val();
        register_code = $('.user_input_code').val();
    }
    e.preventDefault();
    if (register_mobile == ''){
        register_mobile = url.substring(url.lastIndexOf('/') + 1);
    }
    confirmCode(register_mobile,register_code);
});

$('.otp_timer').on('click', function (e) {
    e.preventDefault();
    let mobile_num = $('.username_input').val();
    $('.mobile_num').html('(' + mobile_num + ')');
    sendCode(mobile_num);
});

function after_send_code(mobile, set_page = null) {
    if (set_page != null){
        page = set_page;
    }
    switch (page) {
        case 'register':
            page = 'code';
            change_url('','','/auth/code/' + mobile);
            slide_element('register_page', 'code_page');
            break;
        case 'login':
            page = 'login';
            change_url('','','/auth/code/' + mobile);
            slide_element('default_page', 'login_page');
            break;
    }
}

function after_confirm_code(url) {
    window.location = url;
}

$('.back-btn').on('click', function (e) {
    e.preventDefault();
    switch (page) {
        case 'register':
            page = 'default';
            change_url('','','/auth/default');
            back_slide_element('register_page', 'default_page');
        break;
        case 'not_registered':
            page = 'default';
            change_url('','','/auth/default');
            back_slide_element('not_registered_page', 'default_page');
        break;
        case 'code':
            page = 'register';
            change_url('','','/auth/register');
            back_slide_element('code_page', 'register_page');
        break;
        case 'login':
            page = 'default';
            change_url('','','/auth/default');
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

function sendCode(mobile_field, page = null) {
    if (mobile_field == ''){
        mobile_field = null;
    }
    Swal.fire({
        title: 'در حال اجرای درخواست',
        icon: 'info',
        allowEscapeKey: false,
        allowOutsideClick: false,
    });
    Swal.showLoading();
    $.ajax({
        type: "post",
        url: baseUrl + '/auth/send/code/' + mobile_field,
        dataType: 'json',
        data: {
            'mobile': mobile_field
        },
        success: function (response) {
            hide_error_messages();
            Swal.close();
            if (response.status == 200){
                send_otp($('.otp_timer'));
                alertify.success(response.message);
                after_send_code(mobile_field, page);
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
        url: baseUrl + '/auth/confirm/code/' + mobile_field,
        dataType: 'json',
        data: {
            'mobile': mobile_field,
            'code': code_field
        },
        success: function (response) {
            hide_error_messages();
            if (response.status == 200){
                after_confirm_code(response.url);
            }else {
                Swal.close();
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

function checkUser(mobile_field) {
    Swal.fire({
        title: 'در حال اجرای درخواست',
        icon: 'info',
        allowEscapeKey: false,
        allowOutsideClick: false,
    });
    Swal.showLoading();
    $.ajax({
        type: "post",
        url: baseUrl + '/auth/check/mobile',
        dataType: 'json',
        data: {
            'mobile': mobile_field
        },
        success: function (response) {
            hide_error_messages();
            Swal.close();
            if (response.type === 'not_registered'){
                page = 'not_registered';
                change_url('','','/auth/not_registered');
                slide_element('default_page', 'not_registered_page');
            }else {
                let mobile_num = $('.username_input').val();
                $('.mobile_num').html('(' + mobile_num + ')');
                sendCode(mobile_num, 'login');
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

//start timer
function send_otp(target) {
    target.prop("disabled",true);
    var min=0;
    var sec=4;
    start_count_down(min,sec,target);
}

var interval;
function start_count_down(minutes,seconds,target) {
    let sec = seconds;
    let min = minutes;
    var w1 = 0;
    interval = setInterval(() => {
        if (sec > 0) {
            sec -= 1;
        } else if (min >= 1) {
            min -= 1;
            sec = 59;
        } else {
            clearInterval(interval);
            // action
            target.find('.otp_timer_text').html('ارسال مجدد');
            target.prop("disabled",false);
        }
        target.find('.minutes').html(min);
        target.find('.seconds').html(sec);
    }, 1000);
}
//end timer
