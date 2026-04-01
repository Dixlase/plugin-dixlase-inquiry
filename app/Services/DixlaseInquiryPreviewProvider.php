<?php

/**
 * This file is part of Dixlase.
 *
 * Copyright (C) 2026 exc-D inc.
 * https://exc-d.com
 *
 * Licensed under the GPL-3.0 License.
 * See LICENSE file in the plugin root for details.
 */

namespace Plugins\DixlaseInquiry\App\Services;

use App\Contracts\PluginIntegration\PreviewProviderInterface;
use App\DTO\PluginIntegration\PreviewDTO;
use App\DTO\PluginIntegration\PreviewFieldDTO;
use Plugins\DixlaseInquiry\App\Models\DixlaseInquirySetting;

/**
 * Provides preview data for the inquiry form
 *
 * Translates inquiry plugin settings into core PreviewDTO/PreviewFieldDTO
 * so themes can render an accurate form preview without referencing plugin internals.
 */
class DixlaseInquiryPreviewProvider implements PreviewProviderInterface
{
    public function getPluginSlug(): string
    {
        return 'dixlase-inquiry';
    }

    public function isCapabilityAvailable(): bool
    {
        try {
            return DixlaseInquirySetting::getSettings() !== null;
        } catch (\Exception) {
            return false;
        }
    }

    /**
     * @return string[]
     */
    public function getPreviewKeys(): array
    {
        return ['inquiry_form'];
    }

    public function getPreview(string $key): ?PreviewDTO
    {
        if ($key !== 'inquiry_form') {
            return null;
        }

        return $this->buildFormPreview();
    }

    /**
     * @return PreviewDTO[]
     */
    public function getPreviews(): array
    {
        return array_filter([$this->buildFormPreview()]);
    }

