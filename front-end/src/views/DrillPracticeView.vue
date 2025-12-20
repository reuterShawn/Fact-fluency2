<template>
  <div class="min-h-screen bg-gradient-to-br from-sage-100 via-earth-50 to-cream-50 p-4 md:p-8">
<div class="w-full max-w-4xl mx-auto px-4">      
      <!-- Top Stats Bar -->
      <div v-if="sessionActive" class="bg-white rounded-2xl shadow-lg p-4 mb-6 border-l-4 border-forest-500">
        <div class="flex justify-between items-center">
          <div class="flex items-center gap-6">
            <!-- Progress -->
            <div class="flex items-center gap-2">
              <div class="w-12 h-12 bg-sky-100 rounded-full flex items-center justify-center">
                <span class="text-sky-700 font-bold text-lg">{{ currentProblemIndex + 1 }}</span>
              </div>
              <div>
                <p class="text-xs text-earth-600 font-medium">PROGRESS</p>
                <p class="text-sm text-earth-800 font-bold">{{ currentProblemIndex + 1 }} of {{ problems.length }}</p>
              </div>
            </div>
            
            <!-- Correct Count -->
            <div class="flex items-center gap-2">
              <div class="w-12 h-12 bg-forest-100 rounded-full flex items-center justify-center">
                <span class="text-forest-700 font-bold text-lg">{{ correctCount }}</span>
              </div>
              <div>
                <p class="text-xs text-earth-600 font-medium">CORRECT</p>
                <p class="text-sm text-earth-800 font-bold">{{ Math.round((correctCount / Math.max(currentProblemIndex, 1)) * 100) }}%</p>
              </div>
            </div>
          </div>

          <!-- Logout -->
          <button 
            @click="logout" 
            class="px-4 py-2 text-earth-600 hover:bg-earth-50 rounded-lg transition-all font-medium"
          >
            Exit
          </button>
        </div>
      </div>

      <!-- Setup Screen -->
      <div v-if="!sessionActive && !sessionComplete" class="bg-white rounded-3xl shadow-2xl p-8 md:p-12">
        <div class="max-w-xl mx-auto">
          <h2 class="text-3xl font-bold text-earth-800 mb-2">
</h2>
<p class="text-earth-600">
</p>
          <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-sky-100 rounded-full mb-4">
              <svg class="w-10 h-10 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
              </svg>
            </div>
            <h2 class="text-3xl font-bold text-earth-800 mb-2">Setup Practice</h2>
            <p class="text-earth-600">Choose your settings and let's begin!</p>
          </div>
          
          <div class="space-y-6">
 <!-- Operation Selection with Multi-Select -->
<div>
  <label class="block text-sm font-bold text-earth-700 mb-3">OPERATION TYPES (Select one or more)</label>
  <div class="grid grid-cols-2 gap-3">
    <button 
      v-for="op in operationOptions"
      :key="op.value"
      @click="toggleOperation(op.value)"
      type="button"
      :class="[
        'p-4 rounded-xl border-2 font-semibold transition-all',
        settings.operations.includes(op.value)
          ? 'bg-forest-100 border-forest-500 text-forest-700' 
          : 'bg-sage-50 border-sage-200 text-earth-600 hover:border-sage-300'
      ]"
    >
      <div class="text-2xl mb-1">{{ op.icon }}</div>
      {{ op.label }}
    </button>
  </div>
  <p v-if="settings.operations.length === 0" class="text-red-600 text-sm mt-2">
    Please select at least one operation type
  </p>
