<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        // Gate para administrar el sistema, visible solo para Administradores
        Gate::define('manage-system', function ($user) {
            return $user->hasRole('Administrador');
        });

        // Gate para el área de ventas, visible solo para el rol Ventas
        Gate::define('access-sales', function ($user) {
            return $user->hasRole('Ventas');
        });

        // Gate para agregar un nuevo post en el blog (si aplica para ambos roles)
        Gate::define('sales-manager', function ($user) {
            return $user->hasRole('Jefe de ventas');
        });

        // Gate para agregar un nuevo post en el blog (si aplica para ambos roles)
        Gate::define('add-blog-post', function ($user) {
            return $user->hasRole('Administrador') || $user->hasRole('Ventas') || $user->hasRole('Jefe de ventas');
        });

        // Gate para modulos de administracion (si aplica para ambos roles)
        Gate::define('add-blog-manager', function ($user) {
            return $user->hasRole('Administrador') || $user->hasRole('Jefe de ventas');
        });
    }
}
