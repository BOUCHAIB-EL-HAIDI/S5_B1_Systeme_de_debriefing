<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Briefs - Debrief.me</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass { background: rgba(30, 41, 59, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); }
    </style>
</head>
<body class="bg-[#0f172a] text-slate-100 min-h-screen">

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 glass border-r border-white/10 p-6 flex flex-col fixed h-full">
            <div class="flex items-center gap-3 text-2xl font-extrabold mb-10">
                <i data-lucide="graduation-cap" class="text-indigo-500"></i>
                <span>Debrief.me</span>
            </div>
            
            <nav class="space-y-2 flex-1">
                <a href="{{ $baseUrl }}/student/dashboard" class="flex items-center gap-3 p-3 rounded-xl hover:bg-white/5 transition-all text-slate-400">
                    <i data-lucide="layout-dashboard"></i> <span>Dashboard</span>
                </a>
                <a href="{{ $baseUrl }}/student/briefs" class="flex items-center gap-3 p-3 rounded-xl text-white bg-indigo-500 shadow-lg shadow-indigo-500/20">
                    <i data-lucide="file-text"></i> <span>Mes Briefs</span>
                </a>
                <a href="{{ $baseUrl }}/student/progression" class="flex items-center gap-3 p-3 rounded-xl hover:bg-white/5 transition-all text-slate-400">
                    <i data-lucide="award"></i> <span>Mon Parcours</span>
                </a>
            </nav>

            <div class="pt-6 border-t border-white/10">
                <a href="{{ $baseUrl }}/logout" class="flex items-center gap-3 p-3 rounded-xl text-rose-400 hover:bg-rose-500/10 transition-all">
                    <i data-lucide="log-out"></i> <span>Déconnexion</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 ml-64 p-8">
            <header class="flex justify-between items-center mb-10">
                <div>
                    <h1 class="text-3xl font-extrabold">Mes Briefs</h1>
                    <p class="text-slate-400 mt-1">Consultez vos projets et soumettez vos livrables</p>
                </div>
            </header>

            @if(!empty($_SESSION['success']))
                <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-2xl text-sm flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                    {{ $_SESSION['success'] }}
                    @php unset($_SESSION['success']) @endphp
                </div>
            @endif

            @if(!empty($_SESSION['error']))
                <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-2xl text-sm flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                    {{ $_SESSION['error'] }}
                    @php unset($_SESSION['error']) @endphp
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($briefs as $brief)
                <div class="glass p-8 rounded-[2.5rem] group hover:border-indigo-500/50 transition-all relative overflow-hidden flex flex-col h-full">
                    <div class="absolute top-0 right-0 p-6 z-10">
                        @if($brief['is_overdue'])
                            <span class="px-3 py-1 bg-rose-500/20 text-rose-400 text-[10px] font-bold uppercase rounded-full">Expiré</span>
                        @elseif($brief['status'] === 'Soumis')
                            <span class="px-3 py-1 bg-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase rounded-full">Soumis</span>
                        @else
                            <span class="px-3 py-1 bg-indigo-500/20 text-indigo-400 text-[10px] font-bold uppercase rounded-full">En cours</span>
                        @endif
                    </div>

                    <div class="w-14 h-14 bg-slate-900 border border-white/10 rounded-2xl flex items-center justify-center mb-6 flex-shrink-0 group-hover:scale-110 transition-transform">
                        <i data-lucide="code" class="text-indigo-400"></i>
                    </div>

                    <h3 class="text-xl font-extrabold mb-2">{{ $brief['title'] }}</h3>
                    <p class="text-[10px] text-indigo-300 font-bold mb-2 uppercase">{{ $brief['sprint_name'] }}</p>
                    <p class="text-xs text-slate-500 mb-6 leading-relaxed line-clamp-3 flex-grow">{{ $brief['content'] }}</p>

                    <div class="space-y-3 mb-8">
                        <div class="flex items-center justify-between text-[11px]">
                            <span class="text-slate-400">Date de début</span>
                            <span class="font-bold">{{ date('d/m/Y', strtotime($brief['start_date'])) }}</span>
                        </div>
                        <div class="flex items-center justify-between text-[11px]">
                            <span class="text-slate-400">Date de fin</span>
                            <span class="font-bold {{ $brief['is_overdue'] ? 'text-rose-400' : 'text-slate-200' }}">{{ date('d/m/Y', strtotime($brief['end_date'])) }}</span>
                        </div>
                    </div>

                    <div class="mt-auto">
                        <a href="{{ $baseUrl }}/student/brief?id={{ $brief['id'] }}" class="block w-full py-3 mb-3 rounded-xl border border-indigo-500/30 text-indigo-400 hover:bg-indigo-500/10 text-xs font-bold transition-all text-center">
                            Voir les détails complets
                        </a>

                        @if(!$brief['is_overdue'] || $brief['status'] === 'Soumis')
                        <form action="{{ $baseUrl }}/student/briefs/submit" method="POST" class="space-y-3">
                            <input type="hidden" name="brief_id" value="{{ $brief['id'] }}">
                            <div class="relative">
                                <i data-lucide="link" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500"></i>
                                <input type="url" name="livrable_url" value="{{ $brief['livrable_url'] ?? '' }}" placeholder="https://github.com/..." required 
                                    class="w-full bg-slate-900/50 border border-white/10 rounded-xl pl-10 pr-4 py-3 text-xs focus:ring-2 focus:ring-indigo-500 outline-none transition-all placeholder:text-slate-600">
                            </div>
                            <button type="submit" class="w-full py-3 rounded-xl bg-indigo-500 hover:bg-indigo-600 text-xs font-bold transition-all shadow-lg shadow-indigo-500/10 flex items-center justify-center gap-2">
                                <i data-lucide="send" class="w-4 h-4"></i>
                                {{ $brief['status'] === 'Soumis' ? 'Soumettre un nouveau livrable' : 'Soumettre le livrable' }}
                            </button>
                        </form>
                        @else
                            <div class="p-3 bg-rose-500/10 border border-rose-500/20 rounded-xl text-center">
                                <p class="text-xs text-rose-400 font-bold">Date limite dépassée</p>
                            </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </main>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
