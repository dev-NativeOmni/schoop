<?php

namespace App\Services\Boarding;

use App\Models\BoardingBed;
use App\Models\BoardingDormitory;
use App\Models\BoardingRoom;

class BoardingOccupancyService
{
    /**
     * Get aggregated occupancy statistics for multiple dormitories in a single batch.
     */
    public function getDormitoriesStats(iterable $dormitories): array
    {
        $dormitoryIds = [];
        foreach ($dormitories as $dormitory) {
            if ($dormitory && isset($dormitory->id)) {
                $dormitoryIds[] = $dormitory->id;
            }
        }
        $dormitoryIds = array_unique($dormitoryIds);
        if (empty($dormitoryIds)) {
            return [];
        }

        $rooms = BoardingRoom::whereIn('boarding_dormitory_id', $dormitoryIds)
            ->where('is_active', true)
            ->get();

        $roomIds = $rooms->pluck('id')->toArray();

        $bedStats = [];
        if (! empty($roomIds)) {
            $bedStats = BoardingBed::whereIn('boarding_room_id', $roomIds)
                ->selectRaw('boarding_room_id, status, count(*) as count')
                ->groupBy('boarding_room_id', 'status')
                ->get()
                ->groupBy('boarding_room_id');
        }

        $stats = [];
        foreach ($dormitoryIds as $dormitoryId) {
            $stats[$dormitoryId] = [
                'capacity' => 0,
                'occupied' => 0,
                'available' => 0,
                'maintenance' => 0,
                'occupancy_rate' => 0,
                'rooms_count' => 0,
            ];
        }

        $dormitoryRooms = $rooms->groupBy('boarding_dormitory_id');

        foreach ($dormitoryRooms as $dormId => $roomsForDorm) {
            $totalCapacity = $roomsForDorm->sum('capacity');
            $occupiedCount = 0;
            $availableCount = 0;
            $maintenanceCount = 0;

            foreach ($roomsForDorm as $room) {
                if (isset($bedStats[$room->id])) {
                    foreach ($bedStats[$room->id] as $stat) {
                        if ($stat->status === 'occupied') {
                            $occupiedCount += $stat->count;
                        } elseif ($stat->status === 'available') {
                            $availableCount += $stat->count;
                        } elseif ($stat->status === 'maintenance') {
                            $maintenanceCount += $stat->count;
                        }
                    }
                }
            }

            $occupancyRate = $totalCapacity > 0 ? round(($occupiedCount / $totalCapacity) * 100, 1) : 0;

            $stats[$dormId] = [
                'capacity' => $totalCapacity,
                'occupied' => $occupiedCount,
                'available' => $availableCount,
                'maintenance' => $maintenanceCount,
                'occupancy_rate' => $occupancyRate,
                'rooms_count' => $roomsForDorm->count(),
            ];
        }

        return $stats;
    }

    /**
     * Get occupancy statistics for a specific dormitory.
     */
    public function getDormitoryStats(BoardingDormitory $dormitory): array
    {
        $stats = $this->getDormitoriesStats([$dormitory]);

        return $stats[$dormitory->id] ?? [
            'capacity' => 0,
            'occupied' => 0,
            'available' => 0,
            'maintenance' => 0,
            'occupancy_rate' => 0,
            'rooms_count' => 0,
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

        $totalCapacity = 0;
        $totalOccupied = 0;
        $totalAvailable = 0;
        $totalMaintenance = 0;

        $allStats = $this->getDormitoriesStats($dormitories);

        foreach ($dormitories as $dormitory) {
            $stats = $allStats[$dormitory->id] ?? [
                'capacity' => 0,
                'occupied' => 0,
                'available' => 0,
                'maintenance' => 0,
            ];
            $totalCapacity += $stats['capacity'];
            $totalOccupied += $stats['occupied'];
            $totalAvailable += $stats['available'];
            $totalMaintenance += $stats['maintenance'];
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
