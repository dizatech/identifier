<div>
    <div class="card-body p-0">
        <div class="login-form">
            <div class="auth-wrapper">
                @if($page == 'default')
                    <div class="segment">
                        <div class="row">
                            <div class="col-md-8">
                                <p class="create-account-text">اگر در {{ config('dizatech_identifier.site_title') }} حساب کاربری ندارید، ثبت نام کنید:</p>
                            </div>
                            <div class="col-md-4">
                                <a class="btn btn-success float-left" href="/signup">
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
                                <div class="form-group d-flex">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text pl-3 pr-3" id="basic-addon1">
                                            <i class="fa fa-user" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control username-field" value=""
                                           name="username" id="username" placeholder="ایمیل یا موبایل یا نام کاربری">
                                    <a class="btn btn-success float-left mr-2" href="/signup">
                                        <span class="pl-4 pr-4">
                                                            ورود
                                        </span>
                                    </a>
                                    <span class="invalid-feedback d-none">
                                        <strong></strong>
                                    </span>
                                </div>
                                <a href="#" class="forgot-password">فراموش کردید ؟! (بازیابی اطلاعات کاربری)</a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
