<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Mail\MailManager;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Bypass SSL certificate verification untuk SMTP lokal
        // Diperlukan karena PHP di Windows sering tidak punya CA bundle yang lengkap
        $this->app->resolving(MailManager::class, function ($mailer) {
            stream_context_set_default([
                'ssl' => [
                    'verify_peer'       => false,
                    'verify_peer_name'  => false,
                    'allow_self_signed' => true,
                ],
            ]);
        });
    }
}