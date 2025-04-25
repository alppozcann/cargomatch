<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\GemiRoute;
use Carbon\Carbon;

class DeleteExpiredGemiRoutes extends Command
{
    protected $signature = 'gemi:sil-tarihi-gecenler';
    protected $description = 'Tarihi geçmiş gemi rotalarını siler';

    public function handle()
    {
        $now = Carbon::now();

        $deleted = GemiRoute::where('arrival_date', '<', $now)->delete();

        $this->info("Tarihi geçmiş $deleted gemi rotası silindi.");
    }
}

