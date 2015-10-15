<?php

namespace App\Providers;

use App\Episode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Episode::created(function ($episode) {
            DB::table('users')
                ->whereIn('id', function($query) use($episode)
            {
                $query->select('user_id')
                    ->from('favorites')
                    ->where('drama_id', $episode->drama_id)
                    ->whereIn('type', [0, 1]);
            })
                ->increment('dramafeed', 1);
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
