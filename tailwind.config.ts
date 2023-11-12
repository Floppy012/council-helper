/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./app/Enum/*.php",
    ],
  theme: {
    extend: {
        colors: {
            'dark': {
                "50": "#7b7c8f",
                "100": "#696a7d",
                "200": "#555657",
                "300": "#424349",
                "400": "#2f303d",
                "500": "#1c1d27",
                "600": "#1a1b26",
                "700": "#161720",
                "800": "#11121b",
                "900": "#0c0d14"
            }
        }
    },
  },
  plugins: [],
}

