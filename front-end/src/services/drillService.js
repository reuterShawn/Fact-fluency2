import api from './api'

export default {
  async generateProblems(operation, difficulty, count) {
    const response = await api.get('/problems/generate', {
      params: { operation, difficulty, count }
    })
    return response.data
  },

  async startSession(operationTypes, difficultyLevel, timeLimit, problemsCount) {
    const response = await api.post('/sessions/start', {
      operation_types: operationTypes, // Now accepts array
      difficulty_level: difficultyLevel,
      time_limit: timeLimit,
      problems_count: problemsCount
    })
    return response.data
  },

  async startMissedProblemsSession(problemsCount) {
    const response = await api.post('/sessions/start-missed', {
      problems_count: problemsCount
    })
    return response.data
  },

  async getMissedProblems() {
    const response = await api.get('/student/missed-problems')
    return response.data
  },

  async submitAnswer(sessionId, problem, studentAnswer, responseTimeMs) {
    const response = await api.post(`/sessions/${sessionId}/answer`, {
      problem,
      student_answer: studentAnswer,
      response_time_ms: responseTimeMs
    })
    return response.data
  },

  async completeSession(sessionId) {
    const response = await api.post(`/sessions/${sessionId}/complete`)
    return response.data
  },

  async getStudentSessions() {
    const response = await api.get('/student/sessions')
    return response.data
  },

  async getStudentStats() {
  const response = await api.get('/student/stats')
  return response.data
}
}