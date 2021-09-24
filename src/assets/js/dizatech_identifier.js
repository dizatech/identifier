// Config and Headers
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(function () {
    $('[data-toggle="tooltip"]').tooltip();

    $('.form-control').on('keyup', function(e){
        if(e.keyCode == 13){
            $(this).closest('.segment').find('.btn').trigger('click');
        }
    });

    $(document).ajaxError(function(event, request){
        if( request.status == 409 ){
            alertify.error('شما قبلا وارد حساب کاربری خود شده‌اید.');
            location.reload();
        }
    });
});

// Plugins
window.Swal = require('sweetalert2');

window.alertify = require('alertifyjs/build/alertify.min');

require('lity/dist/lity.min');

require('jquery-ui/ui/effects/effect-slide');

const previous_pages = [];

// forgot password handler
$('.open_recovery_not_reg').on('click', function (e) {
    e.preventDefault();
    openRecoveryPage('recovery', 'not_registered');
});

$('.open_recovery').on('click', function (e) {
    e.preventDefault();
    openRecoveryPage('recovery', 'default');
});

$('.forget_action').on('click', function (e) {
    e.preventDefault();
    startLoading();
    let username_input = $('.mobile_or_email').val();
    sendEmailOrSMS(username_input).done(function (response) {
        if (response.status === 200){
            setGroupCookies({
                'identifier_username': username_input,
                'identifier_recovery_type': response.type
            }).done(function (data) {
                if (response.status === 200){
                    $('.user_info').html('('+ username_input +')');
                    send_otp($('.recovery_timer'));
                    alertify.success(response.message);
                    openRecoveryCodePage('recovery_code', 'recovery');
                }
                stopLoading();
            }).fail(function (response) {
                stopLoading();
                if( response.status == 0 ){
                    alertify.error('لطفا اتصال اینترنت را بررسی کنید.');
                }
                else{
                    alertify.error('خطای غیره منتظره‌ای رخ داده.');
                }
            });
        }else {
            stopLoading();
            show_error_messages(response);
            alertify.error(response.message);
        }
    }).fail(function (response) {
        stopLoading();
        if( response.status == 0 ){
            alertify.error('لطفا اتصال اینترنت را بررسی کنید.');
        }
        else{
            show_error_messages(response);
        }
    });
});

$('.recovery_timer').on('click', function (e) {
    e.preventDefault();
    startLoading();
    getCookie('identifier_username').done(function (data) {
        sendEmailOrSMS(data.cookie).done(function (response) {
            stopLoading();
            if (response.status === 200){
                send_otp($('.recovery_timer'));
                alertify.success(response.message);
            }else {
                alertify.error(response.message);
            }
        }).fail(function () {
            stopLoading();
            if( response.status == 0 ){
                alertify.error('لطفا اتصال اینترنت را بررسی کنید.');
            }
            else{
                show_error_messages(response);
            }
        });
    }).fail(function (response) {
        stopLoading();
        if( response.status == 0 ){
            alertify.error('لطفا اتصال اینترنت را بررسی کنید.');
        }
        else{
            alertify.error('خطای غیره منتظره‌ای رخ داده.');
        }
    });
});

$('.confirm_recovery_code').on('click', function (e) {
    e.preventDefault();
    startLoading();
    let confirm_code = $('.recovery_code_input').val();
    getGroupCookies(['identifier_username', 'identifier_recovery_type']).done(function (data) {
        confirmRecoveryCode(data.cookies.identifier_username,
            confirm_code, data.cookies.identifier_recovery_type)
            .done(function (code_result) {
            hide_error_messages();
            if (code_result.status === 200){
                setCookie('identifier_verified_recovery', 'user_verified').done(function () {
                    openChangePasswordPage('change_password', 'recovery_code');
                }).fail(function (response) {
                    stopLoading();
                    if( response.status == 0 ){
                        alertify.error('لطفا اتصال اینترنت را بررسی کنید.');
                    }
                    else{
                        alertify.error('خطای غیره منتظره‌ای رخ داده.');
                    }
                });
            }else {
                alertify.error(code_result.message);
                stopLoading();
            }
        }).fail(function (response) {
            stopLoading();
            if( response.status == 0 ){
                alertify.error('لطفا اتصال اینترنت را بررسی کنید.');
            }
            else{
                show_error_messages(response);
            }
        });
    }).fail(function (response) {
        stopLoading();
        if( response.status == 0 ){
            alertify.error('لطفا اتصال اینترنت را بررسی کنید.');
        }
        else{
            alertify.error('خطای غیره منتظره‌ای رخ داده.');
        }
    });
});

