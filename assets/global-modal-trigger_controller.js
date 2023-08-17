import { Controller } from '@hotwired/stimulus'

export default class extends Controller {
    static values = {
        frameSrc: String, // TODO: si frameSrc non définit, lire le "href" de l'élément
        frameId: String,
        frameTarget: String,
        closeOnSuccess: Boolean,
        stayOnSuccess: Boolean,
        refreshOnSuccess: Boolean,
        relatedTurboFrames: Array
    }

    async click(event) {
        event.preventDefault()

        window.dispatchEvent(new CustomEvent("open-global-modal", {
            cancelable: false,
            target: event.target,
            detail: {
                frameSrc: this.frameSrcValue,
                frameId: this.frameIdValue,
                frameTarget: this.frameTargetValue,
                closeOnSuccess: this.closeOnSuccessValue,
                stayOnSuccess: this.stayOnSuccessValue,
                refreshOnSuccess: this.refreshOnSuccessValue,
                relatedTurboFrames: this.relatedTurboFramesValue
            }
        }))
    }
}
