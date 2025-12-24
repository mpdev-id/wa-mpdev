#!/usr/bin/env php
<?php

/**
 * Test UnlimitedAI Client
 * Jalankan: php test-unlimitedai.php
 */

require __DIR__ . '/vendor/autoload.php';

use App\Services\UnlimitedAIClient;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=================================================\n";
echo "🧪 Testing UnlimitedAI (Alternative Free AI)\n";
echo "=================================================\n\n";

try {
    $client = new UnlimitedAIClient();
    
    echo "📤 Sending test message...\n";
    echo "Message: 'Halo, siapa namamu?'\n\n";
    
    $startTime = microtime(true);
    $result = $client->ask('Halo, siapa namamu?');
    $endTime = microtime(true);
    
    $duration = round(($endTime - $startTime) * 1000, 2);
    
    if ($result && isset($result['text']) && !empty($result['text'])) {
        echo "✅ SUCCESS!\n";
        echo "⏱️  Response time: {$duration}ms\n\n";
        echo "📥 UnlimitedAI Response:\n";
        echo str_repeat('=', 50) . "\n";
        echo $result['text'] . "\n";
        echo str_repeat('=', 50) . "\n\n";
        echo "🎉 UnlimitedAI is working!\n\n";
        
        echo "=================================================\n";
        echo "✅ Test PASSED - UnlimitedAI is working!\n";
        echo "\n📝 This is a great alternative to Gemini scraping!\n";
        echo "   ✅ More stable than Gemini scraping\n";
        echo "   ✅ Free to use\n";
        echo "   ✅ Good response quality\n";
        echo "=================================================\n";
    } else {
        echo "❌ FAILED: No response from UnlimitedAI\n";
        echo "Result: " . json_encode($result) . "\n";
    }
    
} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n\n";
    echo "📋 Possible solutions:\n";
    echo "1. Check your internet connection\n";
    echo "2. UnlimitedAI might be down or blocking requests\n";
    echo "3. Check Laravel logs: tail -f storage/logs/laravel.log\n";
    echo "4. Consider using Custom AI with your own API key\n";
    echo "   Get free API key: https://aistudio.google.com/app/apikey\n";
}

echo "\n";
