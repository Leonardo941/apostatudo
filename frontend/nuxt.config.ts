export default defineNuxtConfig({
  ssr: false,
  modules: [
    '@nuxtjs/tailwindcss',
    '@pinia/nuxt',
  ],
  devtools: { enabled: false },
  app: {
    head: {
      script: [
        {
          innerHTML: `(function(){var t=localStorage.getItem('theme');if(t==='light'){document.documentElement.classList.remove('dark')}else{document.documentElement.classList.add('dark')}})()`,
          type: 'text/javascript',
        },
      ],
      htmlAttrs: {
        class: 'dark',
      },
    },
  },
  runtimeConfig: {
    public: {
      apiBase: 'http://localhost:8000/api',
    },
  },
  devServer: {
    host: '0.0.0.0',
    port: 3000,
  },
  vite: {
    server: {
      watch: {
        usePolling: true,
      },
    },
  },
  compatibilityDate: '2025-01-01',
})
