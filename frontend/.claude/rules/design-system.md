# Design System

The design system is the single source of truth for visual identity. All components compose from the tokens defined here. No raw hex values in components, no one-off spacings, no hard-coded fonts.

---

## Token Layers

Tokens live in `src/styles/tokens.css`; Tailwind reads them through the
`@theme inline` block in `src/app/globals.css`. There is **no
`tailwind.config.ts`** — this is Tailwind 4, configured in CSS.

1. **Primitive tokens** — the colour ramps (`--purple-500`, `--gray-100`, …),
   radii, shadows, motion. Stored as space-separated `R G B` triplets so
   Tailwind can apply alpha (`bg-primary/90`).
2. **Semantic tokens** — `--ts-primary`, `--ts-destructive`, `--ts-background`.
   Product code should reach for these, not the ramps, whenever the colour
   carries a *role* rather than a *hue*.
3. **Component tokens** — a handful of per-component values (`--shadow-focus`).

### The ramps

Every ramp runs `100` (lightest) → `600` (darkest); Gray also has `700`.
The anchor is the shade the brand sheet marks with a dot.

| Ramp | 100 | 200 | 300 | 400 | 500 | 600 | 700 | Anchor |
|---|---|---|---|---|---|---|---|---|
| Purple | `#EFEDFC` | `#C3BAF9` | `#9E8CF5` | `#7B5CF0` | `#5A25DD` | `#37148E` | — | 500 |
| Sky Blue | `#E4F3FC` | `#8CD3F6` | `#4DAED4` | `#3A86A4` | `#286177` | `#173E4D` | — | 300 |
| Green | `#E2FFD4` | `#8AE539` | `#6FBA2C` | `#559120` | `#3D6915` | `#26450A` | — | 300 |
| Amber | `#FED7B5` | `#FED7B5` | `#F5A623` | `#C38319` | `#936210` | `#664208` | — | 300 |
| Orange | `#F9D7C2` | `#F4B189` | `#EF8F54` | `#EB6F24` | `#C15B1E` | `#964717` | — | 400 |
| Blue | `#C8D3E7` | `#94AAD1` | `#6584BC` | `#3961A9` | `#2F508B` | `#243E6C` | — | 400 |
| Gray | `#F0F0F4` | `#CACAD9` | `#A2A2BD` | `#7C7CA1` | `#575782` | `#24243A` | `#1A1A2E` | 700 |

Plus two constants: `--white` (`#FFFFFF`) and `--red` (`#B61F3A`, exposed as
`destructive`). Amber `100` and `200` are the same hex — the brand sheet defines
five tints across six slots.

> **Only these shades exist.** Writing `bg-gray-50`, `text-purple-700`, or
> `bg-red-500` does **not** fail — Tailwind silently falls back to *its own*
> default palette, and you ship an off-brand colour that no palette change will
> ever fix. If you need a lighter tint, use `-100` with an alpha
> (`bg-gray-100/30`), not a shade that isn't in the table.

### Semantic aliases

```
--ts-primary            blue-400     interactive: buttons, controls, focus
--ts-primary-hover      blue-500
--ts-ring               blue-400     every focus affordance
--ts-accent             sky-500
--ts-destructive        red          #B61F3A — errors, cancel, delete
--ts-success            green-500
--ts-warning            amber-500
--ts-urgent             orange-500
--ts-foreground         gray-700
--ts-muted-foreground   gray-400
--ts-border / --ts-input  gray-200
```

Purple is **not** the interactive colour. It remains the brand hue — it drives
the gradients and a set of deliberate purple CTAs (`<Button variant="purple">`).

### Gradients

Tailwind can't express multi-stop gradients through the colour system, so these
ship as utility classes (`bg-gradient-*`, and `text-gradient-*` to clip to glyphs):

| Class | Stops | Use for |
|---|---|---|
| `bg-gradient-brand` | `#5A25DD → #4DAED4` @ 90° | buttons, badges, interactive |
| `bg-gradient-urgent` | `#F5A623 → #E85D24` @ 90° | promotions, urgency, sale tags |
| `bg-gradient-blue` | `#4680B0 → #3861A9` @ 269.72° | navy surfaces, headers, panels |

