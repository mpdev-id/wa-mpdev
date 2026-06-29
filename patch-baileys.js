import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const targetFile = path.join(__dirname, 'node_modules', '@fizzxydev', 'baileys-pro', 'lib', 'Socket', 'messages-send.js');

if (fs.existsSync(targetFile)) {
    let content = fs.readFileSync(targetFile, 'utf8');

    // Remove the previous hardcoded bot biz_bot block if it exists
    const hardcodedBlockRegex = /if\s*\(\s*isPrivate\s*\)\s*\{\s*if\s*\(\!stanza\.content\s*\|\|\s*\!Array\.isArray\(stanza\.content\)\)\s*\{\s*stanza\.content\s*=\s*\[\]\s*\}\s*stanza\.content\.push\(\{\s*tag:\s*'bot',\s*attrs:\s*\{\s*biz_bot:\s*'1'\s*\}\s*\}\)\s*\}/;
    
    // Check if the original block is commented out (from my previous manual edit)
    const manualPatchRegex = /\/\*\s*if\s*\(\s*isPrivate\s*\)[\s\S]*?biz_bot:\s*'1'[\s\S]*?\}\s*\*\//;
    
    if (manualPatchRegex.test(content)) {
        content = content.replace(manualPatchRegex, '');
    } else if (hardcodedBlockRegex.test(content)) {
        content = content.replace(hardcodedBlockRegex, '');
    }

    let patched = false;

    // INJECTION 1: Inside relayMessage (adding biz_bot tag)
    const injectionPoint1 = "logger.debug({ msgId }, `sending message to ${participants.length} devices`)";
    if (content.includes(injectionPoint1) && !content.includes('if (additionalAttributes && additionalAttributes.markAsAi) {')) {
        const customBlock1 = `
         if (additionalAttributes && additionalAttributes.markAsAi) {
            if (!stanza.content || !Array.isArray(stanza.content)) {
               stanza.content = []
            }
            stanza.content.push({
               tag: 'bot',
               attrs: {
                  biz_bot: '1'
               }
            })
         }
         
         `;
        content = content.replace(injectionPoint1, customBlock1 + injectionPoint1);
        patched = true;
    }

    // INJECTION 2: Inside sendMessage (mapping options.markAsAi to additionalAttributes)
    const injectionPoint2 = "const additionalAttributes = {}";
    if (content.includes(injectionPoint2) && !content.includes('if (options && options.markAsAi) {')) {
        const customBlock2 = `const additionalAttributes = {}
            if (options && options.markAsAi) {
               additionalAttributes.markAsAi = true;
            }`;
        content = content.replace(injectionPoint2, customBlock2);
        patched = true;
    }

    if (patched) {
        fs.writeFileSync(targetFile, content);
        console.log('Successfully patched baileys-pro to support markAsAi option (relayMessage & sendMessage)!');
    } else {
        console.log('Baileys already fully patched or injection points not found.');
    }
} else {
    console.error('Baileys file not found: ' + targetFile);
}
