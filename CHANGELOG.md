# Changelog

All notable changes to the Dixlase Inquiry plugin are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this plugin follows Semantic Versioning.

## [0.1.0] — 2026-10-01
Initial release. Requires Dixlase `^0.1.0` (Plugin API `^0.1`), PHP `>= 8.3`.

### Added

- Inquiry / contact form with an admin UI and front-end form routes; submissions
  are stored in the plugin's own tables.
- Shortcode to embed the inquiry form inside page content.
- CAPTCHA integration (`captcha` capability / `CaptchaFormProviderInterface`) —
  supports the core reCAPTCHA v3 and Cloudflare Turnstile drivers.
- Dashboard notification (`DashboardNotificationProviderInterface`) that warns
  when CAPTCHA is not enabled for the inquiry form.
- Multilingual form content (`multilingual-content` capability).
- Implements the `RouteSlugProvider` contract for a configurable form URL.
