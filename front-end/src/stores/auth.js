import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '../services/api'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)
  const token = ref(localStorage.getItem('auth_token') || null)
  
  const isAuthenticated = computed(() => !!token.value)
  const isStudent = computed(() => user.value?.role === 'student')
  const isTeacher = computed(() => user.value?.role === 'teacher')

  async function register(name, email, password, role) {
    try {
      const response = await api.post('/register', {
        name,
        email,
        password,
        role
      })
      return { success: true, data: response.data }
    } catch (error) {
      return { 
        success: false, 
        error: error.response?.data?.message || 'Registration failed' 
      }
    }
  }

  async function login(email, password) {
    try {
      const response = await api.post('/login', { email, password })
      token.value = response.data.token
      user.value = response.data.user
      localStorage.setItem('auth_token', response.data.token)
      return { success: true }
    } catch (error) {
      return { 
        success: false, 
        error: error.response?.data?.message || 'Login failed' 
      }
    }
  }

  async function logout() {
    try {
      await api.post('/logout')
    } catch (error) {
      console.error('Logout error:', error)
    } finally {
      token.value = null
      user.value = null
      localStorage.removeItem('auth_token')
    }
  }

  async function fetchUser() {
    try {
      const response = await api.get('/user')
      user.value = response.data
      return { success: true }
    } catch (error) {
      // Token might be invalid
      logout()
      return { success: false }
    }
  }

  return {
    user,
    token,
    isAuthenticated,
    isStudent,
    isTeacher,
    register,
    login,
    logout,
    fetchUser
  }
})