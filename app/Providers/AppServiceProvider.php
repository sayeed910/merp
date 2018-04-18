<?php

namespace App\Providers;

use App\Data\Repositories\EloquentCategoryRepository;
use App\Data\Repositories\EloquentEmployeeRepository;
use App\Data\Repositories\EloquentBrandRepository;
use App\Data\Repositories\EloquentProductRepository;
use App\Domain\BrandRepository;
use App\Domain\CategoryRepository;
use App\Domain\EmployeeRepository;
use App\Domain\ProductRepository;
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
        $this->app->singleton(EmployeeRepository::class, EloquentEmployeeRepository::class);
        $this->app->singleton(BrandRepository::class, EloquentBrandRepository::class);
        $this->app->singleton(CategoryRepository::class, EloquentCategoryRepository::class);
        $this->app->singleton(ProductRepository::class, EloquentProductRepository::class);
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
