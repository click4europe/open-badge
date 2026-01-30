# Font System Usage Guide

## Font Classes Available

### Title Fonts
- `.font-title` - Standard titles (bold, tight spacing)
- `.font-title-large` - Large titles (extra bold, very tight spacing)
- `.font-title-medium` - Medium titles (semibold, moderate spacing)
- `.font-title-small` - Small titles (semibold, normal spacing)

### Text/Body Fonts
- `.font-body` - Standard body text (normal weight, good readability)
- `.font-body-medium` - Medium body text (medium weight)
- `.font-body-large` - Large body text (better readability for longer content)
- `.font-body-small` - Small body text (slightly tighter spacing)

### UI/Interface Fonts
- `.font-ui` - UI elements (medium weight, uppercase, tracking)
- `.font-label` - Form labels (semibold, small size)
- `.font-caption` - Captions and meta info (light, very small)

### Special Font Classes
- `.font-hero` - Hero sections (black, very tight spacing)
- `.font-heading` - Page headings (bold, tight spacing)
- `.font-subheading` - Subheadings (semibold, normal spacing)

## Usage Examples

### HTML Examples
```html
<!-- Hero Title -->
<h1 class="font-hero text-5xl text-slate-900">Main Hero Title</h1>

<!-- Page Heading -->
<h2 class="font-heading text-3xl text-slate-800">Page Heading</h2>

<!-- Section Title -->
<h3 class="font-title text-2xl text-slate-700">Section Title</h3>

<!-- Body Text -->
<p class="font-body text-slate-600">This is the main body text content.</p>

<!-- UI Button -->
<button class="font-ui px-4 py-2 bg-blue-600 text-white">BUTTON TEXT</button>

<!-- Label -->
<label class="font-label text-slate-700">Form Label</label>

<!-- Caption -->
<span class="font-caption text-slate-500">Caption text</span>
```

### Recommended Combinations

#### Hero Sections
```html
<h1 class="font-hero text-4xl md:text-5xl lg:text-6xl">Hero Title</h1>
<p class="font-body-large text-lg mt-4">Hero description text</p>
```

#### Content Sections
```html
<h2 class="font-heading text-2xl md:text-3xl">Section Heading</h2>
<h3 class="font-title-medium text-xl mt-4">Subsection Title</h3>
<p class="font-body mt-2">Body content goes here...</p>
```

#### Cards and Components
```html
<div class="card p-6">
  <h4 class="font-title text-lg">Card Title</h4>
  <p class="font-body-small text-sm mt-2">Card description</p>
  <span class="font-caption text-xs text-slate-500">Meta info</span>
</div>
```

## Build Instructions

After adding these font classes, you need to rebuild the CSS:

```bash
cd c:\xampp8.2\htdocs\openbadge\web\themes\custom\tailwind
npm run build
```

Then clear Drupal cache:

```bash
drush cr
```

## Font Stack

All font classes use this font stack:
1. **Inter** (primary font from Google Fonts)
2. **Roboto** (fallback from Google Fonts)
3. **system-ui** (system font)
4. **-apple-system, BlinkMacSystemFont, "Segoe UI"** (platform-specific fonts)
5. **sans-serif** (final fallback)

## Typography Scale

The font system is designed to work with Tailwind's default sizing scale:
- Text sizes: `text-xs` to `text-9xl`
- Font weights: `font-light` to `font-black`
- Letter spacing: `tracking-tighter` to `tracking-widest`

## Best Practices

1. **Use semantic font classes**: Choose the most appropriate class for the content type
2. **Maintain hierarchy**: Use larger, bolder fonts for headings, lighter for body text
3. **Consistent spacing**: The built-in line heights provide consistent vertical rhythm
4. **Responsive sizing**: Combine with Tailwind's responsive prefixes for optimal readability
