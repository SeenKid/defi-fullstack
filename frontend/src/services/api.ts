import axios from 'axios'

const API_URL = import.meta.env.VITE_API_URL || 'https://localhost/api/v1'

const apiClient = axios.create({
  baseURL: API_URL,
  headers: {
    'Content-Type': 'application/json',
  },
})

export interface RouteRequest {
  fromStationId: string
  toStationId: string
  analyticCode: string
}

export interface Route {
  id: string
  fromStationId: string
  toStationId: string
  analyticCode: string
  distanceKm: number
  path: string[]
  createdAt: string
}

export interface AnalyticDistance {
  analyticCode: string
  totalDistanceKm: number
  periodStart?: string
  periodEnd?: string
  group?: string
}

export interface AnalyticDistanceList {
  from?: string
  to?: string
  groupBy: string
  items: AnalyticDistance[]
}

export const routeService = {
  async createRoute(request: RouteRequest): Promise<Route> {
    const response = await apiClient.post<Route>('/routes', request)
    return response.data
  },
}

export const statisticsService = {
  async getAnalyticDistances(
    from?: string,
    to?: string,
    groupBy: string = 'none'
  ): Promise<AnalyticDistanceList> {
    const params: Record<string, string> = { groupBy }
    if (from) params.from = from
    if (to) params.to = to

    const response = await apiClient.get<AnalyticDistanceList>('/stats/distances', { params })
    return response.data
  },
}