</div>
            <!-- Difficulty -->
            <div>
              <label class="block text-sm font-bold text-earth-700 mb-3">
                DIFFICULTY LEVEL: <span class="text-forest-600">{{ settings.difficulty }}</span>
              </label>
              <input 
                v-model.number="settings.difficulty" 
                type="range" 
                min="1" 
                max="5" 
                class="w-full h-3 bg-sage-200 rounded-lg appearance-none cursor-pointer"
                style="accent-color: #4a7c2c"
              />
              <div class="flex justify-between text-xs text-earth-500 mt-2 font-medium">
                <span>Easy</span>
                <span>Medium</span>
                <span>Hard</span>
              </div>
            </div>

            <!-- Problem Count -->
            <div>
              <label class="block text-sm font-bold text-earth-700 mb-3">NUMBER OF PROBLEMS</label>
              <input 
                v-model.number="settings.problemsCount" 
                type="number" 
                min="5" 
                max="50" 
                class="w-full px-4 py-3 bg-sage-50 border-2 border-sage-200 rounded-xl focus:border-sky-500 focus:ring-4 focus:ring-sky-100 focus:bg-white transition-all outline-none text-earth-800 font-semibold text-lg"
              />
            </div>

            <!-- Start Button -->
            <button 
              @click="startPractice"
              :disabled="loading"
              class="w-full bg-forest-500 hover:bg-forest-600 active:bg-forest-700 text-white font-bold py-5 rounded-xl transition-all shadow-xl hover:shadow-2xl disabled:bg-sage-300 disabled:cursor-not-allowed transform hover:-translate-y-1 active:translate-y-0 text-lg"
            >
              <span v-if="!loading">🚀 Start Practice</span>
              <span v-else>Starting...</span>
            </button>
          </div>
        </div>
      </div>

      <!-- Practice Screen -->
      <div v-if="sessionActive && currentProblem" class="bg-white rounded-3xl shadow-2xl p-8 md:p-16">
        <div class="text-center max-w-2xl mx-auto">
          <!-- Problem -->
          <div class="mb-12">
            <div class="text-7xl md:text-9xl font-bold text-earth-800 mb-2 font-mono tracking-tight">
              {{ currentProblem.num1 }} {{ currentProblem.operator }} {{ currentProblem.num2 }}
            </div>
            <div class="text-5xl md:text-7xl text-earth-400 font-bold">= ?</div>
          </div>

          <!-- Answer Input -->
          <div class="mb-8">
            <input
              ref="answerInput"
              v-model.number="userAnswer"
              @keyup.enter="submitAnswer"
              type="number"
              placeholder="Type answer..."
              class="w-full max-w-md mx-auto text-5xl md:text-6xl font-bold text-center border-4 border-forest-300 rounded-2xl p-6 focus:border-forest-500 focus:ring-8 focus:ring-forest-100 outline-none bg-sage-50 focus:bg-white transition-all text-earth-800"
            />
          </div>

          <!-- Submit Button -->
          <button
            @click="submitAnswer"
            :disabled="userAnswer === null || userAnswer === ''"
            class="bg-forest-500 hover:bg-forest-600 active:bg-forest-700 text-white font-bold px-16 py-5 rounded-2xl text-2xl transition-all shadow-xl hover:shadow-2xl disabled:bg-sage-300 disabled:cursor-not-allowed transform hover:-translate-y-1 active:translate-y-0"
          >
            Submit Answer
          </button>

          <!-- Feedback -->
          <div v-if="feedback" class="mt-10 p-6 rounded-2xl text-2xl font-bold" 
               :class="feedback.isCorrect ? 'bg-forest-100 text-forest-700 border-2 border-forest-300' : 'bg-red-100 text-red-700 border-2 border-red-300'">
            <div v-if="feedback.isCorrect" class="flex items-center justify-center gap-3">
              <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
              Correct!
            </div>
            <div v-else class="flex items-center justify-center gap-3">
              <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
              </svg>
              Answer: {{ feedback.correctAnswer }}
            </div>
          </div>
        </div>
      </div>

      <!-- Results Screen -->
      <div v-if="sessionComplete && results" class="bg-white rounded-3xl shadow-2xl p-8 md:p-16 text-center">
        <div class="max-w-2xl mx-auto">
          <div class="text-8xl mb-6">🎉</div>
          <h2 class="text-4xl md:text-5xl font-bold text-earth-800 mb-4">Practice Complete!</h2>
          
          <!-- Big Score -->
          <div class="my-10">
            <div class="text-8xl md:text-9xl font-bold text-forest-600 mb-2">{{ results.accuracy }}%</div>
            <p class="text-2xl text-earth-600">
              <span class="font-bold text-forest-600">{{ results.correct_answers }}</span> 
              correct out of 
              <span class="font-bold">{{ results.total_problems }}</span>
            </p>
          </div>

          <!-- Stats -->
          <div class="bg-sage-50 rounded-2xl p-6 mb-8 inline-block">
            <p class="text-earth-600 text-lg">
              ⏱️ Completed in <span class="font-bold text-earth-800">{{ results.time_taken }}</span> seconds
            </p>
          </div>

          <!-- Action Buttons -->
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <button
              @click="reset"
              class="bg-sky-500 hover:bg-sky-600 text-white font-bold px-8 py-4 rounded-xl text-xl transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
            >
              Practice Again
            </button>
            <button
              @click="goToDashboard"
              class="bg-earth-500 hover:bg-earth-600 text-white font-bold px-8 py-4 rounded-xl text-xl transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
            >
              Back to Dashboard
            </button>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, computed, nextTick, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import drillService from '../services/drillService'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()

