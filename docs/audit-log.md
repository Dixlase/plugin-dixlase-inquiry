# Audit log

Operator-policy actions on the inquiry plugin (retention / privacy settings,
per-row expiry edits, trash restore / force-destroy / empty, scheduled or
manual `dls:inquiry:prune` runs, admin mail delivery failures) are recorded
to the core audit stream. Look for entries with `category=plugin` and
`plugin_name=dixlase-inquiry` in the admin **Settings → System → Logs →
Audit** screen.

Visitor submissions themselves are never written to the audit log — storing
them there would defeat the privacy promise `store_inquiries=false` makes.
The audit stream tracks who changed what and when at the operator level,
not what visitors sent.

---

# 監査ログ

問い合わせプラグインの管理者ポリシー操作(保存期間 / プライバシー設定、
個別行の有効期限編集、ゴミ箱の復元・永久削除・空化、スケジュール/手動の
`dls:inquiry:prune` 実行、管理者宛メール送信の失敗)はコアの監査
ストリームに記録されます。管理画面 **設定 → システム → ログ → 監査** で
`category=plugin`、`plugin_name=dixlase-inquiry` のエントリを探してください。

訪問者からの送信内容そのものは監査ログには書き込みません。これを書くと、
`store_inquiries=false` が約束する「保持しない」というプライバシー方針を
自身で破ることになります。監査ストリームが追うのは管理者レベルで
「誰が」「何を」「いつ」変更・削除したかであり、訪問者が送った内容では
ありません。
