@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=>__('API Integration')])
@endsection

@section('content')

<div class="max-w-7xl mx-auto">
  
  <!-- Page Header -->
  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-xl md:text-2xl break-words font-bold text-base-content mb-2">{{ __('API Integration') }}</h1>
      <p class="text-base-content/60">{{ __('REST API documentation and code examples') }}</p>
    </div>
    <a href="{{ route('user.apps.index') }}" class="btn btn-outline gap-2 rounded-full">
      <span class="material-icons text-sm">arrow_back</span>
      {{ __('Back to Apps') }}
    </a>
  </div>

  <!-- API Info Card -->
  <div class="card bg-white shadow-xl mb-6">
    <div class="card-body">
      <h2 class="card-title flex items-center gap-2 mb-4">
        <span class="material-icons">integration_instructions</span>
        {{ __('API Credentials') }}
      </h2>
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <p class="text-sm font-medium text-gray-600 mb-2">{{ __('API Endpoint') }}</p>
          <div class="flex gap-2">
            <input type="text" class="input input-bordered input-sm w-full font-mono text-xs" value="{{ route('api.create.message') }}" readonly />
            <button onclick="copyText('{{ route('api.create.message') }}')" class="btn btn-sm btn-square btn-outline rounded-full">
              <span class="material-icons text-sm">content_copy</span>
            </button>
          </div>
        </div>
        
        <div>
          <p class="text-sm font-medium text-gray-600 mb-2">{{ __('App Key') }}</p>
          <div class="flex gap-2">
            <input type="text" class="input input-bordered input-sm w-full font-mono text-xs" value="{{ $key }}" readonly />
            <button onclick="copyText('{{ $key }}')" class="btn btn-sm btn-square btn-outline rounded-full">
              <span class="material-icons text-sm">content_copy</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Code Examples -->
  <div class="card bg-white shadow-xl mb-6">
    <div class="card-body">
      <h2 class="card-title mb-4">{{ __('Code Examples') }}</h2>
      
      <!-- Tabs -->
      <div class="tabs tabs-boxed bg-base-200 mb-4" role="tablist" id="code-tabs">
        <a class="tab tab-active" data-tab="curl" role="tab">cURL</a>
        <a class="tab" data-tab="php" role="tab">PHP</a>
        <a class="tab" data-tab="nodejs" role="tab">Node.js</a>
        <a class="tab" data-tab="python" role="tab">Python</a>
      </div>

      <!-- cURL Tab -->
      <div id="tab-curl" class="tab-content-panel">
        <h3 class="font-bold text-lg mb-3">{{ __('Text Message Only') }}</h3>
        <div class="mockup-code mb-6">
          <pre><code>curl --location --request POST '{{ route('api.create.message') }}' \
--form 'appkey="{{ $key }}"' \
--form 'authkey="{{ Auth::user()->authkey }}"' \
--form 'to="RECEIVER_NUMBER"' \
--form 'message="Example message"'</code></pre>
        </div>

        <h3 class="font-bold text-lg mb-3">{{ __('With File Attachment') }}</h3>
        <div class="mockup-code">
          <pre><code>curl --location --request POST '{{ route('api.create.message') }}' \
--form 'appkey="{{ $key }}"' \
--form 'authkey="{{ Auth::user()->authkey }}"' \
--form 'to="RECEIVER_NUMBER"' \
--form 'message="Example message"' \
--form 'file="https://example.com/file.pdf"'</code></pre>
        </div>
      </div>

      <!-- PHP Tab -->
      <div id="tab-php" class="tab-content-panel" style="display:none">
        <h3 class="font-bold text-lg mb-3">{{ __('GET Method') }}</h3>
        <div class="mockup-code mb-6">
          <pre><code>{{ route('api.create.message') }}?appkey={{ $key }}&authkey={{ Auth::user()->authkey }}&to=RECEIVER_NUMBER&message=Example message</code></pre>
        </div>

        <h3 class="font-bold text-lg mb-3">{{ __('POST Method') }}</h3>
        <div class="mockup-code">
          <pre><code>$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => '{{ route('api.create.message') }}',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => array(
    'appkey' => '{{ $key }}',
    'authkey' => '{{ Auth::user()->authkey }}',
    'to' => 'RECEIVER_NUMBER',
    'message' => 'Example message'
  ),
));

$response = curl_exec($curl);
curl_close($curl);
echo $response;</code></pre>
        </div>
      </div>

      <!-- Node.js Tab -->
      <div id="tab-nodejs" class="tab-content-panel" style="display:none">
        <h3 class="font-bold text-lg mb-3">{{ __('Using Request Module') }}</h3>
        <div class="mockup-code">
          <pre><code>var request = require('request');

var options = {
  'method': 'POST',
  'url': '{{ route('api.create.message') }}',
  formData: {
    'appkey': '{{ $key }}',
    'authkey': '{{ Auth::user()->authkey }}',
    'to': 'RECEIVER_NUMBER',
    'message': 'Example message'
  }
};

