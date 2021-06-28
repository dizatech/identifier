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

$(function () {
    var url = window.location.href.replace(/\/$/, '');
    page_type = url.substring(url.lastIndexOf('/') + 1);
    if (typeof page_type != 'undefined'){
        if(page_type === 'code'){
            send_otp($('.otp_timer'));
        }
    }
});

// login and register handler
$('.create_account').on('click', function (e) {
    e.preventDefault();
    startLoading();
    setGroupCookies({'notifier_current_page': 'register',
        'notifier_previous_page': 'default'}).done(function (response) {
        stopLoading();
            if (response.status === 200){
                change_url('','','/auth/register');
                slide_element('default', 'register');
            }else {
                alertify.error('خطای غیره منتظره‌ای رخ داده.');
            }
    }).fail(function () {
        stopLoading();
        alertify.error('خطای غیره منتظره‌ای رخ داده.');
    });

});

$('.account_login').on('click', function (e) {
    e.preventDefault();
    setCookie('notifier_username', $('.username_input').val());
    checkUser();
});

$('.code_step').on('click', function (e) {
    e.preventDefault();
    startLoading();
    let mobile_num = $('.register_mobile').val();
    sendCode(mobile_num).done(function (code_result) {
        if (code_result.status === 200){
            send_otp($('.otp_timer'));
            alertify.success(code_result.message);
            $('.mobile_num').html('(' + mobile_num + ')');
            setGroupCookies({
                'notifier_username': mobile_num,
                'notifier_current_page': 'code',
                'notifier_previous_page': 'register'
            }).done(function (response) {
                stopLoading();
                if (response.status === 200){
                    change_url('','','/auth/code');
                    slide_element('register', 'code');
                }else {
                    alertify.error('خطای غیره منتظره‌ای رخ داده.');
                }
            }).fail(function () {
                stopLoading();
                alertify.error('خطای غیره منتظره‌ای رخ داده.');
            });
        }else {
            alertify.error(code_result.message);
        }
    }).fail(function (response) {
        stopLoading();
        show_error_messages(response);
        alertify.error('لطفا خطاهای فرم را بررسی کنید.');
    });
});

$('.confirm_sms_code').on('click', function (e) {
    e.preventDefault();
    startLoading();
    let confirm_code = $('.user_input_code').val();
    getCookie('notifier_username').done(function (data) {
        confirmCode(data.cookie, confirm_code).done(function (code_result) {
            hide_error_messages();
            if (code_result.status === 200){
                window.location = code_result.url;
            }else {
                alertify.error(code_result.message);
                stopLoading();
            }
        }).fail(function (response) {
            stopLoading();
            show_error_messages(response);
            alertify.error('لطفا خطاهای فرم را بررسی کنید.');
        });
    }).fail(function () {
        stopLoading();
        alertify.error('خطای غیره منتظره‌ای رخ داده.');
    });
});

$('.otp_timer').on('click', function (e) {
    e.preventDefault();
    startLoading();
    getCookie('notifier_username').done(function (data) {
        stopLoading();
        sendCode(data.cookie).done(function (code_result) {
            hide_error_messages();
            if (code_result.status === 200){
                alertify.success(code_result.message);
            }else {
                alertify.error(code_result.message);
            }
        }).fail(function () {
            stopLoading();
            show_error_messages(response);
            alertify.error('لطفا خطاهای فرم را بررسی کنید.');
        });
    }).fail(function () {
        stopLoading();
        alertify.error('خطای غیره منتظره‌ای رخ داده.');
    });
});

$('.create_new_account').on('click', function (e) {
    e.preventDefault();
    let mobile_num = $('.not_registered_mobile').val();
    sendCode(mobile_num, 'not_registered');
});

$('.back-btn').on('click', function (e) {
    e.preventDefault();
    startLoading();
    getGroupCookies(['notifier_current_page',
        'notifier_previous_page']).done(function (cookies_result) {
        if (cookies_result.notifier_current_page == 'default') {
            window.location = '/';
        }else {
            change_url('', '', cookies_result.notifier_previous_page);
            setCookie('notifier_previous_page',
                cookies_result.notifier_current_page).done(function () {
                back_slide_element(cookies_result.notifier_current_page, cookies_result.notifier_previous_page);
                stopLoading();
            }).fail(function () {
                stopLoading();
                alertify.error('خطای غیره منتظره‌ای رخ داده.');
            });
        }
    }).fail(function () {
        stopLoading();
        alertify.error('خطای غیره منتظره‌ای رخ داده.');
    });
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
    return $.ajax({
        type: "post",
        url: baseUrl + '/auth/send/code',
        dataType: 'json',
        data: {
            'mobile': mobile_field
        }
    });
}

function checkUser() {
    var mobile_field = getCookie('notifier_username');
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
                $('.mobile_num').html(mobile_field);
                $('.not_registered_mobile').val(mobile_field);
                page = 'not_registered';
                change_url('','','/auth/not_registered');
                slide_element('default', 'not_registered');
            }else {
                $('.mobile_num').html('(' + mobile_field + ')');
                sendCode(mobile_field, 'login');
            }
        },
        error: function (response) {
            show_error_messages(response);
            alertify.error('لطفا خطاهای فرم را بررسی کنید.');
        }
    });
}

function setCookie(cookieName,cookieValue) {
    return $.ajax({
        type: "post",
        url: baseUrl + '/auth/set/cookie',
        dataType: 'json',
        data: {
            'cookie_name': cookieName,
            'cookie_value': cookieValue
        }
    });
}

function setGroupCookies(cookies_array) {
    return  $.ajax({
        type: "post",
        url: baseUrl + '/auth/set/group/cookies',
        dataType: 'json',
        data: {
            're_cookies': cookies_array
        }
    });
}

function getCookie(cookieName) {
    return $.ajax({
        type: "post",
        url: baseUrl + '/auth/get/cookie',
        dataType: 'json',
        data: {
            'cookie_name': cookieName
        }
    });
}

function getGroupCookies(cookieNames) {
    return $.ajax({
        type: "post",
        url: baseUrl + '/auth/get/group/cookies',
        dataType: 'json',
        data: {
            'cookie_names': cookieNames
        }
    });
}

function forgetCookie(cookieName) {
    $.ajax({
        type: "post",
        url: baseUrl + '/auth/forget/cookie',
        dataType: 'json',
        data: {
            'cookie_name': cookieName
        },
        success: function () {
            stopLoading();
        }
    });
}

function confirmCode(mobile,code) {
    return $.ajax({
        type: "post",
        url: baseUrl + '/auth/confirm/code',
        dataType: 'json',
        data: {
            'mobile': mobile,
            'code': code
        }
    });
}

function startLoading() {
    $('.loading_overlay').removeClass('d-none');
    $('.loading_overlay').addClass('d-flex');
}

function stopLoading() {
    $('.loading_overlay').removeClass('d-flex');
    $('.loading_overlay').addClass('d-none');
}
// end helper functions

//start timer
function send_otp(target) {
    target.prop("disabled",true);
    var min=0;
    var sec=4;
    target.find('.otp_timer_text').html('<span class="minutes">2</span>' + ':' + '<span class="seconds">0</span>');
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
