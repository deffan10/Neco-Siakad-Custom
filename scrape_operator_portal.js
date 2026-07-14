import puppeteer from 'puppeteer-core';
import fs from 'fs';
import readline from 'readline';

const edgePaths = [
    'C:\\Program Files (x86)\\Microsoft\\Edge\\Application\\msedge.exe',
    'C:\\Program Files\\Microsoft\\Edge\\Application\\msedge.exe',
    'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe',
    'C:\\Program Files (x86)\\Google\\Chrome\\Application\\chrome.exe'
];

let executablePath = null;
for (const path of edgePaths) {
    if (fs.existsSync(path)) {
        executablePath = path;
        break;
    }
}

if (!executablePath) {
    console.error("ERROR: Microsoft Edge or Google Chrome not found in default Windows directories!");
    process.exit(1);
}

const rl = readline.createInterface({
    input: process.stdin,
    output: process.stdout
});

const waitEnter = (query) => new Promise((resolve) => rl.question(query, resolve));

async function run() {
    console.log("OOGA! Launching browser at path: " + executablePath);
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
    console.log("OOGA BOOGA! ACTION REQUIRED:");
    console.log("1. Go to the browser window and LOG IN with your operator credentials.");
    console.log("2. Wait until you see the main dashboard page.");
    console.log("3. Return to this terminal and press [ENTER] to start scraping!");
    console.log("============================================================\n");

    await waitEnter("Press [ENTER] here when you are logged in and ready...");

    console.log("Saving page HTML source to operator_page.html...");
    const htmlContent = await page.content();
    fs.writeFileSync('operator_page.html', htmlContent);
    console.log("HTML source saved successfully!");

    console.log("Starting scraping... extracting all links from the page...");

    // Extract navigation menu items
    const menuData = await page.evaluate(() => {
        const sidebarLinks = Array.from(document.getElementsByTagName('a'));
        const menus = [];

        for (const link of sidebarLinks) {
            const href = link.getAttribute('href');
            const text = link.innerText.trim() || link.textContent.trim();
            
            if (href && href !== '#' && href !== 'javascript:void(0)' && !href.startsWith('mailto:') && !href.startsWith('tel:')) {
                menus.push({
                    text: text || link.getAttribute('title') || 'Menu Item',
                    href: href
                });
            }
        }
        return menus;
    });

    console.log("Scraped " + menuData.length + " links/buttons from the sidebar.");

    // Clean duplicates and format
    const uniqueMenus = [];
    const seenHrefs = new Set();
    for (const item of menuData) {
        if (item.href && !seenHrefs.has(item.href)) {
            seenHrefs.add(item.href);
            uniqueMenus.push(item);
        }
    }

    // Save JSON
    fs.writeFileSync('operator_scraped_links.json', JSON.stringify(uniqueMenus, null, 4));
    console.log("Saved raw menu data to: operator_scraped_links.json");

    // Save MD Markdown report
    let mdContent = "# Result of Operator Portal Scraped Menus\n\n";
    mdContent += `Total unique active links found: ${uniqueMenus.length}\n\n`;
    mdContent += "| No | Menu Name / Label | URL Path / Route |\n";
    mdContent += "| --- | --- | --- |\n";
    uniqueMenus.forEach((menu, index) => {
        mdContent += `| ${index + 1} | **${menu.text.replace(/\n/g, ' ')}** | \`${menu.href}\` |\n`;
    });

    fs.writeFileSync('operator_scraped_links.md', mdContent);
    console.log("Saved clean formatted menu report to: operator_scraped_links.md");

    console.log("\nOOGA BOOGA! Scraping done! Close browser now.");
    await browser.close();
    rl.close();
}

run().catch(err => {
    console.error("OOGA ERROR:", err);
    rl.close();
});
