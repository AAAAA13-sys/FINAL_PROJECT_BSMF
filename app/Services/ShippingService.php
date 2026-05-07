<?php

declare(strict_types=1);

namespace App\Services;

final class ShippingService
{
    /**
     * Official Philippine Regions
     */
    public static function getRegions(): array
    {
        return [
            'NCR' => 'National Capital Region (Metro Manila)',
            'Region I' => 'Region I (Ilocos Region)',
            'Region II' => 'Region II (Cagayan Valley)',
            'Region III' => 'Region III (Central Luzon)',
            'Region IV-A' => 'Region IV-A (CALABARZON)',
            'Region IV-B' => 'Region IV-B (MIMAROPA)',
            'Region V' => 'Region V (Bicol Region)',
            'Region VI' => 'Region VI (Western Visayas)',
            'Region VII' => 'Region VII (Central Visayas)',
            'Region VIII' => 'Region VIII (Eastern Visayas)',
            'Region IX' => 'Region IX (Zamboanga Peninsula)',
            'Region X' => 'Region X (Northern Mindanao)',
            'Region XI' => 'Region XI (Davao Region)',
            'Region XII' => 'Region XII (SOCCSKSARGEN)',
            'Region XIII' => 'Region XIII (Caraga)',
            'BARMM' => 'Bangsamoro (BARMM)',
            'CAR' => 'Cordillera Administrative Region (CAR)',
        ];
    }

    /**
     * Major Cities per Region for a realistic UI
     */
    public static function getRegionalCities(): array
    {
        return [
            'NCR' => self::getMetroManilaDistances(), // NCR uses the distance-mapped list
            'Region I' => ['Laoag City' => 0, 'Vigan City' => 0, 'Dagupan City' => 0, 'San Fernando City' => 0],
            'Region II' => ['Tuguegarao City' => 0, 'Santiago City' => 0, 'Cauayan City' => 0],
            'Region III' => ['Angeles City' => 0, 'Olongapo City' => 0, 'San Fernando City' => 0, 'Tarlac City' => 0],
            'Region IV-A' => ['Antipolo City' => 0, 'Calamba City' => 0, 'Dasmariñas City' => 0, 'Batangas City' => 0, 'Lucena City' => 0],
            'Region IV-B' => ['Puerto Princesa City' => 0, 'Calapan City' => 0],
            'Region V' => ['Legazpi City' => 0, 'Naga City' => 0, 'Sorsogon City' => 0],
            'Region VI' => ['Iloilo City' => 0, 'Bacolod City' => 0, 'Roxas City' => 0],
            'Region VII' => ['Cebu City' => 0, 'Mandaue City' => 0, 'Lapu-Lapu City' => 0, 'Dumaguete City' => 0, 'Tagbilaran City' => 0],
            'Region VIII' => ['Tacloban City' => 0, 'Ormoc City' => 0, 'Catbalogan City' => 0],
            'Region IX' => ['Zamboanga City' => 0, 'Dipolog City' => 0, 'Pagadian City' => 0],
            'Region X' => ['Cagayan de Oro City' => 0, 'Iligan City' => 0, 'Malaybalay City' => 0, 'Valencia City' => 0],
            'Region XI' => ['Davao City' => 0, 'Tagum City' => 0, 'Digos City' => 0, 'Panabo City' => 0],
            'Region XII' => ['General Santos City' => 0, 'Koronadal City' => 0, 'Kidapawan City' => 0, 'Cotabato City' => 0],
            'Region XIII' => ['Butuan City' => 0, 'Surigao City' => 0, 'Bislig City' => 0],
            'BARMM' => ['Marawi City' => 0, 'Lamitan City' => 0],
            'CAR' => ['Baguio City' => 0, 'Tabuk City' => 0],
        ];
    }

    /**
     * Calculate shipping fee based on official region and distance for NCR
     */
    public static function calculate(string $region, float $distance = 0): float
    {
        if ($region === 'NCR') {
            return self::calculateMetroManila($distance);
        }

        $luzonRegions = ['Region I', 'Region II', 'Region III', 'Region IV-A', 'Region V', 'CAR'];
        if (in_array($region, $luzonRegions)) {
            return 150.00;
        }

        return 180.00; // Increased for VisMin
    }

    /**
     * Get the carrier name based on region
     */
    public static function getCarrier(string $region): string
    {
        return $region === 'NCR' ? 'Lalamove' : 'LBC Express';
    }

    private static function calculateMetroManila(float $distance): float
    {
        // Lalamove-style pricing: Base 49 + distance fees
        $base = 49.00;
        if ($distance <= 0) return 60.00; // Default for NCR if distance unknown
        if ($distance <= 5) return $base + ($distance * 6);
        return $base + (5 * 6) + (($distance - 5) * 5);
    }

    public static function getMetroManilaDistances(): array
    {
        return [
            'Pasig City' => 2,
            'Taguig (BGC)' => 5,
            'Makati' => 7,
            'Mandaluyong' => 4,
            'Quezon City' => 12,
            'Manila' => 12,
            'San Juan' => 6,
            'Marikina' => 5,
            'Parañaque' => 18,
            'Las Piñas' => 22,
            'Muntinlupa' => 25,
            'Valenzuela' => 20,
            'Malabon' => 18,
            'Navotas' => 20,
            'Caloocan' => 15,
            'Pateros' => 3,
        ];
    }
}
