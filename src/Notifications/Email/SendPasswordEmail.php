<?php

namespace Dizatech\Identifier\Notifications\Email;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class SendPasswordEmail extends Notification
{
    use Queueable;

    protected $password;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($password)
    {
        $this->password = $password;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->theme((app()->getLocale() == 'fa') ? 'rtl-theme' : 'default')
            ->greeting('با سلام و عرض ادب')
            ->salutation('اراتمند شما وبسایت ' . config('dizatech_identifier.site_title'))
            ->subject('کد یکبارمصرف حساب کاربری')
            ->line('لطفا برای تغییر رمزعبور حساب کاربری خود، از کد زیر استفاده نمایید')
            ->line($this->password);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
