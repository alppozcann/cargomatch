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
    $yukTeslimTarihiAltLimit = $gemiRoute->departure_date->subMonths(6);
    $yukTeslimTarihiUstLimit = $gemiRoute->departure_date->addMonths(6);
    

    // Ön filtre (tarih, kapasite, durum)
    $query = Yuk::where('status', 'active')
        ->where('match_status', 'pending')
        ->where('weight', '<=', $gemiRoute->available_capacity)
        ->whereBetween('desired_delivery_date', [$yukTeslimTarihiAltLimit, $yukTeslimTarihiUstLimit]);

    $matchingCargo = $query->get();

    // Rota limanları sıralı
    $allRoutePorts = array_merge(
        [$gemiRoute->start_port_id],
        $gemiRoute->way_points ?? [],
        [$gemiRoute->end_port_id]
    );

    // Sıralı liman eşleşmesini filtrele
    $matchingCargo = $matchingCargo->filter(function ($cargo) use ($allRoutePorts) {
        $startIndex = array_search($cargo->from_location, $allRoutePorts);
        $endIndex = array_search($cargo->to_location, $allRoutePorts);

        return $startIndex !== false && $endIndex !== false && $startIndex < $endIndex;
    });

    // Skor ekle
    $matchingCargo->transform(function ($cargo) use ($gemiRoute) {
        $cargo->match_score = $this->calculateMatchScore($cargo, $gemiRoute);
        return $cargo;
    });

    return $matchingCargo;
}


    

    /**
     * Find matching ships for a specific cargo
     *
     * @param Yuk $yuk
     * @return Collection
     */
    public function findMatchingShipsForCargo(Yuk $yuk): Collection
    {
        $now = now();
        $targetDate = $yuk->desired_delivery_date ?? $now;
        $minDate = $targetDate->copy()->subMonths(12);
        $maxDate = $targetDate->copy()->addMonths(12);
    
        $ships = GemiRoute::where('status', 'active')
            //->whereBetween('arrival_date', [$minDate, $maxDate])
            ->get();    
    
            $filtered = $ships->filter(function ($ship) use ($yuk) {
                $portSequence = collect($ship->way_points ?? [])
                    ->prepend($ship->start_location)
                    ->push($ship->end_location)
                    ->values();
            
                $fromIndex = $portSequence->search(fn($val) => (int)$val === (int)$yuk->from_location);
                $toIndex = $portSequence->search(fn($val) => (int)$val === (int)$yuk->to_location);
            
                if ($fromIndex === false && (int)$ship->start_port_id === (int)$yuk->from_location) {
                    $fromIndex = 0;
                }
            
                if ($toIndex === false && (int)$ship->end_port_id === (int)$yuk->to_location) {
                    $toIndex = $portSequence->count() - 1;
                }
                        
                return $fromIndex !== false && $toIndex !== false && $fromIndex < $toIndex;
            });
            
    
        $scored = $filtered->map(function ($ship) use ($yuk) {
            $ship->match_score = $this->calculateMatchScore($yuk, $ship);
            return $ship;
        });
    
        return $scored->sortByDesc('match_score')->values();
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

        // Shipping date compatibility (10% of total score)
        $shippingScore = $this->calculateShippingDateScore($yuk, $gemiRoute);
        $score += $shippingScore * 0.1;

        // Waypoint ETA match score (10%)
        $waypointScore = $this->calculateWaypointEtaScore($yuk, $gemiRoute);
        $score += $waypointScore * 0.1;
        
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

private function calculateShippingDateScore(Yuk $yuk, GemiRoute $gemiRoute): float
{
    if (!$yuk->shipping_date || !$gemiRoute->departure_date) {
        return 0.0;
    }

    $gapDays = $yuk->shipping_date->diffInDays($gemiRoute->departure_date, false);

    if ($gapDays === 0) {
        return 1.0; // perfect match
    } elseif ($gapDays >= -1 && $gapDays <= 1) {
        return 0.8;
    } elseif ($gapDays >= -3 && $gapDays <= 3) {
        return 0.6;
    } elseif ($gapDays >= -7 && $gapDays <= 7) {
        return 0.4;
    } else {
        return 0.2;
    }
}

    /**
     * Calculate waypoint ETA match score
     *
     * @param Yuk $yuk
     * @param GemiRoute $gemiRoute
     * @return float
     */
    private function calculateWaypointEtaScore(Yuk $yuk, GemiRoute $gemiRoute): float
    {
        if (!$yuk->from_location || !$yuk->shipping_date || empty($gemiRoute->way_points)) {
            return 0.0;
        }

        foreach ($gemiRoute->way_points as $point) {
            if ($point['port_id'] == $yuk->from_location && !empty($point['date'])) {
                $gapDays = \Carbon\Carbon::parse($point['date'])->diffInDays($yuk->shipping_date, false);

                if ($gapDays === 0) {
                    return 1.0;
                } elseif (abs($gapDays) <= 1) {
                    return 0.8;
                } elseif (abs($gapDays) <= 3) {
                    return 0.6;
                } elseif (abs($gapDays) <= 7) {
                    return 0.4;
                } else {
                    return 0.2;
                }
            }
        }

        return 0.0; // no matching waypoint found
    }

}