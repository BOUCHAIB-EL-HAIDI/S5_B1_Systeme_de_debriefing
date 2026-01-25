<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $brief['title'] }} - Détails - Debrief.me</title>
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
                <a href="{{ $baseUrl }}/teacher/dashboard" class="flex items-center gap-3 p-3 rounded-xl hover:bg-white/5 transition-all text-slate-400">
                    <i data-lucide="layout-dashboard"></i> <span>Dashboard</span>
                </a>
                <a href="{{ $baseUrl }}/teacher/briefs" class="flex items-center gap-3 p-3 rounded-xl text-white bg-indigo-500 shadow-lg shadow-indigo-500/20">
                    <i data-lucide="file-text"></i> <span>Briefs</span>
                </a>
                <a href="{{ $baseUrl }}/teacher/debriefing" class="flex items-center gap-3 p-3 rounded-xl hover:bg-white/5 transition-all text-slate-400">
                    <i data-lucide="check-square"></i> <span>Débriefing</span>
                </a>
                <a href="{{ $baseUrl }}/teacher/progression" class="flex items-center gap-3 p-3 rounded-xl hover:bg-white/5 transition-all text-slate-400">
                    <i data-lucide="trending-up"></i> <span>Progression</span>
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
                <a href="{{ $baseUrl }}/teacher/briefs" class="inline-flex items-center gap-2 text-slate-400 hover:text-white transition-colors mb-6">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> Retour à la liste
                </a>

                <div class="glass rounded-[2.5rem] p-10 mb-8 border-t border-white/10 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-8 flex gap-3">
                         @php 
                            $isOverdue = strtotime($brief['end_date']) < time(); 
                        @endphp
                        @if($isOverdue)
                            <span class="px-4 py-2 bg-rose-500/20 text-rose-400 text-xs font-bold uppercase rounded-full">Terminé</span>
                        @else
                            <span class="px-4 py-2 bg-emerald-500/20 text-emerald-400 text-xs font-bold uppercase rounded-full">En cours</span>
                        @endif
                    </div>

                    <h1 class="text-4xl font-extrabold mb-2">{{ $brief['title'] }}</h1>
                    <p class="text-indigo-400 font-bold uppercase tracking-widest text-xs mb-6">{{ $brief['class_name'] }} • {{ $brief['sprint_name'] }}</p>

                    <div class="flex gap-6 text-sm text-slate-400 mb-8 border-b border-white/10 pb-8">
                        <span class="flex items-center gap-2"><i data-lucide="calendar" class="w-4 h-4 text-slate-500"></i> Du {{ date('d/m/Y', strtotime($brief['start_date'])) }} au {{ date('d/m/Y', strtotime($brief['end_date'])) }}</span>
                        <span class="flex items-center gap-2"><i data-lucide="users" class="w-4 h-4 text-slate-500"></i> {{ $brief['type'] === 'INDIVIDUAL' ? 'Individuel' : 'Groupe' }}</span>
                    </div>

                    <div class="prose prose-invert max-w-none text-slate-300">
                        {!! nl2br(htmlspecialchars($brief['content'])) !!}
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Stats -->
                    <div class="glass p-8 rounded-3xl">
                        <h3 class="text-xl font-bold mb-6 flex items-center gap-3">
                            <i data-lucide="bar-chart-2" class="text-indigo-400"></i> Statistiques
                        </h3>
                         @php 
                            $percent = $brief['total_students'] > 0 ? round(($brief['submission_count'] / $brief['total_students']) * 100) : 0;
                        @endphp
                        <div class="bg-slate-900/50 p-6 rounded-2xl border border-white/5">
                            <div class="flex justify-between items-end mb-2">
                                <span class="text-slate-400 text-sm font-bold uppercase">Taux de rendu</span>
                                <span class="text-2xl font-black text-white">{{ $percent }}%</span>
                            </div>
                            <div class="w-full h-2 bg-slate-800 rounded-full overflow-hidden mb-2">
                                <div class="h-full bg-indigo-500" style="width: {{ $percent }}%"></div>
                            </div>
                            <p class="text-xs text-slate-500 text-right">{{ $brief['submission_count'] }} rendus sur {{ $brief['total_students'] }} attendus</p>
                        </div>
                    </div>

                    <!-- Competences -->
                    <div class="glass p-8 rounded-3xl">
                        <h3 class="text-xl font-bold mb-6 flex items-center gap-3">
                            <i data-lucide="award" class="text-emerald-400"></i> Compétences visées
                        </h3>
                        <div class="space-y-3">
                            @if(!empty($brief['competences']))
                                @foreach(explode('|', $brief['competences']) as $comp)
                                    @php list($code, $label) = explode(':', $comp); @endphp
                                    <div class="flex items-center gap-4 p-3 bg-slate-900/50 rounded-xl border border-white/5">
                                        <div class="w-8 h-8 rounded-lg bg-emerald-500/20 flex items-center justify-center text-emerald-400 font-bold border border-emerald-500/20 text-xs">{{ $code }}</div>
                                        <span class="text-xs font-medium text-slate-300">{{ $label }}</span>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-slate-500 text-sm italic">Aucune compétence associée.</p>
                            @endif
                        </div>
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
