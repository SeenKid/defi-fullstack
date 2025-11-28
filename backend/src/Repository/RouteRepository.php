<?php

namespace App\Repository;

use App\Entity\Route;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RouteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Route::class);
    }

    public function getAnalyticDistances(?\DateTimeInterface $from = null, ?\DateTimeInterface $to = null, ?string $groupBy = null): array
    {
        $qb = $this->createQueryBuilder('r')
            ->select('r.analyticCode, SUM(r.distanceKm) as totalDistanceKm')
            ->groupBy('r.analyticCode');

        if ($from !== null) {
            $qb->andWhere('r.createdAt >= :from')
                ->setParameter('from', $from);
        }

        if ($to !== null) {
            $qb->andWhere('r.createdAt <= :to')
                ->setParameter('to', $to);
        }

        $results = $qb->getQuery()->getResult();

        $items = [];
        foreach ($results as $result) {
            $item = [
                'analyticCode' => $result['analyticCode'],
                'totalDistanceKm' => (float) $result['totalDistanceKm'],
            ];

            if ($groupBy !== null && $groupBy !== 'none') {
                // For simplicity, we'll use the period range
                $item['periodStart'] = $from ? $from->format('Y-m-d') : null;
                $item['periodEnd'] = $to ? $to->format('Y-m-d') : null;
                $item['group'] = $this->getGroupValue($groupBy, $from ?? new \DateTime());
            }

            $items[] = $item;
        }

        return $items;
    }

    private function getGroupValue(string $groupBy, \DateTimeInterface $date): string
    {
        return match ($groupBy) {
            'day' => $date->format('Y-m-d'),
            'month' => $date->format('Y-m'),
            'year' => $date->format('Y'),
            default => '',
        };
    }
}

