<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use DB;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $siteSettings = DB::table('settings')->where('id', 1)->first(); 
      
        View::share(['siteSettings'=>$siteSettings]);
        
        Blade::directive('showprice', function ($expression) {
            $expression = explode('|', $expression);
            if(isset($expression[1])){
                $decimal = $expression[1];
            }else{
                $decimal = 0;
            }
           
            if(isset($expression[2]) && $expression[2] == 'a'){
                return "<?php echo number_format($expression[0], $decimal).CURR; ?>";
            }else{
                return "<?php echo CURR . number_format($expression[0], $decimal); ?>";
            }
            
        });
       
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
