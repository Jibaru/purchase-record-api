<?php

namespace App\Providers;

use Core\Auth\Domain\Repositories\PermissionRepository;
use Core\Auth\Domain\Repositories\UserRepository;
use Core\Auth\Infrastructure\Repositories\MySqlPermissionRepository;
use Core\Auth\Infrastructure\Repositories\MySqlUserRepository;
use Core\PurchaseRecords\Domain\Repositories\PurchaseRecordRepository;
use Core\PurchaseRecords\Infrastructure\Repositories\MySqlPurchaseRecordRepository;
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
        $this->app->bind(PermissionRepository::class, MySqlPermissionRepository::class);
        $this->app->bind(PurchaseRecordRepository::class, MySqlPurchaseRecordRepository::class);
        $this->app->bind(UserRepository::class, MySqlUserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
