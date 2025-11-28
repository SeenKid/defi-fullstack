<?php

namespace App\Tests\Service;

use App\Exception\RouteNotFoundException;
use App\Service\GraphBuilder;
use App\Service\RoutingService;
use App\Service\StationLoader;
use PHPUnit\Framework\TestCase;

class RoutingServiceTest extends TestCase
{
    private RoutingService $routingService;

    protected function setUp(): void
    {
        $stationLoader = new StationLoader();
        $graphBuilder = new GraphBuilder($stationLoader);
        $this->routingService = new RoutingService($graphBuilder, $stationLoader);
    }

    public function testCalculateRouteBetweenAdjacentStations(): void
    {
        $result = $this->routingService->calculateRoute('MX', 'CGE');
        
        $this->assertArrayHasKey('path', $result);
        $this->assertArrayHasKey('distance', $result);
        $this->assertContains('MX', $result['path']);
        $this->assertContains('CGE', $result['path']);
        $this->assertGreaterThan(0, $result['distance']);
    }

    public function testCalculateRouteBetweenDistantStations(): void
    {
        $result = $this->routingService->calculateRoute('MX', 'ZW');
        
        $this->assertArrayHasKey('path', $result);
        $this->assertArrayHasKey('distance', $result);
        $this->assertEquals('MX', $result['path'][0]);
        $this->assertEquals('ZW', $result['path'][count($result['path']) - 1]);
        $this->assertGreaterThan(0, $result['distance']);
    }

    public function testCalculateRouteSameStation(): void
    {
        $result = $this->routingService->calculateRoute('MX', 'MX');
        
        $this->assertEquals(['MX'], $result['path']);
        $this->assertEquals(0.0, $result['distance']);
    }

    public function testCalculateRouteThrowsExceptionForUnknownFromStation(): void
    {
        $this->expectException(RouteNotFoundException::class);
        $this->routingService->calculateRoute('UNKNOWN', 'MX');
    }

    public function testCalculateRouteThrowsExceptionForUnknownToStation(): void
    {
        $this->expectException(RouteNotFoundException::class);
        $this->routingService->calculateRoute('MX', 'UNKNOWN');
    }
}