request(options, function (error, response) {
  if (error) throw new Error(error);
  console.log(response.body);
});</code></pre>
        </div>
      </div>

      <!-- Python Tab -->
      <div id="tab-python" class="tab-content-panel" style="display:none">
        <h3 class="font-bold text-lg mb-3">{{ __('Using Requests') }}</h3>
        <div class="mockup-code">
          <pre><code>import requests

url = "{{ route('api.create.message') }}"

payload = {
  'appkey': '{{ $key }}',
  'authkey': '{{ Auth::user()->authkey }}',
  'to': 'RECEIVER_NUMBER',
  'message': 'Example message'
}

response = requests.post(url, data=payload)
print(response.text)</code></pre>
        </div>
      </div>
    </div>
  </div>

  <!-- Response Example -->
  <div class="card bg-white shadow-xl mb-6">
    <div class="card-body">
      <h2 class="card-title flex items-center gap-2 mb-4">
        <span class="material-icons">code</span>
        {{ __('Successful Response') }}
      </h2>
      <div class="mockup-code">
        <pre><code>{
  "message_status": "Success",
  "data": {
    "from": "SENDER_NUMBER",
    "to": "RECEIVER_NUMBER",
    "status_code": 200
  }
}</code></pre>
      </div>
    </div>
  </div>

  <!-- Parameters -->
  <div class="card bg-white shadow-xl">
    <div class="card-body p-0">
      <div class="p-6">
        <h2 class="card-title flex items-center gap-2">
          <span class="material-icons">settings</span>
          {{ __('API Parameters') }}
        </h2>
      </div>
      
      <div class="overflow-x-auto">
        <table class="table">
          <thead>
            <tr>
              <th class="bg-base-200">#</th>
              <th class="bg-base-200">{{ __('Parameter') }}</th>
              <th class="bg-base-200">{{ __('Type') }}</th>
              <th class="bg-base-200">{{ __('Required') }}</th>
              <th class="bg-base-200">{{ __('Description') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr class="hover">
              <td>1</td>
              <td><code class="badge badge-outline">appkey</code></td>
              <td><span class="badge badge-info badge-sm">string</span></td>
              <td><span class="badge badge-error badge-sm">Yes</span></td>
              <td>{{ __('App authorization key') }}</td>
            </tr>
            <tr class="hover">
              <td>2</td>
              <td><code class="badge badge-outline">authkey</code></td>
              <td><span class="badge badge-info badge-sm">string</span></td>
              <td><span class="badge badge-error badge-sm">Yes</span></td>
              <td>{{ __('User authorization key') }}</td>
            </tr>
            <tr class="hover">
              <td>3</td>
              <td><code class="badge badge-outline">to</code></td>
              <td><span class="badge badge-info badge-sm">number</span></td>
              <td><span class="badge badge-error badge-sm">Yes</span></td>
              <td>{{ __('Receiver WhatsApp number with country code') }}</td>
            </tr>
            <tr class="hover">
              <td>4</td>
              <td><code class="badge badge-outline">message</code></td>
              <td><span class="badge badge-info badge-sm">string</span></td>
              <td><span class="badge badge-warning badge-sm">No</span></td>
              <td>{{ __('Message text (max 1000 chars)') }}</td>
            </tr>
            <tr class="hover">
              <td>5</td>
              <td><code class="badge badge-outline">template_id</code></td>
              <td><span class="badge badge-info badge-sm">string</span></td>
              <td><span class="badge badge-warning badge-sm">No</span></td>
              <td>{{ __('Template ID to use') }}</td>
            </tr>
            <tr class="hover">
              <td>6</td>
              <td><code class="badge badge-outline">file</code></td>
              <td><span class="badge badge-info badge-sm">string</span></td>
              <td><span class="badge badge-warning badge-sm">No</span></td>
              <td>{{ __('File URL (jpg,png,pdf,docx,etc)') }}</td>
            </tr>
            <tr class="hover">
              <td>7</td>
              <td><code class="badge badge-outline">variables</code></td>
              <td><span class="badge badge-info badge-sm">array</span></td>
              <td><span class="badge badge-warning badge-sm">No</span></td>
              <td>{{ __('Template variable replacements') }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>

@endsection

@push('js')
<script>
// Tab switching — use inline style to bypass Bootstrap's tab-content override
document.querySelectorAll('#code-tabs .tab').forEach(tab => {
  tab.addEventListener('click', function() {
    const targetId = this.getAttribute('data-tab');

    // Remove active from all tabs
    document.querySelectorAll('#code-tabs .tab').forEach(t => t.classList.remove('tab-active'));
    // Hide all panels via inline style
    document.querySelectorAll('.tab-content-panel').forEach(panel => {
      panel.style.display = 'none';
    });

    // Show selected panel
    this.classList.add('tab-active');
    const target = document.getElementById('tab-' + targetId);
    if (target) target.style.display = 'block';
  });
});

// Copy function
function copyText(text) {
  navigator.clipboard.writeText(text).then(() => {
    // Show success (you can add toast notification here)
    alert('Copied to clipboard!');
  });
}
</script>
@endpush
