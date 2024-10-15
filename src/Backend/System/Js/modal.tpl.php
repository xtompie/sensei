<style type="text/css">
.modal {
    position: fixed;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.4);
    color: #fff;
    text-align: center;
    z-index: 2000;
}
.modal .modal-iframe {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.3);
    z-index: 2000;

}
.modal-wrapper {
    background: white;
    padding: 0;
}
.modal-wrapper .modal-content {
    margin: 30px auto;
    background: var(--bs-body-bg);
    border-radius: 4px;
}
.modal-loader {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    margin: auto;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    height: 100%;
}
.modal-footer {
    position: sticky;
    bottom: 0;
    background: #fff;
    border-radius: 0 0 6px 6px;
    z-index: 10;
}
.modal-loader__spinner {
    display: inline-block;
    position: relative;
    width: 80px;
    height: 80px;
}
.modal-loader__spinner div {
    position: absolute;
    top: 33px;
    width: 13px;
    height: 13px;
    border-radius: 50%;
    background: #fff;
    animation-timing-function: cubic-bezier(0, 1, 1, 0);
}
.modal-loader__spinner div:nth-child(1) {
    left: 8px;
    animation: loader-spinner-1 0.6s infinite;
}
.modal-loader__spinner div:nth-child(2) {
    left: 8px;
    animation: loader-spinner-2 0.6s infinite;
}
.modal-loader__spinner div:nth-child(3) {
    left: 32px;
    animation: loader-spinner-2 0.6s infinite;
}
.modal-loader__spinner div:nth-child(4) {
    left: 56px;
    animation: loader-spinner-3 0.6s infinite;
}
@keyframes loader-spinner-1 {
    0% {
        transform: scale(0);
   }
    100% {
        transform: scale(1);
   }
}
@keyframes loader-spinner-3 {
    0% {
        transform: scale(1);
   }
    100% {
        transform: scale(0);
   }
}
@keyframes loader-spinner-2 {
    0% {
        transform: translate(0, 0);
   }
    100% {
        transform: translate(24px, 0);
   }
}
</style>

<script>
var backend = backend || {};
backend.modal = (function () {
    let _modal = {};
    let _callback = null;

    function render(uri) {
        let $modal = document.createElement("div");
        $modal.className = "modal";
        $modal.innerHTML = '<div class="modal-loader"><div class="modal-loader__spinner"><div></div><div></div><div></div><div></div></div><button type="button" class="btn btn-secondary">&times; Abbrechen</button></div>';
        document.body.appendChild($modal);

        let $iframe = document.createElement("iframe");
        $iframe.setAttribute("src", uri);
        $iframe.setAttribute("allowtransparency", "false");
        $iframe.setAttribute("frameborder", "0");
        $iframe.classList.add("modal-iframe");
        $modal.appendChild($iframe);

        $modal.style.display = "block";
        _modal = $modal;

        document.body.style.overflow = "hidden";
        if (navigator.userAgent.match(/(iPad|iPhone|iPod)/g)) {
            document.body.style.position = "fixed";
        }

        _modal.querySelector(".modal-loader .btn").addEventListener("click", () => close());
        _modal.addEventListener("click", () => close());

        isIframeLoaded($iframe);

        return false;
    }
    function isIframeLoaded(iframe) {
        if (iframe) {
            const loader = _modal.querySelector(".modal-loader");
            iframe.onload = () => {
                loader.style.display = "none";
                iframe.style.display = "block";
            };
        }
    }
    function open(uri, callback) {
        _callback = callback;
        render(uri);
    }
    function close() {
        _modal.remove();
        _modal = {};
        document.body.style.overflow = "";
        document.body.style.position = "";
    }
    function result(result) {
        _callback(result);
        delete _callback;
        close();
    }
    function cancel() {
        _callback(null);
        delete _callback;
        close();
    }

    return {
        open,
        result,
        cancel,
    };
})();
</script>
