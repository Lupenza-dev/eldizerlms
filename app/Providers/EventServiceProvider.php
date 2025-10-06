<?php

namespace App\Providers;

use App\Models\Loan\LoanApplication;
use App\Models\Loan\LoanContract;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Observers\UserObserver;
use App\Observers\LoanApplicationObserver;
use App\Models\User;
use App\Observers\LoanContractObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    // protected $listen = [
    //     Registered::class => [
    //         SendEmailVerificationNotification::class,
    //     ],
    // ];

    protected $listen = [
        Registered::class => [
          SendEmailVerificationNotification::class,
        ],
        'App\Event\LoanApplied' => [
          'App\Listeners\SendNotification'
        ]
     ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);
        LoanApplication::observe(LoanApplicationObserver::class);
        LoanContract::observe(LoanContractObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
