import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const targetFile = path.join(__dirname, 'node_modules', '@fizzxydev', 'baileys-pro', 'lib', 'Socket', 'messages-recv.js');

if (!fs.existsSync(targetFile)) {
    console.error('Error: Target file messages-recv.js not found at: ' + targetFile);
    process.exit(1);
}

let content = fs.readFileSync(targetFile, 'utf8');

const targetStr = `            case 'link_code_companion_reg':
                const linkCodeCompanionReg = WABinary_1.getBinaryNodeChild(node, 'link_code_companion_reg')
                const ref = toRequiredBuffer(WABinary_1.getBinaryNodeChildBuffer(linkCodeCompanionReg, 'link_code_pairing_ref'))
                const primaryIdentityPublicKey = toRequiredBuffer(WABinary_1.getBinaryNodeChildBuffer(linkCodeCompanionReg, 'primary_identity_pub'))
                const primaryEphemeralPublicKeyWrapped = toRequiredBuffer(WABinary_1.getBinaryNodeChildBuffer(linkCodeCompanionReg, 'link_code_pairing_wrapped_primary_ephemeral_pub'))`;

const replacementStr = `            case 'link_code_companion_reg':
                const linkCodeCompanionReg = WABinary_1.getBinaryNodeChild(node, 'link_code_companion_reg')
                const primaryIdentityPubNode = WABinary_1.getBinaryNodeChildBuffer(linkCodeCompanionReg, 'primary_identity_pub')
                const primaryEphemeralPubNode = WABinary_1.getBinaryNodeChildBuffer(linkCodeCompanionReg, 'link_code_pairing_wrapped_primary_ephemeral_pub')
                const refNode = WABinary_1.getBinaryNodeChildBuffer(linkCodeCompanionReg, 'link_code_pairing_ref')
                if (!primaryIdentityPubNode || !primaryEphemeralPubNode || !refNode) {
                    logger.info('link_code_companion_reg node incomplete, skipping...');
                    break
                }
                const ref = toRequiredBuffer(refNode)
                const primaryIdentityPublicKey = toRequiredBuffer(primaryIdentityPubNode)
                const primaryEphemeralPublicKeyWrapped = toRequiredBuffer(primaryEphemeralPubNode)`;

// Support both LF and CRLF line endings in the source file
const normalizedContent = content.replace(/\r\n/g, '\n');
const normalizedTarget = targetStr.replace(/\r\n/g, '\n');
const normalizedReplacement = replacementStr.replace(/\r\n/g, '\n');

if (!normalizedContent.includes(normalizedTarget)) {
    // Check if it's already patched
    if (normalizedContent.includes("link_code_companion_reg node incomplete, skipping...")) {
        console.log('Success: Baileys-pro is already patched!');
        process.exit(0);
    }
    console.error('Error: Could not find target code block to patch in messages-recv.js.');
    process.exit(1);
}

const patchedContent = normalizedContent.replace(normalizedTarget, normalizedReplacement);
fs.writeFileSync(targetFile, patchedContent, 'utf8');

console.log('Success: Baileys-pro pairing code crash bug patched successfully!');
