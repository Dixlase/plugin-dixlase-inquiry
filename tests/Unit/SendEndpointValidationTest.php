<?php

/**
 * This file is part of the Dixlase Inquiry plugin.
 *
 * Copyright (C) 2026 exc-D inc. and Dixlase contributors
 * https://exc-d.com
 */

namespace Plugins\DixlaseInquiry\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Plugins\DixlaseInquiry\App\Http\Controllers\Front\DixlaseInquiryFrontController;
use Plugins\DixlaseInquiry\App\Http\Requests\DixlaseInquirySubmitRequest;
use Plugins\DixlaseInquiry\App\Http\Requests\Front\DixlaseInquiryEmbedSendRequest;
use ReflectionMethod;
use Tests\TestCase;

/**
 * inquiry.send is the only unauthenticated write endpoint this plugin exposes.
 * It used to take a bare Illuminate\Http\Request and call $request->all(), so
 * nothing was validated: no required fields, no length limits, no type checks,
 * and -- because the Form Request is what pulls in VerifiesCaptcha -- no
 * CAPTCHA either. The auto-reply mails an address taken straight from the
 * submission, so an unvalidated endpoint also made the site a relay that could
 * be pointed at arbitrary recipients.
 *
 * The route is registered only when use_single_page is off, which is a
 * supported admin toggle rather than an exotic configuration. These tests pin
 * the validation regardless of the current setting.
 */
class SendEndpointValidationTest extends TestCase
{
    // rules() reads the plugin settings row, so the schema has to exist.
    use RefreshDatabase;

    /**
     * The security property is the type hint: with a Form Request, Laravel
     * validates before the controller body runs. With a bare Request it does
     * not, and no amount of care inside the method makes up for it.
     */
    public function test_send_is_type_hinted_with_the_validating_form_request(): void
    {
        $parameters = (new ReflectionMethod(DixlaseInquiryFrontController::class, 'send'))->getParameters();

        $this->assertNotEmpty($parameters, 'send() must accept a request object.');

        $type = $parameters[0]->getType();

        $this->assertNotNull($type, 'send() must type-hint its request parameter.');
        $this->assertSame(
            DixlaseInquirySubmitRequest::class,
            $type->getName(),
            'send() must take the Form Request. A bare Illuminate\Http\Request skips validation and the CAPTCHA rules entirely.'
        );
    }

    /**
     * embedSend() has always validated. Pinning it alongside send() records
     * that the two submission paths are expected to be equally guarded -- the
     * bug was that only one of them was.
     */
    public function test_embed_send_is_also_validated(): void
    {
        $parameters = (new ReflectionMethod(DixlaseInquiryFrontController::class, 'embedSend'))->getParameters();
        $type = $parameters[0]->getType();

        $this->assertNotNull($type);
        $this->assertStringContainsString(
            'Request',
            $type->getName(),
            'embedSend() must keep taking a Form Request.'
        );
        $this->assertNotSame(
            \Illuminate\Http\Request::class,
            $type->getName(),
            'A bare Request would mean this path validates nothing either.'
        );
    }

    /**
     * The confirm step sits between the form and send(). CAPTCHA tokens are
     * single-use, so if the confirmation form does not re-emit the token the
     * newly-added validation would reject every legitimate submission. This
     * asserts the forwarding exists -- a fix that locks out real users is not
     * a fix.
     */
    public function test_confirm_view_forwards_the_captcha_token(): void
    {
        $view = file_get_contents(
            dirname(__DIR__, 2).'/resources/views/front/inquiries/confirm.blade.php'
        );

        $this->assertStringContainsString(
            '$captchaFields',
            $view,
            'The confirmation form must re-emit the CAPTCHA response so the single-use token survives the hop to send().'
        );
    }

    /**
     * The rules are settings-driven, so this only pins the fields that exist
     * unconditionally. Their absence would mean the Form Request had been
     * gutted while keeping its name.
     */
    public function test_core_fields_are_required(): void
    {
        $rules = (new DixlaseInquirySubmitRequest())->rules();

        foreach (['last_name', 'first_name', 'email', 'message'] as $field) {
            $this->assertArrayHasKey($field, $rules, "{$field} must be validated.");

            $serialised = is_array($rules[$field]) ? implode('|', $rules[$field]) : $rules[$field];
            $this->assertStringContainsString('required', $serialised, "{$field} must be required.");
        }

        $this->assertStringContainsString(
            'email',
            is_array($rules['email']) ? implode('|', $rules['email']) : $rules['email'],
            'The address the auto-reply is sent to must be validated as an email address.'
        );
    }

    /**
     * VerifiesCaptcha only verifies the token when its withValidator() hook
     * is the one Laravel calls. A request that declared its own would
     * shadow it silently, and the CAPTCHA would be back to a presence check.
     */
    public function test_both_requests_leave_the_captcha_hook_to_the_trait(): void
    {
        foreach ([DixlaseInquirySubmitRequest::class, DixlaseInquiryEmbedSendRequest::class] as $class) {
            $method = new ReflectionMethod($class, 'withValidator');
            $this->assertStringEndsWith(
                'VerifiesCaptcha.php',
                (string) $method->getFileName(),
                "{$class} must not override withValidator(); the trait's hook is what verifies the token."
            );
        }
    }

    /**
     * The form key gates verification on the form's own CAPTCHA setting and
     * lets reCAPTCHA Enterprise match the action the widget was rendered with.
     */
    public function test_both_requests_name_the_inquiry_captcha_form(): void
    {
        foreach ([DixlaseInquirySubmitRequest::class, DixlaseInquiryEmbedSendRequest::class] as $class) {
            $method = new ReflectionMethod($class, 'captchaFormKey');
            $method->setAccessible(true);
            $this->assertSame('dixlase-inquiry.inquiry_contact', $method->invoke(new $class()));
        }
    }
}
