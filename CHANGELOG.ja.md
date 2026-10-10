# 変更履歴

Dixlase Inquiry プラグインの主要な変更はすべてこのファイルに記録します。

フォーマットは [Keep a Changelog](https://keepachangelog.com/en/1.1.0/) に準拠し、
本プラグインはセマンティックバージョニングに従います。

## [0.2.0] — 2026-10-10

### 追加

- 問い合わせの保存をオプトインにするトグル(`store_inquiries`、初期値は **オフ**)と、
  専用の **プライバシー** 設定ページ(#49)。トグルがオフのときは従来どおりメール
  配送のみを行い、`plg_dixlase_inquiries` に行を書き込まない。
- 保存期間ポリシー: `retention_days`(初期値 `90`、`null` は無期限)と、
  `plg_dixlase_inquiries` テーブルの新しい `expires_at` カラム。保存期間フォームは
  プリセット(30 / 90 / 180 / 365 日、無期限)と任意の値(1〜3650)を用意する。
- 問い合わせ詳細画面で 1 件ごとに有効期限を編集できる機能 — 全体ポリシーに触れずに
  個別の有効期限を変更できる。プリセットの日数は送信日ではなく「今日」から数える。
- 保存期間を過ぎた行の自動削除用の Artisan コマンド `dls:inquiry:prune`。
  `--soft`(ハード削除ではなくゴミ箱へソフト削除)と `--dry-run`(削除せず対象件数を
  数える)を備える。`chunkById(500)` で逐次処理するため、大量の滞留があっても
  メモリが跳ねない。
- `dls:inquiry:prune` を毎日 03:15 に実行するスケジュール登録。プラグインの
  ServiceProvider から Laravel の `Schedule` ファサード経由で登録する。

### 変更

- **初期挙動の変更:** サイト側が明示的にオプトインしない限り、問い合わせはデータ
  ベースに保存しなくなった。メール配送の挙動は従来どおり。既存の行はそのまま残るが、
  新しい送信はプライバシー設定ページで `store_inquiries` をオンにするまで保存され
  ない。
- `plugin.json`: `system.register_commands` と `declares.commands` を宣言(本プラグ
  インが Artisan コマンドを提供するようになったため)し、既存の設定書き込みコード
  パスに合うよう `permissions.settings.write_own` を `true` に修正した。

## [0.1.1] — 2026-10-01

### 変更

- ビルドツール: `vite` を 5 から 8.3.1 に更新し、`esbuild` と `postcss`(8.5.28)も
  あわせて更新(#42)。これらのパッケージに出ていた Dependabot の勧告を解消した。
  どれも開発サーバと画面用ファイルのビルドだけに関わるもので、サイトに配布される
  ものには含まれない。リリース ZIP のビルド済みファイルは同じビルドで作られ、
  ハッシュ付きのファイル名だけが変わる。

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
