# 変更履歴

Dixlase Inquiry プラグインの主要な変更はすべてこのファイルに記録します。

フォーマットは [Keep a Changelog](https://keepachangelog.com/en/1.1.0/) に準拠し、
本プラグインはセマンティックバージョニングに従います。

## [0.1.1] — 2026-09-27

### 変更

- インストール済みサイトでプラグインの更新とロールバックを検証するためのリリース。
  バージョン番号以外に機能の変更はありません。

## [0.1.0] — 2026-10-01
初回リリース。Dixlase `^0.1.0`（Plugin API `^0.1`）、PHP `>= 8.3` が必要です。

### 追加

- 管理 UI とフロントエンドのフォームルートを備えた問い合わせ／コンタクト
  フォーム。送信内容はプラグイン専用テーブルに保存。
- ページコンテンツ内に問い合わせフォームを埋め込むショートコード。
- CAPTCHA 連携（`captcha` capability / `CaptchaFormProviderInterface`） —
  コアの reCAPTCHA v3 および Cloudflare Turnstile ドライバに対応。
- 問い合わせフォームで CAPTCHA が有効になっていない場合に警告する、
  ダッシュボード通知（`DashboardNotificationProviderInterface`）。
- 設定可能なフォーム URL のために `RouteSlugProvider` 契約を実装。
