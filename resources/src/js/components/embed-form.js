// 問い合わせフォーム埋め込み用Alpine.jsコンポーネント（CSP厳格モード対応）

/**
 * 問い合わせフォームのAlpine.jsコンポーネント
 * @param {boolean} showConfirmationPage - 確認画面を表示するかどうか
 * @param {boolean} nameOrderWestern - 名前の順序が欧米式かどうか
 * @returns {object} Alpine.jsコンポーネントオブジェクト
 */
// Ask for JSON explicitly so a validation failure comes back as JSON in every
// core configuration; the body is read by extractErrors() below.
const FETCH_HEADERS = { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' };

/**
 * Pull the field => messages map out of a failed response body.
 * Laravel's default validation payload is `{ errors }`; Dixlase's API error
 * envelope (bootstrap/app.php, used when the request asks for JSON) is
 * `{ error: { details } }`. Returns null when neither is present.
 */
function extractErrors(data) {
    if (!data || typeof data !== 'object') {
        return null;
    }
    if (data.errors && typeof data.errors === 'object') {
        return data.errors;
    }
    if (data.error && data.error.details && typeof data.error.details === 'object') {
        return data.error.details;
    }
    return null;
}

/**
 * Give the visitor a fresh CAPTCHA token for the retry. Tokens are single-use,
 * so resubmitting after a failure with the same token is rejected as
 * "timeout-or-duplicate". Prefers the core-provided hook and falls back to
 * the provider APIs the widget scripts expose.
 */
function resetCaptchaWidget() {
    try {
        if (typeof window.dixlaseCaptchaReset === 'function') {
            window.dixlaseCaptchaReset();
            return;
        }
        if (window.turnstile && typeof window.turnstile.reset === 'function') {
            window.turnstile.reset();
        }
        if (window.grecaptcha && typeof window.grecaptcha.reset === 'function') {
            window.grecaptcha.reset();
        }
    } catch {
        // Nothing to recover here: the widget is rebuilt on the next page load.
    }
}

export function createInquiryEmbedForm(showConfirmationPage = true, nameOrderWestern = false) {
    if (!showConfirmationPage) {
        // 確認画面なしの場合は最低限のAJAX送信機能のみ
        return {
            currentView: 'form',
            isTransitioning: false,
            isSubmitting: false,

            async submitFormAjax() {
                if (this.isSubmitting) {
                    return;
                }
                const form = this.$refs.inquiryForm;
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }
                this.isSubmitting = true;
                try {
                    const formData = new FormData(form);
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: FETCH_HEADERS,
                    });
                    // A followed redirect (expired session, maintenance page)
                    // is a 200 too, but not a completed submission.
                    if (response.ok && !response.redirected) {
                        await this.transitionTo('complete');
                    } else {
                        const errors = extractErrors(await response.json().catch(() => null));
                        if (errors) {
                            this.showValidationErrors(errors);
                            resetCaptchaWidget();
                            this.scrollToErrors();
                        } else {
                            form.submit();
                        }
                    }
                } catch {
                    form.submit();
                } finally {
                    this.isSubmitting = false;
                }
            },

            showValidationErrors(errors) {
                // $root, not $el: the handlers run on the submit button /
                // form, and Alpine v3 resolves $el to that element, so a
                // querySelector from $el never finds the box at the section top.
                const container = this.$root.querySelector('.inquiry-errors');
                if (!container) {
                    return;
                }
                const messages = Object.values(errors).flat();
                container.innerHTML = '<ul class="list-disc list-inside">' +
                    messages.map(m => '<li>' + m + '</li>').join('') + '</ul>';
                container.classList.remove('hidden');
            },

            scrollToErrors() {
                const container = this.$root.querySelector('.inquiry-errors');
                (container || this.$root).scrollIntoView({ behavior: 'smooth', block: 'center' });
            },

            async transitionTo(view) {
                const container = this.$refs.heightContainer;
                const oldH = container ? container.scrollHeight : 0;
                if (container) {
                    container.style.height = oldH + 'px';
                }
                this.isTransitioning = true;
                await this.sleep(300);
                if (container) {
                    container.classList.remove('transition-[height]');
                }
                this.currentView = view;
                await this.$nextTick();
                await this.nextFrame();
                if (container) {
                    container.style.height = 'auto';
                    const newH = container.scrollHeight;
                    container.style.height = oldH + 'px';
                    container.classList.add('transition-[height]');
                    await this.nextFrame();
                    container.style.height = newH + 'px';
                    await this.sleep(300);
                    container.style.height = 'auto';
                }
                await this.nextFrame();
                this.isTransitioning = false;
                this.$root.scrollIntoView({ behavior: 'smooth', block: 'start' });
            },

            sleep(ms) {
                return new Promise(resolve => setTimeout(resolve, ms));
            },

            nextFrame() {
                return new Promise(resolve => requestAnimationFrame(() => requestAnimationFrame(resolve)));
            },
        };
    }

    return {
        currentView: 'form',
        isTransitioning: false,
        isSubmitting: false,
        formData: {
            subject: '',
            first_name: '',
            last_name: '',
            last_name_kana: '',
            first_name_kana: '',
            email: '',
            postal_code: '',
            address: '',
            phone: '',
            gender: '',
            genderLabel: '',
            message: ''
        },
        nameOrderWestern: nameOrderWestern,

        get fullName() {
            if (this.nameOrderWestern) {
                return (this.formData.first_name + ' ' + this.formData.last_name).trim();
            }
            return (this.formData.last_name + ' ' + this.formData.first_name).trim();
        },

        /**
         * ビュー切り替え（フェードアウト→高さアニメーション→フェードイン）
         */
        async transitionTo(view) {
            const container = this.$refs.heightContainer;
            const oldHeight = container ? container.scrollHeight : 0;

            // 現在の高さを固定（auto → 具体的なpx値）
            if (container) {
                container.style.height = oldHeight + 'px';
            }

            // フェードアウト
            this.isTransitioning = true;
            await this.sleep(300);

            // トランジション無効化してビュー切り替え
            if (container) {
                container.classList.remove('transition-[height]');
            }
            this.currentView = view;
            await this.$nextTick();
            await this.nextFrame();

            if (container) {
                // 同期的に: auto→測定→旧高さに戻す（フレームをまたがないのでFOUC無し）
                container.style.height = 'auto';
                const newHeight = container.scrollHeight;
                container.style.height = oldHeight + 'px';

                // トランジションを再有効化してアニメーション
                container.classList.add('transition-[height]');
                await this.nextFrame();
                container.style.height = newHeight + 'px';
                await this.sleep(300);
                container.style.height = 'auto';
            }

            // フェードイン開始
            await this.nextFrame();
            this.isTransitioning = false;
            this.$root.scrollIntoView({ behavior: 'smooth', block: 'start' });
        },

        sleep(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        },

        nextFrame() {
            return new Promise(resolve => requestAnimationFrame(() => requestAnimationFrame(resolve)));
        },

        showConfirm() {
            const form = this.$refs.inquiryForm;
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // フォームデータを収集
            this.formData.subject = form.querySelector('[name="subject"]')?.value || '';
            this.formData.first_name = form.querySelector('[name="first_name"]')?.value || '';
            this.formData.last_name = form.querySelector('[name="last_name"]')?.value || '';
            this.formData.email = form.querySelector('[name="email"]')?.value || '';
            this.formData.last_name_kana = form.querySelector('[name="last_name_kana"]')?.value || '';
            this.formData.first_name_kana = form.querySelector('[name="first_name_kana"]')?.value || '';
            this.formData.message = form.querySelector('[name="message"]')?.value || '';

            // 郵便番号の収集
            if (this.nameOrderWestern) {
                this.formData.postal_code = form.querySelector('[name="postal_code"]')?.value || '';
            } else {
                const pc1 = form.querySelector('[name="postal_code_1"]')?.value || '';
                const pc2 = form.querySelector('[name="postal_code_2"]')?.value || '';
                this.formData.postal_code = (pc1 && pc2) ? pc1 + '-' + pc2 : '';
            }

            // 住所の収集
            if (this.nameOrderWestern) {
                const parts = [
                    form.querySelector('[name="street_address"]')?.value,
                    form.querySelector('[name="building"]')?.value,
                    form.querySelector('[name="city"]')?.value,
                    form.querySelector('[name="state"]')?.value,
                    form.querySelector('[name="country"]')?.value,
                ].filter(Boolean);
                this.formData.address = parts.join(', ');
            } else {
                const prefSelect = form.querySelector('[name="prefecture"]');
                const prefecture = prefSelect ? prefSelect.options[prefSelect.selectedIndex]?.text : '';
                const city = form.querySelector('[name="city"]')?.value || '';
                const addressLine = form.querySelector('[name="address_line"]')?.value || '';
                const building = form.querySelector('[name="building"]')?.value || '';
                let addr = (prefecture !== '' && prefSelect?.value !== '' ? prefecture : '') + city + addressLine;
                if (building) {
                    addr += ' ' + building;
                }
                this.formData.address = addr.trim();
            }

            // 電話番号の収集
            if (this.nameOrderWestern) {
                this.formData.phone = form.querySelector('[name="phone"]')?.value || '';
            } else {
                const p1 = form.querySelector('[name="phone_1"]')?.value || '';
                const p2 = form.querySelector('[name="phone_2"]')?.value || '';
                const p3 = form.querySelector('[name="phone_3"]')?.value || '';
                this.formData.phone = (p1 && p2 && p3) ? p1 + '-' + p2 + '-' + p3 : '';
            }

            // 性別の収集
            const genderInput = form.querySelector('[name="gender"]:checked');
            this.formData.gender = genderInput?.value || '';
            if (genderInput) {
                const label = genderInput.closest('label');
                const spanEl = label?.querySelector('span.text-sm');
                this.formData.genderLabel = spanEl?.textContent?.trim() || this.formData.gender;
            } else {
                this.formData.genderLabel = '';
            }

            this.transitionTo('confirmation');
        },

        goBack() {
            this.transitionTo('form');
        },

        /**
         * AJAX送信でフォームを送信し、完了画面にフェード遷移する
         */
        async submitForm() {
            if (this.isSubmitting) {
                return;
            }
            const form = this.$refs.inquiryForm;
            this.isSubmitting = true;
            try {
                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: FETCH_HEADERS,
                });
                // A followed redirect (expired session, maintenance page) is a
                // 200 too, but not a completed submission.
                if (response.ok && !response.redirected) {
                    await this.transitionTo('complete');
                } else {
                    const errors = extractErrors(await response.json().catch(() => null));
                    if (errors) {
                        this.showValidationErrors(errors);
                        resetCaptchaWidget();
                        await this.transitionTo('form');
                        // transitionTo() scrolls to the section top, where a
                        // fixed header can cover the error box; bring the
                        // box itself into view last.
                        this.scrollToErrors();
                    } else {
                        // フォールバック: 通常の送信
                        form.submit();
                    }
                }
            } catch {
                // ネットワークエラー時はフォールバック
                form.submit();
            } finally {
                this.isSubmitting = false;
            }
        },

        showValidationErrors(errors) {
            // $root, not $el — see the single-page variant above.
            const container = this.$root.querySelector('.inquiry-errors');
            if (!container) {
                return;
            }
            const messages = Object.values(errors).flat();
            container.innerHTML = '<ul class="list-disc list-inside">' +
                messages.map(m => '<li>' + m + '</li>').join('') + '</ul>';
            container.classList.remove('hidden');
        },

        scrollToErrors() {
            const container = this.$root.querySelector('.inquiry-errors');
            (container || this.$root).scrollIntoView({ behavior: 'smooth', block: 'center' });
        },
    };
}

/**
 * Alpineにコンポーネントを登録し、必要に応じて既存DOM要素を初期化する
 */
function registerComponent() {
    window.Alpine.data('inquiryEmbedForm', (showConfirmationPage = true, nameOrderWestern = false) =>
        createInquiryEmbedForm(showConfirmationPage, nameOrderWestern)
    );

    // Alpine.start() 後に読み込まれた場合、既存のDOM要素を遅延初期化する
    document.querySelectorAll('[x-data*="inquiryEmbedForm"]').forEach(el => {
        if (!el._x_dataStack) {
            window.Alpine.initTree(el);
        }
    });
}

// Alpine.data() でコンポーネントを登録（CSP厳格モード対応）
// Alpine が既にロード済みの場合は即座に登録、未ロードの場合は alpine:init イベントで登録
if (typeof window !== 'undefined') {
    if (window.Alpine) {
        registerComponent();
    } else {
        document.addEventListener('alpine:init', registerComponent);
    }
}
