<?php

namespace App\Service;

use App\Exception\RouteNotFoundException;

class RoutingService
{
    private GraphBuilder $graphBuilder;
    private StationLoader $stationLoader;

    public function __construct(GraphBuilder $graphBuilder, StationLoader $stationLoader)
    {
        $this->graphBuilder = $graphBuilder;
        $this->stationLoader = $stationLoader;
    }

    /**
     * Calculate shortest path using Dijkstra's algorithm
     */
    public function calculateRoute(string $fromStationId, string $toStationId): array
    {
        if (!$this->stationLoader->stationExists($fromStationId)) {
            throw new RouteNotFoundException("Station de départ inconnue: {$fromStationId}");
        }

        if (!$this->stationLoader->stationExists($toStationId)) {
            throw new RouteNotFoundException("Station d'arrivée inconnue: {$toStationId}");
        }

        if ($fromStationId === $toStationId) {
            return [
                'path' => [$fromStationId],
                'distance' => 0.0
            ];
        }

        $graph = $this->graphBuilder->getGraph();
        
        // Dijkstra's algorithm
        $distances = [];
        $previous = [];
        $unvisited = [];
        
        foreach (array_keys($graph) as $station) {
            $distances[$station] = INF;
            $previous[$station] = null;
            $unvisited[$station] = true;
        }
        
        $distances[$fromStationId] = 0;
        
        while (!empty($unvisited)) {
            // Find unvisited node with smallest distance
            $current = null;
            $minDistance = INF;
            
            foreach ($unvisited as $station => $value) {
                if ($distances[$station] < $minDistance) {
                    $minDistance = $distances[$station];
                    $current = $station;
                }
            }
            
            if ($current === null || $minDistance === INF) {
                throw new RouteNotFoundException("Aucun chemin trouvé entre {$fromStationId} et {$toStationId}");
            }
            
            if ($current === $toStationId) {
                break;
            }
            
            unset($unvisited[$current]);
            
            // Update distances to neighbors
            foreach ($graph[$current] as $neighbor => $edgeDistance) {
                if (isset($unvisited[$neighbor])) {
                    $alt = $distances[$current] + $edgeDistance;
                    if ($alt < $distances[$neighbor]) {
                        $distances[$neighbor] = $alt;
                        $previous[$neighbor] = $current;
                    }
                }
            }
        }
        
        // Reconstruct path
        $path = [];
        $current = $toStationId;
        
        while ($current !== null) {
            array_unshift($path, $current);
            $current = $previous[$current];
        }
        
        if ($path[0] !== $fromStationId) {
            throw new RouteNotFoundException("Aucun chemin trouvé entre {$fromStationId} et {$toStationId}");
        }
        
        return [
            'path' => $path,
            'distance' => round($distances[$toStationId], 2)
        ];
    }
}

