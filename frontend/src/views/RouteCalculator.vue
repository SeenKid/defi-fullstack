<template>
  <v-container>
    <v-row>
      <v-col cols="12">
        <v-card>
          <v-card-title>Calculer un trajet</v-card-title>
          <v-card-text>
            <v-form @submit.prevent="calculateRoute">
              <v-row>
                <v-col cols="12" md="4">
                  <v-select
                    v-model="form.fromStationId"
                    :items="stationItems"
                    item-title="longName"
                    item-value="shortName"
                    label="Station de départ"
                    required
                  ></v-select>
                </v-col>
                <v-col cols="12" md="4">
                  <v-select
                    v-model="form.toStationId"
                    :items="stationItems"
                    item-title="longName"
                    item-value="shortName"
                    label="Station d'arrivée"
                    required
                  ></v-select>
                </v-col>
                <v-col cols="12" md="4">
                  <v-text-field
                    v-model="form.analyticCode"
                    label="Code analytique"
                    required
                  ></v-text-field>
                </v-col>
              </v-row>
              <v-btn type="submit" color="primary" :loading="loading">
                Calculer le trajet
              </v-btn>
            </v-form>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>

    <v-row v-if="route">
      <v-col cols="12">
        <v-card>
          <v-card-title>Résultat du trajet</v-card-title>
          <v-card-text>
            <v-alert type="success" class="mb-4">
              Distance totale: <strong>{{ route.distanceKm }} km</strong>
            </v-alert>
            <div>
              <h3>Chemin parcouru:</h3>
              <v-chip-group>
                <v-chip
                  v-for="(station, index) in route.path"
                  :key="index"
                  :color="index === 0 || index === route.path.length - 1 ? 'primary' : 'default'"
                >
                  {{ getStationName(station) }}
                </v-chip>
              </v-chip-group>
            </div>
            <div class="mt-4">
              <p><strong>Code analytique:</strong> {{ route.analyticCode }}</p>
              <p><strong>Date de création:</strong> {{ formatDate(route.createdAt) }}</p>
            </div>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>

    <v-row v-if="error">
      <v-col cols="12">
        <v-alert type="error">{{ error }}</v-alert>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { routeService, type Route } from '../services/api'
import { useStationsStore } from '../stores/stations'

const stationsStore = useStationsStore()

const form = ref({
  fromStationId: '',
  toStationId: '',
  analyticCode: '',
})

const route = ref<Route | null>(null)
const loading = ref(false)
const error = ref<string | null>(null)

const stationItems = computed(() => stationsStore.stations)

function getStationName(shortName: string): string {
  const station = stationsStore.getStationByShortName(shortName)
  return station ? station.longName : shortName
}

function formatDate(dateString: string): string {
  return new Date(dateString).toLocaleString('fr-FR')
}

async function calculateRoute() {
  loading.value = true
  error.value = null
  route.value = null

  try {
    route.value = await routeService.createRoute({
      fromStationId: form.value.fromStationId,
      toStationId: form.value.toStationId,
      analyticCode: form.value.analyticCode,
    })
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Erreur lors du calcul du trajet'
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  stationsStore.loadStations()
})
</script>

