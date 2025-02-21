<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use App\Policies\CategoryPolicy;
use App\Policies\ExpensePolicy;
use App\Models\Category;
use App\Models\Expense;
use \Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;


class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Category::class => CategoryPolicy::class,
        Expense::class => ExpensePolicy::class,
    ];
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
        //
        $this->registerPolicies();
    }
}
