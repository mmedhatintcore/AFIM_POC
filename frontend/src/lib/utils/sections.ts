import type { Section, SectionMap } from "@/types/api";

/** Safe string read from a section's loosely-typed `extra` bag. */
export function extraString(
  section: Section | null | undefined,
  key: string,
): string | null {
  const value = section?.extra?.[key];
  return typeof value === "string" && value.length > 0 ? value : null;
}

export function toSectionMap(sections: Section[] | null): SectionMap {
  const map: SectionMap = {};
  for (const section of sections ?? []) {
    if (section.is_enabled) map[section.key] = section;
  }
  return map;
}
