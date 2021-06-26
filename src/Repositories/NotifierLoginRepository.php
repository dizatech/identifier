<?php


namespace Dizatech\Identifier\Repositories;

use Carbon\Carbon;
use Dizatech\Identifier\Models\NotifierOtpCode;
use Illuminate\Support\Facades\Auth;

class NotifierLoginRepository
{
    protected function user()
    {
        $class = config('dizatech_identifier.user_model');
        return new $class;
    }

    public function sendSMS($mobile)
    {
        $user = $this->createOrExistUser($mobile);
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

    public function attempLogin($user)
    {
        if (Auth::loginUsingId($user->id)){
            return [
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

    public function confirmSMS($mobile, $code)
    {
        $user = $this->createOrExistUser($mobile);
        $code_status = $this->checkIfOtpLogExpired($code,$user->mobile,$user);
        if ($code_status == 'not_expired'){
            $this->verifyUser($user);
            return (object) [
                'status' => 200,
                'message' => 'ثبت نام باموفقیت انجام شد.',
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
        return stringToken(6,'0123456789');
    }

    protected function newOtpLog($otp_pass,$user_id)
    {
        $this->makeExpireLastOtpLog($user_id);
        NotifierOtpCode::query()->create([
            'code' => $otp_pass,
            'user_id' => $user_id,
            'expires_at' => Carbon::now()->addMinutes(2),
            'is_expired' => 'no'
        ]);
    }

    protected function checkIfOtpLogExpired($otp_code,$mobile,$user)
    {
        $otp = $this->getOtpLog($otp_code,$mobile);
        if (is_null($otp)){
            return 'not_valid';
        }
        if (Carbon::now()->toDateTimeString() > $otp->expires_at){
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
            if (Carbon::now()->toDateTimeString() > $otp->expires_at){
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
            NotifierOtpCode::query()->where('id', '=', $last_otp_code->id)
                ->update([
                    'is_expired' => 'yes'
                    ]);
        }
    }

    protected function getLastOtp($user_id)
    {
        return NotifierOtpCode::query()
            ->where('user_id','=', $user_id)
            ->latest()->first();
    }

    protected function getOtpLog($otp_code,$mobile)
    {
        $user = $this->createOrExistUser($mobile);
        return NotifierOtpCode::query()
            ->where('user_id','=', $user->id)
            ->where('code', '=', $otp_code)
            ->first();
    }

    protected function getOtpTimeDiff($otp)
    {
        $diff_in_sec = Carbon::parse(Carbon::now()->toDateTimeString())->diffInSeconds($otp->expires_at);
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
}
