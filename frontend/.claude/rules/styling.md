# Styling

**Tailwind CSS** is the default. Reach for it first for every spacing, color, typography, and layout decision. Only drop to custom CSS (in `globals.css` or a co-located `.module.css`) for things Tailwind cannot express cleanly.

---

## Tailwind-First Principle

Use a Tailwind utility if one exists. Don't write:

```css
/* ❌ */
.card-title { font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem; }
```

When you can write:

```tsx
/* ✅ */
<h2 className="text-xl font-semibold mb-2">{title}</h2>
```

**Exceptions** (okay to write CSS):
- Custom `@keyframes` beyond what Tailwind provides.
- Complex selectors (`:has()`, `::after` with content, deeply nested states) where the utility chain becomes unreadable.
- Third-party integrations that only accept class names or CSS (e.g. a rich text editor's theme hooks).
- Design-token CSS variables in `styles/tokens.css` (see [design-system.md](./design-system.md)).

---

## `cn()` Helper

Every component that composes classes uses the `cn()` helper from `lib/utils/cn.ts` (clsx + tailwind-merge). This handles conditional classes and dedupes conflicts:

```ts
// lib/utils/cn.ts
import { clsx, type ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]): string {
  return twMerge(clsx(inputs));
}
```

```tsx
<button
  className={cn(
    'inline-flex items-center rounded-md px-4 py-2',
    'bg-primary text-primary-foreground hover:bg-primary/90',
    disabled && 'opacity-50 pointer-events-none',
    className, // allow override from props
  )}
/>
```

Never concatenate classes with `+` or template strings — `cn()` is the only way.

---

## Variants with `class-variance-authority`

For primitives (Button, Badge, Alert) use **CVA** to define variants cleanly:

```ts
// components/ui/Button.tsx
import { cva, type VariantProps } from 'class-variance-authority';

const buttonVariants = cva(
  'inline-flex items-center justify-center rounded-md font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 disabled:opacity-50 disabled:pointer-events-none',
  {
    variants: {
      variant: {
        primary:   'bg-primary text-primary-foreground hover:bg-primary/90',
        secondary: 'bg-secondary text-secondary-foreground hover:bg-secondary/80',
        ghost:     'hover:bg-accent hover:text-accent-foreground',
        destructive: 'bg-destructive text-destructive-foreground hover:bg-destructive/90',
      },
      size: {
        sm: 'h-9 px-3 text-sm',
        md: 'h-10 px-4',
        lg: 'h-12 px-6 text-lg',
      },
    },
    defaultVariants: { variant: 'primary', size: 'md' },
  },
);

type ButtonProps = React.ButtonHTMLAttributes<HTMLButtonElement> & VariantProps<typeof buttonVariants>;

export function Button({ className, variant, size, ...props }: ButtonProps) {
  return <button className={cn(buttonVariants({ variant, size }), className)} {...props} />;
}
```

Callers pick a variant prop — they don't pass raw classes for color/size. That's the contract.

---

## RTL — Always Use Logical Properties

The app supports Arabic (RTL) and English (LTR). **Never** use physical directions when a logical equivalent exists:

| ❌ Physical          | ✅ Logical (Tailwind)    |
| -------------------- | ------------------------ |
| `ml-4` / `mr-4`      | `ms-4` / `me-4`          |
| `pl-6` / `pr-6`      | `ps-6` / `pe-6`          |
| `left-0` / `right-0` | `start-0` / `end-0`      |
| `border-l`/`border-r`| `border-s` / `border-e`  |
| `text-left`          | `text-start`             |
| `rounded-l-md`       | `rounded-s-md`           |

The locale layout sets `dir="rtl"` on `<html>` for Arabic; logical classes flip automatically.

**Icons and chevrons** inside buttons (e.g. "Next →") must also mirror — use a CSS transform or a direction-aware icon component:

```tsx
<ChevronRight className="rtl:rotate-180" />
```

---

## Responsive Design

- **Mobile-first** — unprefixed utilities apply everywhere; add breakpoint prefixes for larger screens.
- Standard breakpoints: `sm` 640px, `md` 768px, `lg` 1024px, `xl` 1280px, `2xl` 1536px.
- Wrap page layouts in a `Container` component (from `components/layout/`) that handles max-width and horizontal padding — don't repeat `mx-auto max-w-7xl px-4 …` everywhere.

```tsx
<Container>
  <h1 className="text-2xl md:text-4xl font-bold">…</h1>
</Container>
```

---

## Dark Mode

Drive it with Tailwind's `class` strategy and a `theme` preference in the UI Zustand store. Root layout sets `class="dark"` on `<html>` when appropriate:

```tsx
<html className={theme === 'dark' ? 'dark' : undefined}>
```

All color utilities use the design-system tokens (`bg-background`, `text-foreground`, `border-border`) — never raw Tailwind colors like `bg-slate-900`. That way a single token file controls both themes (see [design-system.md](./design-system.md)).

---

## Component Reuse — Extract at 3

Copy-pasting a pattern once is fine. Twice is a yellow flag. **Three times is the extraction trigger.**

Before extracting, decide:

- **Primitive** (reused across features, no domain knowledge) → `components/ui/`
- **Feature** (knows about `Event`/`Booking`/etc., reused in 2+ places) → `components/features/`
- **Layout** (wraps a page region) → `components/layout/`

If you only need the component once and it's under ~40 lines, leave it inline in the page. Premature abstraction is worse than light duplication.

---

## Styling Anti-Patterns (Don't)

- ❌ Inline `style={{ ... }}` — use Tailwind. Dynamic values go through CSS variables on the element's `style` (e.g. `style={{ '--progress': `${pct}%` }}`).
- ❌ Global class overrides in `globals.css` targeting arbitrary components. Only Tailwind `@layer base` resets and `@layer components` re-usable patterns belong there.
- ❌ CSS Modules for new components. Only use them for legacy integration or third-party scoping.
- ❌ `!important` — if you need it, the cascade is wrong. Fix the upstream class or CVA variant.
- ❌ `bg-[#ff00ff]` one-off colors. Add a token to the design system instead (see [design-system.md](./design-system.md)).

---

## Accessibility

Tailwind utilities don't absolve a11y concerns:

- Every interactive element has a visible `focus-visible:` state (`ring-2 ring-primary`).
- Color pairs meet WCAG AA contrast — test tokens, not one-off hex.
- Never rely on color alone to convey meaning; add an icon or text.
- Touch targets are ≥ 44×44 px on mobile (`min-h-11 min-w-11`).
- Use semantic HTML first (`button`, `a`, `nav`, `main`, `article`). A `div` with `onClick` is almost always wrong.
