<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Purchase;
use App\Models\Basket;
use App\Models\Book;
use App\Models\Distributor;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
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
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        /**
         * Register policy
         */

        //Register Post policy

        //Purchase
        Gate::define('read-purchase', function($user){
            return $user->role == 'customer' || $user->role == 'admin';
        });

        Gate::define('update-purchase', function ($user, $purchase){
            if($user->role == 'admin'){
                return false;
            }else if($user->role == 'customer'){
                return $purchase->id_user == $user->id;
            }
        });

        Gate::define('create-purchase', function ($user){
            if($user->role == 'admin'){
                return false;
            }else if($user->role == 'customer'){
                return true;
            }

        });

        Gate::define('delete-purchase', function ($user, $purchase){
            if($user->role == 'admin'){
                return false;
            }else if($user->role == 'customer'){
                return $purchase->id_user == $user->id;
            }
        });

        //Basket
        Gate::define('read-basket', function($user){
            return $user->role == 'customer' || $user->role == 'admin';
        });

        Gate::define('update-basket', function ($user, $basket){
            if($user->role == 'admin'){
                return false;
            }else if($user->role == 'customer'){
                return true;
            }
        });

        Gate::define('create-basket', function ($user){
            if($user->role == 'admin'){
                return false;
            }else if($user->role == 'customer'){
                return true;
            }

        });

        Gate::define('delete-basket', function ($user, $basket){
            if($user->role == 'admin'){
                return false;
            }else if($user->role == 'customer'){
                return true;
            }
        });

        //Distributor
        Gate::define('read-distributor', function($user){
            return $user->role == 'admin';
        });

        Gate::define('update-distributor', function ($user, $distributor){
            return $user->role == 'admin';
        });

        Gate::define('create-distributor', function ($user){
            return $user->role == 'admin';

        });

        Gate::define('delete-distributor', function ($user, $distributor){
            return $user->role == 'admin';
        });

         //Book
        Gate::define('read-book', function($user){
            return $user->role == 'customer' || $user->role == 'admin';
        });

        Gate::define('read-imagebook', function($user){
            return $user->role == 'customer' || $user->role == 'admin';
        });

        Gate::define('update-book', function ($user, $book){
            if($user->role == 'admin'){
                return true;
            }else if($user->role == 'customer'){
                return false;
            }
        });

        Gate::define('create-book', function ($user){
            if($user->role == 'admin'){
                return true;
            }else if($user->role == 'customer'){
                return false;
            }

        });

        Gate::define('delete-book', function ($user, $book){
            if($user->role == 'admin'){
                return true;
            }else if($user->role == 'customer'){
                return false;
            }
        });

        $this->app['auth']->viaRequest('api', function ($request) {
            if ($request->input('api_token')) {
                return User::where('api_token', $request->input('api_token'))->first();
            }
        });
    }
}
