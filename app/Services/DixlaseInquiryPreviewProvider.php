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

        // Name fields (order depends on locale setting)
        $nameWestern = filter_var($settings->name_order_western ?? false, FILTER_VALIDATE_BOOLEAN);
        $firstField = new PreviewFieldDTO(
            name: 'first_name',
            type: 'text',
            label: __('dixlase-inquiry::front.form.first_name'),
            required: true,
            placeholder: __('dixlase-inquiry::front.form.first_name_placeholder'),
            group: 'name',
            order: $order,
        );
        $lastField = new PreviewFieldDTO(
            name: 'last_name',
            type: 'text',
            label: __('dixlase-inquiry::front.form.last_name'),
            required: true,
            placeholder: __('dixlase-inquiry::front.form.last_name_placeholder'),
            group: 'name',
            order: $order,
        );
        if ($nameWestern) {
            $fields[] = $firstField;
            $fields[] = $lastField;
        } else {
            $fields[] = $lastField;
            $fields[] = $firstField;
        }
        $order++;

        // Kana fields (conditional)
        if (filter_var($settings->show_kana ?? false, FILTER_VALIDATE_BOOLEAN)) {
            $fields[] = new PreviewFieldDTO(
                name: 'last_name_kana',
                type: 'text',
                label: __('dixlase-inquiry::front.form.last_name').'（カナ）',
                required: false,
                group: 'kana',
                order: $order,
            );
            $fields[] = new PreviewFieldDTO(
                name: 'first_name_kana',
                type: 'text',
                label: __('dixlase-inquiry::front.form.first_name').'（カナ）',
                required: false,
                group: 'kana',
                order: $order,
            );
            $order++;
        }

        // Email
        $fields[] = new PreviewFieldDTO(
            name: 'email',
            type: 'email',
            label: __('dixlase-inquiry::front.form.email'),
            required: true,
            placeholder: __('dixlase-inquiry::front.form.email_placeholder'),
            order: $order++,
        );

        // Subject (conditional)
        if (filter_var($settings->show_subject ?? false, FILTER_VALIDATE_BOOLEAN)) {
            $fields[] = new PreviewFieldDTO(
                name: 'subject',
                type: 'text',
                label: __('dixlase-inquiry::front.form.subject'),
                required: filter_var($settings->subject_required ?? false, FILTER_VALIDATE_BOOLEAN),
                placeholder: __('dixlase-inquiry::front.form.subject_placeholder'),
                order: $order++,
            );
        }

        // Phone (conditional)
        if (filter_var($settings->show_phone ?? true, FILTER_VALIDATE_BOOLEAN)) {
            $fields[] = new PreviewFieldDTO(
                name: 'phone',
                type: 'tel',
                label: __('dixlase-inquiry::front.form.phone'),
                required: filter_var($settings->phone_required ?? false, FILTER_VALIDATE_BOOLEAN),
                placeholder: __('dixlase-inquiry::front.form.phone_placeholder'),
                order: $order++,
            );
        }

        // Address (conditional)
        if (filter_var($settings->show_address ?? false, FILTER_VALIDATE_BOOLEAN)) {
            $fields[] = new PreviewFieldDTO(
                name: 'address',
                type: 'text',
                label: __('dixlase-inquiry::front.form.address'),
                required: filter_var($settings->address_required ?? false, FILTER_VALIDATE_BOOLEAN),
                placeholder: __('dixlase-inquiry::front.form.address_placeholder'),
                order: $order++,
            );
        }

        // Message
        $fields[] = new PreviewFieldDTO(
            name: 'message',
            type: 'textarea',
            label: __('dixlase-inquiry::front.form.message'),
            required: true,
            placeholder: __('dixlase-inquiry::front.form.message_placeholder'),
            order: $order++,
            meta: ['rows' => 6],
        );

        $showConfirmation = filter_var($settings->show_confirmation_page ?? true, FILTER_VALIDATE_BOOLEAN);

        return new PreviewDTO(
            key: 'inquiry_form',
            type: PreviewProviderInterface::TYPE_FORM,
            title: __('dixlase-inquiry::front.form.heading'),
            source: 'dixlase-inquiry',
            fields: $fields,
            submitLabel: $showConfirmation
                ? __('dixlase-inquiry::front.buttons.confirm')
                : __('dixlase-inquiry::front.form.submit'),
            submitIcon: 'fas fa-paper-plane',
            meta: [
                'name_order_western' => $nameWestern,
                'show_confirmation_page' => $showConfirmation,
            ],
        );
    }
}
