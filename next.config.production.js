/** @type {import('next').NextConfig} */
const nextConfig = {
  // Production Configuration
  env: {
    NEXT_PUBLIC_API_URL: 'https://api.raphnesia.my.id',
    NEXT_PUBLIC_APP_URL: 'https://raphnesia.my.id',
    NEXT_PUBLIC_STORAGE_URL: 'https://api.raphnesia.my.id/storage',
  },
  
  // Image domains for production
  images: {
    domains: ['api.raphnesia.my.id', 'raphnesia.my.id'],
    remotePatterns: [
      {
        protocol: 'https',
        hostname: 'api.raphnesia.my.id',
        port: '',
        pathname: '/storage/**',
      },
      {
        protocol: 'https',
        hostname: 'raphnesia.my.id',
        port: '',
        pathname: '/**',
      },
    ],
  },
  
  // Production optimizations
  compress: true,
  poweredByHeader: false,
  generateEtags: false,
  
  // Security headers
  async headers() {
    return [
      {
        source: '/(.*)',
        headers: [
          {
            key: 'X-Frame-Options',
            value: 'DENY',
          },
          {
            key: 'X-Content-Type-Options',
            value: 'nosniff',
          },
          {
            key: 'Referrer-Policy',
            value: 'origin-when-cross-origin',
          },
        ],
      },
    ];
  },
  
  // Redirects for production
  async redirects() {
    return [
      {
        source: '/api/:path*',
        destination: 'https://api.raphnesia.my.id/api/:path*',
        permanent: true,
      },
    ];
  },
};

module.exports = nextConfig;
