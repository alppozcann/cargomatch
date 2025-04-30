<?php

namespace App\Services;

use App\Models\GemiRoute;
use App\Models\Yuk;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ShipCargoMatchingService
{
    /**
     * Find matching cargo for a specific ship route
     *
     * @param GemiRoute $gemiRoute
     * @return Collection
     */
    public function findMatchingCargoForShip(GemiRoute $gemiRoute): Collection
    {
        // Yalnızca kapasite ve tarih kriteri
        $query = Yuk::where('status', 'active')
            ->where('from_location', $gemiRoute->start_port_id)
            ->where('to_location', $gemiRoute->end_port_id)
            ->where('match_status','pending')
            ->where('weight', '<=', $gemiRoute->available_capacity)
            ->where('desired_delivery_date', '>=', $gemiRoute->departure_date);
    
        // Aday yükleri al
        $matchingCargo = $query->get();
    
        // Her biri için eşleşme skoru hesapla
        $matchingCargo->transform(function ($cargo) use ($gemiRoute) {
            $cargo->match_score = $this->calculateMatchScore($cargo, $gemiRoute);
            return $cargo;
        });

        // Konum tabanlı uyumluluğu açıkça filtrele
        $matchingCargo = $matchingCargo->filter(function ($cargo) use ($gemiRoute) {
            $routePorts = collect($gemiRoute->way_points)
                ->pluck('port_id')
                ->prepend($gemiRoute->start_port_id)
                ->push($gemiRoute->end_port_id)
                ->values();

            $fromIndex = $routePorts->search($cargo->from_location);
            $toIndex = $routePorts->search($cargo->to_location);

            return $fromIndex !== false && $toIndex !== false && $fromIndex < $toIndex;
        });

        // Skoru düşük olanları filtreleyebilirsin (isteğe bağlı)
        return $matchingCargo
            ->filter(fn($cargo) => $cargo->match_score >= 0.1)
            ->sortByDesc('match_score');
    }
    

    /**
     * Find matching ships for a specific cargo
     *
     * @param Yuk $yuk
     * @return Collection
     */
    public function findMatchingShipsForCargo(Yuk $yuk): Collection
{
    $ships = GemiRoute::where('status', 'active')->get();

    // Her rota için skor hesapla
    $ships->transform(function ($ship) use ($yuk) {
        $ship->match_score = $this->calculateMatchScore($yuk, $ship);
        return $ship;
    });

    // Skorları logla test için
    foreach ($ships as $ship) {
        \Log::info("Route ID {$ship->id} → Match Score: {$ship->match_score}");
    }

    return $ships->sortByDesc('match_score'); // Filtreleme yapmadan direkt dön
}


    /**
     * Calculate a match score between a cargo and a ship
     * Higher score means better match
     *
     * @param Yuk $yuk
     * @param GemiRoute $gemiRoute
     * @return float
     */
    private function calculateMatchScore(Yuk $yuk, GemiRoute $gemiRoute): float
    {
        $score = 0;
        
        // Location match (40% of total score)
        $locationScore = $this->calculateLocationMatchScore($yuk, $gemiRoute);
        $score += $locationScore * 0.4;
        
        // Capacity utilization (20% of total score)
        $capacityScore = $this->calculateCapacityScore($yuk, $gemiRoute);
        $score += $capacityScore * 0.2;
        
        // Time compatibility (20% of total score)
        $timeScore = $this->calculateTimeScore($yuk, $gemiRoute);
        $score += $timeScore * 0.2;
        
        // Price compatibility (20% of total score)
        $priceScore = $this->calculatePriceScore($yuk, $gemiRoute);
        $score += $priceScore * 0.2;
        
        return $score;
    }

    /**
     * Calculate location match score
     *
     * @param Yuk $yuk
     * @param GemiRoute $gemiRoute
     * @return float
     */
    public function calculateLocationMatchScore(Yuk $yuk, GemiRoute $gemiRoute): float
    {
        $shipRoute = collect($gemiRoute->way_points)
            ->pluck('port_id')
            ->prepend($gemiRoute->start_port_id)
            ->push($gemiRoute->end_port_id)
            ->values();

        $fromIndex = $shipRoute->search($yuk->from_location);
        $toIndex = $shipRoute->search($yuk->to_location);

        if ($fromIndex === false || $toIndex === false || $fromIndex >= $toIndex) {
            return 0.0;
        }

        return 1.0;
    }

    /**
     * Calculate price compatibility score
     *
     * @param Yuk $yuk
     * @param GemiRoute $gemiRoute
     * @return float
     */
    private function calculatePriceScore(Yuk $yuk, GemiRoute $gemiRoute): float
    {
        // Calculate price per kg for both cargo and ship
        $cargoPricePerKg = $yuk->proposed_price / $yuk->weight;
        $shipPricePerKg = $gemiRoute->price / $gemiRoute->available_capacity;
        
        // Calculate price ratio (cargo price / ship price)
        $priceRatio = $cargoPricePerKg / $shipPricePerKg;
        
        // Ideal ratio is close to 1 (cargo price matches ship price)
        if ($priceRatio >= 0.9 && $priceRatio <= 1.1) {
            return 1.0;
        } elseif ($priceRatio >= 0.7 && $priceRatio < 0.9) {
            return 0.8;
        } elseif ($priceRatio > 1.1 && $priceRatio <= 1.3) {
            return 0.6;
        } elseif ($priceRatio >= 0.5 && $priceRatio < 0.7) {
            return 0.4;
        } elseif ($priceRatio > 1.3 && $priceRatio <= 1.5) {
            return 0.3;
        } else {
            return 0.2;
        }
    }

    /**
     * Automatically match available cargo with ships
     * This method can be called by a scheduled command
     *
     * @return array
     */
    public function autoMatchCargoAndShips(): array
    {
        $matches = [];
        $matchedCargoIds = [];
        $matchedShipIds = [];
        
        // Get all active ships
        $activeShips = GemiRoute::where('status', 'active')->get();
        
        foreach ($activeShips as $ship) {
            // Skip if ship is already matched
            if (in_array($ship->id, $matchedShipIds)) {
                continue;
            }
            
            // Find matching cargo for this ship
            $matchingCargo = $this->findMatchingCargoForShip($ship);
            
            // Filter out cargo that's already matched
            $matchingCargo = $matchingCargo->filter(function ($cargo) use ($matchedCargoIds) {
                return !in_array($cargo->id, $matchedCargoIds);
            });
            
            // If we have matching cargo, take the best match
            if ($matchingCargo->isNotEmpty()) {
                $bestMatch = $matchingCargo->first();
                
                // Only consider it a match if the score is above a threshold
                if ($bestMatch->match_score >= 0.7) {
                    $matches[] = [
                        'ship' => $ship,
                        'cargo' => $bestMatch,
                        'score' => $bestMatch->match_score
                    ];
                    
                    // Mark as matched
                    $matchedCargoIds[] = $bestMatch->id;
                    $matchedShipIds[] = $ship->id;
                }
            }
        }
        
        return $matches;
    }

    private function calculateCapacityScore(Yuk $yuk, GemiRoute $gemiRoute): float
{
    $cargoWeight = $yuk->weight_unit === 'ton' ? $yuk->weight * 1000 : $yuk->weight;
    $shipCapacity = $gemiRoute->available_capacity;

    $utilization = $cargoWeight / $shipCapacity;

    if ($utilization >= 0.7 && $utilization <= 0.9) {
        return 1.0;
    } elseif ($utilization >= 0.5 && $utilization < 0.7) {
        return 0.8;
    } elseif ($utilization > 0.9 && $utilization <= 1.0) {
        return 0.6;
    } elseif ($utilization >= 0.3 && $utilization < 0.5) {
        return 0.4;
    } else {
        return 0.2;
    }
}
private function calculateTimeScore(Yuk $yuk, GemiRoute $gemiRoute): float
{
    $arrivalDate = $gemiRoute->arrival_date;
    $desiredDeliveryDate = $yuk->desired_delivery_date;

    if ($arrivalDate <= $desiredDeliveryDate) {
        $bufferDays = $desiredDeliveryDate->diffInDays($arrivalDate, false);
        if ($bufferDays >= 7) return 1.0;
        if ($bufferDays >= 3) return 0.8;
        if ($bufferDays >= 1) return 0.6;
        return 0.4;
    } else {
        $delayDays = $desiredDeliveryDate->diffInDays($arrivalDate, false);
        if ($delayDays <= 1) return 0.3;
        if ($delayDays <= 3) return 0.2;
        return 0.1;
    }
}

} 