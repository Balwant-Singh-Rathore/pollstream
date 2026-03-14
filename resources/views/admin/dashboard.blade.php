<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div>
                        <div class="mb-6">
                            <h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>
                            <p class="mt-1 text-sm text-gray-500">Overview of your poll activity</p>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-500">Total Polls</p>
                                </div>

                                <p class="mt-2 text-3xl font-semibold text-gray-900">{{ $totalPolls }}</p>
                            </div>
                            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-500">Total Votes</p>
                                </div>

                                <p class="mt-2 text-3xl font-semibold text-gray-900">{{ $totalVotes }}</p>
                            </div>
                            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-500">Active Polls</p>
                                </div>

                                <p class="mt-2 text-3xl font-semibold text-gray-900">{{ $totalActivePolls }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
