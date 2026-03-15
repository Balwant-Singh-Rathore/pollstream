<x-public-layout>
    <div class="relative flex min-h-screen items-center justify-center px-4 py-12 bg-gray-100 dark:bg-transparent bg-cover bg-center">
        <div class="relative z-10 w-full max-w-xl">
            <div class="mb-5 flex items-center justify-center gap-3">
                <div
                    class="flex items-center gap-2 rounded-full border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 px-4 py-1.5 backdrop-blur-md">
                    <span class="h-2.5 w-2.5 rounded-full bg-red-500 animate-pulse"></span>
                    <span class="text-xs font-bold uppercase tracking-widest text-gray-700 dark:text-white/90">
                        Live Poll
                    </span>
                </div>
            </div>


            <div class="overflow-hidden rounded-3xl border border-gray-200 dark:border-white/10 bg-white dark:bg-white/10 backdrop-blur-xl">

                <!-- Header -->
                <div class="px-7 pb-5 pt-7">
                    <div class="mb-3 flex items-center gap-2">
                        <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-white/60">
                            Community Poll
                        </span>
                    </div>

                    <h1 class="text-xl font-bold leading-snug text-gray-900 dark:text-white">
                        {{ $poll->question }}
                    </h1>

                    <div class="mt-3 flex items-center gap-4 text-gray-500 dark:text-white/50 text-xs">
                        <div class="flex items-center gap-1.5 text-gray-500 dark:text-white/40">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="h-3.5 w-3.5">

                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                <path d="M16 3.128a4 4 0 0 1 0 7.744" />
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                <circle cx="9" cy="7" r="4" />
                            </svg>
                            <span class="text-xs font-medium text-gray-500 dark:text-white/40" id="totalVotes">
                                {{ $poll->total_votes }} total votes
                            </span>
                            <span id="plusOneAnimation"
                                class="right-0 -top-2 text-green-500 dark:text-green-400 text-xs font-bold opacity-0 pointer-events-none">
                                +1
                            </span>
                        </div>
                        <div class="flex items-center gap-1.5 text-gray-500 dark:text-white/40">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="h-3.5 w-3.5">
                                <path
                                    d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z" />
                            </svg>
                            <span class="text-xs font-medium text-gray-500 dark:text-white/40">
                                Updates live
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mx-7 h-px bg-gray-200 dark:bg-white/10"></div>

                <!-- Body -->
                <div class="px-7 py-6">
                    @if (!session('voted') && !$alreadyVoted)
                        <div class="space-y-3">
                            <p class="mb-4 text-sm font-medium text-gray-600 dark:text-white/50">
                                Choose your answer
                            </p>
                            <form method="POST" action="{{ route('public.poll.vote', $poll->id) }}">
                                @csrf
                                <input type="hidden" name="poll_id" value="{{ $poll->id }}">
                                <div class="space-y-3">
                                    @foreach ($poll->options as $option)
                                        <label
                                            class="flex cursor-pointer items-center gap-3 rounded-2xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 px-4 py-4 hover:bg-gray-100 dark:hover:bg-white/10">
                                            <input type="radio" name="option_id" value="{{ $option->id }}"
                                                class="text-purple-500" required>
                                            <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ $option->option_text }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                                @if (session('error'))
                                    <div x-data="{ show: true }" x-show="show" x-transition
                                        class="mb-4 mt-4 flex items-start justify-between rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                                        <span>{{ session('error') }}</span>
                                        <button type="button" @click="show=false"
                                            class="ml-4 text-red-700 hover:text-red-900">
                                            ✕
                                        </button>
                                    </div>
                                @endif
                                <button
                                    class="mt-6 w-full rounded-2xl bg-gradient-to-r from-purple-600 to-blue-500 px-4 py-3 text-sm font-bold text-white shadow-lg hover:opacity-90">
                                    Cast Your Vote
                                </button>
                            </form>
                        </div>
                    @endif

                    @if (session('voted') || $alreadyVoted)
                        <div class="space-y-3">
                            <div
                                class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/15 px-2.5 py-1 border border-emerald-400/20">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="h-3 w-3 text-emerald-400">
                                    <path d="M20 6 9 17l-5-5" />
                                </svg>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-300">
                                    Your vote has been cast 😍
                                </span>
                            </div>
                            <div class="mb-4 flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-600 dark:text-white/50">
                                    Results
                                </p>
                                <div class="flex items-center gap-1.5 rounded-full bg-gray-100 dark:bg-white/5 px-2.5 py-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="h-3 w-3 text-red-400">
                                        <path d="M16.247 7.761a6 6 0 0 1 0 8.478" />
                                        <path d="M19.075 4.933a10 10 0 0 1 0 14.134" />
                                        <path d="M4.925 19.067a10 10 0 0 1 0-14.134" />
                                        <path d="M7.753 16.239a6 6 0 0 1 0-8.478" />
                                        <circle cx="12" cy="12" r="2" />
                                    </svg>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-white/40">
                                        Live
                                    </span>
                                </div>
                            </div>
                            @foreach ($poll->options->sortByDesc('votes_count') as $option)
                                @php
                                    $totalVotes = $poll->total_votes ?? 0;
                                    $percentage =
                                        $totalVotes > 0 ? round(($option->votes_count / $totalVotes) * 100) : 0;
                                @endphp
                                <div>
                                    <div class="flex justify-between text-sm text-gray-900 dark:text-white">
                                        <span>{{ $option->option_text }}</span>
                                        <span id="percentage-{{ $option->id }}">{{ $percentage }}%</span>
                                    </div>
                                    <div class="mt-1 h-2.5 w-full bg-gray-200 dark:bg-white/10 rounded-full">
                                        <div class="h-full rounded-full bg-gradient-to-r from-purple-500 to-blue-500 transition-all duration-700 ease-out"
                                            style="width: {{ $percentage }}%" id="progress-{{ $option->id }}">
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-gray-500 dark:text-white/40 mt-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="h-3.5 w-3.5">

                                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                            <path d="M16 3.128a4 4 0 0 1 0 7.744" />
                                            <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                            <circle cx="9" cy="7" r="4" />
                                        </svg>
                                        <span class="text-xs font-medium text-gray-500 dark:text-white/40"
                                            id="voteCount-{{ $option->id }}">
                                            {{ $option->votes_count }} votes
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-10 flex flex-col items-center text-center">
                <p class="text-xs tracking-wide text-gray-500 dark:text-white/50 uppercase">
                    Powered by
                </p>
                <a href="#" class="group flex items-center gap-3 rounded-xl px-4 py-2 transition">
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-500 shadow-md shadow-indigo-500/30 transition group-hover:scale-105">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <span
                        class="text-lg font-semibold tracking-wide text-gray-900 dark:text-white transition group-hover:text-indigo-400">
                        Pollstream
                    </span>
                </a>
            </div>
        </div>
    </div>
    <div id="poll-stream" data-poll-id="{{ $poll->id }}" data-options='@json($poll->options)'></div>
</x-public-layout>
