<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UsuarioCreadoMailable extends Mailable
{
    use Queueable, SerializesModels;
    public $email;
    public $password;
    public $usuario;
    public $tipo_usuario;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $password, $usuario, $tipo_usuario)
    {
        $this->email = $email;
        $this->password = $password;
        $this->usuario = $usuario;
        $this->tipo_usuario = $tipo_usuario;

    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Usuario del Sistema',
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
            view: 'emails.usuario_creado',
            with: [
                'email' => $this->email,
                'password' => $this->password,
                'usuario' => $this->password,
                'tipo_usuario' => $this->password,
            ]
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
