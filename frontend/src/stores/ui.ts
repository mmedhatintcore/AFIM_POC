"use client";

import { create } from "zustand";
import { createJSONStorage, persist } from "zustand/middleware";

export type Theme = "light" | "dark";

type UIState = {
  theme: Theme;
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
      theme: "light",
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