const user = computed(() => authStore.user)

const operationOptions = [
  { value: 'addition', label: 'Addition', icon: '➕' },
  { value: 'subtraction', label: 'Subtraction', icon: '➖' },
  { value: 'multiplication', label: 'Multiplication', icon: '✖️' },
  { value: 'division', label: 'Division', icon: '➗' }
]

const settings = ref({
  operations: ['addition'],
  difficulty: 1,
  problemsCount: 10
})

const loading = ref(false)
const sessionActive = ref(false)
const sessionComplete = ref(false)
const sessionId = ref(null)
const problems = ref([])
const currentProblemIndex = ref(0)
const userAnswer = ref(null)
const feedback = ref(null)
const correctCount = ref(0)
const results = ref(null)
const answerInput = ref(null)
const startTime = ref(null)

const currentProblem = computed(() => problems.value[currentProblemIndex.value])

function toggleOperation(operation) {
  const index = settings.value.operations.indexOf(operation)
  if (index > -1) {
    // Remove if already selected
    if (settings.value.operations.length > 1) { // Keep at least one
      settings.value.operations.splice(index, 1)
    }
  } else {
    // Add if not selected
    settings.value.operations.push(operation)
  }
}
async function startPractice() {
  if (settings.value.operations.length === 0) {
    alert('Please select at least one operation type')
    return
  }
  
  loading.value = true
  try {
    let response
    
    // Check if we're in "missed problems" mode
    if (route.query.mode === 'missed') {
      response = await drillService.startMissedProblemsSession(settings.value.problemsCount)
    } else {
      response = await drillService.startSession(
        settings.value.operations,
        settings.value.difficulty,
        60,
        settings.value.problemsCount
      )
    }
    
    sessionId.value = response.session_id
    problems.value = response.problems
    sessionActive.value = true
    currentProblemIndex.value = 0
    correctCount.value = 0
    startTime.value = Date.now()
    
    await nextTick()
    answerInput.value?.focus()
  } catch (error) {
    console.error('Failed to start session:', error)
    if (error.response?.status === 404) {
      alert('No missed problems found! Complete some practice sessions first.')
      router.push('/dashboard')
    } else {
      alert('Failed to start practice. Please try again.')
    }
  } finally {
    loading.value = false
  }
}
async function submitAnswer() {
  if (userAnswer.value === null || userAnswer.value === '') return

  const responseTime = Date.now() - startTime.value
  
  try {
    const response = await drillService.submitAnswer(
      sessionId.value,
      currentProblem.value,
      userAnswer.value,
      responseTime
    )
    
    feedback.value = {
      isCorrect: response.is_correct,
      correctAnswer: response.correct_answer
    }
    
    if (response.is_correct) {
      correctCount.value++
    }
    
    setTimeout(() => {
      if (currentProblemIndex.value < problems.value.length - 1) {
        currentProblemIndex.value++
        userAnswer.value = null
        feedback.value = null
        startTime.value = Date.now()
        nextTick(() => answerInput.value?.focus())
      } else {
        completeSession()
      }
    }, 1200)
    
  } catch (error) {
    console.error('Failed to submit answer:', error)
  }
}

async function completeSession() {
  try {
    const response = await drillService.completeSession(sessionId.value)
    results.value = response
    sessionActive.value = false
    sessionComplete.value = true
  } catch (error) {
    console.error('Failed to complete session:', error)
  }
}

function reset() {
  sessionComplete.value = false
  sessionActive.value = false
  results.value = null
  problems.value = []
  currentProblemIndex.value = 0
  userAnswer.value = null
  feedback.value = null
  correctCount.value = 0
}

function goToDashboard() {
  router.push('/dashboard')
}

function logout() {
  authStore.logout()
  router.push('/login')
}

const isMissedMode = computed(() => route.query.mode === 'missed')
</script>