<?php

namespace App\Service;

class GraphBuilder
{
    private array $graph = [];
    private StationLoader $stationLoader;

    public function __construct(StationLoader $stationLoader)
    {
        $this->stationLoader = $stationLoader;
        $this->buildGraph();
    }

    private function buildGraph(): void
    {
        $distancesPath = __DIR__ . '/../../data/distances.json';
        if (!file_exists($distancesPath)) {
            $distancesPath = __DIR__ . '/../../../distances.json';
        }

        $data = json_decode(file_get_contents($distancesPath), true);

        foreach ($data as $line) {
            foreach ($line['distances'] as $distance) {
                $parent = $distance['parent'];
                $child = $distance['child'];
                $dist = $distance['distance'];

                // Bidirectional graph
                if (!isset($this->graph[$parent])) {
                    $this->graph[$parent] = [];
                }
                if (!isset($this->graph[$child])) {
                    $this->graph[$child] = [];
                }

                $this->graph[$parent][$child] = $dist;
                $this->graph[$child][$parent] = $dist;
            }
        }
    }

    public function getGraph(): array
    {
        return $this->graph;
    }

    public function getNeighbors(string $station): array
    {
        return $this->graph[$station] ?? [];
    }
}

