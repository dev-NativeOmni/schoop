/**
 * Tailwind CSS Configuration
 */
module.exports = {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
    './resources/**/*.tsx',
  ],
  darkMode: 'class', // enable class-based dark mode
  theme: {
    extend: {
      colors: {
        primary: 'hsl(210, 60%, 55%)', // vibrant blue
        accent: 'hsl(340, 70%, 55%)',   // lively pink
      },
      fontFamily: {
        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
      },
    },
  },
  plugins: [],
};
