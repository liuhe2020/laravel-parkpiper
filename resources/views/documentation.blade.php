@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto pt-8 pb-16">
        <h1 class="text-3xl font-semibold text-gray-900 dark:text-gray-100 mb-2">API Documentation</h1>
        <p class="text-base text-gray-500 dark:text-gray-300 mb-8">
            Endpoints for managing and checking parking permits.<br>
        </p>

        <div class="space-y-10">
            <!-- Create Permit -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Create a Permit</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-300 mt-0.5">POST <span class="font-mono text-sm bg-gray-100 dark:bg-gray-900 px-2 py-1 rounded">/api/permits</span></p>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-sm text-gray-700 dark:text-gray-200 font-semibold mb-1">Request JSON example</p>
                        <pre class="bg-gray-100 dark:bg-gray-900 p-3 rounded text-sm overflow-x-auto"><code class="font-mono text-gray-800 dark:text-gray-200">{
    "licence_plate": "FX17NBB",
    "valid_from": "2025-11-23T08:00:00",
    "valid_to": "2025-11-24T08:00:00"
}</code></pre>
                    </div>
                    <div>
                        <p class="text-sm text-gray-700 dark:text-gray-200 font-semibold mb-1">cURL</p>
                        <pre class="bg-gray-100 dark:bg-gray-900 p-3 rounded text-sm overflow-x-auto"><code class="font-mono text-gray-800 dark:text-gray-200">curl -X POST https://parkpiper.vercel.app/api/permits \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{"licence_plate":"FX17NBB","valid_from":"2025-11-23T08:00:00","valid_to":"2025-11-24T08:00:00"}'</code></pre>
                    </div>
                    <div>
                        <p class="text-sm text-gray-700 dark:text-gray-200 font-semibold mb-1">Success (201)</p>
                        <pre class="bg-gray-100 dark:bg-gray-900 p-3 rounded text-sm overflow-x-auto"><code class="font-mono text-gray-800 dark:text-gray-200">{
    "id": 123,
    "licence_plate": "FX17NBB",
    "valid_from": "2025-11-23T08:00:00",
    "valid_to": "2025-11-24T08:00:00",
    "created_at": "...",
    "updated_at": "..."
}</code></pre>
                    </div>
                </div>
            </div>

            <!-- Check Permit Coverage -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Check Permit Coverage</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-300 mt-0.5">GET <span class="font-mono text-sm bg-gray-100 dark:bg-gray-900 px-2 py-1 rounded">/api/permits/check</span></p>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-sm text-gray-700 dark:text-gray-200 font-semibold mb-1">Required query:</p>
                        <span class="font-mono text-sm dark:text-gray-300 bg-gray-100 dark:bg-gray-900 px-2 py-1 rounded">licence_plate</span>
                        <ul class="list-disc list-inside text-sm text-gray-600 dark:text-gray-300 mt-2 mb-2">
                            <li>check_date: <span class="font-mono">YYYY-MM-DDTHH:MM:SS</span> (optional — defaults to now)</li>
                            <li>stay_start: <span class="font-mono">YYYY-MM-DDTHH:MM:SS</span> (required for duration check)</li>
                            <li>stay_end: <span class="font-mono">YYYY-MM-DDTHH:MM:SS</span> (required for duration check)</li>
                            <li>include_related: <span class="font-mono">boolean</span> (optional - include overlapping permits)</li>
                            <li>include_all: <span class="font-mono">boolean</span> (optional - include all permits)</li>
                        </ul>
                    </div>

                    <div>
    <p class="text-sm text-gray-700 dark:text-gray-200 font-semibold mb-1">Request JSON example - specific date check</p>
    <pre class="bg-gray-100 dark:bg-gray-900 p-3 rounded text-sm overflow-x-auto"><code class="font-mono text-gray-800 dark:text-gray-200">{
    "licence_plate": "FX17NBB",
    "check_date": "2025-12-11T12:00:00",
}</code></pre>

</div>
                    <div>
    <p class="text-sm text-gray-700 dark:text-gray-200 font-semibold mb-1">Request JSON example - duration check</p>
    <pre class="bg-gray-100 dark:bg-gray-900 p-3 rounded text-sm overflow-x-auto"><code class="font-mono text-gray-800 dark:text-gray-200">{
    "licence_plate": "FX17NBB",
    "stay_start": "2025-12-10T08:00:00",
    "stay_end": "2025-12-30T08:00:00",
    "include_related": true,
    "include_all": true
}</code></pre>
</div>

                    <div>
                        <p class="text-sm text-gray-700 dark:text-gray-200 font-semibold mb-1">Specific date example</p>
                        <pre class="bg-gray-100 dark:bg-gray-900 p-3 rounded text-sm overflow-x-auto"><code class="font-mono text-gray-800 dark:text-gray-200">curl "https://parkpiper.vercel.app/api/permits/check" \
  -G \
  --data-urlencode "licence_plate=FX17NBB" \
  --data-urlencode "check_date=2025-12-01T12:00:00" \
  -H "Accept: application/json"</code></pre>
                    </div>
                    <div>
                        <p class="text-sm text-gray-700 dark:text-gray-200 font-semibold mb-1">Duration example</p>
                        <pre class="bg-gray-100 dark:bg-gray-900 p-3 rounded text-sm overflow-x-auto"><code class="font-mono text-gray-800 dark:text-gray-200">curl "https://parkpiper.vercel.app/api/permits/check" \
  -G \
  --data-urlencode "licence_plate=FX17NBB" \
  --data-urlencode "stay_start=2025-12-01T08:00:00" \
  --data-urlencode "stay_end=2025-12-30T08:00:00" \
  -H "Accept: application/json"</code></pre>
                        <p class="text-sm text-gray-700 dark:text-gray-200 mt-4 mb-1 font-semibold">Sample response for duration check</p>
                        <pre class="bg-gray-100 dark:bg-gray-900 p-3 rounded text-sm overflow-x-auto"><code class="font-mono text-gray-800 dark:text-gray-200">{
    "status": "partially_covered",          // covered | partially_covered | not_covered
    "licence_plate": "FX17NBB",
    "coverage": {
        "covered_periods": [
            { "start": "...", "end": "...", "permit_id": 12 }
            ],
            "uncovered_periods": [
                { "start": "...", "end": "..." }
                ]
            },
    "related_permits": [ ... ],            // permits that overlap the requested stay window
    "all_permits": [ ... ]
}</code></pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection