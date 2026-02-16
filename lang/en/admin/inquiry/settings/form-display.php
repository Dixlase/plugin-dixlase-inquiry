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
    'heading' => 'Form Display Settings',
    'description' => 'Configure the display method (single/separate page) and URL slug for the inquiry form.',

    'form_type' => 'Form Display Method',
    'single_page' => 'Single Page (Dynamic confirmation and completion screens)',
    'separate_pages' => 'Separate Pages (Input, confirmation, and completion screens on separate pages)',
    'single_page_help' => 'Single page method processes everything from input to completion on one page.',

    'inquiry_url' => 'Inquiry Page URL',
    'inquiry_url_slug_help' => 'Set the URL for the inquiry page (e.g., inquiry -> /inquiry). Only lowercase letters, numbers, and hyphens are allowed.',
    'preview_page_help' => 'Opens the inquiry page in a new tab. You can preview before saving the settings.',

    'confirm_title' => 'Save Form Display Settings',
    'confirm_message' => 'Are you sure you want to save the form display settings?',
    'settings_updated' => 'Form display settings have been updated.',
];
