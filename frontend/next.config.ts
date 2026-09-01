import type { NextConfig } from "next";
import createNextIntlPlugin from "next-intl/plugin";

const withNextIntl = createNextIntlPlugin();

// Uploaded photos/images (team photos, news thumbnails) are served from the
// Laravel API's public disk (`{APP_URL}/storage/...`), so next/image needs
// that host allow-listed. NEXT_PUBLIC_API_URL must be set at build time.
const apiUrl = process.env.NEXT_PUBLIC_API_URL;
const apiHost = apiUrl ? new URL(apiUrl) : null;

const nextConfig: NextConfig = {
  output: "standalone",
  images: {
    remotePatterns: [
      {
        protocol: "http",
        hostname: "localhost",
        port: "8000",
        pathname: "/storage/**",
      },
      ...(apiHost && apiHost.hostname !== "localhost"
        ? [
            {
              protocol: apiHost.protocol.replace(":", "") as "http" | "https",
              hostname: apiHost.hostname,
              pathname: "/storage/**",
            },
          ]
        : []),
    ],
  },
};

export default withNextIntl(nextConfig);
