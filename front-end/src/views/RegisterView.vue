<template>
  <div class="min-h-screen bg-gradient-to-br from-sage-50 via-earth-50 to-sky-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md border-t-4 border-sky-500">
      <div class="text-center mb-6">
        <div class="inline-block p-3 bg-sky-100 rounded-full mb-3">
          <svg class="w-12 h-12 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
          </svg>
        </div>
        <h1 class="text-3xl font-bold text-earth-800 mb-2">Create Account</h1>
        <p class="text-earth-600">Start your math fluency journey</p>
      </div>

      <form @submit.prevent="handleRegister" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-earth-700 mb-1">Name</label>
          <input 
            v-model="name"
            type="text" 
            required
            class="w-full px-4 py-3 border-2 border-sage-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent transition-all"
            placeholder="John Doe"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-earth-700 mb-1">Email</label>
          <input 
            v-model="email"
            type="email" 
            required
            class="w-full px-4 py-3 border-2 border-sage-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent transition-all"
            placeholder="you@example.com"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-earth-700 mb-1">Password</label>
          <input 
            v-model="password"
            type="password" 
            required
            minlength="8"
            class="w-full px-4 py-3 border-2 border-sage-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent transition-all"
            placeholder="••••••••"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-earth-700 mb-1">I am a...</label>
          <select 
            v-model="role"
            class="w-full px-4 py-3 border-2 border-sage-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent transition-all"
          >
            <option value="student">Student</option>
            <option value="teacher">Teacher</option>
          </select>
        </div>

        <div v-if="error" class="bg-red-50 border-l-4 border-red-500 p-3 rounded">
          <p class="text-red-700 text-sm">{{ error }}</p>
        </div>

        <div v-if="success" class="bg-green-50 border-l-4 border-forest-500 p-3 rounded">
          <p class="text-forest-700 text-sm">Registration successful! Redirecting to login...</p>
        </div>

        <button 
          type="submit"
          :disabled="loading"
          class="w-full bg-gradient-to-r from-sky-500 to-sky-600 text-white py-3 rounded-lg font-semibold hover:from-sky-600 hover:to-sky-700 transition-all shadow-md hover:shadow-lg disabled:bg-sage-300 disabled:cursor-not-allowed"
        >
          {{ loading ? 'Creating account...' : 'Create Account' }}
        </button>
      </form>

      <p class="text-center text-earth-600 mt-6">
        Already have an account? 
        <router-link to="/login" class="text-sky-600 font-semibold hover:text-sky-700 hover:underline">
          Sign in
        </router-link>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const name = ref('')
const email = ref('')
const password = ref('')
const role = ref('student')
const error = ref('')
const success = ref(false)
const loading = ref(false)

async function handleRegister() {
  loading.value = true
  error.value = ''
  success.value = false

  const result = await authStore.register(
    name.value, 
    email.value, 
    password.value, 
    role.value
  )
  
  if (result.success) {
    success.value = true
    setTimeout(() => {
      router.push('/login')
    }, 1500)
  } else {
    error.value = result.error
  }
  
  loading.value = false
}
</script>