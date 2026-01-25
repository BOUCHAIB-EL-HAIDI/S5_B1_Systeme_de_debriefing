<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ma Progression - Debrief.me</title>
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
                <a href="{{ $baseUrl }}/student/briefs" class="flex items-center gap-3 p-3 rounded-xl hover:bg-white/5 transition-all text-slate-400">
                    <i data-lucide="file-text"></i> <span>Mes Briefs</span>
                </a>
                <a href="{{ $baseUrl }}/student/progression" class="flex items-center gap-3 p-3 rounded-xl text-white bg-indigo-500 shadow-lg shadow-indigo-500/20">
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
                    <h1 class="text-3xl font-extrabold">Mon Parcours Pédagogique</h1>
                    <p class="text-slate-400 mt-1">Suivez votre progression et vos validations</p>
                </div>
            </header>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
                <div class="glass p-6 rounded-3xl border-l-4 border-indigo-500">
                    <p class="text-xs text-slate-400 uppercase tracking-widest mb-1">Compétences Évaluées</p>
                    <h2 class="text-4xl font-extrabold text-white">{{ count($progression) }}</h2>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Skill Map -->
                <div class="lg:col-span-2 glass p-8 rounded-3xl">
                    <h3 class="text-xl font-bold mb-8">Maillage des Compétences</h3>
                    
                    @if(empty($progression))
                        <div class="text-center py-20 text-slate-500 italic">
                            <i data-lucide="award" class="w-12 h-12 mb-4 mx-auto opacity-20"></i>
                            <p>Aucune compétence validée pour le moment.</p>
                        </div>
                    @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($progression as $prog)
                        @php 
                            $isValid = $prog['status'] === 'VALIDEE';
                            $highestValidated = $prog['highest_validated'] ?? null;
                            $latestNiveau = $prog['latest_niveau'] ?? 'NIVEAU_1';
                            
                            // Determine badge color based on latest status
                            $badgeClass = $isValid ? 'bg-emerald-500/10 text-emerald-400' : 'bg-rose-500/10 text-rose-400';
                            $borderClass = $isValid ? 'hover:border-emerald-500/30' : 'hover:border-rose-500/30';
                            
                            // Determine colors for N1 bar
                            if ($highestValidated && 'NIVEAU_1' <= $highestValidated) {
                                $n1Color = 'bg-emerald-500/50';
                                $n1Label = 'text-emerald-400';
                            } elseif ('NIVEAU_1' == $latestNiveau && !$isValid) {
                                $n1Color = 'bg-rose-500/50';
                                $n1Label = 'text-rose-400';
                            } else {
                                $n1Color = 'bg-slate-700';
                                $n1Label = '';
                            }
                            
                            // Determine colors for N2 bar
                            if ($highestValidated && 'NIVEAU_2' <= $highestValidated) {
                                $n2Color = 'bg-emerald-500/50';
                                $n2Label = 'text-emerald-400';
                            } elseif ('NIVEAU_2' == $latestNiveau && !$isValid) {
                                $n2Color = 'bg-rose-500/50';
                                $n2Label = 'text-rose-400';
                            } else {
                                $n2Color = 'bg-slate-700';
                                $n2Label = '';
                            }
                            
                            // Determine colors for N3 bar
                            if ($highestValidated && 'NIVEAU_3' <= $highestValidated) {
                                $n3Color = 'bg-emerald-500/50';
                                $n3Label = 'text-emerald-400';
                            } elseif ('NIVEAU_3' == $latestNiveau && !$isValid) {
                                $n3Color = 'bg-rose-500/50';
                                $n3Label = 'text-rose-400';
                            } else {
                                $n3Color = 'bg-slate-700';
                                $n3Label = '';
                            }
                        @endphp
                        <div class="bg-slate-900/50 border border-white/5 p-6 rounded-2xl {{ $borderClass }} transition-all group">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-sm font-bold transition-colors">{{ $prog['code'] }} : {{ $prog['label'] }}</h4>
                                <span class="{{ $badgeClass }} px-2 py-1 rounded text-[10px] font-bold uppercase">{{ $latestNiveau }}</span>
                            </div>
                            
                            <!-- Progress Steps -->
                            <div class="flex gap-1 mb-3">
                                <div class="h-2 flex-1 rounded-full {{ $n1Color }}"></div>
                                <div class="h-2 flex-1 rounded-full {{ $n2Color }}"></div>
                                <div class="h-2 flex-1 rounded-full {{ $n3Color }}"></div>
                            </div>
                            
                            <div class="flex justify-between text-[10px] text-slate-500 font-bold uppercase tracking-tighter">
                                <span class="{{ $n1Label }}">N1 - Imiter</span>
                                <span class="{{ $n2Label }}">N2 - Adapter</span>
                                <span class="{{ $n3Label }}">N3 - Transposer</span>
                            </div>
                            
                            <div class="mt-4 pt-4 border-t border-white/5 text-[10px] text-slate-500 uppercase tracking-wider">
                                Validé via : <span class="text-white font-bold">{{ $prog['brief_title'] }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                <!-- Recent Activity -->
                <div class="glass p-8 rounded-3xl lg:col-span-1">
                    <h3 class="text-xl font-bold mb-6">Derniers Retours</h3>
                    <div class="space-y-6">
                        @if(empty($recent))
                            <p class="text-slate-500 text-sm">Aucun retour pour le moment.</p>
                        @endif
                        @foreach($recent as $feed)
                        <div class="relative pl-6 border-l-2 border-indigo-500/30">
                            <div class="absolute -left-[5px] top-0 w-2 h-2 rounded-full bg-indigo-500 shadow-lg shadow-indigo-500/50"></div>
                            <span class="text-[10px] text-slate-500 uppercase font-bold">{{ date('d F Y', strtotime($feed['date'])) }}</span>
                            <h4 class="text-sm font-bold text-white mt-1">{{ $feed['brief_title'] }}</h4>
                            <p class="text-xs text-slate-400 mt-2 line-clamp-3 italic">"{{ $feed['comment'] }}"</p>
                            <div class="flex gap-2 mt-3 flex-wrap">
                                @if(!empty($feed['evaluations']))
                                    @foreach($feed['evaluations'] as $eval)
                                        @php 
                                            $isValide = $eval['status'] === 'VALIDEE';
                                            $colorClass = $isValide ? 'text-emerald-400 border-emerald-500/10' : 'text-rose-400 border-rose-500/10';
                                        @endphp
                                        <span class="text-[9px] bg-slate-800 px-2 py-0.5 rounded {{ $colorClass }} border" title="{{ $eval['status'] }}">
                                            {{ $eval['code'] }}: {{ $eval['niveau'] }}
                                        </span>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-8 pt-8 border-t border-white/10">
                        <h3 class="text-lg font-bold mb-4">En Attente de Correction</h3>
                        @if(empty($pending))
                            <p class="text-slate-500 text-sm">Aucun brief en attente.</p>
                        @else
                            <div class="space-y-3">
                                @foreach($pending as $p)
                                <div class="p-3 bg-slate-900/50 border border-white/5 rounded-xl flex items-center justify-between">
                                    <div>
                                        <h4 class="text-sm font-bold text-white">{{ $p['title'] }}</h4>
                                        <p class="text-[10px] text-slate-500">Soumis le {{ date('d/m', strtotime($p['submitted_at'])) }}</p>
                                    </div>
                                    <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                                </div>
                                @endforeach
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
