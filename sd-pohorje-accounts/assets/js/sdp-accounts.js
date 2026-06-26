(function () {
    document.addEventListener('DOMContentLoaded', function () {
        var wrapElements = document.querySelectorAll('.sdp-auth-wrap');
        if (!wrapElements.length) {
            return;
        }

        wrapElements.forEach(function (el, index) {
            el.style.animationDelay = (index * 90) + 'ms';
        });
    });
})();
