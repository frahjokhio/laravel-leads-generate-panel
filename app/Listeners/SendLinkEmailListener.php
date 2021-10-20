<?php
namespace App\Listeners;

use App\Events\SendLinkEmailEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Mail\Mailer;


class SendLinkEmailListener
{
    public $mailer;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Handle the event.
     *
     * @param  PasswordWasRecovered  $event
     * @return void
     */
    public function handle(SendLinkEmailEvent $event)
    {
        $client = $event->client;
        //dd($client);
        $this->mailer->send('email.send-email-link', ['client' => $client], function ($m) use ($client) {
            $m->to($client->client->email)->subject('Files Upload Link');
        });

    }
}
