const ENTRANCE = [{ opacity: 0, translate: '0 8px' }, { opacity: 1, translate: 'none' }];
const TIMING = { duration: 320, easing: 'cubic-bezier(.2, .8, .2, 1)' };

// The CSS entrance on [data-ob-reveal] runs when an element is added, seen or not. This replays it
// for elements that were below the fold at the time, as they scroll into view. It keeps no state on
// the element, so a Livewire morph cannot undo it and nothing is hidden if this script never runs.
export function shouldReplay(addedAt, now, graceMs = 500) {
    return now - addedAt >= graceMs;
}

export function watchReveals(root = document.documentElement) {
    if (!('IntersectionObserver' in window) || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    const addedAt = new WeakMap();

    const viewport = new IntersectionObserver((entries) => {
        for (const entry of entries) {
            if (!entry.isIntersecting) continue;

            viewport.unobserve(entry.target);

            if (shouldReplay(addedAt.get(entry.target), performance.now())) {
                entry.target.animate(ENTRANCE, TIMING);
            }
        }
    });

    const scan = (node) => {
        if (node.nodeType !== Node.ELEMENT_NODE) return;

        for (const element of [node, ...node.querySelectorAll('[data-ob-reveal]')]) {
            if (element.matches('[data-ob-reveal]') && !addedAt.has(element)) {
                addedAt.set(element, performance.now());
                viewport.observe(element);
            }
        }
    };

    scan(root);
    new MutationObserver((records) => records.forEach((record) => record.addedNodes.forEach(scan))).observe(root, { childList: true, subtree: true });
}
