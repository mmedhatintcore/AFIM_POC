"use client";

import { create } from "zustand";
import { createJSONStorage, persist } from "zustand/middleware";

export type Theme = "light" | "dark";

type UIState = {
  /** null = follow system preference (until the user toggles) */
  theme: Theme | null;
  finderOpen: boolean;
};

type UIActions = {
  setTheme: (theme: Theme) => void;
  openFinder: () => void;
  closeFinder: () => void;
};

export const useUIStore = create<UIState & UIActions>()(
  persist(
    (set) => ({
      theme: null,
      finderOpen: false,
      setTheme: (theme) => set({ theme }),
      openFinder: () => set({ finderOpen: true }),
      closeFinder: () => set({ finderOpen: false }),
    }),
    {
      name: "afim-ui",
      storage: createJSONStorage(() => localStorage),
      partialize: (state) => ({ theme: state.theme }),
    },
  ),
);
