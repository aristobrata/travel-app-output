<?php
namespace App\Services;

class SeatService
{
    public function getCols(string $vehicleType): int
    {
        return match ($vehicleType) {
            'car'     => 2,
            'minibus' => 3,
            default   => 4,
        };
    }
}