    private function buildFormPreview(): ?PreviewDTO
    {
        try {
            $settings = DixlaseInquirySetting::getSettings();
        } catch (\Exception) {
            return null;
        }

        if (! $settings) {
            return null;
        }

        $fields = [];
        $order = 0;
        $bool = fn ($key, $default = false) => filter_var($settings->$key ?? $default, FILTER_VALIDATE_BOOLEAN);
        $nameWestern = $bool('name_order_western');
        $locale = $this->resolveLocale($settings->lang ?? 'auto');

        /** @var \Closure(string, array<string, string>): string $t ロケール指定付き翻訳ヘルパー */
        $t = fn (string $key, array $replace = []): string => __($key, $replace, $locale);

        // Name fields (order depends on locale setting)
        $firstField = new PreviewFieldDTO(
            name: 'first_name', type: 'text',
            label: $t('dixlase-inquiry::front.form.first_name'),
            required: true, group: 'name', order: $order,
        );
        $lastField = new PreviewFieldDTO(
            name: 'last_name', type: 'text',
            label: $t('dixlase-inquiry::front.form.last_name'),
            required: true, group: 'name', order: $order,
        );
        $fields = $nameWestern ? [$firstField, $lastField] : [$lastField, $firstField];
        $order++;

        // Kana fields (conditional)
        if ($bool('show_kana')) {
            $kanaRequired = $bool('require_kana');
            $kanaLast = new PreviewFieldDTO(
                name: 'last_name_kana', type: 'text',
                label: $t('dixlase-inquiry::front.form.last_name').'（カナ）',
                required: $kanaRequired, group: 'kana', order: $order,
            );
            $kanaFirst = new PreviewFieldDTO(
                name: 'first_name_kana', type: 'text',
                label: $t('dixlase-inquiry::front.form.first_name').'（カナ）',
                required: $kanaRequired, group: 'kana', order: $order,
            );
            $fields[] = $nameWestern ? $kanaFirst : $kanaLast;
            $fields[] = $nameWestern ? $kanaLast : $kanaFirst;
            $order++;
        }

        // Email
        $fields[] = new PreviewFieldDTO(
            name: 'email', type: 'email',
            label: $t('dixlase-inquiry::front.form.email'),
            required: true, order: $order++,
        );

        // Email confirmation
        $fields[] = new PreviewFieldDTO(
            name: 'email_confirmation', type: 'email',
            label: $t('dixlase-inquiry::front.form.email_confirmation'),
            required: true, order: $order++,
        );

        // Postal code (conditional) — controlled by show_address
        if ($bool('show_address')) {
            $fields[] = new PreviewFieldDTO(
                name: 'postal_code_1', type: 'text',
                label: $t('dixlase-inquiry::front.form.postal_code'),
                required: $bool('postal_code_required'), group: 'postal_code', order: $order,
                meta: ['width' => 'narrow'],
            );
            $fields[] = new PreviewFieldDTO(
                name: 'postal_code_2', type: 'text',
                label: '-', required: $bool('postal_code_required'),
                group: 'postal_code', order: $order,
                meta: ['width' => 'narrow'],
            );
            $order++;
        }

        // Address (conditional)
        if ($bool('show_address')) {
            $addrRequired = $bool('address_required');
            if (! $nameWestern) {
                // Japanese address format: prefecture, city, street, building
                $fields[] = new PreviewFieldDTO(
                    name: 'prefecture', type: 'select',
                    label: $t('dixlase-inquiry::front.form.prefecture'),
                    required: $addrRequired, order: $order++,
                );
                $fields[] = new PreviewFieldDTO(
                    name: 'city', type: 'text',
                    label: $t('dixlase-inquiry::front.form.city'),
                    required: $addrRequired, order: $order++,
                );
                $fields[] = new PreviewFieldDTO(
                    name: 'street_address', type: 'text',
                    label: $t('dixlase-inquiry::front.form.street_address'),
                    required: $addrRequired, order: $order++,
                );
                $fields[] = new PreviewFieldDTO(
                    name: 'building', type: 'text',
                    label: $t('dixlase-inquiry::front.form.building'),
                    required: false, order: $order++,
                );
            } else {
                // Western address format: single address line
                $fields[] = new PreviewFieldDTO(
                    name: 'address', type: 'text',
                    label: $t('dixlase-inquiry::front.form.address'),
                    required: $addrRequired, order: $order++,
                );
            }
        }

        // Phone (conditional - Japanese style with 3 split fields)
        if ($bool('show_phone', true)) {
            $phoneRequired = $bool('phone_required');
            if (! $nameWestern) {
                $fields[] = new PreviewFieldDTO(
                    name: 'phone_1', type: 'tel',
                    label: $t('dixlase-inquiry::front.form.phone'),
                    required: $phoneRequired, group: 'phone', order: $order,
                    meta: ['width' => 'narrow'],
                );
                $fields[] = new PreviewFieldDTO(
                    name: 'phone_2', type: 'tel',
                    label: '-', required: $phoneRequired,
                    group: 'phone', order: $order,
                    meta: ['width' => 'narrow'],
                );
                $fields[] = new PreviewFieldDTO(
                    name: 'phone_3', type: 'tel',
                    label: '-', required: $phoneRequired,
                    group: 'phone', order: $order,
                    meta: ['width' => 'narrow'],
                );
            } else {
                $fields[] = new PreviewFieldDTO(
                    name: 'phone', type: 'tel',
                    label: $t('dixlase-inquiry::front.form.phone'),
                    required: $phoneRequired, order: $order,
                );
            }
            $order++;
        }

        // Gender (conditional)
        if ($bool('show_gender')) {
            $genderOptions = [
                'male' => $t('dixlase-inquiry::front.form.gender_male'),
                'female' => $t('dixlase-inquiry::front.form.gender_female'),
            ];
            if ($bool('show_gender_other')) {
                $genderOptions['other'] = $t('dixlase-inquiry::front.form.gender_other');
            }
            if ($bool('show_gender_prefer_not_to_say')) {
                $genderOptions['prefer_not_to_say'] = $t('dixlase-inquiry::front.form.gender_prefer_not_to_say');
            }
            $fields[] = new PreviewFieldDTO(
                name: 'gender', type: 'radio_card',
                label: $t('dixlase-inquiry::front.form.gender'),
                required: $bool('gender_required'), order: $order++,
                options: $genderOptions,
            );
        }

        // Subject (conditional)
        if ($bool('show_subject')) {
            $fields[] = new PreviewFieldDTO(
                name: 'subject', type: 'text',
                label: $t('dixlase-inquiry::front.form.subject'),
                required: $bool('subject_required'), order: $order++,
            );
        }

        // Message
        $fields[] = new PreviewFieldDTO(
            name: 'message', type: 'textarea',
            label: $t('dixlase-inquiry::front.form.message'),
            required: true, order: $order++,
            meta: ['rows' => 6],
        );

        // Privacy consent checkbox (conditional)
        if ($bool('privacy_consent_enabled')) {
            $privacyUrl = $settings->privacy_policy_url ?? '';
            $customText = $settings->privacy_consent_text ?? '';
            $consentLabel = $customText ?: (
                $privacyUrl
                    ? $t('dixlase-inquiry::front.form.privacy_consent', ['url' => $privacyUrl])
                    : $t('dixlase-inquiry::front.form.privacy_consent_default')
            );

            $fields[] = new PreviewFieldDTO(
                name: 'privacy_agreed', type: 'checkbox',
                label: $consentLabel,
                required: true, order: $order++,
                meta: [
                    'privacy_policy_url' => $privacyUrl,
                    'custom_text' => $customText,
                ],
            );
        }

        $showConfirmation = filter_var($settings->show_confirmation_page ?? true, FILTER_VALIDATE_BOOLEAN);

        $formHeading = ! empty($settings->form_heading) ? $settings->form_heading : '';
        $formDescription = ! empty($settings->form_description) ? $settings->form_description : null;

        return new PreviewDTO(
            key: 'inquiry_form',
            type: PreviewProviderInterface::TYPE_FORM,
            title: $formHeading,
            description: $formDescription,
            source: 'dixlase-inquiry',
            fields: $fields,
            submitLabel: $showConfirmation
                ? $t('dixlase-inquiry::front.buttons.confirm')
                : $t('dixlase-inquiry::front.form.submit'),
            submitIcon: 'fas fa-paper-plane',
            meta: [
                'name_order_western' => $nameWestern,
                'show_confirmation_page' => $showConfirmation,
            ],
        );
    }

    /**
     * lang設定値からロケールを解決する
     */
    private function resolveLocale(string $lang): string
    {
        if ($lang === 'auto' || $lang === '') {
            return app()->getLocale();
        }

        return $lang;
    }
}
