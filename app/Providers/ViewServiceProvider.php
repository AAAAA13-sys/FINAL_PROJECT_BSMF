<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Order;
use App\Models\Dispute;
use Illuminate\Support\Facades\Auth;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            
            if (Auth::check()) {
                $view->with('userOrders', Order::where('user_id', Auth::id())->latest()->get());
                $view->with('userDisputes', Dispute::with('messages.user')->where('user_id', Auth::id())->latest()->get());
            }
        });
    }
}
