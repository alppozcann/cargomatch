<?php

namespace App\Console\Commands;

use App\Services\ShipCargoMatchingService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MatchCargoAndShips extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cargo:match';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Match available cargo with ships based on various criteria';

    /**
     * Execute the console command.
     */
    public function handle(ShipCargoMatchingService $matchingService)
    {
        $this->info('Starting cargo-ship matching process...');
        
        try {
            // Get matches
            $matches = $matchingService->autoMatchCargoAndShips();
            
            // Log results
            $this->info(sprintf('Found %d matches', count($matches)));
            
            foreach ($matches as $match) {
                $this->info(sprintf(
                    'Match found: Ship %s with Cargo %s (Score: %.2f)',
                    $match['ship']->id,
                    $match['cargo']->id,
                    $match['score']
                ));
                
                // Here you could add code to notify users or update the database
                // For example:
                // $match['ship']->update(['status' => 'matched']);
                // $match['cargo']->update(['status' => 'matched']);
                
                // Send notification to users
                // Notification::send($match['ship']->user, new MatchFoundNotification($match));
                // Notification::send($match['cargo']->user, new MatchFoundNotification($match));
            }
            
            $this->info('Matching process completed successfully.');
            
        } catch (\Exception $e) {
            $this->error('An error occurred during the matching process: ' . $e->getMessage());
            Log::error('Cargo-ship matching error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            
            return 1;
        }
        
        return 0;
    }
} 