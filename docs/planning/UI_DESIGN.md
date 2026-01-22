# DuoManifest - UI Design Guide

## Color Palette (Duolingo Inspired)

### Primary Colors
```css
--primary-green: #58CC02;      /* Main brand color */
--primary-green-dark: #58A700; /* Hover states */
--primary-green-light: #89E219; /* Highlights */
```

### Secondary Colors
```css
--blue: #1CB0F6;               /* Info, links */
--purple: #CE82FF;             /* Special features */
--red: #FF4B4B;                /* Errors, hearts */
--orange: #FF9600;             /* Warnings, fire streaks */
--yellow: #FFC800;             /* Stars, achievements */
--pink: #FF86D0;               /* Love category */
```

### Neutral Colors
```css
--white: #FFFFFF;
--gray-50: #F7F7F7;
--gray-100: #E5E5E5;
--gray-200: #AFAFAF;
--gray-300: #777777;
--gray-400: #4B4B4B;
--dark: #3C3C3C;
```

### Gradients
```css
--gradient-success: linear-gradient(180deg, #58CC02 0%, #58A700 100%);
--gradient-premium: linear-gradient(180deg, #CE82FF 0%, #9B51E0 100%);
--gradient-fire: linear-gradient(180deg, #FF9600 0%, #FF4B4B 100%);
```

## Typography

### Font Family
```css
--font-primary: 'Nunito', 'DIN Round', sans-serif;
```

### Font Sizes
```css
--text-xs: 0.75rem;    /* 12px */
--text-sm: 0.875rem;   /* 14px */
--text-base: 1rem;     /* 16px */
--text-lg: 1.125rem;   /* 18px */
--text-xl: 1.25rem;    /* 20px */
--text-2xl: 1.5rem;    /* 24px */
--text-3xl: 1.875rem;  /* 30px */
--text-4xl: 2.25rem;   /* 36px */
```

### Font Weights
```css
--font-normal: 400;
--font-medium: 500;
--font-semibold: 600;
--font-bold: 700;
--font-extrabold: 800;
```

## Component Styles

### Buttons
```css
/* Primary Button */
.btn-primary {
  background: var(--primary-green);
  border-bottom: 4px solid var(--primary-green-dark);
  border-radius: 16px;
  color: white;
  font-weight: 700;
  padding: 12px 24px;
  text-transform: uppercase;
  transition: all 0.1s;
}

.btn-primary:active {
  border-bottom-width: 0;
  margin-top: 4px;
}

/* Secondary Button */
.btn-secondary {
  background: white;
  border: 2px solid var(--gray-100);
  border-bottom: 4px solid var(--gray-200);
  border-radius: 16px;
  color: var(--gray-400);
}
```

### Cards
```css
.card {
  background: white;
  border: 2px solid var(--gray-100);
  border-radius: 16px;
  padding: 20px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.card-interactive {
  cursor: pointer;
  transition: transform 0.1s, box-shadow 0.1s;
}

.card-interactive:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
```

### Progress Bars
```css
.progress-bar {
  background: var(--gray-100);
  border-radius: 999px;
  height: 16px;
  overflow: hidden;
}

.progress-fill {
  background: var(--gradient-success);
  height: 100%;
  border-radius: 999px;
  transition: width 0.3s ease;
}
```

### Streak Fire Icon
```css
.streak-fire {
  background: var(--gradient-fire);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}
```

## Layout Components

### Navigation Sidebar
- Width: 280px (desktop)
- Background: White
- Border-right: 2px solid gray-100
- Fixed position
- Logo at top
- Navigation items with icons
- User profile at bottom

### Main Content Area
- Max-width: 800px
- Centered
- Padding: 40px

### Top Header
- Height: 60px
- Shows streak, XP, hearts
- Profile dropdown

## Mascot Design
- Friendly owl-like character
- Named "Mani" (for Manifestation)
- Poses:
  - Encouraging (thumbs up)
  - Celebrating (confetti)
  - Thinking (for tips)
  - Sleeping (for streak breaks)

## Animations

### Celebrations
```css
@keyframes confetti {
  0% { transform: translateY(0) rotate(0); opacity: 1; }
  100% { transform: translateY(-100vh) rotate(720deg); opacity: 0; }
}

@keyframes bounce {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-10px); }
}

@keyframes pulse {
  0%, 100% { transform: scale(1); }
  50% { transform: scale(1.05); }
}
```

### XP Gain
```css
@keyframes xp-float {
  0% { transform: translateY(0); opacity: 1; }
  100% { transform: translateY(-50px); opacity: 0; }
}
```

## Responsive Breakpoints
```css
--mobile: 640px;
--tablet: 768px;
--desktop: 1024px;
--wide: 1280px;
```

## Icon System
- Use Heroicons or custom SVG icons
- Consistent 24px size for navigation
- 20px for inline icons
- 32px for feature icons
