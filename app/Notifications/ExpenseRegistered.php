<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ExpenseRegistered extends Notification implements ShouldQueue
{
    use Queueable;

    private $expense;

    public function __construct($expense)
    {
        $this->expense = $expense;
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
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Despesa Cadastrada')
            ->line('Uma nova despesa foi cadastrada.')
            ->line('Descrição: ' . $this->expense->description)
            ->line('Valor: R$ ' . $this->expense->amount)
            ->line('Data: ' . $this->expense->date)
            ->action('Visualizar Despesas', url('/despesas'))
            ->line('Obrigado por usar nosso aplicativo!');
    }
}
