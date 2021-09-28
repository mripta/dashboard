<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AlertNotification extends Notification
{
    use Queueable;
    private $user;
    private $team;
    private $ref;
    private $param;
    private $number;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $team, $ref, $param, $number)
    {
        $this->user = $user;
        $this->team = $team;
        $this->ref = $ref;
        $this->param = $param;
        $this->number = $number;
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
            ->subject('MRIPTA - Alerta do Sensor '.$this->ref.' - '.$this->param)
            ->greeting('Olá '.$this->user.'!')
            ->line('Foram registados '.$this->number.' alertas na plataforma MRIPTA para o sensor '.$this->ref.' com o parâmetro '.$this->param.' nos últimos 5 minutos.')
            ->salutation('Muito Obrigado');
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
