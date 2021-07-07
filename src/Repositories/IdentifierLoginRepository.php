<?php


namespace Dizatech\Identifier\Repositories;

use App\Models\User;
use Carbon\Carbon;
use Dizatech\Identifier\Models\IdentifierOtpCode;
use Dizatech\Identifier\Notifications\Email\SendPasswordEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class IdentifierLoginRepository
{
    protected function user()
    {
        $class = config('dizatech_identifier.user_model');
        return new $class;
    }

    public function sendSMS($mobile, $recovery_mode = null)
    {
        if (!is_null($recovery_mode)){
            $checkUser = $this->existUserMobile($mobile);
            if ($checkUser->status != 200){
                return [
                    'status' => $checkUser->status,
                    'message' => $checkUser->message
                ];
            }else{
                $user = $checkUser->user;
            }
        }else{
            $user = $this->createOrExistUser($mobile);
        }
        if ($this->checkIfLastOtpLogExpired($user) == 'expired'){
            $this->sendCode($user,'1', $this->generateOTP());
            return [
                'status' => 200,
                'message' => 'پیامک کد برایتان ارسال شد.'
            ];
        }else{
            $diff = $this->getOtpTimeDiff($this->getLastOtp($user->id));
            return [
                'status' => 400,
                'message' => 'امکان ارسال مجدد تا ' . $diff . ' دیگر'
            ];
        }
    }

    public function sendConfirmEmail($email)
    {
        $checkUser = $this->existUserEmail($email);
        if ($checkUser->status == 200){
            if ($this->checkIfLastOtpLogExpired($checkUser->user) == 'expired'){
                $this->sendEmail($checkUser->user,$this->generateOTP());
                return [
                    'status' => 200,
                    'message' => 'ایمیل کد برایتان ارسال شد.'
                ];
            }else{
                $diff = $this->getOtpTimeDiff($this->getLastOtp($checkUser->user->id));
                return [
                    'status' => 400,
                    'message' => 'امکان ارسال مجدد تا ' . $diff . ' دیگر'
                ];
            }
        }else{
            return [
                'status' => 200,
                'message' => 'ایمیل وجود ندارد.'
            ];
        }
    }

    protected function sendEmail($user,$otp_code)
    {
        $user->notify(new SendPasswordEmail($otp_code));
        $this->makeExpireLastOtpLog($user->id);
        $this->newOtpLog($otp_code,$user->id);
    }

    public function attempLogin($user)
    {
        if (Auth::loginUsingId($user->id)){
            return (object) [
                'status' => 200,
                'message' => "باموفقیت وارد شدید"
            ];
        }
    }

    protected function verifyUser($user)
    {
        if ($user->email_verified_at == null){
            $this->user()->where('id', $user->id)->update([
                'email_verified_at' => Carbon::now()->toDateTimeString()
            ]);
        }
    }

    public function confirmSMS($mobile, $code, $recovery_mode = null)
    {
        if (!is_null($recovery_mode)){
            $checkUser = $this->existUserMobile($mobile);
            if ($checkUser->status != 200){
                return [
                    'status' => $checkUser->status,
                    'message' => $checkUser->message
                ];
            }else{
                $user = $checkUser->user;
            }
        }else{
            $user = $this->createOrExistUser($mobile);
        }
        $code_status = $this->checkIfOtpLogExpired($code,$user->mobile,$user);
        if ($code_status == 'not_expired'){
            $this->verifyUser($user);
            $this->forgetAllCookies();
            $this->makeExpireLastOtpLog($user->id);
            return (object) [
                'status' => 200,
                'message' => 'کد باموفقیت تایید شد.',
                'user' => $user
            ];
        }else{
            if ($code_status == 'not_valid'){
                return (object) [
                    'status' => 400,
                    'message' => 'کد وارد شده معتبر نیست.'
                ];
            }else{
                return (object) [
                    'status' => 400,
                    'message' => 'کد وارد شده منقضی شده است.'
                ];
            }
        }
    }

    public function confirmEmail($email, $code)
    {
        $checkUser = $this->existUserEmail($email);
        if ($checkUser->status == 200){
            $code_status = $this->checkIfOtpLogExpired($code,$checkUser->user->mobile,$checkUser->user);
            if ($code_status == 'not_expired'){
                $this->verifyUser($checkUser->user);
                return (object) [
                    'status' => 200,
                    'message' => 'کد باموفقیت تایید شد.',
                    'user' => $checkUser->user
                ];
            }else{
                if ($code_status == 'not_valid'){
                    return (object) [
                        'status' => 400,
                        'message' => 'کد وارد شده معتبر نیست.'
                    ];
                }else{
                    return (object) [
                        'status' => 400,
                        'message' => 'کد وارد شده منقضی شده است.'
                    ];
                }
            }
        }else{
            return (object) [
                'status' => 400,
                'message' => 'کاربر پیدا نشد.'
            ];
        }
    }

    protected function forgetAllCookies()
    {
        $this->forgetCookie(\request()->cookie('identifier_verified_recovery'));
        $this->forgetCookie(\request()->cookie('identifier_username'));
        $this->forgetCookie(\request()->cookie('identifier_recovery_type'));
    }

    public function changePasswordViaMobile($username, $new_password)
    {
        $checkUser = $this->existUserMobile($username);
        if ($checkUser->status == 200){
            $this->updateUserPassword($new_password,$checkUser->user);
            $this->attempLogin($checkUser->user);
            $this->forgetAllCookies();
            $this->makeExpireLastOtpLog($checkUser->user->id);
            return (object) [
                'user' => $checkUser->user,
                'status' => 200,
                'message' => 'رمزعبور باموفقیت تغییر کرد.'
            ];
        }else{
            return (object) [
                'status' => 400,
                'message' => 'کاربر پیدا نشد.'
            ];
        }
    }

    public function loginViaEmail()
    {
        $checkUser = $this->existUserEmail($username);
        if ($checkUser->status == 200){
            $this->attempLogin($checkUser->user);
            $this->forgetAllCookies();
            $this->makeExpireLastOtpLog($checkUser->user->id);
            return (object) [
                'user' => $checkUser->user,
                'status' => 200,
                'message' => 'باموفقیت وارد شدید.'
            ];
        }else{
            return (object) [
                'status' => 400,
                'message' => 'کاربر پیدا نشد.'
            ];
        }
    }

    public function changePasswordViaEmail($username, $new_password)
    {
        $checkUser = $this->existUserEmail($username);
        if ($checkUser->status == 200){
            $this->updateUserPassword($new_password,$checkUser->user);
            $this->attempLogin($checkUser->user);
            $this->forgetAllCookies();
            $this->makeExpireLastOtpLog($checkUser->user->id);
            return (object) [
                'user' => $checkUser->user,
                'status' => 200,
                'message' => 'رمزعبور باموفقیت تغییر کرد.'
            ];
        }else{
            return (object) [
                'status' => 400,
                'message' => 'کاربر پیدا نشد.'
            ];
        }
    }

    protected function updateUserPassword($password,User $user)
    {
        $user->password = Hash::make($password);
        $user->save();
    }

    public function loginViaPassword($username, $password)
    {
        $checkUser = $this->existUserMobile($username);
        if ($checkUser->status == 200){
            if(Auth::attempt([
                'mobile' => $username,
                'password' => $password
            ])){
                $this->forgetAllCookies();
                return (object) [
                    'user' => $checkUser->user,
                    'status' => 200,
                    'message' => 'باموفقیت وارد شدید.'
                ];
            }else{
                return (object) [
                    'status' => 400,
                    'message' => 'اطلاعات واردشده اشتباه است.'
                ];
            }
        }else{
            return (object) [
                'status' => 400,
                'message' => 'کاربر پیدا نشد.'
            ];
        }
    }

    protected function forgetCookie($cookie_name)
    {
        if (!is_null($cookie_name)){
            \Cookie::forget($cookie_name);
        }
        return json_encode([
            'status' => 200
        ]);
    }

    protected function createOrExistUser($mobile)
    {
        $user_object = $this->user()::query()->where('mobile', '=', $mobile);
        if ($user_object->count() > 0){
            return $user_object->first();
        }else{
            $user_object = $this->user()->fill([
                'username' => stringToken(8, 'abcdefg123456789'),
                'mobile' => $mobile,
                'email' => null,
                'two_factor_status' => 'off',
                'user_type' => 'other'
            ]);
            $user_object->password = bcrypt(stringToken(8));
            $user_object->save();
            return $user_object;
        }
    }

    protected function existUserEmail($email)
    {
        $user_object = $this->user()::query()->where('email', '=', $email);
        if ($user_object->count() > 0){
            return (object) [
                'user' => $user_object->first(),
                'status' => 200
            ];
        }else{
            return (object) [
                'status' => 404,
                'message' => 'ایمیل پیدا نشد.'
            ];
        }
    }

    protected function existUserMobile($mobile)
    {
        $user_object = $this->user()::query()->where('mobile', '=', $mobile);
        if ($user_object->count() > 0){
            return (object) [
                'user' => $user_object->first(),
                'status' => 200
            ];
        }else{
            return (object) [
                'status' => 404,
                'message' => 'شماره موبایل پیدا نشد.'
            ];
        }
    }

    protected function sendCode($user,$templateId,$otp_pass)
    {
        \Notifier::userId($user->id)
            ->templateId($templateId)
            ->params(['param1' => $otp_pass])
            ->options(['method' => 'otp','ghasedak_template_name' => 'registration',
                'hasPassword' => 'yes', 'receiver' => $user->mobile])
            ->send();
        $this->makeExpireLastOtpLog($user->id);
        $this->newOtpLog($otp_pass,$user->id);
    }

    protected function generateOTP()
    {
        $length = config('dizatech_identifier.otp_digit');
        return stringToken($length,'0123456789');
    }

    protected function newOtpLog($otp_pass,$user_id)
    {
        $this->makeExpireLastOtpLog($user_id);
        IdentifierOtpCode::query()->create([
            'code' => $otp_pass,
            'user_id' => $user_id,
            'expired_at' => Carbon::now()->addMinutes(2),
            'is_expired' => 'no'
        ]);
    }

    protected function checkIfOtpLogExpired($otp_code,$mobile,$user)
    {
        $otp = $this->getOtpLog($otp_code,$mobile);
        if (is_null($otp)){
            return 'not_valid';
        }
        if (Carbon::now()->toDateTimeString() > $otp->expired_at){
            $this->makeExpireLastOtpLog($user->id);
            return 'expired';
        }else{
            return 'not_expired';
        }
    }

    protected function checkIfLastOtpLogExpired($user)
    {
        $otp = $this->getLastOtp($user->id);
        if (!is_null($otp)){
            if (Carbon::now()->toDateTimeString() > $otp->expired_at){
                return 'expired';
            }else{
                return 'not_expired';
            }
        }else{
            return 'expired';
        }
    }

    protected function makeExpireLastOtpLog($user_id)
    {
        $last_otp_code = $this->getLastOtp($user_id);
        if (!is_null($last_otp_code) || !empty($last_otp_code)){
            IdentifierOtpCode::query()->where('id', '=', $last_otp_code->id)
                ->update([
                    'is_expired' => 'yes'
                    ]);
        }
    }

    protected function getLastOtp($user_id)
    {
        return IdentifierOtpCode::query()
            ->where('user_id','=', $user_id)
            ->latest()->first();
    }

    protected function getOtpLog($otp_code,$mobile)
    {
        $user = $this->createOrExistUser($mobile);
        return IdentifierOtpCode::query()
            ->where('user_id','=', $user->id)
            ->where('code', '=', $otp_code)
            ->first();
    }

    protected function getOtpTimeDiff($otp)
    {
        $diff_in_sec = Carbon::parse(Carbon::now()->toDateTimeString())->diffInSeconds($otp->expired_at);
        return gmdate('i:s', $diff_in_sec) . ' ثانیه';
    }

    public function checkMobileExist($mobile)
    {
        $user_object = $this->user()::query()
            ->where('mobile', '=', $mobile)->where('email_verified_at', '!=', null);
        if ($user_object->count() > 0){
            return 'registered';
        }else{
            return 'not_registered';
        }
    }

    public function checkEmailExist($email)
    {
        $user_object = $this->user()::query()
            ->where('email', '=', $email)->where('email_verified_at', '!=', null);
        if ($user_object->count() > 0){
            return 'registered';
        }else{
            return 'not_registered';
        }
    }

    public function isEmail($input): bool
    {
        $regex = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";
        if(preg_match($regex, $input)) {
            return true;
        }
        return false;
    }

    public function isMobile($input): bool
    {
        $regex = "/^09\d{9}$/";
        if(preg_match($regex, $input)) {
            return true;
        }
        return false;
    }
}
