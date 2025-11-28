import { createRouter, createWebHistory } from 'vue-router'
import RouteCalculator from '../views/RouteCalculator.vue'
import Statistics from '../views/Statistics.vue'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/',
      name: 'home',
      component: RouteCalculator,
    },
    {
      path: '/stats',
      name: 'statistics',
      component: Statistics,
    },
  ],
})

export default router

