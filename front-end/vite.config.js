import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
  plugins: [vue()],
  server: {
    host: '0.0.0.0',
    port: 5173,
    // hmr: {
    //   host: 'gertrud-overelegant-abiogenetically.ngrok-free.dev',
    //   port: 443,
    //   protocol: 'https'
    // }
  }
})