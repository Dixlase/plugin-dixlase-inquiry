# 受付トグル: つまみが動かない

**種別:** UI バグ(微細な表示回帰)
**初出:** 2026-09-28、ワンライナーのインストール環境で問い合わせ設定概要画面
**優先度:** 低 — 動作自体は正しく、見た目の手掛かりだけが誤り

## 症状

`admin/inquiry/settings`(設定概要)の「問い合わせを受け付けています / 停止しています」トグルは:

- トラックの色は緑(受付中)⇔ 灰色(停止中)に正しく切り替わる。
- **白いつまみが動かない** — 受付中でも停止中でも右側に張り付いたまま。
- 内部状態と API 呼び出しは正しく動作している(ページを再読込するとアイコン・見出し・説明はきちんと切り替わる)。つまみの位置だけが固まっている。

参考スクショ: Desktop, 2026-09-28 22:40 の 2 枚。両方ともつまみが右にある。

## 該当箇所

`resources/views/admin/inquiry/settings/index.blade.php:73-79`

```blade
<button type="button" @click="toggleAccepting()" :disabled="isToggling"
    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50"
    :class="accepting ? 'bg-green-600' : 'bg-gray-300 dark:bg-gray-600'"
    role="switch" :aria-checked="accepting.toString()">
    <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
        :class="accepting ? 'translate-x-5' : 'translate-x-0'"></span>
</button>
```

Alpine の `:class` バインド自体は正しく、`translate-x-5`(右)と `translate-x-0`(左)を切り替える設計になっている。

## 原因の当たり

ユーティリティ `translate-x-5` と `translate-x-0` は Alpine の `:class` 式の中にのみ現れ、静的なクラストークンとしては書かれていない。Tailwind のコンテンツスキャンは単純な文字列一致でクラス名を抽出するため、動的な式の中だけに存在するユーティリティは環境次第でスキャンから漏れ、ビルド後の CSS から purge されうる。その結果クラス指定が実質無効になり、`translate-x-0` は空指定と同じになる。span は flex 行の中に置かれていて他の translate も無いので、初期レイアウトの位置(右)にそのまま留まる。

修正時に併せて確認したい候補:

1. 他所で衝突する `transform` ルール(現在のツリー内には見当たらない)。
2. 初回描画時の Alpine `:class` 評価タイミング(片方で固定されるような事象は本来起きにくい)。

## 修正案

- 推奨: 2 つの動的ユーティリティを、他の管理画面と揃えて **共通 `<x-form-toggle>` コンポーネント** に置き換える。つまみの動き、ARIA 属性、無効状態を一括で担保でき、コンポーネント側のクラストークンは常に静的なのでスキャン漏れが起きない。
- 現行ボタンを維持するなら、両方のクラスを Tailwind のスキャナが確実に認識できる形に揃える:
  - 明示的にセーフリスト化する(ファイル冒頭に Blade コメントで `class="translate-x-0 translate-x-5"` を書く、あるいは実 DOM 要素にどこかでこの 2 クラスを載せる)、
  - Alpine registered data + CSS 変数駆動の transform に切り替える、
  - `x-bind:style="{ transform: accepting ? 'translateX(1.25rem)' : 'translateX(0)' }"` に切り替える。

## 受入条件

- 受付状態の切替でつまみが左右に動くのが目視で確認できる。
- トラックの色は従来どおりアニメーション付きで切り替わる。
- ダークモードでも視認性が保たれる。
- キーボード操作(フォーカス中の Space / Enter)でもつまみが動く。
