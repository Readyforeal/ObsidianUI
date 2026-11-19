import { expect, test } from 'vitest';
import { shouldReplay } from './reveal.js';

test('does not replay the entrance for elements already on screen when added', () => {
    expect(shouldReplay(1000, 1040)).toBe(false);
});

test('replays the entrance for elements scrolled into view later', () => {
    expect(shouldReplay(1000, 1500)).toBe(true);
    expect(shouldReplay(1000, 9000)).toBe(true);
});