`--gradient-urgent` ends on `#E85D24`, which is *not* `orange-400` (`#EB6F24`).
It is pinned via `--gradient-urgent-to` so it can't drift when the ramp changes.
The blue gradient's stops are likewise off-ramp literals.

---

## Tailwind Integration

Colours reach Tailwind through `@theme inline` in `globals.css`:

```css
@theme inline {
  --color-primary:   var(--primary);      /* semantic → shadcn var → --ts-* */
  --color-blue-400:  rgb(var(--blue-400)); /* ramp */
  /* … */
}
```

`inline` means Tailwind substitutes the value directly into each utility rather
than emitting a `--color-*` variable, so `.bg-blue-400` compiles to
`background-color: rgb(var(--blue-400))`.

**Two consequences worth knowing:**

- A ramp shade you don't declare here keeps Tailwind's default. That's why the
  shade table above is exhaustive, and why `blue-50` / `gray-800` are traps.
- Turbopack does **not** reliably hot-reload `@theme` edits. After changing the
  block, `touch src/app/globals.css` or clear `.next` — otherwise new utilities
  compile against the *previous* theme and you'll chase a ghost.

Components use `bg-primary`, `text-foreground`, `border-border` for roles, and
`bg-orange-400`, `text-gray-500` for hues. Never `bg-[#eb6f24]` — a hard-coded
hex is invisible to the token system and survives every re-skin.

---

## Typography

Latin + Arabic fonts loaded via `next/font`. Until the brand guide lands, use:

```ts
// app/[locale]/layout.tsx
import { Inter, Noto_Kufi_Arabic } from 'next/font/google';

const inter = Inter({ subsets: ['latin'], variable: '--font-sans-latin', display: 'swap' });
const arabic = Noto_Kufi_Arabic({ subsets: ['arabic'], variable: '--font-sans-arabic', display: 'swap' });

// <html className={`${inter.variable} ${arabic.variable}`}>
```

```css
:root {
  --font-sans: var(--font-sans-latin), system-ui, sans-serif;
}
[dir="rtl"] {
  --font-sans: var(--font-sans-arabic), system-ui, sans-serif;
}
```

### Type scale (tailwind defaults, alias if brand says otherwise)

| Token       | Size    | Line height | Use for                    |
| ----------- | ------- | ----------- | -------------------------- |
| `text-xs`   | 12px    | 16px        | Captions, helper text      |
| `text-sm`   | 14px    | 20px        | Body small, form labels    |
| `text-base` | 16px    | 24px        | Default body               |
| `text-lg`   | 18px    | 28px        | Lead paragraphs            |
| `text-xl`   | 20px    | 28px        | Card titles                |
| `text-2xl`  | 24px    | 32px        | Section headings           |
| `text-3xl`  | 30px    | 36px        | Page titles                |
| `text-4xl`  | 36px    | 40px        | Hero                       |

Arabic script renders heavier — if the brand guide raises the Arabic default by a step, apply via `[dir="rtl"] .prose { font-size: … }` or a Tailwind plugin. Don't branch in component code.

---

## Spacing Scale

Stick to Tailwind's default 4-px scale: `0, 0.5, 1, 2, 3, 4, 6, 8, 10, 12, 16, 20, 24`. Avoid arbitrary `p-[13px]` — if the design asks for it, revisit the design.

---

## Primitive Components

Every primitive lives in `components/ui/`. Each primitive:

1. Uses CVA for variants.
2. Forwards a `ref` (use `forwardRef`).
3. Spreads `...props` onto the underlying element for flexibility.
4. Accepts a `className` prop merged via `cn()`.

### Button variants

`filled` is the default — an **orange** pill. Reach for a variant, never a
`className` colour override: `cn()` is `twMerge`, so a `bg-*` in `className`
beats the variant's background but leaves its `disabled:*` classes intact — and
you get an orange disabled state on a purple button.

| Variant | Enabled | Hover / Pressed | Disabled |
|---|---|---|---|
| `filled` | `orange-400` | `orange-500` | `orange-100` bg, `orange-200` text |
| `outline` | `orange-400` border + text | `orange-200` fill | `orange-100` border + text |
| `blue` | `blue-400` | `blue-500` | `blue-100` bg, `blue-200` text |
| `purple` | `purple-500` | `purple-600` | `purple-100` bg, `purple-200` text |
| `secondary` | `gray-100` | `gray-200` | `gray-300` text |
| `gradient` | `bg-gradient-brand` | `brightness-110` | 40% opacity |
| `ghost` / `destructive` | transparent / `destructive` | — | 40% opacity |

