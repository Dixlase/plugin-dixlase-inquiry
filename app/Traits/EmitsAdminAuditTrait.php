<?php

/**
 * This file is part of DixlaseInquiry.
 *
 * Copyright (C) 2026 exc-D inc. and Dixlase contributors
 * https://exc-d.com
 *
 * DixlaseInquiry is dual-licensed. You may use this file under either:
 *
 *   (a) the GNU General Public License version 3 or later, as published
 *       by the Free Software Foundation; or
 *
 *   (b) a commercial license agreement obtained from exc-D inc.
 *
 * Unless you have entered into a commercial license agreement, this
 * file is governed by the GPL terms below.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

namespace Plugins\DixlaseInquiry\App\Traits;

use App\Facades\Audit;
use App\Models\AuditLog;
use App\Models\Member;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Emit audit entries for the plugin's administrator-policy actions.
 *
 * Scope — what belongs here:
 *   - settings changes (privacy, retention)
 *   - per-row retention edits
 *   - trash operations (restore, force-destroy, empty)
 *   - scheduled / manual prune runs
 *   - metadata of mail-delivery failures
 *
 * Scope — what does NOT belong here:
 *   - visitor submission content (name / email / message / phone).
 *     Writing those to `audit_logs` would re-create the storage that
 *     `store_inquiries=false` promises not to keep. Every call site must
 *     only pass identifiers (ids, counts, class names) in `$context`.
 */
trait EmitsAdminAuditTrait
{
    /**
     * Emit a plugin-scoped audit entry.
     *
     * The call is wrapped in try/catch so an audit-table outage never
     * blocks the business action that was being audited.
     *
     * @param  string  $action  Flat snake_case action slug (e.g. `inquiry_settings_privacy_updated`).
     * @param  Model|null  $target  Target model instance (or null for settings-level actions).
     * @param  array<string, mixed>  $context  JSON-serialisable metadata. **Must not contain PII.**
     * @param  string  $severity  AuditLog::SEVERITY_* constant.
     * @param  string  $outcome  AuditLog::OUTCOME_* constant.
     * @param  Member|null  $actor  Explicit actor; null falls back to the authenticated member guard.
     * @param  string|null  $targetLabel  Human-readable identifier when `$target` is null (e.g. a command label).
     */
    protected function emitPluginAudit(
        string $action,
        ?Model $target = null,
        array $context = [],
        string $severity = AuditLog::SEVERITY_NOTICE,
        string $outcome = AuditLog::OUTCOME_SUCCESS,
        ?Member $actor = null,
        ?string $targetLabel = null,
    ): void {
        try {
            $resolvedActor = $actor ?? Auth::guard('member')->user();

            $payload = [
                'severity' => $severity,
                'outcome' => $outcome,
                'actor' => $resolvedActor,
                'context' => $context,
                'plugin_name' => 'dixlase-inquiry',
                'plugin_version' => $this->resolveInquiryPluginVersion(),
            ];

            if ($target !== null) {
                $payload['target'] = $target;
            }
            if ($targetLabel !== null) {
                $payload['target_label'] = $targetLabel;
            }

            Audit::logPlugin($action, $payload);
        } catch (\Throwable) {
            // Deliberately swallowed: an audit-table write failure must
            // never prevent the business action from completing.
        }
    }

    /**
     * Read the plugin version from `plugin.json` once per request.
     *
     * The version is bumped by `scripts/bump-version.php` on release, so
     * hard-coding it would drift. Reading the manifest keeps the audit
     * entries honest about which plugin version produced them.
     */
    protected function resolveInquiryPluginVersion(): string
    {
        static $cached = null;

        if ($cached !== null) {
            return $cached;
        }

        $path = base_path('plugins/DixlaseInquiry/plugin.json');
        if (! is_file($path)) {
            return $cached = '0.0.0';
        }

        $manifest = json_decode((string) file_get_contents($path), true);

        return $cached = (string) ($manifest['version'] ?? '0.0.0');
    }
}
