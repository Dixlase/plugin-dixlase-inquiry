# Dixlase Inquiry

For Japanese, see [README.ja.md](./README.ja.md).

Contact-form plugin for Dixlase: a configurable inquiry form under a fixed URL prefix (`/inquiry` by default) with toggleable optional fields (kana, phone, postal code, address, gender, etc.), single-page or split-step submission, admin and customer email notifications with template variables, privacy-consent linking, CAPTCHA opt-in, per-IP submission throttling, and an embeddable shortcode for placing the form on any page.

## Features

- **Public inquiry form** — Configurable URL slug (default `/inquiry`). Single-page or split-step submission.
- **Toggleable fields** — name / email / message are always required; kana / subject / phone / postal code / address / gender have show / required toggles.
- **Name-order modes** — auto (locale-based) / western / Japanese.
- **Privacy consent** — Optional checkbox with a link target accepting a full URL or a server-absolute path.
- **Mail templates** — Admin notification and customer auto-reply, both with `{{variable}}` substitution.
- **CAPTCHA opt-in** — Compatible CAPTCHA plugins attach a widget via the `captcha` capability.
- **Submission throttling** — Per-IP rate limit with configurable max attempts and decay.
- **Inquiry management** — Admin list / detail / status / bulk delete.
- **Shortcode** — `[dixlase-inquiry-form]` embeds the form in any host page.
- **Role-based permission** — Per-menu role permissions.
- **Dashboard notifications** — New-inquiry count and CAPTCHA-not-enabled prompt.

## Installation

Open the admin panel under **Dashboard → Plugins**, find this plugin, then download and enable it. The plugin's tables are created automatically on enable.

## Usage

Once enabled, **Inquiries** appears in the admin sidebar with list / detail / settings screens.

The public URL is composed from the configured slug. With the default `inquiry`, the form is reachable at `/inquiry`.

To embed the form inside another page, insert the `[dixlase-inquiry-form]` shortcode at the desired position.

## Capabilities

This plugin declares the following capability in `plugin.json`:

- **`captcha`** — Contract for compatible CAPTCHA plugins to attach a widget to the inquiry form via `CaptchaFormProviderInterface`.

## License

Dixlase Inquiry is distributed under a **dual license**:

- **Open Source License**: [GNU General Public License v3](./LICENSE)
- **Commercial License**: A separate commercial license is planned for use cases where GPL v3 compliance is not feasible. **It is not yet available** — only a draft of the eventual terms is present in [LICENSE-COMMERCIAL](./LICENSE-COMMERCIAL). For availability timing or other questions, contact **info@dixlase.org**.

A short overview of how these files fit together is in [NOTICE](./NOTICE) ([日本語](./NOTICE.ja)).

## Contributing

We do not yet accept external code Pull Requests.  
They will open once we have assessed core API stability and how the project operates after the initial release, and prepared a Contributor License Agreement (CLA) that has passed legal review.  
Once the CLA is finalized, contributions will fall under the [Dixlase Copyright Policy](https://github.com/Dixlase/dixlase-core/blob/main/COPYRIGHT-POLICY.md) and the Dixlase CLA (see CONTRIBUTING.md).  
Bug reports and proposals via Issues are welcome.  
For feature proposals, please take a look at [the Dixlase philosophy](https://dixlase.org/en/philosophy) — and consider whether the feature belongs in the core or could work as a plugin. It helps us align on direction.

---

© 2026 exc-D inc. and Dixlase contributors
