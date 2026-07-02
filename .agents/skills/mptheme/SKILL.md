---
name: mptheme
description: Applies a Professional Sci-Fi Game HUD (Cyberpunk/Glassmorphism) theme with dynamic procedural particles, gaming grid, and glass panels to a web project.
---
# Cyber HUD Theme Skill

Use this skill when the user wants to apply the "Professional Game HUD", "Cyberpunk", or "Sci-Fi" theme to a web application or component. This skill provides the exact design tokens, CSS patterns, and Javascript required to replicate the immersive HUD interface.

## 1. Core Principles
- **Glassmorphism**: UI panels should use `backdrop-filter: blur(16px)` and translucent backgrounds.
- **Neon Glows**: Hover states should trigger vibrant box-shadow glows.
- **Micro-interactions**: Interactive elements should scale up slightly and show inset selection brackets on hover.
- **Depth**: The background must contain a perspective "Gaming Grid" and floating "Procedural Particles".

## 2. Design Tokens (CSS Variables)
Inject these into the root of the application's CSS (e.g., `:root` and `.dark`):

```css
:root {
    /* Light Theme - Softer Game HUD (Sky & Clouds Blue) */
    --bg-main: #E0F2FE; /* Soft sky blue (sky-100) */
    --bg-card: rgba(255, 255, 255, 0.72); /* Translucent white cloud card */
    --bg-card-hover: rgba(255, 255, 255, 0.88);
    --bg-inner: rgba(240, 249, 255, 0.75); /* Soft light sky inset background */
    
    --text-main: #0369A1; /* Ocean deep blue (sky-700) */
    --text-body: #0369A1;
    --text-secondary: #0284C7; /* Sky-600 */
    
    --border-color: rgba(14, 165, 233, 0.24);
    
    --color-primary: #0284C7;
    --color-accent: #0369A1;
    
    --particle-color: rgba(3, 105, 161, 0.14);
    --hud-glow: 0 0 10px rgba(2, 132, 199, 0.06);
    --hud-glow-hover: 0 0 16px rgba(2, 132, 199, 0.18);
}

.dark {
    /* Dark Theme - Softer Space HUD (Slate Tones) */
    --bg-main: #1E293B; /* Slate-800 (not too dark) */
    --bg-card: rgba(15, 23, 42, 0.45); /* Slate-900 transparent */
    --bg-card-hover: rgba(30, 41, 59, 0.65); /* Slate-800 transparent */
    --bg-inner: rgba(15, 23, 42, 0.7);
    
    --text-main: #F8FAFC; /* Slate-50 */
    --text-body: #F8FAFC;
    --text-secondary: #94A3B8; /* Slate-400 */
    
    --border-color: rgba(255, 255, 255, 0.12);
    
    --color-primary: #3B82F6; /* Soft Blue-500 */
    --color-accent: #F59E0B; /* Softer Amber-500 */
    
    --particle-color: rgba(255, 255, 255, 0.08);
    --hud-glow: 0 0 10px rgba(59, 130, 246, 0.08);
    --hud-glow-hover: 0 0 18px rgba(59, 130, 246, 0.22);
}
```

## 3. Background: Gaming Grid & Scanner
Add these `div` elements as the lowest `z-index` elements to create a perspective grid and a sweeping laser scanner line.

**HTML:**
```html
<div class="gaming-grid"></div>
<div class="scanner-line"></div>
```

**CSS:**
```css
.gaming-grid {
    position: fixed;
    inset: 0;
    background-image: 
        linear-gradient(var(--border-color) 1.5px, transparent 1.5px),
        linear-gradient(90deg, var(--border-color) 1.5px, transparent 1.5px);
    background-size: 40px 40px;
    opacity: 0.35;
    pointer-events: none;
    z-index: 0;
}
.dark .gaming-grid {
    opacity: 0.26;
}

/* Sharper Sweeping Laser Scanner Line */
.scanner-line {
    position: fixed;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, transparent, var(--color-primary), transparent);
    opacity: 0.24;
    pointer-events: none;
    z-index: 1;
    animation: scan-vertical 14s infinite linear;
}
.dark .scanner-line {
    opacity: 0.28;
}

@keyframes scan-vertical {
    0% { top: -20px; }
    100% { top: 110%; }
}
```

## 4. Background: Procedural Particles
Always include this script to inject randomized HUD vectors floating in the background.

**HTML:**
```html
<div class="particles fixed inset-0 overflow-hidden pointer-events-none z-0" id="particles-container"></div>
```

