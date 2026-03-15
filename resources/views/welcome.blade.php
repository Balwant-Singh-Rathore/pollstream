<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pollstream - Real-Time Polling Platform</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script>
        window.tailwind = window.tailwind || {};
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0f4ff',
                            100: '#e0e9ff',
                            200: '#c7d6fe',
                            300: '#a5b9fc',
                            400: '#8193f8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --bg: #fafbfc;
            --fg: #18181b;
            --muted: #71717a;
            --accent: #6366f1;
            --accent-hover: #4f46e5;
            --card: #ffffff;
            --border: #e4e4e7;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        @media (prefers-reduced-motion: reduce) {
            html {
                scroll-behavior: auto;
            }

            .animate-on-scroll,
            .stagger-item {
                opacity: 1 !important;
                transform: none !important;
                transition: none !important;
            }
        }

        body {
            background: var(--bg);
            color: var(--fg);
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
        }

        .bg-gradient-subtle {
            background:
                radial-gradient(ellipse 80% 50% at 50% -20%, rgba(99, 102, 241, 0.08) 0%, transparent 50%),
                radial-gradient(ellipse 60% 40% at 100% 50%, rgba(99, 102, 241, 0.05) 0%, transparent 50%),
                var(--bg);
        }

        .btn-primary {
            background: var(--accent);
            color: white;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background: var(--accent-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-secondary {
            background: transparent;
            color: var(--fg);
            border: 1px solid var(--border);
            transition: all 0.2s ease;
        }

        .btn-secondary:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            transition: all 0.3s ease;
        }

        .card:hover {
            border-color: rgba(99, 102, 241, 0.3);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.04);
        }

        .animate-on-scroll {
            opacity: 1;
            transform: translateY(20px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        .animate-on-scroll.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .stagger-item {
            opacity: 1;
            transform: translateY(20px);
            transition: opacity 0.5s ease, transform 0.5s ease;
        }

        .stagger-item.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .poll-option {
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .poll-option:hover {
            background: rgba(99, 102, 241, 0.05);
        }

        .poll-option.selected {
            background: rgba(99, 102, 241, 0.08);
            border-color: var(--accent);
        }

        .vote-bar {
            transition: width 0.6s ease;
        }

        .step-line {
            background: linear-gradient(to bottom, var(--accent), transparent);
        }

        .icon-container {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(99, 102, 241, 0.05) 100%);
        }

        :focus-visible {
            outline: 2px solid var(--accent);
            outline-offset: 2px;
        }
    </style>
</head>

<body data-turbo="false" class="bg-gradient-subtle min-h-screen">
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-md border-b border-zinc-200/60">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="#" class="flex items-center absolute gap-2 font-bold text-xl text-zinc-900">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>

                <div class="hidden md:flex items-center gap-8">
                    @if (Route::has('login'))
                        <nav class="flex items-center justify-end gap-4">
                            @auth
                                <a href="{{ url('/dashboard') }}"
                                    class="btn-primary px-4 py-2 rounded-lg text-sm font-semibold">
                                    Dashboard
                                </a>
                            @else
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}"
                                        class="btn-primary px-4 py-2 rounded-lg text-sm font-semibold">
                                        Register
                                    </a>
                                @endif
                            @endauth
                        </nav>
                    @endif
                </div>

                <button id="mobile-menu-btn" class="md:hidden p-2 rounded-lg hover:bg-zinc-100 transition-colors"
                    aria-label="Toggle menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        <div id="mobile-menu" class="hidden md:hidden border-t border-zinc-200/60 bg-white">
            <div class="px-4 py-4 space-y-3">
                <a href="#features"
                    class="block text-sm font-medium text-zinc-600 hover:text-zinc-900 py-2">Features</a>
                <a href="#how-it-works" class="block text-sm font-medium text-zinc-600 hover:text-zinc-900 py-2">How It
                    Works</a>
                <a href="{{ route('register') }}" class="btn-primary px-4 py-2 rounded-lg text-sm font-semibold">
                    Create Poll
                </a>
            </div>
        </div>
    </nav>

    <section class="pt-32 pb-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto text-center">
            <div class="animate-on-scroll">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-50 text-brand-600 text-sm font-medium mb-6">
                    <span class="w-2 h-2 rounded-full bg-brand-500 animate-pulse"></span>
                    Real-time polling powered by WebSockets
                </div>
            </div>

            <h1
                class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-zinc-900 tracking-tight leading-tight mb-6 animate-on-scroll">
                Create Real-Time Polls<br class="hidden sm:block"> in Seconds
            </h1>

            <p class="text-lg sm:text-xl text-zinc-600 max-w-2xl mx-auto mb-10 animate-on-scroll">
                A simple polling platform where admins create polls and users vote to see live results instantly. No
                signup required.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 animate-on-scroll">
                <a href="{{ route('register') }}"
                    class="btn-primary text-white px-8 py-3 rounded-xl font-semibold transition-colors">
                    Create Poll Now
                </a>
            </div>
        </div>
    </section>

    <section id="features" class="py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16 animate-on-scroll">
                <h2 class="text-3xl sm:text-4xl font-bold text-zinc-900 mb-4">Simple Yet Powerful</h2>
                <p class="text-zinc-600 max-w-xl mx-auto">Everything you need to gather opinions and make decisions
                    together.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <div class="card rounded-2xl p-6 stagger-item">
                    <div class="icon-container w-12 h-12 rounded-xl flex items-center justify-center mb-5">
                        <svg class="w-6 h-6 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-zinc-900 mb-2">Create Polls Easily</h3>
                    <p class="text-zinc-600 text-sm leading-relaxed">Set up a poll in under 30 seconds. Add your
                        question, options, and you're ready to share.</p>
                </div>

                <div class="card rounded-2xl p-6 stagger-item">
                    <div class="icon-container w-12 h-12 rounded-xl flex items-center justify-center mb-5">
                        <svg class="w-6 h-6 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-zinc-900 mb-2">Vote Instantly</h3>
                    <p class="text-zinc-600 text-sm leading-relaxed">No accounts or logins required. Voters simply click
                        their choice and submit.</p>
                </div>

                <div class="card rounded-2xl p-6 stagger-item">
                    <div class="icon-container w-12 h-12 rounded-xl flex items-center justify-center mb-5">
                        <svg class="w-6 h-6 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-zinc-900 mb-2">Real-Time Results</h3>
                    <p class="text-zinc-600 text-sm leading-relaxed">Watch votes appear instantly with
                        WebSocket-powered live updates.</p>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
