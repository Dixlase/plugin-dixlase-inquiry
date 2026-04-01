// 問い合わせフォーム埋め込み用Alpine.jsコンポーネント（CSP厳格モード対応）

/**
 * 問い合わせフォームのAlpine.jsコンポーネント
 * @param {boolean} showConfirmationPage - 確認画面を表示するかどうか
 * @param {boolean} nameOrderWestern - 名前の順序が欧米式かどうか
 * @returns {object} Alpine.jsコンポーネントオブジェクト
 */
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
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    });
                    if (response.ok) {
                        await this.transitionTo('complete');
                    } else {
                        const data = await response.json().catch(() => null);
                        if (data && data.errors) {
                            this.showValidationErrors(data.errors);
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
                const container = this.$el.querySelector('.inquiry-errors');
                if (!container) {
                    return;
                }
                const messages = Object.values(errors).flat();
                container.innerHTML = '<ul class="list-disc list-inside">' +
                    messages.map(m => '<li>' + m + '</li>').join('') + '</ul>';
                container.classList.remove('hidden');
                this.$el.scrollIntoView({ behavior: 'smooth', block: 'start' });
            },

            async transitionTo(view) {
                const container = this.$refs.heightContainer;
                if (container) {
                    container.style.height = container.scrollHeight + 'px';
                }
                this.isTransitioning = true;
                await this.sleep(300);
                if (container) {
                    container.style.transition = 'none';
                }
                this.currentView = view;
                await this.$nextTick();
                await this.nextFrame();
                if (container) {
                    const newHeight = container.scrollHeight;
                    container.style.transition = '';
                    await this.nextFrame();
                    container.style.height = newHeight + 'px';
                    await this.sleep(300);
                    container.style.height = 'auto';
                }
                await this.nextFrame();
                this.isTransitioning = false;
                this.$el.scrollIntoView({ behavior: 'smooth', block: 'start' });
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

            // 現在の高さを固定（auto → 具体的なpx値）
            if (container) {
                container.style.height = container.scrollHeight + 'px';
            }

            // フェードアウト
            this.isTransitioning = true;
            await this.sleep(300);

            // 高さアニメーションを一時停止してビュー切り替え
            if (container) {
                container.style.transition = 'none';
            }
            this.currentView = view;
            await this.$nextTick();
            await this.nextFrame();

            // 新しいコンテンツの高さを取得
            if (container) {
                const newHeight = container.scrollHeight;
                // トランジションを再有効化して高さアニメーション
                container.style.transition = '';
                await this.nextFrame();
                container.style.height = newHeight + 'px';
                await this.sleep(300);
                container.style.height = 'auto';
            }

            // フェードイン開始
            await this.nextFrame();
            this.isTransitioning = false;
            this.$el.scrollIntoView({ behavior: 'smooth', block: 'start' });
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
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                });
                if (response.ok) {
                    await this.transitionTo('complete');
                } else {
                    const data = await response.json().catch(() => null);
                    if (data && data.errors) {
                        this.showValidationErrors(data.errors);
                        await this.transitionTo('form');
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
            const container = this.$el.querySelector('.inquiry-errors');
            if (!container) {
                return;
            }
            const messages = Object.values(errors).flat();
            container.innerHTML = '<ul class="list-disc list-inside">' +
                messages.map(m => '<li>' + m + '</li>').join('') + '</ul>';
            container.classList.remove('hidden');
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
