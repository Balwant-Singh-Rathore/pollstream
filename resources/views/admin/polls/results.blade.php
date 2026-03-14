<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl">

                <a href="{{ route('polls') }}"
                    class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 mb-2">
                    ←
                    Back to Polls
                </a>

                <div class="mb-6">
                    <h1 class="text-2xl font-semibold text-gray-900">Live Poll Results</h1>
                    <p class="mt-1 text-sm text-gray-500">View live realtime voting results</p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

                    <h2 class="text-lg font-semibold text-gray-900">{{ $poll['question'] }}</h2>
                    <p class="mt-1 text-sm text-gray-500">{{ $poll['total_votes'] ?? 0 }} total votes</p>

                    <div class="mt-6 space-y-4">

                        @foreach ($poll['options'] as $opt)
                            @php
                                $pct = round(($opt['votes_count'] / ($poll['total_votes'] ?? 1)) * 100);
                            @endphp

                            <div>
                                <div class="mb-1 flex items-center justify-between text-sm">
                                    <span class="font-medium text-gray-900">{{ $opt['option_text'] }}</span>
                                    <span class="text-gray-500">
                                        {{ $opt['votes_count'] }} votes · {{ $pct }}%
                                    </span>
                                </div>

                                <div class="h-2.5 w-full overflow-hidden rounded-full bg-gray-200">
                                    <div class="h-full rounded-full bg-blue-600 transition-all duration-500"
                                        style="width: {{ $pct }}%">
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>

                    <div class="mt-6 flex items-center gap-2 rounded-lg border border-gray-200 bg-gray-50 px-3 py-2">
                        <span class="flex-1 truncate text-sm text-gray-500">{{ $shareLink }}</span>

                        <button onclick="copyLink()" id="copyLinkBtn" class="rounded-md p-1.5 text-gray-500 hover:bg-gray-200">
                            Copy
                        </button>
                    </div>

                </div>
            </div>

            <script>
                function copyLink() {
                    navigator.clipboard.writeText("{{ $shareLink }}");
                    const btn = document.getElementById('copyLinkBtn');
                    btn.textContent = 'Copied!';
                    setTimeout(() => {
                        btn.textContent = 'Copy';
                    }, 2000);
                }
            </script>
        </div>
    </div>
</x-app-layout>
