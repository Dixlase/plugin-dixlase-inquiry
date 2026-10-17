# Changelog

All notable changes to the Dixlase Inquiry plugin are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this plugin follows Semantic Versioning.

## [0.5.6] — 2026-10-18

### Changed

- Comments, language-file comments and the notes in `plugin.json` no longer name plugins that are not distributed publicly. Each integration is
  described by the core mechanism it goes through (#76, fixes #75). No behaviour changed.

## [0.5.5] — 2026-10-14

### Development

- A self-audit test replays the admin plugin audit's own checks: it fails when a
  permission declared in `plugin.json` does not match the code, or when a view breaks
  the CSP strict mode (#72, fixes #71). The `settings.write_own` mismatch that shipped
  from 0.2.0 to 0.5.3 would have failed this test.
- The admin audit log test compares the recorded context key by key instead of as an
  ordered array. MySQL 8 reorders the keys of a JSON column, so the test failed only
  against MySQL (#74, fixes #73).

No runtime code changed.

## [0.5.4] — 2026-10-13

### Fixed

- `plugin.json`'s `permissions.settings.write_own` is now `false`, which is
  what the scanner has always expected it to be (#69). The flag was flipped
  to `true` in 0.2.0 to match the plugin's own settings-write code paths,
  but `App\Services\Plugin\Scanning\SettingsDetectionPattern` only matches
  writes against core's `SiteSetting` / `SecuritySetting` — this plugin
  writes to its own `plg_dixlase_inquiry_settings` through
  `DixlaseInquirySetting`, which is already covered by
  `database.own_tables`. 0.2.0 → 0.5.3 shipped with the mismatch, which
  surfaced in the admin plugin audit panel as a "permission mismatch:
  `settings.write_own` — unused permission declaration" warning. The
  bilingual `_notes` block now spells out why the flag is `false` so the
  next reader does not flip it back.

## [0.5.3] — 2026-10-12

### Changed

- Admin GET smoke test now asserts the role-specific status each route
  should return, not just `< 500` (#66). Three gaps are closed in one
  go:
    - 404 no longer counts as a pass. Route parameters are resolved
      from `parameterNames()`, and a placeholder with no supplied test
      value fails the test loudly instead of letting a literal
      `{slug}` go out on the wire and silently return 404.
    - the stack is now `web + auth:member + plugin.admin.access`, so
      the role gate the Inquiry 0.3.0 class of regression would land
      in actually fires. The editor test exercises the editor's gate
      (admin-tier settings return 403), where before it reached every
      screen with no gate in the stack.
    - a discovery pass that finds nothing fails: `assertContains` is
      pinned against the two anchor routes `inquiry.index` and
      `inquiry.settings.index`. Failures are collected per role and
      reported together with the exception class and message, so one
      run names every broken screen.
  The theme stub now lives in a throwaway directory under
  `storage/framework/testing/` and `tearDown` removes it, replacing the
  tracked `tests/stubs/theme/`.

## [0.5.2] — 2026-10-12

### Fixed

- Admin GET smoke tests now restore `INSTALLED` to its original value in
  `tearDown()` instead of writing a blanket `false` back (#64). The old
  code happened to be harmless on this plugin's own harness only because
  `phpunit.xml` does not pin that env var; on harnesses that do pin it
  (e.g. `plugin-dixlase-seo`, `plugin-dixlase-menus`), subsequent tests
  running in the same process would have seen `false` and been
  redirected by `CheckInstallationReady`. Pre-setUp state is now
  snapshotted — "was not set" and `"false"` are kept distinct — so the
  harness is left exactly the way it was found.

## [0.5.1] — 2026-10-12

### Changed

- Admin sidebar label for the plugin's settings group is now **Inquiry
  Settings** (ja: **問い合わせ設定**), up from the plain **Settings**
  (ja: **設定**) it used to render as (#63). The sibling entry labelled
  **Inquiry list** already read self-contained, so a quick glance at the
  sidebar now anchors both children without having to re-read the
  parent. No route, permission, or structural change — one translation
  key on each side.

## [0.5.0] — 2026-10-12

### Added

- Audit log for policy-affecting administrator actions (#59). Entries are
  emitted whenever an operator changes retention policy, edits a single
  row's expiry, restores or permanently removes a trashed inquiry, empties
  the trash, runs `dls:inquiry:prune`, or when an admin mail delivery
  fails. All entries flow through core's `App\Facades\Audit` with
  `category=plugin`, `plugin_name=dixlase-inquiry`, and a free-form
  `context` payload. Visitor PII (name / email / message / phone) is
  deliberately kept out of the context — the audit stream tracks the
  operator's actions, not the visitor's content, so a site that leaves
  `store_inquiries` off is not re-collecting the data it promised not to.
  First plugin to adopt core's `App\DTO\Audit\AuditLogPayload` /
  `App\Events\AuditLogCreated` surface; the shape doubles as a worked
  example for other plugins.

## [0.4.0] — 2026-10-12

### Fixed

- Silent-loss of a submission when `store_inquiries` is off and the admin
  notification fails to send. `send()` and `embedSend()` used to show the
  visitor the success page in that case, so neither the operator (no
  mail) nor the visitor (no error) knew the inquiry had been lost. With
  `store_inquiries` off being the default since 0.2.0, a freshly
  installed site plus an SMTP outage was the most likely path to this
  bug. When nothing acknowledgeable survives — no DB row and no admin
  mail — the visitor now returns to the confirm screen with an error
  banner and their input intact, so they can retry.

### Added

- Dashboard notification that counts inquiry mail-send failures in the
  last 24 hours. This is the only visible signal when `store_inquiries`
  is off and the SMTP relay is down — core's error email rides on the
  same broken mailer, so without this count a quiet outage can hide a
  day's worth of lost submissions.
- `plg_dixlase_inquiry_mail_failures` log table and
  `DixlaseInquiryMailFailure` model behind the dashboard count. Rows
  carry metadata only (context, recipient role, exception class) — no
  addresses, subject or body — and are swept after 90 days by the
  existing `DatabaseCleanupService` pipeline.

## [0.3.0] — 2026-10-11

### Fixed

- Embedded form backgrounds now fade cleanly when the theme's appearance toggle is
  used, instead of jumping (#48).
- Re-signed. The signature no longer matched the tree, so sites reported the plugin
  as `signature_invalid` and deducted from its health score. 0.2.0 bumped the
  version and wrote its changelog entry but was never signed or tagged: the change
  reached `main` through a pull-request merge, which does not run the local signing
  hook. Sites that report 0.2.0 are running that unsigned build; 0.3.0 is the first
  signed release that carries the 0.2.0 work.

### Changed

- Built front-end assets are no longer tracked in git; the release build produces
  them (#46).
- CI and dependency maintenance: `source-map-js` 1.2.1 → 1.2.2 (#56), the dead
  `COMPOSER_AUTH` block removed (#52), the checkout token no longer passed as a
  secret (#57), and the deploy also covers the documentation site (#54).

## [0.2.0] — 2026-10-10

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
