import { Controller } from '@hotwired/stimulus'

export default class extends Controller {
    static values = {
        stackId: { type: String, default: 'global-modal-stack' },
        frameSrc: String,
        frameId: String,
        frameTarget: String,
        closeOnSuccess: Boolean,
        stayOnSuccess: Boolean,
        refreshOnSuccess: Boolean,
        relatedTurboFrames: Array,
    }

    connect () {
        this.element.addEventListener('click', this.click.bind(this))
    }

    async click(event) {
        event.preventDefault()

        window.dispatchEvent(new CustomEvent("open-global-modal", {
            cancelable: false,
            target: event.target,
            detail: {
                stackId: this.stackIdValue,
                frameSrc: this.frameSrcValue !== '' ? this.frameSrcValue : this.element.getAttribute('href'),
                frameId: this.frameIdValue !== '' ? this.frameIdValue : 'page-content',
                frameTarget: this.frameTargetValue,
                closeOnSuccess: this.closeOnSuccessValue,
                stayOnSuccess: this.stayOnSuccessValue,
                refreshOnSuccess: this.refreshOnSuccessValue,
                relatedTurboFrames: this.relatedTurboFramesValue
            }
        }))
    }
}
