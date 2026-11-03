/** Faelynn design system — "love for nature"
 *  forest  : deep green-black ink taken from the logo
 *  moss/sage: botanical greens
 *  ivory/cream/sand: undyed-cotton neutrals
 *  clay/rose: warm accents from the floral embroidery
 */
/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['./*.php', './inc/**/*.php', './partials/**/*.php', './assets/js/**/*.js'],
  theme: {
    container: {
      center: true,
      padding: { DEFAULT: '1rem', sm: '1.5rem', lg: '2rem' },
      screens: { '2xl': '1400px' },
    },
    extend: {
      colors: {
        forest: { DEFAULT: '#1C241E', 900: '#141A16', 800: '#1C241E', 700: '#2A362D', 600: '#3B4A3F' },
        moss: { DEFAULT: '#3F5A45', light: '#56735C' },
        sage: { DEFAULT: '#A7B8A2', light: '#DCE4D6', pale: '#EEF2EA' },
        ivory: '#FBF8F2',
        cream: '#F4EEE3',
        sand: { DEFAULT: '#E7DCC8', dark: '#CDBB9C' },
        clay: { DEFAULT: '#B5705A', light: '#E9CFC3' },
        rose: { DEFAULT: '#D9A9A0', pale: '#F6E7E2' },
        sky: { pale: '#E3EAF1' },
        ink: { DEFAULT: '#1C241E', soft: '#4D574F', muted: '#7C857E' },
        line: '#E4DDD0',
      },
      fontFamily: {
        serif: ['"Cormorant Garamond"', 'Georgia', 'serif'],
        sans: ['Jost', 'ui-sans-serif', 'system-ui', 'sans-serif'],
      },
      letterSpacing: { widest2: '0.25em' },
      boxShadow: {
        soft: '0 10px 30px -12px rgba(28,36,30,.18)',
        card: '0 2px 10px -4px rgba(28,36,30,.12)',
      },
      keyframes: {
        marquee: { '0%': { transform: 'translateX(0)' }, '100%': { transform: 'translateX(-50%)' } },
        float: { '0%,100%': { transform: 'translateY(0) rotate(-4deg)' }, '50%': { transform: 'translateY(-8px) rotate(4deg)' } },
        fadeUp: { '0%': { opacity: 0, transform: 'translateY(16px)' }, '100%': { opacity: 1, transform: 'translateY(0)' } },
      },
      animation: {
        marquee: 'marquee 40s linear infinite',
        float: 'float 6s ease-in-out infinite',
        fadeUp: 'fadeUp .7s ease both',
      },
    },
  },
  plugins: [require('@tailwindcss/forms')],
};
