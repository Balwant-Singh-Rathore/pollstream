<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mx-auto max-w-lg">

                <a href="{{ route('polls') }}"
                    class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 mb-2">
                    ←
                    Back to Polls
                </a>

                <div class="mb-6">
                    <h1 class="text-2xl font-semibold text-gray-900">Create Poll</h1>
                    <p class="mt-1 text-sm text-gray-500">Add a new poll with custom options</p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

                    <form method="POST" action="{{ route('polls.store') }}" class="space-y-5">
                        @csrf
                        <div class="mt-4">
                            <x-input-label for="question" :value="__('Poll Question')" />

                            <x-text-input id="question" class="block mt-1 w-full" type="text" name="question"
                                placeholder="What would you like to ask?" required autocomplete="question" />

                            <x-input-error :messages="$errors->get('question')" class="mt-2" />
                        </div>

                        <div x-data="{ options: ['', ''] }">
                            <x-input-label for="options" :value="__('Poll Options')" />

                            <p class="mt-1 text-sm text-gray-500">Add at least 2 options for voters to choose from</p>
                            <template x-for="(option, index) in options" :key="index">
                                <div class="flex items-center gap-2 mt-2">

                                    <input type="text" name="options[]" x-model="options[index]"
                                        :placeholder="'Option ' + (index + 1)"
                                        class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm" required>

                                    <button type="button" x-show="options.length > 2" @click="options.splice(index,1)"
                                        class="text-red-500">
                                        ⛔
                                    </button>
                                </div>
                            </template>

                            <button type="button" @click="options.push('')"
                                class="mt-2 text-sm font-medium text-[#6366f1] hover:underline">
                                + Add Option
                            </button>
                        </div>

                        <!-- Submit -->
                        <button type="submit"
                            class="w-full rounded-lg bg-[#6366f1] px-4 py-2 text-sm font-medium text-white hover:bg-[#6366f1]-700">
                            Create Poll
                        </button>

                    </form>

                </div>

            </div>
        </div>
    </div>
</x-app-layout>
