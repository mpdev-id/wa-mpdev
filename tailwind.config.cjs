/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./public/**/*.html",
  ],
  theme: {
    extend: {
      colors: {
        'primary': '#10b981',
        'secondary': '#8b5cf6',
        'accent': '#f59e0b',
        'neutral': '#3d4451',
        'base-100': '#ffffff',
      },
      fontFamily: {
        sans: ['Inter', 'ui-sans-serif', 'system-ui'],
      },
      animation: {
        'fade-in': 'fadeIn 0.5s ease-in-out',
        'slide-up': 'slideUp 0.5s ease-out',
        'slide-down': 'slideDown 0.5s ease-out',
        'scale-in': 'scaleIn 0.3s ease-out',
      },
      keyframes: {
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        slideUp: {
          '0%': { transform: 'translateY(20px)', opacity: '0' },
          '100%': { transform: 'translateY(0)', opacity: '1' },
        },
        slideDown: {
          '0%': { transform: 'translateY(-20px)', opacity: '0' },
          '100%': { transform: 'translateY(0)', opacity: '1' },
        },
        scaleIn: {
          '0%': { transform: 'scale(0.9)', opacity: '0' },
          '100%': { transform: 'scale(1)', opacity: '1' },
        },
      },
    },
  },
  plugins: [
    require('daisyui'),
  ],
  daisyui: {
    themes: [
      {
        light: {
          primary: "#10b981",
          secondary: "#8b5cf6",
          accent: "#f59e0b",
          neutral: "#3d4451",
          "base-100": "#ffffff",
          info: "#3abff8",
          success: "#36d399",
          warning: "#fbbd23",
          error: "#f87272",
        },
        dark: {
          primary: "#10b981",
          secondary: "#8b5cf6",
          accent: "#f59e0b",
          neutral: "#2a323c",
          "base-100": "#1d232a",
          info: "#3abff8",
          success: "#36d399",
          warning: "#fbbd23",
          error: "#f87272",
        },
        
dark: {
  name: "dark";
  default: false;
  prefersdark: true;
  color-scheme: "dark";
  --color-base-100: oklch(14% 0 0);
  --color-base-200: oklch(20% 0 0);
  --color-base-300: oklch(26% 0 0);
  --color-base-content: oklch(97% 0 0);
  --color-primary: oklch(70% 0.14 182.503);
  --color-primary-content: oklch(98% 0.014 180.72);
  --color-secondary: oklch(65% 0.241 354.308);
  --color-secondary-content: oklch(97% 0.014 343.198);
  --color-accent: oklch(55% 0.013 58.071);
  --color-accent-content: oklch(98% 0.001 106.423);
  --color-neutral: oklch(43% 0 0);
  --color-neutral-content: oklch(98% 0 0);
  --color-info: oklch(74% 0.16 232.661);
  --color-info-content: oklch(29% 0.066 243.157);
  --color-success: oklch(76% 0.177 163.223);
  --color-success-content: oklch(26% 0.051 172.552);
  --color-warning: oklch(75% 0.183 55.934);
  --color-warning-content: oklch(26% 0.079 36.259);
  --color-error: oklch(71% 0.202 349.761);
  --color-error-content: oklch(28% 0.109 3.907);
  --radius-selector: 2rem;
  --radius-field: 2rem;
  --radius-box: 2rem;
  --size-selector: 0.3125rem;
  --size-field: 0.28125rem;
  --border: 0.5px;
  --depth: 1;
  --noise: 0;
}
      },
    ],
    darkTheme: "dark",
    base: true,
    styled: true,
    utils: true,
    logs: true,
  },
};
