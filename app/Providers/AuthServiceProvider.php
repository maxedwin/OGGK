<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        $gate->define('register', function ($user) {
            return (in_array($user->puesto, [1,2,4,5,11]));
        });

        $gate->define('entregas_pendientes', function ($user) {
            return (in_array($user->puesto, [1,2,3,4,5,6,7,8,9,10]));
        });

        $gate->define('facturas_pendientes', function ($user) {
            return (in_array($user->puesto, [1,2,3,4,5,6,7,9,10]));
        });
    }
}
