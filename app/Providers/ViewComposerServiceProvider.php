<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;
use App\Models\Vendor;

class ViewComposerServiceProvider extends ServiceProvider
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
        View::composer('layouts.landing', function ($view) {
            $settings = Setting::first();
            $vendor = null; // Default value
            
            if (auth()->check()) {
                $iduser = auth()->user()->id;
                $vendor = Vendor::where('user_id', $iduser)->first();
            }
            
            $view->with('settings', $settings)
                 ->with('vendor', $vendor);
        });
    }
}
