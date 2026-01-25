<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $brief['title'] }} - Debrief.me</title>
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
            <div class="max-w-4xl mx-auto">
                <a href="{{ $baseUrl }}/student/briefs" class="inline-flex items-center gap-2 text-slate-400 hover:text-white transition-colors mb-6">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> Retour aux briefs
                </a>

                <div class="glass rounded-[2.5rem] p-10 mb-8 border-t border-white/10 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-8">
                        @if($brief['is_overdue'])
                            <span class="px-4 py-2 bg-rose-500/20 text-rose-400 text-xs font-bold uppercase rounded-full">Expiré</span>
                        @elseif($brief['status'] === 'Soumis')
                            <span class="px-4 py-2 bg-emerald-500/20 text-emerald-400 text-xs font-bold uppercase rounded-full">Soumis</span>
                        @else
                            <span class="px-4 py-2 bg-indigo-500/20 text-indigo-400 text-xs font-bold uppercase rounded-full">En cours</span>
                        @endif
                    </div>

                    <h1 class="text-4xl font-extrabold mb-4">{{ $brief['title'] }}</h1>
                    <div class="flex gap-6 text-sm text-slate-400 mb-8">
                        <span class="flex items-center gap-2"><i data-lucide="calendar" class="w-4 h-4 text-indigo-400"></i> Du {{ date('d/m', strtotime($brief['start_date'])) }} au {{ date('d/m/Y', strtotime($brief['end_date'])) }}</span>
                        <span class="flex items-center gap-2"><i data-lucide="target" class="w-4 h-4 text-indigo-400"></i> Sprint: {{ $brief['sprint_name'] }}</span>
                    </div>

                    <div class="prose prose-invert max-w-none text-slate-300">
                        {!! nl2br(htmlspecialchars($brief['content'])) !!}
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Competences -->
                    <div class="glass p-8 rounded-3xl">
                        <h3 class="text-xl font-bold mb-6 flex items-center gap-3">
                            <i data-lucide="award" class="text-indigo-400"></i> Compétences visées
                        </h3>
                        <div class="space-y-4">
                            @if(!empty($brief['competences']))
                                @foreach(explode('|', $brief['competences']) as $comp)
                                    @php list($code, $label) = explode(':', $comp); @endphp
                                    <div class="flex items-center gap-4 p-4 bg-slate-900/50 rounded-2xl border border-white/5">
                                        <div class="w-10 h-10 rounded-xl bg-indigo-500/20 flex items-center justify-center text-indigo-400 font-bold border border-indigo-500/20">{{ $code }}</div>
                                        <span class="text-sm font-medium">{{ $label }}</span>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-slate-500 text-sm italic">Aucune compétence associée.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Submission -->
                    <div class="glass p-8 rounded-3xl">
                        <h3 class="text-xl font-bold mb-6 flex items-center gap-3">
                            <i data-lucide="send" class="text-emerald-400"></i> Rendu du travail
                        </h3>
                        
                        @if(!$brief['is_overdue'] || $brief['status'] === 'Soumis')
                        <form action="{{ $baseUrl }}/student/briefs/submit" method="POST" class="space-y-4">
                            <input type="hidden" name="brief_id" value="{{ $brief['id'] }}">
                            <div>
                                <label class="text-xs text-slate-400 mb-2 block uppercase tracking-widest">Lien du livrable (GitHub, etc.)</label>
                                <div class="relative">
                                    <i data-lucide="link" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500"></i>
                                    <input type="url" name="livrable_url" value="{{ $brief['status'] === 'Soumis' ? '' : ($brief['livrable_url'] ?? '') }}" placeholder="https://github.com/..." required 
                                        class="w-full bg-slate-900/50 border border-white/10 rounded-xl pl-12 pr-4 py-4 text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all placeholder:text-slate-600">
                                </div>
                            </div>
                            <button type="submit" class="w-full py-4 rounded-xl bg-indigo-500 hover:bg-indigo-600 font-bold transition-all shadow-lg shadow-indigo-500/20 flex items-center justify-center gap-2">
                                <i data-lucide="check" class="w-5 h-5"></i>
                                {{ $brief['status'] === 'Soumis' ? 'Soumettre un autre livrable' : 'Soumettre mon travail' }}
                            </button>
                        </form>
                        @if($brief['status'] === 'Soumis')
                        <p class="mt-4 text-xs text-center text-emerald-400 flex items-center justify-center gap-2">
                            <i data-lucide="clock" class="w-3 h-3"></i> Dernière soumission : {{ date('d/m/Y H:i', strtotime($brief['submitted_at'])) }}
                        </p>
                        @endif
                        @else
                            <div class="p-6 bg-rose-500/10 border border-rose-500/20 rounded-2xl text-center">
                                <i data-lucide="alert-octagon" class="w-8 h-8 text-rose-400 mx-auto mb-3"></i>
                                <p class="text-rose-400 font-bold">La date limite est dépassée.</p>
                                <p class="text-xs text-rose-300/70 mt-1">Vous ne pouvez plus soumettre de livrable pour ce brief.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
