import { describe, it, expect, vi, beforeEach } from 'vitest'
import axios from 'axios'
import { routeService, statisticsService } from '../api'

vi.mock('axios')
const mockedAxios = axios as any

describe('API Services', () => {
  beforeEach(() => {
    vi.clearAllMocks()
  })

  describe('routeService', () => {
    it('should create a route', async () => {
      const mockRoute = {
        id: '123',
        fromStationId: 'MX',
        toStationId: 'ZW',
        analyticCode: 'ANA-123',
        distanceKm: 50.5,
        path: ['MX', 'CGE', 'ZW'],
        createdAt: '2025-01-01T00:00:00Z',
      }

      mockedAxios.create.mockReturnValue({
        post: vi.fn().mockResolvedValue({ data: mockRoute }),
      })

      const result = await routeService.createRoute({
        fromStationId: 'MX',
        toStationId: 'ZW',
        analyticCode: 'ANA-123',
      })

      expect(result).toEqual(mockRoute)
    })
  })

  describe('statisticsService', () => {
    it('should get analytic distances', async () => {
      const mockStats = {
        from: '2025-01-01',
        to: '2025-01-31',
        groupBy: 'none',
        items: [
          {
            analyticCode: 'ANA-123',
            totalDistanceKm: 100.5,
          },
        ],
      }

      mockedAxios.create.mockReturnValue({
        get: vi.fn().mockResolvedValue({ data: mockStats }),
      })

      const result = await statisticsService.getAnalyticDistances()

      expect(result).toEqual(mockStats)
    })
  })
})

