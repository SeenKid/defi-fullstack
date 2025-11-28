<template>
  <v-container>
    <v-row>
      <v-col cols="12">
        <v-card>
          <v-card-title>Statistiques par code analytique</v-card-title>
          <v-card-text>
            <v-form>
              <v-row>
                <v-col cols="12" md="3">
                  <v-text-field
                    v-model="filters.from"
                    type="date"
                    label="Date de début"
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="3">
                  <v-text-field
                    v-model="filters.to"
                    type="date"
                    label="Date de fin"
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="3">
                  <v-select
                    v-model="filters.groupBy"
                    :items="groupByOptions"
                    label="Grouper par"
                  ></v-select>
                </v-col>
                <v-col cols="12" md="3">
                  <v-btn color="primary" @click="loadStatistics" :loading="loading">
                    Charger
                  </v-btn>
                </v-col>
              </v-row>
            </v-form>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>

    <v-row v-if="statistics">
      <v-col cols="12">
        <v-card>
          <v-card-title>Résultats</v-card-title>
          <v-card-text>
            <v-data-table
              :headers="headers"
              :items="statistics.items"
              :loading="loading"
            >
              <template v-slot:item.totalDistanceKm="{ item }">
                {{ item.totalDistanceKm.toFixed(2) }} km
              </template>
            </v-data-table>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>

    <v-row v-if="statistics && statistics.items.length > 0">
      <v-col cols="12">
        <v-card>
          <v-card-title>Graphique</v-card-title>
          <v-card-text>
            <Bar :data="chartData" :options="chartOptions" />
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
import { Bar } from 'vue-chartjs'
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend,
} from 'chart.js'
import { statisticsService, type AnalyticDistanceList } from '../services/api'

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend)

const statistics = ref<AnalyticDistanceList | null>(null)
const loading = ref(false)
const error = ref<string | null>(null)

const filters = ref({
  from: '',
  to: '',
  groupBy: 'none',
})

const groupByOptions = [
  { title: 'Aucun', value: 'none' },
  { title: 'Jour', value: 'day' },
  { title: 'Mois', value: 'month' },
  { title: 'Année', value: 'year' },
]

const headers = [
  { title: 'Code analytique', key: 'analyticCode' },
  { title: 'Distance totale (km)', key: 'totalDistanceKm' },
  { title: 'Période début', key: 'periodStart' },
  { title: 'Période fin', key: 'periodEnd' },
]

const chartData = computed(() => {
  if (!statistics.value || statistics.value.items.length === 0) {
    return {
      labels: [],
      datasets: [],
    }
  }

  return {
    labels: statistics.value.items.map(item => item.analyticCode),
    datasets: [
      {
        label: 'Distance totale (km)',
        backgroundColor: '#1976D2',
        data: statistics.value.items.map(item => item.totalDistanceKm),
      },
    ],
  }
})

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  scales: {
    y: {
      beginAtZero: true,
    },
  },
}

async function loadStatistics() {
  loading.value = true
  error.value = null

  try {
    statistics.value = await statisticsService.getAnalyticDistances(
      filters.value.from || undefined,
      filters.value.to || undefined,
      filters.value.groupBy
    )
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Erreur lors du chargement des statistiques'
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadStatistics()
})
</script>

