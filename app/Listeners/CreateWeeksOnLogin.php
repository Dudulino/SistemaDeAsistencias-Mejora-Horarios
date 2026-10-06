<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Authenticated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Semanas;
use Carbon\Carbon;

class CreateWeeksOnLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Authenticated  $event
     * @return void
     */
    public function handle(Authenticated $event)
    {
        $primerDiaMes = Carbon::now()->startOfMonth();

        while (!$primerDiaMes->isMonday()) {
            $primerDiaMes->addDay();
        }

        while ($primerDiaMes->month == Carbon::now()->month) {
            if (!Semanas::where('fecha_lunes', $primerDiaMes->toDateString())->exists()) {
                Semanas::create(['fecha_lunes' => $primerDiaMes->toDateString()]);
            }

            $primerDiaMes->addWeek();
        }
    }
}
