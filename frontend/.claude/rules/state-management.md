# State Management

**Zustand** is the only client state library. Don't reach for Redux, Recoil, Jotai, or sprawling Context providers. Don't store server data here that you could re-derive from a request.

---

## When to use Zustand vs. alternatives

| Concern                                          | Put it in…                                       |
| ------------------------------------------------ | ------------------------------------------------ |
| Data fetched from the backend                    | RSC fetch → props, or SWR/TanStack Query cache   |
| Auth token, current user, locale, cart contents  | **Zustand store**                                |
| Form field state                                 | `react-hook-form` (not Zustand)                  |
| A modal's open/close for one component           | `useState` (not Zustand)                         |
| Toast queue, global drawer, feature flags        | **Zustand store**                                |
| Route params, search params                      | `useParams` / `useSearchParams`                  |

Rule of thumb: Zustand for **client state shared across unrelated trees**. Everything else stays local.

---

## File Layout

```
stores/
  auth.ts            # token, user, login/logout actions
  cart.ts            # tickets selected before checkout
  ui.ts              # toast queue, drawer, dark mode
  locale.ts          # current locale + setter (mirrors cookie)
```

One store per domain. Don't create a "root store" that pulls everything into one file.

---

## Store Pattern

```ts
// stores/auth.ts
import { create } from 'zustand';
import { persist, createJSONStorage } from 'zustand/middleware';
import type { User } from '@/types/api';

type AuthState = {
  token: string | null;
  user: User | null;
};

type AuthActions = {
  setSession: (token: string, user: User) => void;
  clear: () => void;
};

export const useAuthStore = create<AuthState & AuthActions>()(
  persist(
    (set) => ({
      token: null,
      user: null,
      setSession: (token, user) => set({ token, user }),
      clear: () => set({ token: null, user: null }),
    }),
    {
      name: 'ts.auth',
      storage: createJSONStorage(() => sessionStorage), // NOT localStorage for tokens
      partialize: (state) => ({ token: state.token, user: state.user }),
    },
  ),
);
```

**Rules:**
- Separate `State` and `Actions` types — actions live alongside state, not in a separate file.
- No computed state inside the store — derive in selectors (`useAuthStore(s => !!s.token)`).
- Persist only what needs to survive a refresh (`partialize`).
- **Tokens go in `sessionStorage`, not `localStorage`** (XSS mitigation). Long-lived session also has an httpOnly cookie for SSR (see [api-client.md](./api-client.md)).

---

## Selectors — Always Use Them

Subscribing to the whole store re-renders on every change. Always pass a selector:

```tsx
// ✅ re-renders only when token changes
const isAuthed = useAuthStore((s) => !!s.token);

// ❌ re-renders when ANY field changes
const { token } = useAuthStore();
```

For multiple fields, use `useShallow` from `zustand/shallow`:

```tsx
import { useShallow } from 'zustand/shallow';

const { token, user } = useAuthStore(useShallow((s) => ({ token: s.token, user: s.user })));
```

---

## SSR & Hydration

Zustand runs on the server too. To avoid "server rendered X, client rendered Y" mismatches:

1. **Don't read persisted state during the server render.** Gate UI that depends on it:
   ```tsx
   'use client';
   const hasHydrated = useAuthStore.persist.hasHydrated();
   if (!hasHydrated) return <Skeleton />;
   ```
2. **Prefer cookies for values the server needs** (locale, auth token). The store mirrors the cookie; SSR reads the cookie directly.
3. **Never access `window` / `document` / `sessionStorage` in the initializer** — use middleware or lazy setters.

---

## Actions — Keep Them Minimal

A store action should mutate state. **API calls belong in hooks or services**, not inside actions. The action gets called with the API result:

```ts
// hooks/useLogin.ts
export function useLogin() {
  const setSession = useAuthStore((s) => s.setSession);
  return async (payload: LoginPayload) => {
    const { data } = await api.post('/v1/auth/login', payload);
    setSession(data.data.token, data.data.user);
  };
}
```

This keeps stores test-friendly and side-effect-free.

---

## Resetting State on Logout

Every store that holds user-scoped data must expose a `clear()` or `reset()` action. On logout, call them together:

```ts
// hooks/useLogout.ts
export function useLogout() {
  return () => {
    useAuthStore.getState().clear();
    useCartStore.getState().clear();
    // UI store stays (toasts, dark mode)
  };
}
```

---

## Toast / Notification Store

Centralize toasts so any component or interceptor can push one:

```ts
// stores/ui.ts
type Toast = { id: string; variant: 'success' | 'error' | 'info'; title: string; description?: string };

type UIState = { toasts: Toast[] };
type UIActions = {
  pushToast: (t: Omit<Toast, 'id'>) => void;
  dismissToast: (id: string) => void;
};

export const useUIStore = create<UIState & UIActions>()((set) => ({
  toasts: [],
  pushToast: (t) => set((s) => ({ toasts: [...s.toasts, { ...t, id: crypto.randomUUID() }] })),
  dismissToast: (id) => set((s) => ({ toasts: s.toasts.filter((t) => t.id !== id) })),
}));
```

Axios error interceptor can now push a toast on 5xx without importing React. That's the whole point.

---

## What NOT to Store

- **Server data that can be refetched** (events list, user profile details). Put these in SWR / TanStack Query with keys — Zustand re-implementing a cache is an anti-pattern.
- **Form values** — `react-hook-form`.
- **Ephemeral UI state for one component** — `useState`.
- **Derived values** — compute with a selector.
- **Giant blobs** (parsed CSVs, base64 images). Keep them out of the persisted slice.

---

## Debugging

Enable the Redux DevTools middleware in development:

```ts
import { devtools } from 'zustand/middleware';

export const useAuthStore = create<AuthState & AuthActions>()(
  devtools(
    persist(/* … */),
    { name: 'auth', enabled: process.env.NODE_ENV !== 'production' },
  ),
);
```
