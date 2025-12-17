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

        <div class="bg-gradient-to-br from-forest-50 to-sage-50 rounded-2xl p-8 border-2 border-forest-200 mb-6">
          <div class="text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-forest-100 rounded-full mb-4">
              <svg class="w-10 h-10 text-forest-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
              </svg>
            </div>
            <h2 class="text-2xl font-bold text-earth-800 mb-2">Ready to Practice?</h2>
            <p class="text-earth-600 mb-6">Sharpen your math skills with focused practice sessions</p>
            <button 
              @click="goToPractice"
              class="bg-forest-500 hover:bg-forest-600 active:bg-forest-700 text-white px-10 py-4 rounded-xl text-xl font-bold transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1 active:translate-y-0"
            >
              🚀 Start Practice
            </button>
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
</script>