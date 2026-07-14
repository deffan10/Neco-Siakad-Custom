import puppeteer from 'puppeteer-core';
import fs from 'fs';
import readline from 'readline';
import path from 'path';

const edgePaths = [
    'C:\\Program Files (x86)\\Microsoft\\Edge\\Application\\msedge.exe',
    'C:\\Program Files\\Microsoft\\Edge\\Application\\msedge.exe',
    'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe',
    'C:\\Program Files (x86)\\Google\\Chrome\\Application\\chrome.exe'
];

let executablePath = null;
for (const p of edgePaths) {
    if (fs.existsSync(p)) {
        executablePath = p;
        break;
    }
}

if (!executablePath) {
    console.error("ERROR: Browser not found!");
    process.exit(1);
}

const rl = readline.createInterface({
    input: process.stdin,
    output: process.stdout
});

const waitEnter = (query) => new Promise((resolve) => rl.question(query, resolve));

async function run() {
    if (!fs.existsSync('operator_scraped_links.json')) {
        console.error("ERROR: operator_scraped_links.json not found! Run scrape_operator_portal.js first.");
        process.exit(1);
    }

    const menus = JSON.parse(fs.readFileSync('operator_scraped_links.json', 'utf8'));
    console.log(`Loaded ${menus.length} menus to scrape.`);

    const browser = await puppeteer.launch({
        headless: false,
        executablePath: executablePath,
        defaultViewport: null,
        args: ['--start-maximized']
    });

    const page = await browser.newPage();
    console.log("Navigating to Operator Portal...");
    await page.goto('https://operator.ummada.ac.id/', { waitUntil: 'load', timeout: 60000 });

    console.log("\n============================================================");
    console.log("ACTION REQUIRED:");
    console.log("1. Log in manually in the browser window.");
    console.log("2. Wait for the main page to load.");
    console.log("3. Return here and press [ENTER] to start scraping all subpages!");
    console.log("============================================================\n");

    await waitEnter("Press [ENTER] when ready...");

    const baseUrl = page.url();
    console.log("Base URL is: " + baseUrl);

    // Create scraped_pages directory
    const outputDir = './scraped_pages';
    if (!fs.existsSync(outputDir)) {
        fs.mkdirSync(outputDir);
    }

    // Filter useful pages to avoid duplicates or external redirects
    const targetMenus = menus.filter(m => {
        return m.href && 
               !m.href.startsWith('http') && 
               m.href !== '../' && 
               !m.href.includes('logout');
    });

    console.log(`Filtering down to ${targetMenus.length} target subpages to scrape.`);

    for (let i = 0; i < targetMenus.length; i++) {
        const menu = targetMenus[i];
        try {
            // Resolve relative url
            const resolvedUrl = new URL(menu.href, baseUrl).href;
            console.log(`[${i + 1}/${targetMenus.length}] Visiting: ${menu.text} -> ${resolvedUrl}`);

            await page.goto(resolvedUrl, { waitUntil: 'domcontentloaded', timeout: 30000 });
            await new Promise(r => setTimeout(r, 2000)); // wait for renders

            // Extract page body HTML
            const bodyHtml = await page.evaluate(() => document.body.outerHTML);

            // Create safe filename
            const safeName = menu.text.replace(/[^a-z0-9]/gi, '_').toLowerCase() + `_${i}.html`;
            fs.writeFileSync(path.join(outputDir, safeName), bodyHtml);
            console.log(`   Saved body to: ${path.join(outputDir, safeName)}`);
        } catch (e) {
            console.error(`   Failed to scrape: ${menu.text}. Error: ${e.message}`);
        }
    }

    console.log("\nOOGA BOOGA! All subpage contents scraped and saved to scraped_pages/ directory.");
    await browser.close();
    rl.close();
}

run().catch(err => {
    console.error("OOGA ERROR:", err);
    rl.close();
});
