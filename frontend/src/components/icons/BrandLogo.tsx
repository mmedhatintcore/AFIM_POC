import Image from "next/image";
import { cn } from "@/lib/utils/cn";

const LOGO_WIDTH = 2616;
const LOGO_HEIGHT = 506;

/**
 * The full AFIM lockup (EN + AR names + ACH shield). Renders the navy
 * artwork in light mode and the white artwork in dark mode — swapped with
 * CSS so it never flashes on theme change. Size it via `className` height
 * (e.g. `h-9`); width follows the aspect ratio.
 */
export function BrandLogo({
  alt,
  className,
  priority = false,
  sizes = "200px",
}: {
  alt: string;
  className?: string;
  priority?: boolean;
  sizes?: string;
}) {
  return (
    <span className={cn("block", className)}>
      <Image
        src="/logo-blue.png"
        alt={alt}
        width={LOGO_WIDTH}
        height={LOGO_HEIGHT}
        priority={priority}
        sizes={sizes}
        className="block h-full w-auto dark:hidden"
      />
      <Image
        src="/logo-white.png"
        alt=""
        aria-hidden="true"
        width={LOGO_WIDTH}
        height={LOGO_HEIGHT}
        priority={priority}
        sizes={sizes}
        className="hidden h-full w-auto dark:block"
      />
    </span>
  );
}
