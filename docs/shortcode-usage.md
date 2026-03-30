# DixlaseInquiry ショートコード使用ガイド

## 概要

DixlaseInquiryプラグインは `[inquiry]` ショートコードを提供しており、ページプラグイン（DixlasePages）のコンテンツ内で問い合わせフォームを簡単に埋め込むことができます。

## 基本的な使い方

### ステップ1: ページの作成

1. 管理画面にログイン
2. 「ページ」→「新規作成」を選択
3. タイトルとスラッグを入力

### ステップ2: ショートコードの挿入

コンテンツエディタに以下のショートコードを入力します：

```
[inquiry]
```

### ステップ3: ページの公開

ページを公開すると、ショートコードの位置に問い合わせフォームが表示されます。

## ショートコードの仕組み

### 処理フロー

```
1. ページコンテンツの読み込み
   ↓
2. shortcode_parse() 関数でショートコードを検出
   ↓
3. InquiryFormShortcode::render() を実行
   ↓
4. embed-form.blade.php をレンダリング
   ↓
5. HTMLに置換されて表示
```

### 関連ファイル

| ファイル | 役割 |
|---------|------|
| `app/Shortcodes/InquiryFormShortcode.php` | ショートコードの処理クラス |
| `resources/views/front/inquiries/embed-form.blade.php` | フォームのBladeテンプレート |
| `app/Providers/DixlaseInquiryServiceProvider.php` | ショートコードの登録 |

## ショートコードが使える場所

### DixlasePagesプラグイン

ページプラグインのコンテンツ内で使用できます。

```blade
{{-- plugins/DixlasePages/resources/views/front/page.blade.php --}}
{!! shortcode_parse($page->content) !!}
```

### テーマのフロントページコンテンツ

フロントページの編集可能コンテンツ内でも使用できます。

```blade
{{-- themes/DixlaseOnePage/resources/views/index.blade.php --}}
{!! shortcode_parse($frontPageContent) !!}
```

### カスタムテンプレート

任意のBladeテンプレートで `shortcode_parse()` を使用することで、ショートコードを有効にできます。

```blade
{!! shortcode_parse($content) !!}
```

## ショートコードの登録

DixlaseInquiryプラグインは起動時に自動的にショートコードを登録します。

```php
// app/Providers/DixlaseInquiryServiceProvider.php
protected function registerShortcodes(): void
{
    if (class_exists(\App\Helpers\PluginHelper::class)) {
        \App\Helpers\PluginHelper::registerShortcode(
            'inquiry',
            \Plugins\DixlaseInquiry\App\Shortcodes\InquiryFormShortcode::class
        );
    }
}
```

## ショートコードクラスの実装

```php
// app/Shortcodes/InquiryFormShortcode.php
namespace Plugins\DixlaseInquiry\App\Shortcodes;

use Plugins\DixlaseInquiry\App\Models\InquirySetting;

class InquiryFormShortcode
{
    public function render(array $attributes = []): string
    {
        $settings = InquirySetting::getSettings();
        
        if (!$settings || empty($settings->admin_email)) {
            return '';
        }
        
        try {
            return view('dixlase-inquiry::front.inquiries.embed-form', [
                'settings' => $settings,
            ])->render();
        } catch (\Exception $e) {
            \Log::error('InquiryFormShortcode render error: ' . $e->getMessage());
            return '';
        }
    }
}
```

## 将来の拡張: 属性のサポート

現在のショートコードは属性をサポートしていませんが、将来的に以下のような拡張が可能です：

```
[inquiry theme="dark" show_title="false"]
```

### 属性の実装例

```php
public function render(array $attributes = []): string
{
    $defaults = [
        'theme' => 'default',
        'show_title' => true,
    ];
    
    $attributes = array_merge($defaults, $attributes);
    
    // 属性を使用してレンダリング
    return view('dixlase-inquiry::front.inquiries.embed-form', [
        'settings' => $settings,
        'theme' => $attributes['theme'],
        'showTitle' => $attributes['show_title'],
    ])->render();
}
```

## ショートコードを有効にする方法

### 新しいテーマで有効にする

テーマのBladeテンプレートでコンテンツを表示する際に `shortcode_parse()` を使用します。

```blade
{{-- コンテンツ内のショートコードを処理 --}}
<div class="content">
    {!! shortcode_parse($content) !!}
</div>
```

### 新しいプラグインで有効にする

プラグインのビューでショートコードを有効にする場合：

```blade
{{-- プラグインのビューでショートコードを処理 --}}
<article>
    {!! shortcode_parse($article->body) !!}
</article>
```

## shortcode_parse() 関数

### 定義

```php
// app/helpers.php
function shortcode_parse(string $content): string
{
    return app(\App\Services\ShortcodeManager::class)->parse($content);
}
```

### ShortcodeManagerの動作

```php
// app/Services/ShortcodeManager.php
public function parse(string $content): string
{
    // [tagname attr="value"] 形式のショートコードを検出
    $pattern = '/\[(\w+)([^\]]*)\]/';
    
    return preg_replace_callback($pattern, function ($matches) {
        $tag = $matches[1];
        $attributes = $this->parseAttributes($matches[2] ?? '');
        
        if (isset($this->shortcodes[$tag])) {
            $shortcode = app($this->shortcodes[$tag]);
            return $shortcode->render($attributes);
        }
        
        return $matches[0]; // 未登録のショートコードはそのまま
    }, $content);
}
```

## トラブルシューティング

### ショートコードが処理されない

1. **shortcode_parse() が使用されているか確認**
   ```blade
   {{-- NG: ショートコードが処理されない --}}
   {{ $content }}
   
   {{-- OK: ショートコードが処理される --}}
   {!! shortcode_parse($content) !!}
   ```

2. **プラグインが有効か確認**
   - 管理画面 → 設定 → プラグイン で DixlaseInquiry が有効になっているか確認

3. **ショートコードの書式を確認**
   ```
   [inquiry]  ← 正しい
   [ inquiry ] ← スペースがあると動作しない
   {{inquiry}} ← 形式が違う
   ```

### フォームが空で表示される

1. **管理画面で設定を確認**
   - 設定 → お問い合わせ で `admin_email` が設定されているか確認

2. **ログを確認**
   ```bash
   tail -f storage/logs/laravel.log
   ```

### ショートコードがそのまま表示される

1. **ShortcodeManagerにショートコードが登録されているか確認**
   ```php
   // tinkerで確認
   app(\App\Services\ShortcodeManager::class)->getShortcodes();
   ```

2. **プラグインのServiceProviderが正しく読み込まれているか確認**
   - `config/app.php` または `plugins.json` を確認

## 他のショートコードとの共存

複数のプラグインがショートコードを提供する場合、それぞれ異なるタグ名を使用する必要があります。

```
[inquiry]     ← DixlaseInquiry
[gallery]     ← 別のプラグイン
[map]         ← 別のプラグイン
```

同じコンテンツ内で複数のショートコードを使用できます：

```
お問い合わせは以下のフォームからお願いします。

[inquiry]

アクセスマップ：

[map location="Tokyo"]
```
