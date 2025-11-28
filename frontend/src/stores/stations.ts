import { defineStore } from 'pinia'
import { ref } from 'vue'

export interface Station {
  id: number
  shortName: string
  longName: string
}

export const useStationsStore = defineStore('stations', () => {
  const stations = ref<Station[]>([])
  const loading = ref(false)

  async function loadStations() {
    if (stations.value.length > 0) return

    loading.value = true
    try {
      // In a real app, this would come from an API endpoint
      // For now, we'll use a mock or load from a static file
      const response = await fetch('/stations.json')
      stations.value = await response.json()
    } catch (error) {
      console.error('Failed to load stations:', error)
    } finally {
      loading.value = false
    }
  }

  function getStationByShortName(shortName: string): Station | undefined {
    return stations.value.find(s => s.shortName === shortName)
  }

  return {
    stations,
    loading,
    loadStations,
    getStationByShortName,
  }
})

