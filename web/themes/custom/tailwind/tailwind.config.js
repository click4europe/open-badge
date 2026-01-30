/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './templates/**/*.html.twig',
    './src/**/*.js',
    '../../../modules/custom/**/*.html.twig',
    '../../../modules/custom/**/*.php',
    '../../default/modules/**/*.html.twig',
    '../../default/modules/**/*.php',
  ],
  theme: {
    extend: {
      colors: {
        'obYellow': '#facc15',
        'obBlue': '#0f172a',
        'obLightBlue': '#1d4ed8',
      },
    },
  },
  plugins: [],
}
