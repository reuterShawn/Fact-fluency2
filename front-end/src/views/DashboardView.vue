<template>
  <div class="min-h-screen bg-gradient-to-br from-sage-100 via-earth-50 to-cream-50 py-8 px-4">
    <div class="w-full max-w-4xl mx-auto">
      <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-10">
        <div class="flex justify-between items-center mb-8">
          <div>
            <h1 class="text-4xl font-bold text-earth-800 mb-2">Dashboard</h1>
            <p class="text-earth-600 text-lg">Welcome back, <span class="font-semibold text-forest-600">{{ user?.name }}</span>!</p>
          </div>
          <button 
            @click="logout" 
            class="px-6 py-3 bg-earth-500 hover:bg-earth-600 text-white rounded-xl transition-all shadow-md hover:shadow-lg font-medium"
          >
            Logout
          </button>
        </div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
  <!-- Regular Practice -->
  <div class="bg-gradient-to-br from-forest-50 to-sage-50 rounded-2xl p-6 border-2 border-forest-200">
    <div class="text-center">
      <div class="text-4xl mb-3">📚</div>
      <h3 class="text-xl font-bold text-earth-800 mb-2">New Practice</h3>
      <p class="text-earth-600 text-sm mb-4">Start a fresh practice session</p>
      <button 
        @click="goToPractice"
        class="bg-forest-500 hover:bg-forest-600 text-white px-6 py-3 rounded-xl font-semibold transition-all shadow-lg w-full"
      >
        Start Practice
      </button>
    </div>
  </div>

  <!-- Missed Problems Practice -->
  <div class="bg-gradient-to-br from-sky-50 to-sage-50 rounded-2xl p-6 border-2 border-sky-200">
    <div class="text-center">
      <div class="text-4xl mb-3">🎯</div>
      <h3 class="text-xl font-bold text-earth-800 mb-2">Missed Problems</h3>
      <p class="text-earth-600 text-sm mb-4">Practice problems you got wrong</p>
      <button 
        @click="goToPracticeMissed"
        class="bg-sky-500 hover:bg-sky-600 text-white px-6 py-3 rounded-xl font-semibold transition-all shadow-lg w-full"
      >
        Practice Missed
      </button>
    </div>
  </div>
</div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div class="bg-sky-50 border-2 border-sky-200 rounded-xl p-6 text-center">
            <p class="text-sky-600 text-sm font-bold mb-2">TOTAL SESSIONS</p>
            <p class="text-4xl font-bold text-sky-700">0</p>
          </div>
          <div class="bg-forest-50 border-2 border-forest-200 rounded-xl p-6 text-center">
            <p class="text-forest-600 text-sm font-bold mb-2">ACCURACY</p>
            <p class="text-4xl font-bold text-forest-700">-%</p>
          </div>
          <div class="bg-earth-50 border-2 border-earth-200 rounded-xl p-6 text-center">
            <p class="text-earth-600 text-sm font-bold mb-2">PROBLEMS SOLVED</p>
            <p class="text-4xl font-bold text-earth-700">0</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const user = computed(() => authStore.user)

function goToPractice() {
  router.push('/practice')
}

function logout() {
  authStore.logout()
  router.push('/login')
}

function goToPracticeMissed() {
  router.push({ name: 'Practice', query: { mode: 'missed' } })
}
</script>