import { ChevronDown } from "lucide-react";

/**
 * Collapsible list of trading platforms. Uses native <details> so it works
 * without JS from both server and client components (label passed in).
 */
export function PlatformsList({
  platforms,
  label,
}: {
  platforms: string[];
  label: string;
}) {
  if (platforms.length === 0) return null;
  return (
    <details className="group mt-2">
      <summary className="inline-flex cursor-pointer list-none items-center gap-1.5 text-[0.76rem] font-semibold text-accent [&::-webkit-details-marker]:hidden">
        {label}
        <ChevronDown
          className="size-3.5 transition-transform duration-300 group-open:rotate-180"
          aria-hidden="true"
        />
      </summary>
      <ul className="mt-2 flex flex-wrap gap-2">
        {platforms.map((platform) => (
          <li
            key={platform}
            className="rounded-full border border-border bg-background-2 px-2.5 py-1 text-[0.74rem] text-soft"
          >
            {platform}
          </li>
        ))}
      </ul>
    </details>
  );
}
