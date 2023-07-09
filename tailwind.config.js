/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['./public/*.html'],
  theme: {
    screens:{
      sm: '480px',
      md: '768px',
      lg: '960px',
      xl: '1280px',
      xxl: '1440px'
    },
    extend: {},
  },
  plugins: [],
}

