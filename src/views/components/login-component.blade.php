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
                                    <a class="btn btn-success float-left create_account" href="#">
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
                                        <div class="d-flex">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text pl-3 pr-3" id="basic-addon1">
                                                    <i class="fa fa-user icon-size" aria-hidden="true"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control username-field" value=""
                                                   name="username" id="username" placeholder="ایمیل یا موبایل یا نام کاربری">
                                            <a class="btn btn-success float-left mr-2" href="/signup">
                                                <span class="pl-4 pr-4">
                                                    ورود
                                                </span>
                                            </a>
                                        </div>
                                        <span class="invalid-feedback d-none">
                                            <strong></strong>
                                        </span>
                                    </div>
                                    <a href="#" class="forgot-password">فراموش کردید ؟! (بازیابی اطلاعات کاربری)</a>
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
                                            <a class="btn btn-success float-left mr-2 code_step" href="#">
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
                    @case('code')
                        <div class="segment">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="head-title">ثبت‌نام در {{ config('dizatech_identifier.site_title') }}</h4>
                                    <div class="mb-2">
                                        <span class="mobile-code-text">
                                            <i class="sms-icon"></i>
                                            کد فرستاده شده برای ( 98912*****43 ) را وارد کنید
                                        </span>
                                    </div>
                                    <div class="form-group">
                                        <div class="group d-flex">
                                            <input type="text" class="form-control username-field" value=""
                                                   name="confirm_code" placeholder="کد تایید">
                                            <a class="btn btn-success float-left mr-2" href="#">
                                            <span class="pl-4 pr-4">
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
                @endswitch
            </div>
        </div>
    </div>
</div>