$('.change_password_btn').on('click', function (e) {
    e.preventDefault();
    startLoading();
    let new_pass = $('.recovery_new_password').val();
    let confirm_new_pass = $('.recovery_new_password_confirm').val();
    $.ajax({
        type: "post",
        url: baseUrl + '/auth/change/password',
        dataType: 'json',
        data: {
            'new_password': new_pass,
            'password_confirm': confirm_new_pass
        },
        success: function (response) {
            if (response.status === 200){
                window.location = response.url;
            }else {
                alertify.error(response.message);
                stopLoading();
            }
        },
        error: function (response) {
            stopLoading();
            if( response.status == 0 ){
                alertify.error('لطفا اتصال اینترنت را بررسی کنید.');
            }
            else{
                show_error_messages(response);
            }
        }
    });
});

function openRecoveryPage(current_page,previous_page) {
    change_url('','','/auth/recovery');
    slide_element(previous_page, current_page);
    previous_pages.push(previous_page);
}

$('.login_via_password').on('click', function (e) {
    e.preventDefault();
    openPasswordPage('password', 'code');
});

$('.login_via_code').on('click', function (e) {
    e.preventDefault();
    openCodePage('password');
});

$('.login_email_via_password').on('click', function (e) {
    e.preventDefault();
    openPasswordPage('password', 'email_code');
});

$('.login_with_password').on('click', function (e) {
    e.preventDefault();
    startLoading();
    let password_input = $('.password_input').val();
    $.ajax({
        type: "post",
        url: baseUrl + '/auth/login/password',
        dataType: 'json',
        data: {
            'password': password_input,
        },
        success: function (response) {
            if (response.status === 200){
                window.location = response.url;
            }else {
                alertify.error(response.message);
                stopLoading();
            }
        },
        error: function (response) {
            stopLoading();
            if( response.status == 0 ){
                alertify.error('لطفا اتصال اینترنت را بررسی کنید.');
            }
            else{
                show_error_messages(response);
            }
        }
    });
});

function openPasswordPage(current_page,previous_page) {
    change_url('','','/auth/password');
    slide_element(previous_page, current_page);
    previous_pages.push(previous_page);
}

function openCodePage(previous_page) {
    startLoading();
    getGroupCookies(['identifier_username', 'identifier_recovery_type']).done(function (data) {
        if (data.cookies.identifier_recovery_type === 'mobile'){
            send_code_handler(data.cookies.identifier_username, 'code', previous_page);
        }else {
            send_email_handler(data.cookies.identifier_username, 'email_code', previous_page);
        }
    }).fail(function (response) {
        stopLoading();
        if( response.status == 0 ){
            alertify.error('لطفا اتصال اینترنت را بررسی کنید.');
        }
        else{
            alertify.error('خطای غیره منتظره‌ای رخ داده.');
        }
    });
}


function openRecoveryCodePage(current_page,previous_page) {
    change_url('','','/auth/recovery_code');
    slide_element(previous_page, current_page);
    previous_pages.push(previous_page);
}

function openChangePasswordPage(current_page,previous_page) {
    change_url('','','/auth/change_password');
    slide_element(previous_page, current_page);
    previous_pages.push(previous_page);
    stopLoading();
}

