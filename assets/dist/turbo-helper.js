const TurboHelper = class {
    constructor() {
        document.addEventListener('turbo:before-cache', () => {
            this.resetFormClass()
            this.resetSubmitButtons()
        });

        document.addEventListener('turbo:submit-start', (event) => {
            const submitter = event.detail.formSubmission.submitter;

            if (!submitter) {
                return
            }

            event.target.classList.add('turbo-submitting')

            submitter.toggleAttribute('disabled', true)
            submitter.classList.add('turbo-submit-disabled')
        })

        document.addEventListener('turbo:before-fetch-request', (event) => {
            if (!event.target.dataset.turboOnSuccess) {
                return;
            }

            event.detail.fetchOptions.headers['Turbo-On-Success'] = event.target.dataset.turboOnSuccess;
        });
    }

    resetFormClass() {
        document.querySelector('.turbo-submitting')?.classList?.remove('turbo-submitting')
    }

    resetSubmitButtons() {
        document.querySelectorAll('.turbo-submit-disabled').forEach((button) => {
            button.toggleAttribute('disabled', false)
            button.classList.remove('turbo-submit-disabled')
        })
    }
}

export default new TurboHelper();
