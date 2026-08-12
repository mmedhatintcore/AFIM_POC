import { notFound } from "next/navigation";

/** Catch-all inside the locale segment — unknown paths render the 404 page. */
export default function CatchAllPage(): never {
  notFound();
}
