module.exports = {
  purge: ['./index.html', './src/**/*.{vue,js,ts,jsx,tsx}'],
  darkMode: false, // or 'media' or 'class'
  theme: {
    colors: {
      "red": '#C10041',
      "dark-red": "#4F2424",
      "grey": "#B5B5B5",
      'hover-red': '#9A0034',
      'white' : '#ffffff'
    },
    extend: {},
  },
  variants: {
    extend: {},
  },
  plugins: [],
}