**JavaScript Generator:**
```javascript
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('particles-container');
    if(!container) return;
    
    const svgs = [
        `<svg viewBox="0 0 30 30"><circle cx="15" cy="15" r="9" stroke="var(--particle-color)" fill="none" stroke-width="2"/><circle cx="15" cy="15" r="2" fill="var(--particle-color)"/><path d="M15,2 L15,7 M15,23 L15,28 M2,15 L7,15 M23,15 L28,15" stroke="var(--particle-color)" stroke-width="2" stroke-linecap="round"/></svg>`,
        `<svg viewBox="0 0 30 30"><polygon points="15,2 28,15 15,28 2,15" stroke="var(--particle-color)" fill="none" stroke-width="2"/><polygon points="15,8 22,15 15,22 8,15" stroke="var(--particle-color)" fill="none" stroke-width="1" stroke-dasharray="2,2"/></svg>`,
        `<svg viewBox="0 0 24 24"><path d="M4,4 h2 v2 h-2 z M18,4 h2 v2 h-2 z M6,6 h12 v2 h-12 z M8,8 h8 v2 h-8 z M4,10 h16 v2 h-16 z M2,12 h20 v2 h-20 z M2,14 h2 v2 h-2 z M6,14 h12 v2 h-12 z M20,14 h2 v2 h-2 z M4,16 h2 v2 h-2 z M18,16 h2 v2 h-2 z" fill="var(--particle-color)"/></svg>`,
        `<svg viewBox="0 0 30 30"><path d="M15,2 L18,9 L25,9 L20,14 L22,21 L15,17 L8,21 L10,14 L5,9 L12,9 Z" stroke="var(--particle-color)" fill="none" stroke-width="2"/></svg>`,
        `<svg viewBox="0 0 30 30"><rect x="3" y="3" width="24" height="24" rx="2" stroke="var(--particle-color)" fill="none" stroke-width="2"/><path d="M15,3 L15,27 M3,15 L27,15" stroke="var(--particle-color)" stroke-width="1.5" stroke-dasharray="2,2"/></svg>`,
        `<svg viewBox="0 0 30 30"><circle cx="15" cy="15" r="11" stroke="var(--particle-color)" fill="none" stroke-width="2"/></svg>`,
        `<svg viewBox="0 0 24 24"><path d="M4,4 h2 v2 h-2 z M18,4 h2 v2 h-2 z M6,6 h12 v2 h-12 z M8,8 h8 v2 h-8 z M4,10 h16 v2 h-16 z M2,12 h20 v2 h-20 z" fill="var(--particle-color)"/></svg>`,
        `<svg viewBox="0 0 30 30"><path d="M6,6 L24,24 M6,24 L24,6" stroke="var(--particle-color)" stroke-width="2.5" stroke-linecap="round"/></svg>`,
        `<svg viewBox="0 0 30 30"><polygon points="15,4 27,25 3,25" stroke="var(--particle-color)" fill="none" stroke-width="2"/></svg>`
    ];

    const numParticles = 65; 
    for(let i = 0; i < numParticles; i++) {
        const el = document.createElement('div');
        el.classList.add('particle');
        el.style.position = 'absolute';
        
        el.innerHTML = svgs[Math.floor(Math.random() * svgs.length)];
        const svgEl = el.querySelector('svg');
        
        const size = Math.floor(Math.random() * 50) + 15; 
        svgEl.setAttribute('width', size);
        svgEl.setAttribute('height', size);
        
        el.style.top = Math.floor(Math.random() * 100) + '%';
        el.style.left = Math.floor(Math.random() * 100) + '%';
        
        const duration = Math.floor(Math.random() * 30) + 20; 
        const delay = Math.floor(Math.random() * -50); 
        const animType = Math.floor(Math.random() * 4) + 1; 
        
        el.style.animation = `float-particle-${animType} ${duration}s infinite linear`;
        el.style.animationDelay = `${delay}s`;
        
        el.style.opacity = Math.random() * 0.7 + 0.3; 

        container.appendChild(el);
    }
});
```

**Required Keyframes (CSS):**
```css
@keyframes float-particle-1 {
    0% { transform: translateY(0) rotate(0deg) translateX(0); }
    33% { transform: translateY(-40px) rotate(120deg) translateX(20px); }
    66% { transform: translateY(25px) rotate(240deg) translateX(-20px); }
    100% { transform: translateY(0) rotate(360deg) translateX(0); }
}
@keyframes float-particle-2 {
    0% { transform: translateY(0) rotate(0deg) translateX(0); }
    50% { transform: translateY(60px) rotate(-180deg) translateX(-30px); }
    100% { transform: translateY(0) rotate(-360deg) translateX(0); }
}
@keyframes float-particle-3 {
    0% { transform: translateY(0) rotate(0deg) translateX(0); }
    33% { transform: translateY(-30px) rotate(90deg) translateX(-15px); }
    66% { transform: translateY(-60px) rotate(270deg) translateX(15px); }
    100% { transform: translateY(0) rotate(360deg) translateX(0); }
}
@keyframes float-particle-4 {
    0% { transform: translateY(0) rotate(0deg) translateX(0); }
    50% { transform: translateY(-55px) rotate(180deg) translateX(40px); }
    100% { transform: translateY(0) rotate(360deg) translateX(0); }
}
```

## 5. UI Elements Styling
- Use `backdrop-filter: blur(16px)` on any absolute/fixed panels (Modals, Navbars).
- Typography should favor `font-family: monospace` for metadata and navigation to emulate HUD systems.
- Borders should be `.border-[var(--border-color)]`.
