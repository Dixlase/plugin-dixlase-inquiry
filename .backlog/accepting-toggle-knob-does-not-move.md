# Accepting-inquiries toggle: the knob does not move

**Type:** UI bug — minor visual regression
**First seen:** 2026-09-28 on the Inquiry settings overview screen (oneliner install)
**Priority:** low — behaviour is correct, only the visual affordance is wrong

## Symptom

On `admin/inquiry/settings` (settings index), the "accepting inquiries" toggle:

- Correctly switches its **track colour** between green (accepting) and grey (paused).
- **Does not** move the white knob between the on and off positions — it stays on the right in both states.
- The underlying state and the API call work: reload after toggling, and the icon / heading / description all flip. Only the knob position is stuck.

Screenshots on record (Desktop, 2026-09-28 22:40): the knob is on the right in both accepting and paused states.

## Where the toggle lives

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

The Alpine `:class` binding is correct in principle — it swaps `translate-x-5` (right) and `translate-x-0` (left) on the knob.

## Suspected cause

The utility classes `translate-x-5` and `translate-x-0` appear **only** inside an Alpine `:class` expression, never as static class tokens. Tailwind's content scan tokenises class names by simple text match; utilities that live exclusively inside a dynamic expression can be missed on some scanner configurations and get purged from the built CSS. The classes then resolve to nothing at runtime, `translate-x-0` becomes a no-op, and the knob's position is whatever the browser laid out by default (right, because the span is inside a flex row with no other translate).

Two other candidates worth ruling out during the fix pass:

1. A conflicting `transform` rule elsewhere (unlikely — none in the current tree).
2. Alpine `:class` evaluation timing on the first paint (unlikely to leave a permanent stuck state; a click should still flip it).

## Suggested fix

- Preferred: replace the two dynamic utilities with **the shared `<x-form-toggle>`** component (used across the rest of the admin). It handles the knob motion, ARIA attributes, and disabled state consistently, and its class tokens are always static so the scanner sees them.
- If keeping the local button, ensure both class values are visible to the Tailwind scanner. Either:
  - safelist them: emit both classes as static tokens somewhere Tailwind will read (a Blade comment with `class="translate-x-0 translate-x-5"` at the top of the file, or a real element that carries them), or
  - move the toggle to Alpine registered data + use CSS-variable-driven transform, or
  - switch to `x-bind:style="{ transform: accepting ? 'translateX(1.25rem)' : 'translateX(0)' }"`.

## Acceptance

- Toggle knob visibly moves left ↔ right as accepting state flips.
- Track colour still animates as before.
- Dark mode variant remains readable.
- Keyboard toggling (Space / Enter on the focused button) also moves the knob.
