<template>
  <v-app>
    <v-app-bar color="primary" prominent>
      <v-app-bar-title>🚆 Train Routing & Analytics</v-app-bar-title>
      <v-spacer></v-spacer>
      <v-btn icon="mdi-routes" to="/" variant="text"></v-btn>
      <v-btn icon="mdi-chart-line" to="/stats" variant="text"></v-btn>
    </v-app-bar>

    <v-main>
      <router-view />
    </v-main>
  </v-app>
</template>

<script setup lang="ts">
</script>

