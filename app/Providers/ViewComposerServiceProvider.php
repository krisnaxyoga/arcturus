<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;
use App\Models\Vendor;
use App\Models\Slider;
use App\Models\User;
use App\Models\PaymentGetwayTransaction;
use App\Models\Booking;

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
        View::composer('affiliate.layout.app', function ($view) {
            $code = session('auth_code');
            $id = session('id_affiliate');
            $name = session('name_affiliate');
            $view->with('code', $code)->with('id',$id)->with('name',$name);
        });

        View::composer('layouts.admin', function ($view) {
            $bookingconfirmation = PaymentGetwayTransaction::where('is_see',0)->where('payment_method','BANK-TRANSFER')->count();
            $topupconfirmation = PaymentGetwayTransaction::where('is_see',0)->where('payment_method','BANK-TRANSFER-TOP-UP')->count();
            $booking = Booking::where('is_see',0)->count();
            $view->with('bookingconfirmation', $bookingconfirmation)->with('topupconfirmation',$topupconfirmation)->with('booking',$booking);
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

        View::composer('landingpage.agent.layouts.app', function ($view) {
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
