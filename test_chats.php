<?php
use App\Models\Device;
use App\Traits\Whatsapp;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

class ChatTester {
    use Whatsapp;
    
    public function test($uuid) {
        $device = Device::where('uuid', $uuid)->first();
        if (!$device) {
            echo "Device not found\n";
            return;
        }
        echo "Device ID: " . $device->id . "\n";
        
        // Mock Auth if needed, or just call getChats directly if it was public.
        // But getChats is private.
        // I can use Reflection to call it.
        
        $reflection = new ReflectionClass($this);
        $method = $reflection->getMethod('getChats');
        $method->setAccessible(true);
        
        $result = $method->invoke($this, $device->id);
        
        print_r($result);
    }
}

$tester = new ChatTester();
$tester->test('13f943ab-7a41-44e4-bc60-7d4040708ba8');
