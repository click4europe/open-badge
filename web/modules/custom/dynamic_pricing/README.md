# Dynamic Pricing Module

This module provides dynamic pricing cards using Paragraphs in Drupal 10.

## Installation

1. Place the module in `web/modules/custom/dynamic_pricing`
2. Enable the module at `/admin/modules`
3. The module will automatically create:
   - A custom block type called "Pricing Cards"
   - All necessary fields and configurations

## Usage

### Creating Pricing Cards

1. Go to **Structure > Block content > Add Pricing Cards** (`/admin/structure/block/add/pricing_cards`)
2. Add pricing cards by clicking "Add Pricing Card"
3. For each card, fill in:
   - **Plan title**: e.g., "STARTER", "STANDARD", etc.
   - **Price**: e.g., "€70", "gratis", "contattaci"
   - **Price suffix**: e.g., "/mese"
   - **Badge count**: Number of badges included
   - **Featured plan**: Check for the recommended plan (Professional)
   - **Features**: Add features with enabled/disabled status
   - **CTA text**: Button text e.g., "Inizia Ora", "Attiva Account"
   - **CTA link**: Button destination

### Adding Features to Cards

For each pricing card:
1. Click "Add Features"
2. For each feature:
   - **Feature label**: The feature text
   - **Feature enabled**: Check for included (✓), uncheck for excluded (✗)

### Placing the Pricing Cards Block

1. Go to **Structure > Block layout** (`/admin/structure/block`)
2. Place a "Pricing Cards" block in the desired region
3. Or use the existing Prezzi e Abbonamenti page which automatically loads pricing cards

## Features

- Dynamic pricing cards with expandable details
- Featured plan highlighting
- Include/exclude feature toggles with visual indicators
- Responsive design matching your theme
- Easy-to-use interface for content editors

## Templates

The module provides these templates:
- `paragraph--pricing-card.html.twig` - Individual pricing card
- `paragraph--pricing-feature.html.twig` - Individual feature
- `block--block-content--pricing-cards.html.twig` - Pricing cards wrapper

## Styling

The cards use Tailwind CSS classes and match your existing design:
- Gradient backgrounds
- Hover effects
- Smooth animations
- Mobile-responsive grid layout
