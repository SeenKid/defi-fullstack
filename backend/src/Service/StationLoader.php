<?php

namespace App\Service;

class StationLoader
{
    private array $stations = [];
    private array $stationMap = [];

    public function __construct()
    {
        $this->loadStations();
    }

    private function loadStations(): void
    {
        $stationsPath = __DIR__ . '/../../data/stations.json';
        if (!file_exists($stationsPath)) {
            $stationsPath = __DIR__ . '/../../../stations.json';
        }

        $data = json_decode(file_get_contents($stationsPath), true);
        
        foreach ($data as $station) {
            $this->stations[$station['shortName']] = $station;
            $this->stationMap[$station['shortName']] = $station['longName'];
        }
    }

    public function getStation(string $shortName): ?array
    {
        return $this->stations[$shortName] ?? null;
    }

    public function getAllStations(): array
    {
        return $this->stations;
    }

    public function stationExists(string $shortName): bool
    {
        return isset($this->stations[$shortName]);
    }

    public function getStationMap(): array
    {
        return $this->stationMap;
    }
}