// login and register handler
$('.create_account').on('click', function (e) {
    e.preventDefault();
    change_url('','','/auth/register');
    slide_element('default', 'register');
    previous_pages.push('default');
});

$('.account_login').on('click', function (e) {
    e.preventDefault();
    startLoading();
    var username = $('.username_input').val();
    alreadyRegisteredUsername(username).done(function (data) {
        hide_error_messages();
        if (data.status === 200){
            setCookie('identifier_username', username).done(function () {
                if (data.registeration_status === 'not_registered'){
                    if (data.type === 'mobile'){
                        $('.mobile_num').html(username);
                        $('.not_registered_mobile').val(username);
                        change_url('','','/auth/not_registered');
                        slide_element('default', 'not_registered');
                        previous_pages.push('default');
                    }else {
                        alertify.error('این ایمیل درسیستم ثبت نشده.');
                    }
                    stopLoading();
                }else {
                    setGroupCookies({
                        'identifier_username': username,
                        'identifier_recovery_type': data.type
                    }).done(function () {
                        openPasswordPage('password', 'default');
                        stopLoading();
                    }).fail(function (response) {
                        stopLoading();
                        if( response.status == 0 ){
                            alertify.error('لطفا اتصال اینترنت را بررسی کنید.');
                        }
                        else{
                            alertify.error('خطای غیره منتظره‌ای رخ داده.');
                        }
                    });
                }
            }).fail(function (response) {
                stopLoading();
                if( response.status == 0 ){
                    alertify.error('لطفا اتصال اینترنت را بررسی کنید.');
                }
                else{
                    alertify.error('خطای غیره منتظره‌ای رخ داده.');
                }
            });
        }else {
            stopLoading();
            alertify.error(data.message);
        }
    }).fail(function (response) {
        stopLoading();
            if( response.status == 0 ){
                alertify.error('لطفا اتصال اینترنت را بررسی کنید.');
            }
            else{
                show_error_messages(response);
            }
    });
});

$('.confirm_email_code').on('click', function (e) {
    e.preventDefault();
    startLoading();
    let confirm_code = $('.email_code_input').val();
    getCookie('identifier_username').done(function (data) {
        confirmEmailCode(data.cookie, confirm_code)
            .done(function (code_result) {
                hide_error_messages();
                if (code_result.status === 200){
                    window.location = code_result.url;
                }else {
                    alertify.error(code_result.message);
                    stopLoading();
                }
            }).fail(function (response) {
                stopLoading();
                if( response.status == 0 ){
                    alertify.error('لطفا اتصال اینترنت را بررسی کنید.');
                }
                else{
                    show_error_messages(response);
                }
            });
    }).fail(function (response) {
        stopLoading();
        if( response.status == 0 ){
            alertify.error('لطفا اتصال اینترنت را بررسی کنید.');
        }
        else{
            alertify.error('خطای غیره منتظره‌ای رخ داده.');
        }
    });
});

$('.code_step').on('click', function (e) {
    e.preventDefault();
    startLoading();
    let mobile_num = $('.register_mobile').val();
    let accepted_tos = $('.accepted_tos').prop('checked') ? true : null;
    send_reg_code_handler(mobile_num, accepted_tos, 'code', 'register');
});

function send_email_handler(username, current_page, previous_page) {
    sendEmailOrSMS(username).done(function (response) {
        if (response.status === 200){
            $('.user_info').html('('+ username +')');
            send_otp($('.recovery_timer'));
            alertify.success(response.message);
            change_url('','','/auth/' + current_page);
            slide_element(previous_page, current_page);
            previous_pages.push(previous_page);
            stopLoading();
        }else {
            stopLoading();
            alertify.error(response.message);
        }
    }).fail(function (response) {
        stopLoading();
        let msg = '';
        if( response.status == 0 ){
            alertify.error('لطفا اتصال اینترنت را بررسی کنید.');
        }
        else if (response.status === 500){
            msg = 'خطایی در ارسال ایمیل رخ داده. لطفا چند دقیقه دیگر دوباره امتحان کنید یا به ما اطلاع دهید.';
            alertify.error(msg);
        }
        else {
            show_error_messages(response);
            msg = 'خطای غیره منتظره‌ای رخ داده.';
            alertify.error(msg);
        }
    });
}

