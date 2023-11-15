<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;
use App\Models\Vendor;
use App\Models\Slider;
use App\Models\User;
use App\Models\PaymentGetwayTransaction;

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
        View::composer('layouts.admin', function ($view) {
            $bookingconfirmation = PaymentGetwayTransaction::count();
            
            $view->with('bookingconfirmation', $bookingconfirmation);
        });

        View::composer('layouts.landing', function ($view) {
            $settings = Setting::first();
            $vendor = null; // Default value
            
            if(auth()->check()){
                $iduser = auth()->user()->id;
                $vendor = Vendor::where('user_id', $iduser)->first();

                $user = User::where('id',$iduser)->first();
    
                if($user->role_id == 2){
    
                    $slider = Slider::where('user_id',$iduser)->get();
                }else{
                    $slider = Slider::where('user_id',1)->get();
                }
    
            }else{
                $slider = Slider::where('user_id',1)->get();
            }
            
            
            $view->with('settings', $settings)
                 ->with('vendor', $vendor)
                 ->with('slider',$slider);
        });
    }
}
