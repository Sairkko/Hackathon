module.exports = {
  purge: ['./index.html', './src/**/*.{vue,js,ts,jsx,tsx}'],
  darkMode: false, // or 'media' or 'class'
  theme: {
    colors: {

      "red": '#C10041',
      "red-900": '#C71954',
      "red-800": '#CD3367',
      "red-700": '#D34C7A',
      "red-600": '#D9668D',
      "red-500": '#E07FA0',
      "red-400": '#E699B3',
      "red-300": '#ECB2C6',
      "red-200": '#F2CCD9',
      "red-100": '#F8E5EC',
      "dark-red": "#4F2424",
      "grey": "#B5B5B5",
      "lite-grey": "#E8E8E8",
      'hover-red': '#9A0034',
      'white' : '#ffffff',
      'blue-cyan' : '#2A8DA3',
      'green' : '#309A1F',
      'danger': '#FF0000'
    },
    extend: {},
  },
  variants: {
    extend: {},
  },
  plugins: [],
}