function send_reg_code_handler(mobile_num, accepted_tos, current_page, previous_page){
    sendRegCode(mobile_num, accepted_tos).done(function (code_result) {
        setCookie('identifier_username', mobile_num).done(function () {
            if (code_result.status === 200){
                send_otp($('.otp_timer'));
                alertify.success(code_result.message);
                $('.mobile_num').html('(' + mobile_num + ')');
                stopLoading();
                change_url('','','/auth/code');
                slide_element(previous_page, current_page);
                previous_pages.push(previous_page);
            }else {
                stopLoading();
                alertify.error(code_result.message);
            }
        }).fail(function (response) {
            stopLoading();
            if( response.status == 0 ){
                alertify.error('لطفا اتصال اینترنت را بررسی کنید.');
            }
            else{
                alertify.error('خطای غیره منتظره‌ای رخ داده.');
            }
        });
    }).fail(function (response) {
        stopLoading();
        let msg = '';
        if( response.status == 0 ){
            alertify.error('لطفا اتصال اینترنت را بررسی کنید.');
        }
        else if(response.status === 500){
            msg = 'خطایی در ارسال پیامک رخ داده. لطفا چند دقیقه دیگر دوباره امتحان کنید یا به ما اطلاع دهید.';
            alertify.error(msg);
        }else {
            show_error_messages(response);
        }
    });
}

function send_code_handler(mobile_num, current_page, previous_page) {
    sendCode(mobile_num).done(function (code_result) {
        setCookie('identifier_username', mobile_num).done(function () {
            if (code_result.status === 200){
                send_otp($('.otp_timer'));
                alertify.success(code_result.message);
                $('.mobile_num').html('(' + mobile_num + ')');
                stopLoading();
                change_url('','','/auth/code');
                slide_element(previous_page, current_page);
                previous_pages.push(previous_page);
            }else {
                stopLoading();
                alertify.error(code_result.message);
            }
        }).fail(function (response) {
            stopLoading();
            if( response.status == 0 ){
                alertify.error('لطفا اتصال اینترنت را بررسی کنید.');
            }
            else{
                alertify.error('خطای غیره منتظره‌ای رخ داده.');
            }
        });
    }).fail(function (response) {
        stopLoading();
        let msg = '';
        if( response.status == 0 ){
            alertify.error('لطفا اتصال اینترنت را بررسی کنید.');
        }
        else if(response.status === 500){
            msg = 'خطایی در ارسال پیامک رخ داده. لطفا چند دقیقه دیگر دوباره امتحان کنید یا به ما اطلاع دهید.';
            alertify.error(msg);
        }else {
            show_error_messages(response);
        }
    });
}

$('.confirm_sms_code').on('click', function (e) {
    e.preventDefault();
    startLoading();
    let confirm_code = $('.user_input_code').val();
    let mobile = '';
    getCookie('identifier_username').done(function (data) {
        mobile = data.cookie;
        confirmCode(mobile, confirm_code).done(function (code_result) {
            hide_error_messages();
            if (code_result.status === 200){
                window.location = code_result.url;
            }else {
                alertify.error(code_result.message);
                stopLoading();
            }
        }).fail(function (response) {
            stopLoading();
            if( response.status == 0 ){
                alertify.error('لطفا اتصال اینترنت را بررسی کنید.');
            }
            else{
                show_error_messages(response);
            }
        });
    }).fail(function (response) {
        stopLoading();
        if( response.status == 0 ){
            alertify.error('لطفا اتصال اینترنت را بررسی کنید.');
        }
        else{
            alertify.error('خطای غیره منتظره‌ای رخ داده.');
        }
    });
});

