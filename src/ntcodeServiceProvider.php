<?php

namespace fc\ntcode;


use Illuminate\Support\ServiceProvider;

class ntcodeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        // include __DIR __。'/ routes.php';
        
        $this->loadViewsFrom(__DIR__.'/views','ntcode');
        
        $this->publishes([
            __DIR__.'/views'=>base_path('resources/views/vendor/ntcode'),
            __DIR__.'/config/ntcode.php' => config_path('ntcode.php'),
        ]);
        
        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/ntcode'),
        ], 'ntcode_public');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        
        
        
        // $this->app['ntcode'] = $this->app->share(function ($app) {
        //     return new Toastr($app['session'], $app['config']);
        // });
        
        // print_r( new NtcodeNotifier );exit();
        
        $this->app->singleton( 'ntcode' , function ($app) {
            //  return new Twitter($app['config'], $app['session.store']);
            // return $this->app->make('fc\ntcode\NtcodeNotifier');
            // print_r( new NtcodeNotifier );exit();
            // return new NtcodeNotifier;
            return $this->app->makeWith('fc\ntcode\NtcodeNotifier', [$app['session'], $app['config']]);
        });
        
        
        // $this->app->bind(
        // 'fc\ntcode\session'
        // );
    // exit();
    
        // $this->app->singleton('ntcode',function(){
        //     // echo "ok";exit();
        //     return $this->app->make('fc\ntcode\NtcodeNotifier');
        //     // return $this->app->makeWith('fc\ntcode\NtcodeNotifier', [$app['session'], $app['config']]);
        // });
        
        // $this->app['ntcode'] = $this->app->share(function ($app) {
        //         return new Toastr($app['session'], $app['config']);
        // });
            
    }
    
    
 
        // public function provides()
        // {
        //     return ['ntcode'];
        // }
        
}
