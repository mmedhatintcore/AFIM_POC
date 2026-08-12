import createMiddleware from "next-intl/middleware";
import { routing } from "./i18n/routing";

// Next.js 16 renamed `middleware` to `proxy`. next-intl's handler is
// runtime-compatible — it receives the request and returns a response.
export default createMiddleware(routing);

export const config = {
  // Skip Next internals and all static files (anything with an extension).
  matcher: "/((?!api|_next|_vercel|.*\\..*).*)",
};