$('.otp_timer').on('click', function (e) {
    e.preventDefault();
    startLoading();
    getCookie('identifier_username').done(function (data) {
        sendCode(data.cookie).done(function (code_result) {
            hide_error_messages();
            if (code_result.status === 200){
                send_otp($('.otp_timer'));
                alertify.success(code_result.message);
            }else {
                alertify.error(code_result.message);
            }
            stopLoading();
        }).fail(function () {
            stopLoading();
            let msg = '';
            if( response.status == 0 ){
                alertify.error('لطفا اتصال اینترنت را بررسی کنید.');
            }
            else if (response.status === 500){
                msg = 'خطایی در ارسال پیامک رخ داده. لطفا چند دقیقه دیگر دوباره امتحان کنید یا به ما اطلاع دهید.';
                alertify.error(msg);
            }
            else {
                show_error_messages(response);
            }
        });
    }).fail(function (response) {
        stopLoading();
        if( response.status == 0 ){
            alertify.error('لطفا اتصال اینترنت را بررسی کنید.');
        }
        else{
            alertify.error('خطای غیره منتظره‌ای رخ داده.');
        }
    });
});

$('.create_new_account').on('click', function (e) {
    e.preventDefault();
    url = window.location.href.replace(/\/$/, '');
    page_type = url.substring(url.lastIndexOf('/') + 1);
    change_url('','','/auth/register');
    slide_element(page_type, 'register');
    previous_pages.push('default');
});

let perv_page = '';
var url = '';
$('.back-btn').on('click', function (e) {
    e.preventDefault();
    url = window.location.href.replace(/\/$/, '');
    page_type = url.substring(url.lastIndexOf('/') + 1);
    if (page_type === 'default'){
        window.location = '/';
    }else {
        perv_page = previous_pages.pop();
        change_url('','','/auth/' + perv_page);
        back_slide_element(page_type, perv_page);
        hide_error_messages();
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

function show_error_messages(response){
    if( response.status == 409 ){
        return;
    }

    alertify.error('لطفا خطاهای فرم را بررسی کنید.');
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

function sendRegCode(mobile_field, accepted_tos) {
    return $.ajax({
        type: "post",
        url: baseUrl + '/auth/send/reg_code',
        dataType: 'json',
        data: {
            'mobile': mobile_field,
            'accepted_tos': accepted_tos
        }
    });
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

function checkUser(mobile_field) {
    return $.ajax({
        type: "post",
        url: baseUrl + '/auth/check/mobile',
        dataType: 'json',
        data: {
            'mobile': mobile_field
        }
    });
}

function alreadyRegisteredUsername(user_field) {
    return $.ajax({
        type: "post",
        url: baseUrl + '/auth/check/registered/user',
        dataType: 'json',
        data: {
            'username_input': user_field
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
    return $.ajax({
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

function confirmRecoveryCode(username,code,type) {
    return $.ajax({
        type: "post",
        url: baseUrl + '/auth/confirm/recovery',
        dataType: 'json',
        data: {
            'username': username,
            'code': code,
            'type': type
        }
    });
}

function confirmEmailCode(username,code) {
    return $.ajax({
        type: "post",
        url: baseUrl + '/auth/confirm/email/code',
        dataType: 'json',
        data: {
            'username': username,
            'code': code
        }
    });
}

function sendEmailOrSMS(username_input) {
    return $.ajax({
        type: "post",
        url: baseUrl + '/auth/check/username',
        dataType: 'json',
        data: {
            'username': username_input
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
    var min=2;
    var sec=0;
    target.find('.otp_timer_text').html('<span class="minutes">2</span>' + ':' + '<span class="seconds">0</span>');
    clearInterval(interval);
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
