<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Dompdf\Dompdf;
use Illuminate\Notifications\Notification;

class InvoiceNotification extends Notification
{
    use Queueable;
    protected $order;


    /**
     * Create a new notification instance.
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $dompdf = new Dompdf();
        return (new MailMessage)
            ->subject('Envoie de facture')
            ->greeting('Bonjour !')
            ->line('Votre commande a été payé avec succés !')
            ->line('Merci pour votre confiance et à très bien tôt.')
            ->attachData($dompdf->output(), "Facture de paiements.pdf");
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
