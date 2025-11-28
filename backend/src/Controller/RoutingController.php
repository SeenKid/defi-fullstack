<?php

namespace App\Controller;

use App\Entity\Route;
use App\Exception\RouteNotFoundException;
use App\Repository\RouteRepository;
use App\Service\RoutingService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route as RouteAttribute;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[RouteAttribute('/api/v1', name: 'api_')]
class RoutingController extends AbstractController
{
    public function __construct(
        private RoutingService $routingService,
        private EntityManagerInterface $entityManager,
        private RouteRepository $routeRepository
    ) {
    }

    #[RouteAttribute('/routes', name: 'create_route', methods: ['POST'])]
    public function createRoute(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return new JsonResponse([
                'message' => 'Invalid JSON',
                'code' => 'INVALID_JSON'
            ], Response::HTTP_BAD_REQUEST);
        }

        $fromStationId = $data['fromStationId'] ?? null;
        $toStationId = $data['toStationId'] ?? null;
        $analyticCode = $data['analyticCode'] ?? null;

        if (!$fromStationId || !$toStationId || !$analyticCode) {
            return new JsonResponse([
                'message' => 'Missing required fields: fromStationId, toStationId, analyticCode',
                'code' => 'MISSING_FIELDS'
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $result = $this->routingService->calculateRoute($fromStationId, $toStationId);

            $route = new Route();
            $route->setId((string) Uuid::v4());
            $route->setFromStationId($fromStationId);
            $route->setToStationId($toStationId);
            $route->setAnalyticCode($analyticCode);
            $route->setDistanceKm($result['distance']);
            $route->setPath($result['path']);

            $this->entityManager->persist($route);
            $this->entityManager->flush();

            return new JsonResponse($route->toArray(), Response::HTTP_CREATED);
        } catch (RouteNotFoundException $e) {
            return new JsonResponse([
                'message' => $e->getMessage(),
                'code' => 'ROUTE_NOT_FOUND',
                'details' => []
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    #[RouteAttribute('/routes', name: 'routes_method_not_allowed', methods: ['GET'])]
    public function routesMethodNotAllowed(): JsonResponse
    {
        return new JsonResponse([
            'message' => 'Method not allowed. Use POST to create a route.',
            'code' => 'METHOD_NOT_ALLOWED'
        ], Response::HTTP_METHOD_NOT_ALLOWED);
    }

    #[RouteAttribute('/stats/distances', name: 'get_analytic_distances', methods: ['GET'])]
    public function getAnalyticDistances(Request $request): JsonResponse
    {
        $from = $request->query->get('from');
        $to = $request->query->get('to');
        $groupBy = $request->query->get('groupBy', 'none');

        $fromDate = $from ? new \DateTime($from) : null;
        $toDate = $to ? new \DateTime($to) : null;

        if ($fromDate && $toDate && $fromDate > $toDate) {
            return new JsonResponse([
                'message' => 'Date de début doit être antérieure à la date de fin',
                'code' => 'INVALID_DATE_RANGE'
            ], Response::HTTP_BAD_REQUEST);
        }

        $items = $this->routeRepository->getAnalyticDistances($fromDate, $toDate, $groupBy);

        return new JsonResponse([
            'from' => $from,
            'to' => $to,
            'groupBy' => $groupBy,
            'items' => $items
        ], Response::HTTP_OK);
    }
}

