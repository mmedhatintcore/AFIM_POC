"use client";

import { ChevronLeft, ChevronRight } from "lucide-react";
import { useCallback, useEffect, useRef, type ReactNode } from "react";

const STEP = 320;
const AUTO_MS = 3800;

/**
 * Horizontal fund-price rail: prev/next buttons, drag-to-scroll,
 * auto-advance (paused on hover/drag, disabled for reduced motion), RTL-aware.
 */
export function PricesCarousel({
  children,
  ariaLabel,
  prevLabel,
  nextLabel,
}: {
  children: ReactNode;
  ariaLabel: string;
  prevLabel: string;
  nextLabel: string;
}) {
  const trackRef = useRef<HTMLDivElement>(null);
  const timerRef = useRef<ReturnType<typeof setInterval> | null>(null);

  const dirSign = () =>
    typeof document !== "undefined" && document.dir === "rtl" ? -1 : 1;

  const stopAuto = useCallback(() => {
    if (timerRef.current) {
      clearInterval(timerRef.current);
      timerRef.current = null;
    }
  }, []);

  const startAuto = useCallback(() => {
    if (timerRef.current) return;
    if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) return;
    timerRef.current = setInterval(() => {
      const track = trackRef.current;
      if (!track) return;
      const max = track.scrollWidth - track.clientWidth - 4;
      if (max <= 0) return;
      if (Math.abs(track.scrollLeft) >= max) {
        track.scrollTo({ left: 0, behavior: "smooth" });
      } else {
        track.scrollBy({ left: STEP * dirSign(), behavior: "smooth" });
      }
    }, AUTO_MS);
  }, []);

  useEffect(() => {
    startAuto();
    return stopAuto;
  }, [startAuto, stopAuto]);

  // drag to scroll
  useEffect(() => {
    const track = trackRef.current;
    if (!track) return;
    let down = false;
    let startX = 0;
    let startScroll = 0;

    const onDown = (event: PointerEvent) => {
      down = true;
      startX = event.clientX;
      startScroll = track.scrollLeft;
      stopAuto();
    };
    const onMove = (event: PointerEvent) => {
      if (!down) return;
      const delta = event.clientX - startX;
      if (Math.abs(delta) > 5) track.style.cursor = "grabbing";
      track.scrollLeft = startScroll - delta;
    };
    const onUp = () => {
      down = false;
      track.style.cursor = "";
      startAuto();
    };

    track.addEventListener("pointerdown", onDown);
    track.addEventListener("pointermove", onMove);
    track.addEventListener("pointerup", onUp);
    track.addEventListener("pointerleave", onUp);
    track.addEventListener("pointercancel", onUp);
    return () => {
      track.removeEventListener("pointerdown", onDown);
      track.removeEventListener("pointermove", onMove);
      track.removeEventListener("pointerup", onUp);
      track.removeEventListener("pointerleave", onUp);
      track.removeEventListener("pointercancel", onUp);
    };
  }, [startAuto, stopAuto]);

  const scrollByStep = (direction: 1 | -1) => {
    trackRef.current?.scrollBy({
      left: direction * STEP * dirSign(),
      behavior: "smooth",
    });
    stopAuto();
  };

  const buttonClass =
    "absolute top-[42%] z-5 grid size-11 place-items-center rounded-full bg-accent text-white shadow-elev-2 transition-transform duration-300 ease-out-soft hover:scale-110";

  return (
    <div className="relative" data-testid="prices-carousel">
      <button
        type="button"
        onClick={() => scrollByStep(-1)}
        aria-label={prevLabel}
        data-testid="prices-prev"
        className={`${buttonClass} -start-2.5`}
      >
        <ChevronLeft className="size-6 rtl:-scale-x-100" aria-hidden="true" />
      </button>
      <div
        ref={trackRef}
        className="carousel-track cursor-grab"
        aria-label={ariaLabel}
        onMouseEnter={stopAuto}
        onMouseLeave={startAuto}
        data-testid="prices-track"
      >
        {children}
      </div>
      <button
        type="button"
        onClick={() => scrollByStep(1)}
        aria-label={nextLabel}
        data-testid="prices-next"
        className={`${buttonClass} -end-2.5`}
      >
        <ChevronRight className="size-6 rtl:-scale-x-100" aria-hidden="true" />
      </button>
    </div>
  );
}
