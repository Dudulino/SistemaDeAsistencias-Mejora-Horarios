<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReunionIntegranteRemovidoMailable extends Mailable
{
    use Queueable, SerializesModels;
    public $colaborador;
    public $reunion;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($colaborador, $reunion)
    {
        $this->colaborador = $colaborador;
        $this->reunion = $reunion;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            // from: new Address('cristopher.delacruz.1555@gmail.com', 'Cristopher De la Cruz'),
            // subject: 'Notificación de Reunión',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.reunion_integrante_removido',
            with: [
                'colaborador' => $this->colaborador,
                'reunion' => $this->reunion
            ],

        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
