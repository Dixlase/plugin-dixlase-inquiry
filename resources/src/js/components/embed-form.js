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
            email: '',
            phone: '',
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
            this.formData.phone = form.querySelector('[name="phone"]')?.value || '';
            this.formData.message = form.querySelector('[name="message"]')?.value || '';
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
