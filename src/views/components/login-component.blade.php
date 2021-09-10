<div>
    <div class="card-body p-0">
        <div class="login-form">
            <div class="auth-wrapper">
                @switch($page)
                    @case('default')
                        <div class="segment">
                            <div class="segment-content">
                                <div class="create-account-text">اگر در {{ config('dizatech_identifier.site_title') }} حساب کاربری ندارید، ثبت نام کنید:</div>
                                <a type="button" class="btn btn-warning animate-btn create_account" href="#">
                                    <span class="text">ایجاد حساب کاربری</span>
                                    <span class="fa fa-user-plus fa-fw fa-display"></span>
                                    <div class="overlay"></div>
                                </a>
                            </div>
                        </div>
                        <div class="segment">
                            <div class="row">
                                <div class="col-md-12">
                                    <div>اگر در {{ config('dizatech_identifier.site_title') }} حساب کاربری دارید، وارد شوید:</div>
                                    <div class="form-group">
                                        <div class="group segment-content">
                                            <div class="group-input-holder">
                                                <div class="input-group-prepend">
                                                <span class="input-group-text pl-3 pr-3" id="basic-addon1">
                                                    <i class="fa fa-user icon-size" aria-hidden="true"></i>
                                                </span>
                                                </div>
                                                <input type="text" class="form-control username-field username_input" value=""
                                                       name="username_input" id="username" placeholder="موبایل یا ایمیل">
                                            </div>
                                            <a type="button" class="btn btn-warning animate-btn account_login" href="#">
                                                <span class="text">ورود</span>
                                                <span class="fa fa-sign-in fa-fw fa-display"></span>
                                                <div class="overlay"></div>
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
                                        <div class="group segment-content">
                                            <div class="group-input-holder">
                                                <div class="input-group-prepend">
                                                <span class="input-group-text pl-3 pr-3" id="basic-addon1">
                                                    <i class="fa fa-mobile icon-size" aria-hidden="true"></i>
                                                </span>
                                                </div>
                                                <input type="text" class="form-control username-field register_mobile" value=""
                                                       name="mobile" placeholder="موبایل">
                                            </div>
                                            <a type="button" class="btn btn-warning animate-btn code_step" href="#">
                                                <span class="text">ورود</span>
                                                <span class="fa fa-sign-in fa-fw fa-display"></span>
                                                <div class="overlay"></div>
                                            </a>
                                        </div>
                                        <span class="invalid-feedback mt-4 d-none">
                                            <strong></strong>
                                        </span>
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="accepted_tos"
                                                id="accepted_tos" value="checkedValue" class="custom-control-input accepted_tos">
                                            <label class="custom-control-label" for="accepted_tos">
                                                قوانین
                                                <a
                                                    href="{{ config('dizatech_identifier.terms_url') }}"
                                                    class="forgot-password">
                                                    {{ config('dizatech_identifier.site_title') }}
                                                </a>
                                                را مطالعه کرده‌ام و قبول دارم.
                                            </label>
                                        </div>
                                        <span class="invalid-feedback d-none">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @break
                    @case('login')
                    @case('code')
                        <div class="segment">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="head-title">{{ request()->cookie('identifier_previous_page') == 'default' ? ' ورود به' : 'ثبت‌نام در ' }} {{ config('dizatech_identifier.site_title') }}</h4>
                                    <div class="">
                                        <span class="mobile-code-text">
                                            <i class="sms-icon"></i>
                                            کد فرستاده شده برای  <span class="mobile_num">({{ request()->cookie('identifier_username') }})</span>  را وارد کنید
                                        </span>
                                    </div>
                                    <div class="form-group">
                                        <div class="group segment-content">
                                            <div class="group-input-holder">
                                                <input type="text" class="form-control username-field @if($page == 'code') user_input_code @elseif($page == 'login') login_input_code @elseif($page == 'not_registered') not_registered_input_code @endif" value=""
                                                       name="code" placeholder="کد تایید">
                                            </div>
                                            <a type="button" class="btn btn-warning animate-btn confirm_sms_code" href="#">
                                                <span class="text">تایید</span>
                                                <span class="fa fa-check fa-fw fa-display"></span>
                                                <div class="overlay"></div>
                                            </a>
                                        </div>
                                        <span class="invalid-feedback mt-4 d-none">
                                            <strong></strong>
                                        </span>
                                        <div class="timer-register">
                                            <span>
                                                زمان باقیمانده
                                            </span>
                                            <button class='btn btn-sm btn-info float-right text-center mr-2 otp_timer' disabled>
                                                    <span class='d-flex justfy-content-between align-items-center flex-row-reverse otp_timer_text'>
                                                        <span class="minutes">2</span>
                                                        :
                                                        <span class="seconds">0</span>
                                                    </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="segment">
                            <a href="#" class="login_via_password">
                                <svg xmlns="http://www.w3.org/2000/svg" class="lock-icon" width="512" height="512" viewBox="0 0 535.5 535.5"><path d="M420.75 178.5h-25.5v-51c0-71.4-56.1-127.5-127.5-127.5s-127.5 56.1-127.5 127.5v51h-25.5c-28.05 0-51 22.95-51 51v255c0 28.05 22.95 51 51 51h306c28.05 0 51-22.95 51-51v-255c0-28.05-22.95-51-51-51zm-153-130.05c43.35 0 79.05 35.7 79.05 79.05v51H191.25v-51h-2.55c0-43.35 35.7-79.05 79.05-79.05zm153 436.05h-306v-255h306v255zm-153-76.5c28.05 0 51-22.95 51-51s-22.95-51-51-51-51 22.95-51 51 22.95 51 51 51z"></path></svg>
                                می‌خواهم با رمز عبور وارد شوم
                            </a>
                        </div>
                    @break
                    @case('not_registered')
                        <div class="segment segment-colored">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="head-title text-center mb-0">
                                        <span class="mobile_number">
                                            شماره موبایل <span class="mobile_num">{{ request()->cookie('identifier_username') }}</span> در {{ config('dizatech_identifier.site_title') }} ثبت نشده
                                        </span>
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="not_registered_mobile" class="not_registered_mobile" value="">
                        <div class="segment">
                            <a href="#" class="not_registered_button create_new_account">
                                ساخت حساب کاربری جدید با <span class="mobile_num">{{ request()->cookie('identifier_username') }}</span>
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
                                    <div class="group segment-content">
                                        <div class="group-input-holder">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text pl-3 pr-3" id="basic-addon1">
                                                    <i class="fa fa-mobile icon-size" aria-hidden="true"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control username-field mobile_or_email" value=""
                                                   name="username" placeholder="موبایل یا ایمیل">
                                        </div>
                                        <a type="button" class="btn btn-warning animate-btn forget_action" href="#">
                                            <span class="text">ورود</span>
                                            <span class="fa fa-sign-in fa-fw fa-display"></span>
                                            <div class="overlay"></div>
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
                    @case('recovery_code')
                    <div class="segment">
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="head-title">بازیابی رمزعبور</h4>
                                <div class="mb-4">
                                        <span class="mobile-code-text">
                                            کد فرستاده شده برای  <span class="user_info">({{ request()->cookie('identifier_username') }})</span>  را وارد کنید
                                        </span>
                                </div>
                                <div class="form-group">
                                    <div class="group segment-content">
                                        <div class="group-input-holder">
                                            <input type="text" class="form-control username-field recovery_code_input" value=""
                                                   name="code" placeholder="کد تایید">
                                        </div>
                                        <a type="button" class="btn btn-warning animate-btn confirm_recovery_code" href="#">
                                            <span class="text">تایید</span>
                                            <span class="fa fa-check fa-fw fa-display"></span>
                                            <div class="overlay"></div>
                                        </a>
                                    </div>
                                    <span class="invalid-feedback mt-4 d-none">
                                        <strong></strong>
                                    </span>
                                    <div class="timer-register">
                                        <span>
                                            زمان باقیمانده
                                        </span>
                                        <button class='btn btn-sm btn-info float-right text-center mr-2 recovery_timer' disabled>
                                                <span class='d-flex justfy-content-between align-items-center flex-row-reverse otp_timer_text'>
                                                    <span class="minutes">2</span>
                                                    :
                                                    <span class="seconds">0</span>
                                                </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @break
                    @case('change_password')
                    <div class="segment">
                        <p class="font-weight-bold">
                            لطفا رمز جدیدی را انتخاب و آن را وارد کنید
                        </p>
                    </div>
                    <div class="segment">

                        <div class="form-group">
                            <span class="recovery_pass_span">رمز جدید خود را وارد کنید</span>
                            <input type="password" class="form-control username-field recovery_new_password" value=""
                                   name="new_password">
                            <span class="tip">رمز انتخابی شما برای قوی&zwnj;تر شدن، باید شامل یکی از حروف (a تا z) و بیش از 6 کاراکتر باشد.</span>
                            <span class="invalid-feedback mt-4 d-none">
                                <strong></strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <span class="recovery_pass_span">رمز جدید خود را یک بار دیگر وارد کنید</span>
                            <input type="password" class="form-control username-field recovery_new_password_confirm" value=""
                                   name="password_confirm">
                            <span class="invalid-feedback mt-4 d-none">
                                <strong></strong>
                            </span>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <a href="#" class="change_password_btn d-inline-block">
                                    تغییر رمز
                                </a>
                            </div>
                        </div>
                    </div>
                    @break
                    @case('password')
                    <div class="segment">
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="head-title">ورود با رمزعبور</h4>
                                <div class="form-group">
                                    <div class="group d-flex">
                                        <input type="password" class="form-control username-field password_input" value=""
                                               name="password" placeholder="رمزعبور">
                                        <a class="btn btn-warning login_with_password float-left mr-2" href="#">
                                            <span>
                                                ورود
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
                    @case('email_code')
                    <div class="segment">
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="head-title">ورود با ایمیل</h4>
                                <div class="mb-4">
                                        <span class="mobile-code-text">
                                            کد فرستاده شده برای  <span class="user_info">({{ request()->cookie('identifier_username') }})</span>  را وارد کنید
                                        </span>
                                </div>
                                <div class="form-group">
                                    <div class="group segment-content">
                                        <div class="group-input-holder">
                                            <input type="text" class="form-control username-field email_code_input" value=""
                                                   name="code" placeholder="کد تایید">
                                        </div>
                                        <a type="button" class="btn btn-warning animate-btn confirm_email_code" href="#">
                                            <span class="text">تایید</span>
                                            <span class="fa fa-check fa-fw fa-display"></span>
                                            <div class="overlay"></div>
                                        </a>
                                    </div>
                                    <span class="invalid-feedback mt-4 d-none">
                                        <strong></strong>
                                    </span>
                                    <div class="timer-register">
                                        <span>
                                            زمان باقیمانده
                                        </span>
                                        <button class='btn btn-sm btn-info float-right text-center mr-2 recovery_timer' disabled>
                                                <span class='d-flex justfy-content-between align-items-center flex-row-reverse otp_timer_text'>
                                                    <span class="minutes">2</span>
                                                    :
                                                    <span class="seconds">0</span>
                                                </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="segment">
                        <a href="#" class="login_email_via_password">
                            <svg xmlns="http://www.w3.org/2000/svg" class="lock-icon" width="512" height="512" viewBox="0 0 535.5 535.5"><path d="M420.75 178.5h-25.5v-51c0-71.4-56.1-127.5-127.5-127.5s-127.5 56.1-127.5 127.5v51h-25.5c-28.05 0-51 22.95-51 51v255c0 28.05 22.95 51 51 51h306c28.05 0 51-22.95 51-51v-255c0-28.05-22.95-51-51-51zm-153-130.05c43.35 0 79.05 35.7 79.05 79.05v51H191.25v-51h-2.55c0-43.35 35.7-79.05 79.05-79.05zm153 436.05h-306v-255h306v255zm-153-76.5c28.05 0 51-22.95 51-51s-22.95-51-51-51-51 22.95-51 51 22.95 51 51 51z"></path></svg>
                            می‌خواهم با رمز عبور وارد شوم
                        </a>
                    </div>
                    @break
                @endswitch
            </div>
        </div>
    </div>
</div>
