import fs from 'fs';
import path from 'path';

const dir = './scraped_pages';
if (!fs.existsSync(dir)) {
    console.error("ERROR: scraped_pages directory not found!");
    process.exit(1);
}

const files = fs.readdirSync(dir).filter(f => f.endsWith('.html'));
console.log(`Found ${files.length} HTML files to analyze...`);

const report = {};

// Regex to find all anchor tags and extract href & text
const linkRegex = /<a\s+[^>]*href=["']([^"']*)["'][^>]*>([\s\S]*?)<\/a>/gi;

files.forEach(file => {
    const filePath = path.join(dir, file);
    const content = fs.readFileSync(filePath, 'utf8');

    // Extract page title or name from filename
    const pageName = file.replace(/_\d+\.html$/, '').replace(/_/g, ' ').toUpperCase();
    
    report[pageName] = [];

    let match;
    const seenLinks = new Set();

    while ((match = linkRegex.exec(content)) !== null) {
        const href = match[1];
        let text = match[2].replace(/<[^>]*>/g, '').trim(); // strip inner HTML tags
        text = text.replace(/\s+/g, ' '); // collapse whitespaces

        // Skip global menus, headers, or empty/redundant links
        if (
            !href ||
            href === '#' ||
            href.startsWith('javascript') ||
            href.includes('logout') ||
            href.includes('loginsso') ||
            href.startsWith('http') ||
            seenLinks.has(href)
        ) {
            continue;
        }

        // Clean text if it matches some common patterns
        if (!text || text.length > 80) {
            text = "Link Action";
        }

        seenLinks.add(href);
        report[pageName].push({ text, href });
    }
});

// Write Markdown Report
let md = "# Operator Portal Deep Submenus & Tabs Analysis\n\n";

function add(text) {
    md += text;
}

add("This report lists all internal submenu items, tabs, and action links extracted directly from the HTML page bodies scraped in the `scraped_pages/` directory.\n\n");

Object.keys(report).sort().forEach(page => {
    const links = report[page];
    if (links.length > 0) {
        add(`## 📂 Page: ${page}\n`);
        add(`*Total internal links discovered: ${links.length}*\n\n`);
        add("| No | Link Label / Tab Text | Target Submenu URL |\n");
        add("| --- | --- | --- |\n");
        links.forEach((link, idx) => {
            add(`| ${idx + 1} | **${link.text}** | \`${link.href}\` |\n`);
        });
        add("\n---\n\n");
    }
});

fs.writeFileSync('operator_submenus_report.md', md);
console.log("Successfully extracted deep submenus to: operator_submenus_report.md");
