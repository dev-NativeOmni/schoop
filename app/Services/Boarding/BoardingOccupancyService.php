<?php

namespace App\Services\Boarding;

use App\Models\BoardingDormitory;
use App\Models\BoardingRoom;
use App\Models\BoardingBed;

class BoardingOccupancyService
{
    /**
     * Get occupancy statistics for a specific dormitory.
     */
    public function getDormitoryStats(BoardingDormitory $dormitory): array
    {
        $rooms = $dormitory->rooms()->where('is_active', true)->get();
        $roomIds = $rooms->pluck('id');

        $totalCapacity = $rooms->sum('capacity');
        
        $occupiedCount = BoardingBed::query()
            ->whereIn('boarding_room_id', $roomIds)
            ->where('status', 'occupied')
            ->count();

        $availableCount = BoardingBed::query()
            ->whereIn('boarding_room_id', $roomIds)
            ->where('status', 'available')
            ->count();

        $maintenanceCount = BoardingBed::query()
            ->whereIn('boarding_room_id', $roomIds)
            ->where('status', 'maintenance')
            ->count();

        $occupancyRate = $totalCapacity > 0 ? round(($occupiedCount / $totalCapacity) * 100, 1) : 0;

        return [
            'capacity' => $totalCapacity,
            'occupied' => $occupiedCount,
            'available' => $availableCount,
            'maintenance' => $maintenanceCount,
            'occupancy_rate' => $occupancyRate,
            'rooms_count' => $rooms->count(),
        ];
    }

    /**
     * Get occupancy statistics for a specific room.
     */
    public function getRoomStats(BoardingRoom $room): array
    {
        $beds = $room->beds;
        
        $capacity = $room->capacity;
        $occupied = $beds->where('status', 'occupied')->count();
        $available = $beds->where('status', 'available')->count();
        $maintenance = $beds->where('status', 'maintenance')->count();
        $inactive = $beds->where('status', 'inactive')->count();

        $occupancyRate = $capacity > 0 ? round(($occupied / $capacity) * 100, 1) : 0;

        return [
            'capacity' => $capacity,
            'occupied' => $occupied,
            'available' => $available,
            'maintenance' => $maintenance,
            'inactive' => $inactive,
            'occupancy_rate' => $occupancyRate,
            'beds_count' => $beds->count(),
        ];
    }

    /**
     * Get aggregated boarding statistics for a school.
     */
    public function getSchoolStats(?int $schoolId): array
    {
        $query = BoardingDormitory::query()->where('is_active', true);
        if ($schoolId) {
            $query->where('school_id', $schoolId);
        }
        $dormitories = $query->get();
        $dormitoryIds = $dormitories->pluck('id');

        if ($dormitoryIds->isEmpty()) {
            return [
                'dormitories_count' => 0,
                'capacity' => 0,
                'occupied' => 0,
                'available' => 0,
                'maintenance' => 0,
                'occupancy_rate' => 0,
            ];
        }

        // Optimization: Batch fetch active rooms for all dormitories
        $rooms = \App\Models\BoardingRoom::query()
            ->whereIn('boarding_dormitory_id', $dormitoryIds)
            ->where('is_active', true)
            ->get();

        $totalCapacity = $rooms->sum('capacity');
        $roomIds = $rooms->pluck('id');

        if ($roomIds->isEmpty()) {
            $totalOccupied = 0;
            $totalAvailable = 0;
            $totalMaintenance = 0;
        } else {
            // Optimization: Batch count bed statuses using group by instead of N+1 queries
            $bedCounts = \App\Models\BoardingBed::query()
                ->select('status', \DB::raw('count(*) as count'))
                ->whereIn('boarding_room_id', $roomIds)
                ->whereIn('status', ['occupied', 'available', 'maintenance'])
                ->groupBy('status')
                ->pluck('count', 'status');

            $totalOccupied = (int) ($bedCounts['occupied'] ?? 0);
            $totalAvailable = (int) ($bedCounts['available'] ?? 0);
            $totalMaintenance = (int) ($bedCounts['maintenance'] ?? 0);
        }

        $occupancyRate = $totalCapacity > 0 ? round(($totalOccupied / $totalCapacity) * 100, 1) : 0;

        return [
            'dormitories_count' => $dormitories->count(),
            'capacity' => $totalCapacity,
            'occupied' => $totalOccupied,
            'available' => $totalAvailable,
            'maintenance' => $totalMaintenance,
            'occupancy_rate' => $occupancyRate,
        ];
    }
}
