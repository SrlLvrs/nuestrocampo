import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

// https://vitejs.dev/config/
export default defineConfig({
  base: '/sistemav2/', // Reemplaza 'mi-app' con el nombre de tu subdirectorio
  plugins: [vue()],
})
