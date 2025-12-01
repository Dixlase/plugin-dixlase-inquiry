# DixlaseInquiry 埋め込みフォーム使用ガイド

## 概要

DixlaseInquiryプラグインは、問い合わせフォームをテーマやページに埋め込むための複数の方法を提供しています。

## 埋め込み方法

### 方法1: Bladeテンプレートで直接埋め込み

テーマのBladeテンプレートから直接フォームを埋め込む方法です。

```blade
{{-- プラグインの有効性チェック --}}
@php
    $inquirySettings = null;
    try {
        if (class_exists(\Plugins\DixlaseInquiry\App\Models\InquirySetting::class)) {
            $inquirySettings = \Plugins\DixlaseInquiry\App\Models\InquirySetting::getSettings();
        }
    } catch (\Exception $e) {
        // プラグインが無効な場合は何もしない
    }
@endphp

{{-- フォームの表示 --}}
@if($inquirySettings)
<section class="inquiry-section py-16 bg-gray-100 dark:bg-gray-800">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto">
            <h2 class="text-3xl font-bold text-center text-gray-900 dark:text-white mb-8">
                {{ __('dixlase-inquiry::front.form.heading') }}
            </h2>
            @include('dixlase-inquiry::front.inquiries.embed-form', ['settings' => $inquirySettings])
        </div>
    </div>
</section>
@endif
```

#### ポイント

- **プラグイン有効性チェック**: `class_exists()` でプラグインが有効かどうかを確認
- **例外処理**: プラグインが無効な場合のエラーを防止
- **設定の取得**: `InquirySetting::getSettings()` で管理画面の設定を取得
- **ビューの読み込み**: `@include('dixlase-inquiry::front.inquiries.embed-form', ['settings' => $inquirySettings])`

### 方法2: ショートコードで埋め込み

ページプラグイン（DixlasePages）のコンテンツ内でショートコードを使用する方法です。

```
[inquiry]
```

#### 使用例

1. 管理画面でページを作成
2. コンテンツエディタに `[inquiry]` と入力
3. ページを公開

ショートコードは `shortcode_parse()` 関数で処理されます。

## 埋め込みフォームの機能

### 確認画面

管理画面で「確認画面を表示」がONの場合、フォーム送信前に確認画面が表示されます。

- Alpine.jsを使用したクライアントサイド実装
- ページ遷移なしで確認画面を表示
- 「戻る」ボタンで入力画面に戻れる

### 表示項目

管理画面の設定に応じて、以下の項目が表示されます：

| 項目 | 設定キー | 必須設定 |
|------|---------|---------|
| 題名 | `show_subject` | `subject_required` |
| 電話番号 | `show_phone` | `phone_required` |
| 郵便番号 | `show_postal_code` | `postal_code_required` |
| 住所 | `show_address` | `address_required` |
| 性別 | `show_gender` | `gender_required` |

### 名前の表示順

- **日本式**: 姓 → 名（デフォルト）
- **欧米式**: 名 → 姓（`name_order_western` がONの場合）

## メール送信

フォーム送信時に以下のメールが送信されます：

### 管理者通知メール

- 送信先: 管理画面で設定した `admin_email`
- テンプレート: `dixlase-inquiry::emails.inquiry-admin`
- Laravel標準のHTMLメールテンプレートを使用

### 自動返信メール

- 送信先: フォームに入力されたメールアドレス
- 条件: `auto_reply_enabled` がONの場合
- テンプレート: `dixlase-inquiry::emails.inquiry-auto-reply`

## 送信後の動作

### 埋め込みフォームの場合

- 同じページにリダイレクト（`#inquiry-form` アンカー付き）
- セッションに `inquiry_success` フラグを設定
- 成功メッセージを表示

### 別ページモードの場合

- 完了画面（`complete.blade.php`）を表示
- 送信内容の確認と「トップページへ戻る」ボタンを表示

## カスタマイズ

### スタイルのカスタマイズ

埋め込みフォームはTailwind CSSクラスを使用しています。テーマのCSSでオーバーライド可能です。

```css
/* フォームコンテナ */
.dixlase-inquiry-embed {
    /* カスタムスタイル */
}

/* 成功メッセージ */
.dixlase-inquiry-embed .bg-green-100 {
    /* カスタムスタイル */
}
```

### 翻訳のカスタマイズ

翻訳キーは `dixlase-inquiry::front.*` 名前空間で定義されています。

```php
// lang/ja/front.php
return [
    'form' => [
        'heading' => 'お問い合わせ',
        'submit' => '送信する',
        'confirm' => '確認する',
        // ...
    ],
];
```

## 必要な依存関係

- **Alpine.js**: 確認画面の動的表示に使用
- **Tailwind CSS**: スタイリングに使用
- **Font Awesome**: アイコンに使用

これらはDixlaseDefaultThemeに含まれています。

## トラブルシューティング

### フォームが表示されない

1. DixlaseInquiryプラグインが有効になっているか確認
2. 管理画面で `admin_email` が設定されているか確認
3. `class_exists()` チェックが正しく動作しているか確認

### メールが送信されない

1. メールサーバー設定（`.env`）を確認
2. `storage/logs/laravel.log` でエラーを確認
3. Mailpitなどのローカルメールサーバーでテスト

### 確認画面が表示されない

1. 管理画面で「確認画面を表示」がONになっているか確認
2. Alpine.jsが正しく読み込まれているか確認
3. ブラウザのコンソールでJavaScriptエラーを確認
