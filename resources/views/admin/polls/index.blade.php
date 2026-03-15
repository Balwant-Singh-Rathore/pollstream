<x-app-layout>
    <div x-data="{ openDelete: false, deleteId: null }">
        <div x-show="openDelete" x-transition x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
            <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-xl">
                <h2 class="text-lg font-semibold text-gray-900">
                    Delete Poll
                </h2>
                <p class="mt-2 text-sm text-gray-500">
                    Are you sure you want to delete this poll?
                    <br><br>
                    <strong>This action will permanently delete the poll and all voting data.</strong>
                </p>
                <div class="mt-6 flex justify-end gap-3">
                    <button @click="openDelete=false" class="rounded-lg border px-4 py-2 text-sm hover:bg-gray-50">
                        Cancel
                    </button>
                    <form method="POST" :action="'/polls/' + deleteId">
                        @csrf
                        @method('DELETE')
                        <button class="rounded-lg bg-red-600 px-4 py-2 text-sm text-white hover:bg-red-700">
                            Delete Poll
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" x-transition
                        class="mb-4 flex items-start justify-between rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                        <span>{{ session('success') }}</span>
                        <button @click="show=false" class="ml-4 text-green-700 hover:text-green-900">
                            ✕
                        </button>
                    </div>
                @endif
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-900">Polls</h1>
                        <p class="mt-1 text-sm text-gray-500">Manage all your polls</p>
                    </div>

                    <a href="{{ route('polls.create') }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-[#6366f1] px-4 py-2 text-sm font-medium text-white hover:bg-[#6366f1]-700">

                        Create New Poll
                    </a>
                </div>
                <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200 bg-gray-50">
                                <th
                                    class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Poll Question
                                </th>

                                <th
                                    class="hidden px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 sm:table-cell">
                                    Votes
                                </th>
                                <th
                                    class="hidden px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 md:table-cell">
                                    Created
                                </th>

                                <th
                                    class="px-5 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Actions
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">
                            @if (count($polls) === 0)
                                <tr>
                                    <td colspan="4" class="px-5 py-5 text-center text-sm text-gray-500">
                                        No polls found. Create your first poll!
                                    </td>
                                </tr>
                            @else
                                @foreach ($polls as $poll)
                                    <tr class="hover:bg-gray-50">

                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-2">

                                                <span class="text-sm font-medium text-gray-900">
                                                    {{ $poll->question }}
                                                </span>

                                                @if ($poll->active)
                                                    <span
                                                        class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">
                                                        Active
                                                    </span>
                                                @endif

                                            </div>
                                        </td>

                                        <td class="hidden px-5 py-4 text-sm text-gray-500 sm:table-cell">
                                            {{ number_format($poll->total_votes) }}
                                        </td>

                                        <td class="hidden px-5 py-4 text-sm text-gray-500 md:table-cell">
                                            {{ $poll->created_at->format('Y-m-d') }}
                                        </td>


                                        <td class="px-5 py-4">
                                            <div class="flex items-center justify-end gap-2">

                                                <a href="{{ route('polls.results', $poll->id) }}"
                                                    class="rounded-lg px-3 py-1 text-sm text-gray-600 hover:bg-gray-100">
                                                    View
                                                </a>

                                                <button
                                                    onclick="copyLink('{{ url('/poll/' . $poll->slug) }}', {{ $poll->id }})"
                                                    id="copyLinkBtn-{{ $poll->id }}"
                                                    class="rounded-lg px-3 py-1 text-sm text-gray-600 hover:bg-gray-100">
                                                    Copy
                                                </button>

                                                <button @click="openDelete = true; deleteId={{ $poll->id }}"
                                                    class="rounded-lg px-3 py-1 text-sm text-red-600 hover:bg-red-50">
                                                    Delete
                                                </button>
                                            </div>
                                        </td>

                                    </tr>
                                @endforeach
                            @endIf
                        </tbody>
                    </table>
                    <div class="p-4">
                        {{ $polls->links() }}
                    </div>
                </div>
                <script>
                    function copyLink(link, pollId) {
                        navigator.clipboard.writeText(link);
                        const btn = document.getElementById('copyLinkBtn-' + pollId);
                        btn.textContent = 'Copied!';
                        setTimeout(() => {
                            btn.textContent = 'Copy';
                        }, 2000);
                    }

                    function confirmDelete() {
                        return confirm(
                            "Are you sure you want to delete this poll?\n\nAll votes and related data will be permanently deleted.");
                    }
                </script>
            </div>
        </div>
    </div>
</x-app-layout>
