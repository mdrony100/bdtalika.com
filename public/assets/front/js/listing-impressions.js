/* BDTalika - Listing impression tracking
 * Counts one impression per listing card when it scrolls into the visitor's
 * viewport, batched into a single request per burst. Cards are identified by
 * the data-impression-listing attribute (explicit hook) or the data-listing
 * attribute on the wishlist button (theme fallback). Listing detail pages are
 * excluded: those visits are already counted as "views".
 */
(function () {
    var url = window.LISTING_IMPRESSION_URL;
    var token = window.LISTING_IMPRESSION_TOKEN;
    if (!url || !token || !('IntersectionObserver' in window)) return;

    // Skip listing details pages - the view event already covers them
    if (/\/details\//.test(window.location.pathname)) return;

    var pending = {};
    var timer = null;

    function flush() {
        timer = null;
        var ids = Object.keys(pending);
        pending = {};
        if (!ids.length) return;

        var fd = new FormData();
        fd.append('_token', token);
        ids.forEach(function (id) { fd.append('ids[]', id); });

        if (navigator.sendBeacon) {
            navigator.sendBeacon(url, fd);
        } else {
            fetch(url, { method: 'POST', body: fd, keepalive: true }).catch(function () {});
        }
    }

    var observer = new IntersectionObserver(function (entries) {
        var queued = false;
        entries.forEach(function (entry) {
            if (!entry.isIntersecting) return;
            observer.unobserve(entry.target);
            var el = entry.target;
            var id = parseInt(el.getAttribute('data-impression-listing') || el.getAttribute('data-listing'), 10);
            if (id) { pending[id] = true; queued = true; }
        });
        // Batch everything visible in the same moment into one request
        if (queued && !timer) timer = setTimeout(flush, 1000);
    }, { threshold: 0.5 });

    function scan() {
        var els = document.querySelectorAll('[data-impression-listing],[data-listing]');
        Array.prototype.forEach.call(els, function (el) {
            if (el.__liSeen) return;
            el.__liSeen = true;
            observer.observe(el);
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        scan();
        // Pick up cards added later (ajax pagination, filtering)
        if ('MutationObserver' in window) {
            new MutationObserver(function () { scan(); })
                .observe(document.body, { childList: true, subtree: true });
        }
    });
})();
