<div>
    <div class="card-body p-0">
        <div class="login-form">
            <div class="auth-wrapper">
                @switch($page)
                    @case('default')
                        <div class="segment">
                            <div class="row">
                                <div class="col-md-8">
                                    <p class="create-account-text">اگر در {{ config('dizatech_identifier.site_title') }} حساب کاربری ندارید، ثبت نام کنید:</p>
                                </div>
                                <div class="col-md-4">
                                    <a class="btn btn-warning float-left create_account" href="#">
                                        <span class="pl-2 pr-2">
                                            ایجاد حساب کاربری
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="segment">
                            <div class="row">
                                <div class="col-md-12">
                                    <p>اگر در {{ config('dizatech_identifier.site_title') }} حساب کاربری دارید، وارد شوید:</p>
                                    <div class="form-group">
                                        <div class="group d-flex">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text pl-3 pr-3" id="basic-addon1">
                                                    <i class="fa fa-user icon-size" aria-hidden="true"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control username-field username_input" value=""
                                                   name="mobile" id="username" placeholder="ایمیل یا موبایل یا نام کاربری">
                                            <a class="btn btn-warning float-left mr-2 account_login" href="#">
                                                <span class="pl-4 pr-4">
                                                    ورود
                                                </span>
                                            </a>
                                        </div>
                                        <span class="invalid-feedback mt-4 d-none">
                                            <strong></strong>
                                        </span>
                                    </div>
                                    <a href="#" class="open_recovery forgot-password">فراموش کردید ؟! (بازیابی اطلاعات کاربری)</a>
                                </div>
                            </div>
                        </div>
                    @break
                    @case('register')
                        <div class="segment">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="head-title">ایجاد حساب کاربری</h4>
                                    <span class="mobile-reg-text">موبایل را وارد کنید</span>
                                    <div class="form-group">
                                        <div class="group d-flex">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text pl-3 pr-3" id="basic-addon1">
                                                    <i class="fa fa-mobile icon-size" aria-hidden="true"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control username-field register_mobile" value=""
                                                   name="mobile" placeholder="موبایل">
                                            <a class="btn btn-warning float-left mr-2 code_step" href="#">
                                                <span class="pl-4 pr-4">
                                                    ادامه
                                                </span>
                                            </a>
                                        </div>
                                        <span class="invalid-feedback mt-4 d-none">
                                            <strong></strong>
                                        </span>
                                    </div>
                                    <p>
                                        قوانین <a href="{{ config('dizatech_identifier.terms_url') }}" class="forgot-password">{{ config('dizatech_identifier.site_title') }}</a> را مطالعه کرده‌ام و قبول دارم.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @break
                    @case('login')
                    @case('code')
                        <div class="segment">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="head-title">{{ $page == 'code' ? ' ثبت‌نام در' : 'ورود به ' }} {{ config('dizatech_identifier.site_title') }}</h4>
                                    <div class="mb-4">
                                        <span class="mobile-code-text">
                                            <i class="sms-icon"></i>
                                            کد فرستاده شده برای  <span class="mobile_num">({{ request()->cookie('notifier_username') }})</span>  را وارد کنید
                                        </span>
                                    </div>
                                    <div class="form-group">
                                        <div class="group d-flex">
                                            <input type="text" class="form-control username-field @if($page == 'code') user_input_code @elseif($page == 'login') login_input_code @elseif($page == 'not_registered') not_registered_input_code @endif" value=""
                                                   name="code" placeholder="کد تایید">
                                            <button class='btn btn-info float-right text-center mr-2 otp_timer' disabled>
                                                <span class='d-flex justfy-content-between align-items-center flex-row-reverse otp_timer_text'>
                                                    <span class="minutes">2</span>
                                                    :
                                                    <span class="seconds">0</span>
                                                </span>
                                            </button>
                                            <a class="btn btn-warning confirm_sms_code float-left mr-2" href="#">
                                                <span>
                                                    تائید
                                                </span>
                                            </a>
                                        </div>
                                        <span class="invalid-feedback mt-4 d-none">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @break
                    @case('not_registered')
                        <div class="segment segment-colored">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="head-title text-center mb-0">
                                        <span class="mobile_number">
                                            شماره موبایل <span class="mobile_num">{{ request()->cookie('notifier_username') }}</span> در {{ config('dizatech_identifier.site_title') }} ثبت نشده
                                        </span>
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="not_registered_mobile" class="not_registered_mobile" value="">
                        <div class="segment">
                            <a href="#" class="not_registered_button create_new_account">
                                ساخت حساب کاربری جدید با <span class="mobile_num">{{ request()->cookie('notifier_username') }}</span>
                            </a>
                        </div>
                        <div class="segment">
                            <a href="#" class="not_registered_button open_recovery_not_reg">
                                حساب کاربری دارم ولی اطلاعات آن را فراموش کردم
                            </a>
                        </div>
                    @break
                    @case('recovery')
                    <div class="segment">
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="head-title">فراموشی رمزعبور</h4>
                                <span class="mobile-reg-text">موبایل یا ایمیل خود را وارد کنید</span>
                                <div class="form-group">
                                    <div class="group d-flex">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text pl-3 pr-3" id="basic-addon1">
                                                <i class="fa fa-mobile icon-size" aria-hidden="true"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control username-field mobile_or_email" value=""
                                               name="username" placeholder="موبایل یا ایمیل">
                                        <a class="btn btn-warning float-left mr-2 forget_action" href="#">
                                            <span class="pl-4 pr-4">
                                                ادامه
                                            </span>
                                        </a>
                                    </div>
                                    <span class="invalid-feedback mt-4 d-none">
                                        <strong></strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @break
                @endswitch
            </div>
        </div>
    </div>
</div>
