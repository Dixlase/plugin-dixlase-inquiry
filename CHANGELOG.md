# Changelog

All notable changes to the Dixlase Inquiry plugin are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this plugin follows Semantic Versioning.

## [0.2.0] — 2026-10-11

### Added

- Opt-in persistence toggle (`store_inquiries`, default **off**) and a dedicated
  **Privacy** settings page (#49). When the toggle is off, submissions are
  delivered by email as before but no row is written to `plg_dixlase_inquiries`.
- Retention policy with `retention_days` (default `90`, `null` = keep
  indefinitely) and a new `expires_at` column on `plg_dixlase_inquiries`. The
  retention form offers presets (30 / 90 / 180 / 365 days, indefinite) and a
  custom value (1–3650).
- Per-row retention editing on the inquiry detail screen — change one row's
  expiry without touching the global policy. Preset days are counted from
  today, not from the original submission.
- Artisan command `dls:inquiry:prune` for the retention sweep, with `--soft`
  (soft-delete into the trash instead of hard delete) and `--dry-run` (count
  without deleting). Rows are iterated with `chunkById(500)` so a large
  backlog does not spike memory.
- Daily scheduled run of `dls:inquiry:prune` at 03:15, registered through
  Laravel's `Schedule` facade from the plugin's service provider.
- Dashboard notification that counts inquiry mail-send failures in the last
  24 hours (#49). This is the one visible signal when `store_inquiries` is
  off and the SMTP relay is down — core's error email rides on the same
  broken mailer, so without this count a quiet outage can hide a day's
  worth of lost submissions.
- `plg_dixlase_inquiry_mail_failures` log table and
  `DixlaseInquiryMailFailure` model behind the dashboard count. Rows carry
  metadata only (context, recipient role, exception class) — no addresses,
  subject or body — and are swept after 90 days by the existing
  `DatabaseCleanupService` pipeline.

### Fixed

- Silent-loss of a submission when `store_inquiries` is off and the admin
  notification fails to send (#49). `send()` and `embedSend()` used to show
  the visitor the success page in that case, so neither the operator (no
  mail) nor the visitor (no error) knew the inquiry had been lost. When
  nothing acknowledgeable survives — no DB row and no admin mail — the
  visitor now returns to the confirm screen with an error banner and their
  input intact, so they can retry.

### Changed

- **Default behavior:** inquiries are no longer persisted to the database
  unless the site opts in. Email delivery is unchanged. Existing rows are
  kept as-is; new submissions will only persist once `store_inquiries` is
  turned on from the Privacy settings page.
- `plugin.json`: declared `system.register_commands` and `declares.commands`
  (the plugin now ships an Artisan command), and corrected
  `permissions.settings.write_own` to `true` to match the settings-write code
  paths that already existed.

## [0.1.1] — 2026-10-01

### Changed

- Build tooling: `vite` 5 → 8.3.1, with `esbuild` and `postcss` 8.5.28 updated
  alongside (#42). This clears the Dependabot advisories for those packages,
  all of which affect only the development server and the asset build — nothing in
  them is shipped to sites. The prebuilt assets in the release ZIP are produced by
  the same build as before; only their hashed file names change.

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
- Implements the `RouteSlugProvider` contract for a configurable form URL.
