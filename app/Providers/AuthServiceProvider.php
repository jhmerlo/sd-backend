<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->greeting("Olá, " . $notifiable['name'])
                ->subject('Confirmação de e-mail')
                ->line('Estamos muito felizes de tê-lo conosco em nosso projeto.')
                ->line("O Solidariedade Digital está cada vez mais somando para que os alunos da UFES tenham acesso à condições de qualidade de inclusão e conhecimento.")
                ->line("Por favor, clique no link abaixo para confirmar o seu e-mail e começar a utilizar o nosso software.")
                ->action('Confirmar e-mail', $url);
        });
    }
}
