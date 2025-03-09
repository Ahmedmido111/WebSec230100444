<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Mail\SendGridTransport;
use SendGrid;

class SendGridServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('sendgrid.transport', function ($app) {
            $config = $app['config']->get('services.sendgrid', []);
            return new SendGridTransport(new SendGrid($config['api_key']));
        });
    }

    public function boot()
    {
        $this->app['mail.manager']->extend('sendgrid', function () {
            return $this->app['sendgrid.transport'];
        });
    }
}
