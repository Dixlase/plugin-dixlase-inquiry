# Dixlase Inquiry

For Japanese, see [README.ja.md](./README.ja.md).

Contact-form plugin for Dixlase: a configurable inquiry form under a fixed URL prefix (`/inquiry` by default) with toggleable optional fields (kana, phone, postal code, address, gender, etc.), single-page or split-step submission, admin and customer email notifications with template variables, privacy-consent linking, CAPTCHA opt-in, per-IP submission throttling, and an embeddable shortcode for placing the form on any page.

## Features

- **Public inquiry form** — Served at a configurable URL slug (default `/inquiry`), with optional single-page mode (input → confirmation → completion in one view) or split mode (separate confirmation page). Locale URL routing mirrors the form under `/{locale}/inquiry`.
- **Toggleable optional fields** — `name`, `email`, and `message` are always required. `name_kana`, `subject`, `phone`, `postal_code`, `address`, and `gender` each have independent show / required toggles. Gender supports an "other" and "prefer not to say" option that can be enabled per site.
- **Name-order modes** — Pick between `auto` (chosen from the active locale), western (first then last), or Japanese (last then first) for how the name input is laid out and how the value is composed in mail templates.
- **Privacy-consent gate** — Optional checkbox with a customisable consent text and a link target that accepts either a fully-qualified URL (`https://example.com/privacy`) or a server-absolute path (`/page/privacy-policy`).
- **Admin notification mail** — Outgoing notification to a configurable admin address with editable subject / body. Template variables (`{{name}}`, `{{email}}`, `{{subject}}`, `{{message}}`, `{{postal_code}}`, `{{address}}`, `{{phone}}`) are substituted at send time.
- **Auto-reply mail** — Optional acknowledgement to the inquirer with its own subject / body, from-email override, and the same set of template variables.
- **CAPTCHA opt-in** — The form is registered as `dixlase-inquiry:form` via `CaptchaFormProviderInterface`, so any compatible CAPTCHA plugin (e.g. reCAPTCHA, Turnstile) can attach its widget without an Inquiry-side change.
- **Submission throttling** — Per-IP rate limit with configurable max attempts and decay minutes; presents a friendly error when exceeded.
- **Inquiry management** — Admin list view with search / status filter / sort, detail view, read / unread status, manual mark-as-read, and bulk delete.
- **Shortcode embed** — `[dixlase-inquiry-form]` renders the form inside any host page (DixlasePages, DixlaseBlog, etc.) without leaving its surrounding template.
- **Dashboard notifications** — New-inquiry count and a "CAPTCHA not enabled" prompt are surfaced on the admin dashboard via `DashboardNotificationProviderInterface`.
- **Role-based permission** — Default permissions for the Inquiry admin menus are declared in `config/admin/roles.php` and resolved at runtime by the core `PermissionRegistry`.
- **Internal-link provider** — Implements `RouteSlugProvider` so the configured inquiry URL slug is a single source of truth across plugins (e.g. a menu link to the inquiry page resolves the current slug, not a hardcoded `/inquiry`).

## Installation

Open the admin panel under **Dashboard → Plugins**, find this plugin, then download and enable it. The plugin's tables are created automatically on enable, and the default URL slug (`inquiry`), default texts (from the active locale's translation files), and default role permissions for the inquiry admin menus are seeded at the same time.

## Usage

After enable, **Dashboard → Inquiries** appears in the admin sidebar.

- **List view** lists received inquiries with search, status filter (new / read), per-column sort, and bulk delete.
- **Detail** shows the full submission and lets you mark it as read or reply via your mail client.
- **Settings** (admin only) is split into tabs:
  - **Form basics** — URL slug, single-page vs split-step, field toggles, name-order, privacy-consent settings.
  - **Admin notification** — recipient address, subject template, body template.
  - **Auto-reply** — enabled toggle, from-email override, subject template, body template.
  - **Completion** — completion-page title and message shown after submission.
  - **CAPTCHA** — toggle and inline status of any attached CAPTCHA plugin.
  - **Throttle** — toggle, max attempts, decay minutes.

The public URL is composed from the configured slug. With the default `inquiry`, the form is reachable at `/inquiry`; if your site uses locale URL routing, the same form is mirrored at `/{locale}/inquiry`.

To embed the form inside another page (e.g. a DixlasePages static page), insert the `[dixlase-inquiry-form]` shortcode at the desired position. The host page's layout is preserved; only the form body is rendered inline.

## Capabilities

This plugin declares the following capabilities in `plugin.json` so other plugins can plug into it through stable contracts:

- **`captcha`** — Contract through which a CAPTCHA plugin attaches its widget to the inquiry form. The form is registered as `dixlase-inquiry:form` via `CaptchaFormProviderInterface`, so any compatible CAPTCHA plugin can opt in without modifying Inquiry.

## License

Dixlase Inquiry is distributed under a **dual license**:

- **Open Source License**: [GNU General Public License v3](./LICENSE)
- **Commercial License**: A separate commercial license is planned for use cases where GPL v3 compliance is not feasible. **It is not yet available** — only a draft of the eventual terms is present in [LICENSE-COMMERCIAL](./LICENSE-COMMERCIAL). For availability timing or other questions, contact **info@dixlase.org**.

A short overview of how these files fit together is in [NOTICE](./NOTICE) ([日本語](./NOTICE.ja)).

## Contributing

The Contributor License Agreement (CLA) is still under review, so code Pull Requests are not being accepted at this time. Once the CLA is finalized, contributions will open under the [Dixlase Copyright Policy](https://github.com/Dixlase/dixlase-core/blob/main/COPYRIGHT-POLICY.md) and the Dixlase CLA (see CONTRIBUTING.md). Bug reports and proposals via Issues are welcome in the meantime.

---
(C) exc-D inc. - 2026