Sizes: `sm`, `md`, `lg`, `icon`. Labels are `font-semibold` — white on
`orange-400` is 3.07:1, which clears WCAG AA only at the large-text threshold,
and bold 14px qualifies as large. Don't drop the weight on a filled button.

### Other primitives

- [ ] `Input`, `Textarea`, `Select`, `Checkbox`, `Radio`, `Switch`
- [ ] `Label`, `FormField` (wraps label + input + error)
- [ ] `Card`, `CardHeader`, `CardContent`, `CardFooter`
- [ ] `Badge` — variants: default, outline, success, warning, destructive, accent, purple, blue
- [ ] `Alert` — variants: info, success, warning, destructive
- [ ] `Dialog` / `Drawer` / `Sheet` — headless (Radix) + styled shell
- [ ] `Tabs`, `Accordion`, `Tooltip`, `Popover` — built on Radix primitives
- [ ] `Avatar`, `Skeleton`, `Spinner`
- [ ] `Toast` — driven by the UI Zustand store (see [state-management.md](./state-management.md))
- [ ] `Pagination`, `Breadcrumbs`
- [ ] `EmptyState`, `ErrorState`

Radix UI (unstyled) is the accessibility backbone. Style the visuals with Tailwind + CVA; let Radix handle keyboard, focus trap, and ARIA.

---

## Iconography

- Use **`lucide-react`** as the default icon library — consistent stroke weight, tree-shakable.
- Icons default to `size-4` (16px) inline with text; `size-5` in buttons; `size-6` in section headers.
- All icons inside buttons get `aria-hidden="true"` — the button's visible or `aria-label` text carries the meaning.

---

## Motion

Keep it restrained. Use Tailwind transitions for hover/focus/open states; use `framer-motion` only for list reordering, route transitions, or orchestrated reveals.

- Default duration: `duration-200` (200ms) via `--duration-base`.
- Respect `prefers-reduced-motion`: `motion-reduce:transition-none motion-reduce:animate-none`.

---

## Colors — Saudi Market Notes

- Colour pairs must pass WCAG AA against `background`. The known exception is
  white on `orange-400` (3.07:1) — legal only because filled button labels are
  bold. Disabled controls are exempt from the contrast minimum.
- Avoid green-only status indicators — pair with an icon (many users distinguish by shape, and green/red culturally reads money-related).
- Third-party brand marks (Mada, Visa, Mastercard, Apple) keep their own hex
  values in `payment-logos.tsx` / `components/icons/`, even where a hex happens
  to equal one of ours. Never tokenise a logo — a palette tweak must not repaint
  someone else's trademark.

---

## Changing the Palette

1. Edit the primitive ramps in `styles/tokens.css`. Nothing else should need to
   change — that is the entire point of the token layer.
2. Adding a **new ramp** also means adding `--color-<ramp>-<shade>` entries to
   the `@theme inline` block in `globals.css`, or the utilities silently fall
   back to Tailwind's defaults.
3. `touch src/app/globals.css` (or clear `.next`) afterwards — Turbopack caches
   the resolved theme and will otherwise compile new utilities against the old one.
4. Verify at `/[locale]/design-system` in **both** `en` and `ar`. The gallery
   renders every ramp, gradient, button state, and form control.
5. Audit for drift before merging — anything these print is a colour the design
   system does not control:

   ```bash
   # shades that don't exist (they resolve to Tailwind's defaults)
   grep -rnE '\-(purple|sky|green|amber|orange|blue)-(50|700|800|900)([^0-9]|$)' src --include='*.tsx'
   grep -rnE '\-gray-(50|800|900)([^0-9]|$)' src --include='*.tsx'
   grep -rnE '\-red-[0-9]{2,3}' src --include='*.tsx'      # no red ramp — use `destructive`

   # hard-coded hex in utilities
   grep -rnE '(bg|text|border|ring|fill|stroke)-\[#[0-9A-Fa-f]{6}\]' src --include='*.tsx'
   ```
