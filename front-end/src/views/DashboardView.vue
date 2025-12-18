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
                :disabled="!hasMissedProblems"
                :class="[
                  'px-6 py-3 rounded-xl font-semibold transition-all shadow-lg w-full',
                  hasMissedProblems 
                    ? 'bg-sky-500 hover:bg-sky-600 text-white' 
                    : 'bg-gray-300 text-gray-500 cursor-not-allowed'
                ]"
              >
                {{ hasMissedProblems ? 'Practice Missed' : 'No Missed Problems Yet' }}
              </button>
            </div>
          </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
          <div class="bg-sky-50 border-2 border-sky-200 rounded-xl p-6 text-center">
            <p class="text-sky-600 text-sm font-bold mb-2">TOTAL SESSIONS</p>
            <p class="text-4xl font-bold text-sky-700">{{ stats.total_sessions }}</p>
          </div>
          <div class="bg-forest-50 border-2 border-forest-200 rounded-xl p-6 text-center">
            <p class="text-forest-600 text-sm font-bold mb-2">ACCURACY</p>
            <p class="text-4xl font-bold text-forest-700">{{ stats.average_accuracy }}%</p>
          </div>
          <div class="bg-earth-50 border-2 border-earth-200 rounded-xl p-6 text-center">
            <p class="text-earth-600 text-sm font-bold mb-2">PROBLEMS SOLVED</p>
            <p class="text-4xl font-bold text-earth-700">{{ stats.total_problems }}</p>
          </div>
        </div>

        <!-- Recent Sessions -->
        <div v-if="recentSessions.length > 0" class="bg-sage-50 rounded-2xl p-6 border-2 border-sage-200">
          <h3 class="text-xl font-bold text-earth-800 mb-4">Recent Sessions</h3>
          <div class="space-y-3">
            <div 
              v-for="session in recentSessions" 
              :key="session.id"
              class="bg-white rounded-xl p-4 flex justify-between items-center border border-sage-200"
            >
              <div>
                <p class="font-semibold text-earth-800">
                  {{ formatDate(session.created_at) }}
                </p>
                <p class="text-sm text-earth-600">
                  {{ session.correct_answers }}/{{ session.total_problems }} correct
                </p>
              </div>
              <div class="text-right">
                <p class="text-2xl font-bold" :class="getAccuracyColor(session)">
                  {{ getAccuracy(session) }}%
                </p>
                <p class="text-xs text-earth-500">{{ session.time_taken_seconds }}s</p>
              </div>
            </div>
          </div>
        </div>

        <div v-else class="bg-sage-50 rounded-2xl p-8 border-2 border-sage-200 text-center">
          <div class="text-5xl mb-3">📊</div>
          <p class="text-earth-600 text-lg">No practice sessions yet. Start practicing to see your progress!</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import drillService from '../services/drillService'

const router = useRouter()
const authStore = useAuthStore()

const user = computed(() => authStore.user)

const stats = ref({
  total_sessions: 0,
  total_problems: 0,
  total_correct: 0,
  average_accuracy: 0
})

const recentSessions = ref([])
const missedProblemsCount = ref(0)

const hasMissedProblems = computed(() => {
  return stats.value.total_problems > stats.value.total_correct
})

onMounted(async () => {
  await loadStats()
  await loadRecentSessions()
})

async function loadStats() {
  try {
    const data = await drillService.getStudentStats()
    stats.value = data
  } catch (error) {
    console.error('Failed to load stats:', error)
  }
}

async function loadRecentSessions() {
  try {
    const sessions = await drillService.getStudentSessions()
    recentSessions.value = sessions.slice(0, 5) // Show last 5
  } catch (error) {
    console.error('Failed to load sessions:', error)
  }
}

function getAccuracy(session) {
  if (session.total_problems === 0) return 0
  return Math.round((session.correct_answers / session.total_problems) * 100)
}

function getAccuracyColor(session) {
  const accuracy = getAccuracy(session)
  if (accuracy >= 90) return 'text-forest-600'
  if (accuracy >= 70) return 'text-sky-600'
  return 'text-earth-600'
}

function formatDate(dateString) {
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', { 
    month: 'short', 
    day: 'numeric',
    hour: 'numeric',
    minute: '2-digit'
  })
}

function goToPractice() {
  router.push('/practice')
}

function goToPracticeMissed() {
  router.push({ name: 'Practice', query: { mode: 'missed' } })
}

function logout() {
  authStore.logout()
  router.push('/login')
}
</script>