<?php

namespace App\Providers;
  
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use View;
use App\Models\Navbar;
  
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
          
    }
  
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
 
        View::composer('*', function($view)
        {
            $navbars = Navbar::orderBy('ordering')->where('is_active', 1)->get();
            $view->with('navbars', $navbars);
        });
    }
}