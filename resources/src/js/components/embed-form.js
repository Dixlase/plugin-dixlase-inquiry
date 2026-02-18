// 問い合わせフォーム埋め込み用Alpine.jsコンポーネント（CSP厳格モード対応）

/**
 * 問い合わせフォームのAlpine.jsコンポーネント
 * @param {boolean} showConfirmationPage - 確認画面を表示するかどうか
 * @param {boolean} nameOrderWestern - 名前の順序が欧米式かどうか
 * @returns {object} Alpine.jsコンポーネントオブジェクト
 */
export function createInquiryEmbedForm(showConfirmationPage = true, nameOrderWestern = false) {
    if (!showConfirmationPage) {
        // 確認画面なしの場合は空のオブジェクトを返す
        return {};
    }

    return {
        showConfirmation: false,
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

            this.showConfirmation = true;

            // スクロールして確認画面を表示
            this.$el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        },

        submitForm() {
            this.$refs.inquiryForm.submit();
        }
    };
}

// グローバル関数として登録（Bladeテンプレートから呼び出せるように）
if (typeof window !== 'undefined') {
    window.inquiryEmbedForm = createInquiryEmbedForm;
}
