<?php

namespace App\Providers;

use App\Support\Import\AcademyRepository;
use App\Support\Import\FilmRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application Support.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(FilmRepository::class, AcademyRepository::class);
    }

    /**
     * Bootstrap any application Support.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
