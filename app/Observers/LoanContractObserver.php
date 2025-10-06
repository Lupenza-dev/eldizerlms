<?php

namespace App\Observers;

use App\Models\LoanContract;

class LoanContractObserver
{
    /**
     * Handle the LoanContract "created" event.
     */
    public function created(LoanContract $loanContract): void
    {
        //
    }

    /**
     * Handle the LoanContract "updated" event.
     */
    public function updated(LoanContract $loanContract): void
    {
        //
    }

    /**
     * Handle the LoanContract "deleted" event.
     */
    public function deleted(LoanContract $loanContract): void
    {
        //
    }

    /**
     * Handle the LoanContract "restored" event.
     */
    public function restored(LoanContract $loanContract): void
    {
        //
    }

    /**
     * Handle the LoanContract "force deleted" event.
     */
    public function forceDeleted(LoanContract $loanContract): void
    {
        //
    }
}
