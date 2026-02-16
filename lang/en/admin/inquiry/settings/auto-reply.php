<?php

/**
 * This file is part of Dixlase Inquiry.
 *
 * Copyright (C) 2026 exc-D inc.
 * https://exc-d.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

return [
    'heading' => 'Auto-Reply Settings',
    'description' => 'Configure auto-reply emails sent to users after they submit the inquiry form.',

    'enabled' => 'Enable Auto-Reply',
    'from_email' => 'Auto-Reply From Email Address',
    'from_email_help' => 'If empty, the system default sender address will be used.',
    'subject' => 'Auto-Reply Subject',
    'body' => 'Auto-Reply Message Body',
    'body_help' => 'Available variables: {{name}}, {{email}}, {{subject}}, {{postal_code}}, {{address}}, {{phone}}, {{message}}',

    'confirm_title' => 'Save Auto-Reply Settings',
    'confirm_message' => 'Are you sure you want to save the auto-reply settings?',
    'settings_updated' => 'Auto-reply settings have been updated.',
];
