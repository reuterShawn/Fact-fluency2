<template>
  <div class="min-h-screen bg-gradient-to-br from-sage-100 via-earth-50 to-cream-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl p-10 w-full max-w-md">
      <!-- Icon Header -->
      <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-forest-100 rounded-full mb-4">
          <svg class="w-10 h-10 text-forest-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
          </svg>
        </div>
        <h1 class="text-3xl font-bold text-earth-800">Welcome Back</h1>
        <p class="text-earth-600 mt-2">Sign in to practice your math skills</p>
      </div>

      <form @submit.prevent="handleLogin" class="space-y-5">
   <!-- Username Input -->
<div>
  <label class="block text-sm font-semibold text-earth-700 mb-2">Username</label>
  <input 
    v-model="username"
    type="text" 
    required
    class="w-full px-4 py-3 bg-sage-50 border-2 border-sage-200 rounded-xl focus:border-forest-500 focus:ring-4 focus:ring-forest-100 focus:bg-white transition-all outline-none text-earth-800"
    placeholder="Enter your username"
  />
</div>

        <!-- Password Input -->
        <div>
          <label class="block text-sm font-semibold text-earth-700 mb-2">Password</label>
          <input 
            v-model="password"
            type="password" 
            required
            class="w-full px-4 py-3 bg-sage-50 border-2 border-sage-200 rounded-xl focus:border-forest-500 focus:ring-4 focus:ring-forest-100 focus:bg-white transition-all outline-none text-earth-800"
            placeholder="••••••••"
          />
        </div>

        <!-- Error Message -->
        <div v-if="error" class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
          <div class="flex items-center">
            <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
            <p class="text-red-700 text-sm font-medium">{{ error }}</p>
          </div>
        </div>

        <!-- Primary Action Button -->
        <button 
          type="submit"
          :disabled="loading"
          class="w-full bg-forest-500 hover:bg-forest-600 active:bg-forest-700 text-white font-bold py-4 rounded-xl transition-all shadow-lg hover:shadow-xl disabled:bg-sage-300 disabled:cursor-not-allowed disabled:shadow-none transform hover:-translate-y-0.5 active:translate-y-0"
        >
          <span v-if="!loading">Sign In</span>
          <span v-else class="flex items-center justify-center">
            <svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Signing in...
          </span>
        </button>
      </form>

      <!-- Secondary Link -->
      <div class="mt-8 text-center">
        <p class="text-earth-600">
          New here? 
          <router-link to="/register" class="text-sky-600 hover:text-sky-700 font-semibold hover:underline ml-1">
            Create an account
          </router-link>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const username = ref('')
const password = ref('')
const error = ref('')
const loading = ref(false)

async function handleLogin() {
  loading.value = true
  error.value = ''

const result = await authStore.login(username.value, password.value)  
  if (result.success) {
    router.push('/dashboard')
  } else {
    error.value = result.error
  }
  
  loading.value = false
}
</script>