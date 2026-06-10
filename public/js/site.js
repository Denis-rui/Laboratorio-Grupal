document.addEventListener('click', function (event) {
    const confirmLink = event.target.closest('[data-confirm]');

    if (confirmLink && !window.confirm(confirmLink.dataset.confirm)) {
        event.preventDefault();
        return;
    }

    const backControl = event.target.closest('[data-go-back]');

    if (backControl) {
        event.preventDefault();
        window.history.back();
    }
});
