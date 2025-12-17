<template>
  <router-view />
</template>

<script setup>
import { onMounted } from 'vue'
import { useAuthStore } from './stores/auth'

const authStore = useAuthStore()

onMounted(() => {
  // Try to fetch user if token exists
  if (authStore.token) {
    authStore.fetchUser()
  }
})
</script>