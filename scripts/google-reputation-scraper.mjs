import { existsSync } from 'node:fs';
import { chromium } from 'playwright-core';

const url = process.argv[2];
const candidates = [
    process.env.CHROME_PATH,
    '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',
    '/usr/bin/google-chrome',
    '/usr/bin/chromium',
    '/usr/bin/chromium-browser',
].filter(Boolean);
const executablePath = candidates.find(existsSync);

if (!url || !executablePath) {
    process.stderr.write(!url ? 'Missing Google profile URL.' : 'Chrome or Chromium is not installed.');
    process.exit(1);
}

const browser = await chromium.launch({
    executablePath,
    headless: true,
    args: ['--no-sandbox', '--disable-dev-shm-usage', '--disable-gpu'],
});

try {
    const context = await browser.newContext({ locale: 'en-US' });
    const page = await context.newPage();
    await page.goto(url, { waitUntil: 'domcontentloaded', timeout: 45000 });

    for (const label of ['Accept all', 'Reject all', 'I agree']) {
        const button = page.getByRole('button', { name: label, exact: true });
        if (await button.count()) {
            await button.first().click({ timeout: 3000 }).catch(() => {});
            break;
        }
    }

    await page.waitForTimeout(8000);

    const data = await page.evaluate(() => {
        const cleanNumber = value => Number.parseInt(String(value || '').replace(/\D/g, ''), 10);
        const ratingNode = document.querySelector('.F7nice [role="img"][aria-label], .F7nice [aria-label]');
        const reviewNode = document.querySelector('.F7nice [aria-label*="review"], [aria-label*="recenz"]');
        const ratingLabel = ratingNode?.getAttribute('aria-label') || '';
        const reviewLabel = reviewNode?.getAttribute('aria-label') || '';
        const ratingMatch = ratingLabel.match(/([0-5](?:[.,]\d)?)/);
        const reviewMatch = reviewLabel.match(/([\d.,\s]+)\s+(?:Google\s+)?(?:reviews?|recenzij\w*)/i);
        const body = document.body?.innerText || '';
        const combinedMatch = body.match(/([0-5][.,]\d)\s*(?:stars?)?\s*\(?([\d.,\s]+)\)?\s+(?:Google\s+)?reviews?/i);
        const name = document.querySelector('h1.DUwDvf, h1')?.textContent?.trim() || null;

        return {
            business_name: name,
            rating: ratingMatch ? Number.parseFloat(ratingMatch[1].replace(',', '.')) : (combinedMatch ? Number.parseFloat(combinedMatch[1].replace(',', '.')) : null),
            review_count: reviewMatch ? cleanNumber(reviewMatch[1]) : (combinedMatch ? cleanNumber(combinedMatch[2]) : null),
            page_title: document.title,
        };
    });

    process.stdout.write(JSON.stringify(data));
} finally {
    await browser.close();
}
