<?php

namespace App\Providers;

use Core\Vouchers\Domain\Repositories\VoucherRepository;
use Core\Vouchers\Infrastructure\Repositories\MySqlVoucherRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(VoucherRepository::class, MySqlVoucherRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
