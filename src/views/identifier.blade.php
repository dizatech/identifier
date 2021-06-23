<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ورود به {{ config('dizatech_identifier.site_title') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ mix(config('dizatech_identifier.css_public_path')) }}" rel="stylesheet">

</head>

<body>
<div id="app">
    <main id="main-wrapper">
        <div id="main-container" class=" ">
            <section class="route-section">
                <a href="#" class="sc-gZMcBi PmjKd">بازگشت</a>
                <div class="auth-wrapper">
                    <div class="segment signin-segment--header">
                        <span class="text">اگر در فیلیمو حساب کاربری ندارید، ثبت نام کنید:</span>
                        <a class="btn btn-green float-left" href="/signup">ایجاد حساب کاربری</a>
                    </div>
                    <div class="segment flex-column">
                        <span class="text">اگر در فیلیمو حساب کاربری دارید، وارد شوید:</span>
                        <form class="margin-top20 flex signin-form" autocomplete="off">
                            <div class="sc-EHOje jSnStR">
                                <label for="username" class="sc-gzVnrw cWWvHC">
                                    <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0Ij48cGF0aCBmaWxsPSIjODA4MDgwIiBkPSJNMTIgMTJjMi4yMSAwIDQtMS43OSA0LTRzLTEuNzktNC00LTQtNCAxLjc5LTQgNCAxLjc5IDQgNCA0em0wIDJjLTIuNjcgMC04IDEuMzQtOCA0djJoMTZ2LTJjMC0yLjY2LTUuMzMtNC04LTR6Ii8+PHBhdGggZD0iTTAgMGgyNHYyNEgweiIgZmlsbD0ibm9uZSIvPjwvc3ZnPgo=" alt="username">
                                    <input type="text" placeholder="ایمیل یا موبایل یا نام کاربری" id="username" class="sc-bZQynM emOqxL" value="">
                                    <div class="additional">
                                        <a class="filimo-links" href="/signin/forget-password">فراموش کردید؟</a>
                                    </div>
                                </label>
                            </div>
                            <button type="submit" class="btn btn-green margin-right5">ورود</button>
                        </form>
                    </div>
                </div>
                <div class="sc-ifAKCX gBChs">
                    <button type="button" class="">پشتیبانی</button>
                    <ul class="support-ways" style="height: 0px;">
                        <li>
                            <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI1MTIiIGhlaWdodD0iNTEyIiB2aWV3Qm94PSIwIDAgNTEwIDUxMCI+PHBhdGggZD0iTTQ1OSA1MUg1MUMyMi45NSA1MSAwIDczLjk1IDAgMTAydjMwNmMwIDI4LjA1IDIyLjk1IDUxIDUxIDUxaDQwOGMyOC4wNSAwIDUxLTIyLjk1IDUxLTUxVjEwMmMwLTI4LjA1LTIyLjk1LTUxLTUxLTUxem0wIDEwMkwyNTUgMjgwLjUgNTEgMTUzdi01MWwyMDQgMTI3LjVMNDU5IDEwMnY1MXoiIGZpbGw9IiM2NjYiLz48L3N2Zz4=" alt="">
                            <span class="value">
                                <span>ایمیل:</span>
                                <a href="mailto:payment@filimo.com">payment@filimo.com</a>
                            </span>
                        </li>
                        <li>
                            <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI1MTIiIGhlaWdodD0iNTEyIiB2aWV3Qm94PSIwIDAgNDU5IDQ1OSI+PHBhdGggZD0iTTkxLjggMTk4LjljMzUuNyA3MS40IDk2LjkgMTMwLjA1IDE2OC4zIDE2OC4zbDU2LjEtNTYuMWM3LjY0OS03LjY0OSAxNy44NS0xMC4xOTkgMjUuNS01LjEgMjguMDUgMTAuMiA1OC42NDkgMTUuMyA5MS44IDE1LjMgMTUuMyAwIDI1LjUgMTAuMiAyNS41IDI1LjV2ODYuN2MwIDE1LjMtMTAuMiAyNS41LTI1LjUgMjUuNUMxOTMuOCA0NTkgMCAyNjUuMiAwIDI1LjUgMCAxMC4yIDEwLjIgMCAyNS41IDBoODkuMjVjMTUuMyAwIDI1LjUgMTAuMiAyNS41IDI1LjUgMCAzMC42IDUuMSA2MS4yIDE1LjMgOTEuOCAyLjU1IDcuNjUgMCAxNy44NS01LjEgMjUuNUw5MS44IDE5OC45eiIgZmlsbD0iIzY2NiIvPjwvc3ZnPg==" alt="">
                            <span class="value">
                                <span>شماره تلفن:</span>
                                <a href="tel://(021)74524" dir="auto">(021)74524</a>
                            </span>
                        </li>
                        <li>
                            <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA5MiA5MiIgd2lkdGg9IjUxMiIgaGVpZ2h0PSI1MTIiPjxwYXRoIGQ9Ik00NS4zODYuMDA0QzE5Ljk4My4zNDQtLjMzMyAyMS4yMTUuMDA1IDQ2LjYxOWMuMzQgMjUuMzkzIDIxLjIwOSA0NS43MTUgNDYuNjExIDQ1LjM3NyAyNS4zOTgtLjM0MiA0NS43MTgtMjEuMjEzIDQ1LjM4LTQ2LjYxNS0uMzQtMjUuMzk1LTIxLjIxLTQ1LjcxNi00Ni42MS00NS4zNzd6TTQ1LjI1IDc0bC0uMjU0LS4wMDRjLTMuOTEyLS4xMTYtNi42Ny0yLjk5OC02LjU1OS02Ljg1Mi4xMDktMy43ODggMi45MzQtNi41MzggNi43MTctNi41MzhsLjIyNy4wMDRjNC4wMjEuMTE5IDYuNzQ4IDIuOTcyIDYuNjM1IDYuOTM3QzUxLjkwNCA3MS4zNDYgNDkuMTIzIDc0IDQ1LjI1IDc0em0xNi40NTUtMzIuNjU5Yy0uOTIgMS4zMDctMi45NDMgMi45My01LjQ5MiA0LjkxNmwtMi44MDcgMS45MzhjLTEuNTQxIDEuMTk4LTIuNDcxIDIuMzI1LTIuODIgMy40MzQtLjI3NS44NzMtLjQxIDEuMTA0LS40MzQgMi44OGwtLjAwNC40NTFIMzkuNDNsLjAzMS0uOTA3Yy4xMzEtMy43MjguMjIzLTUuOTIxIDEuNzY4LTcuNzMzIDIuNDI0LTIuODQ2IDcuNzcxLTYuMjg5IDcuOTk4LTYuNDM1Ljc2Ni0uNTc3IDEuNDEyLTEuMjM0IDEuODkzLTEuOTM2IDEuMTI1LTEuNTUxIDEuNjIzLTIuNzcyIDEuNjIzLTMuOTcyYTcuNzQgNy43NCAwIDAgMC0xLjQ3MS00LjU3NmMtLjkzOS0xLjMyMy0yLjcyMy0xLjk5My01LjMwMy0xLjk5My0yLjU1OSAwLTQuMzExLjgxMi01LjM1OSAyLjQ3OC0xLjA3OCAxLjcxMy0xLjYyMyAzLjUxMi0xLjYyMyA1LjM1di40NTdIMjcuOTM2bC4wMi0uNDc3Yy4yODUtNi43NjkgMi43MDEtMTEuNjQzIDcuMTc4LTE0LjQ4N0MzNy45NDcgMTguOTE4IDQxLjQ0NyAxOCA0NS41MzEgMThjNS4zNDYgMCA5Ljg1OSAxLjI5OSAxMy40MTIgMy44NjEgMy42IDIuNTk2IDUuNDI2IDYuNDg0IDUuNDI2IDExLjU1NiAwIDIuODM3LS44OTYgNS41MDItMi42NjQgNy45MjR6IiBmaWxsPSIjNjY2Ii8+PC9zdmc+" alt="">
                            <span class="value">
                                <a href="https://filimo.crisp.help/fa/">پرسش&zwnj;های متداول</a>
                            </span>
                        </li>
                    </ul>
                </div>
            </section>
        </div>
    </main>
</div>

<!-- Scripts -->
<script>let baseUrl = "{{ URL::to('/') }}";</script>
<script src="{{ mix(config('dizatech_identifier.js_public_path')) }}"></script>
@yield('script')


</body>
</html>
