<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Formateur - Debrief.me</title>
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
                <a href="/teacher/dashboard" class="flex items-center gap-3 p-3 rounded-xl text-white bg-indigo-500 shadow-lg shadow-indigo-500/20">
                    <i data-lucide="layout-dashboard"></i> <span>Dashboard</span>
                </a>
                <a href="/teacher/briefs" class="flex items-center gap-3 p-3 rounded-xl hover:bg-white/5 transition-all text-slate-400">
                    <i data-lucide="file-text"></i> <span>Briefs</span>
                </a>
                <a href="/teacher/debriefing" class="flex items-center gap-3 p-3 rounded-xl hover:bg-white/5 transition-all text-slate-400">
                    <i data-lucide="check-square"></i> <span>Débriefing</span>
                </a>
                <a href="/teacher/progression" class="flex items-center gap-3 p-3 rounded-xl hover:bg-white/5 transition-all text-slate-400">
                    <i data-lucide="trending-up"></i> <span>Progression</span>
                </a>
            </nav>

            <div class="pt-6 border-t border-white/10">
                <a href="/logout" class="flex items-center gap-3 p-3 rounded-xl text-rose-400 hover:bg-rose-500/10 transition-all">
                    <i data-lucide="log-out"></i> <span>Déconnexion</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 ml-64 p-8">
            <header class="flex justify-between items-center mb-10">
                <div>
                    <h1 class="text-3xl font-extrabold">Espace Formateur</h1>
                    <p class="text-slate-400 mt-1">Gérez vos briefs et évaluez vos classes</p>
                </div>
                <div class="flex gap-4">
                    <a href="/teacher/debriefing" class="bg-indigo-500 hover:bg-indigo-600 text-white px-6 py-3 rounded-2xl font-bold flex items-center gap-2 transition-all shadow-lg shadow-indigo-500/20">
                        <i data-lucide="zap" class="w-5 h-5"></i>
                        Quick Debrief
                    </a>
                </div>
            </header>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left: Recent Submissions -->
                <div class="lg:col-span-2 space-y-8">
                    <div class="glass p-8 rounded-[2.5rem]">
                        <h3 class="text-xl font-bold mb-6">Derniers Livrables Reçus</h3>
                        <div class="space-y-4">
                            @foreach($submissions as $sub)
                            <div class="flex items-center justify-between p-4 bg-white/5 rounded-2xl border border-white/5 hover:border-indigo-500/30 transition-all cursor-pointer group">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-slate-800 flex items-center justify-center font-bold text-indigo-400">{{ substr($sub['name'], 0, 1) }}</div>
                                    <div>
                                        <p class="text-sm font-bold">{{ $sub['name'] }}</p>
                                        <p class="text-xs text-slate-500">{{ $sub['brief'] }} • {{ $sub['type'] }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] text-slate-500 uppercase font-bold mb-1">{{ $sub['time'] }}</p>
                                    <button class="text-xs text-indigo-400 font-bold group-hover:underline">Évaluer maintenant</button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="glass p-8 rounded-[2.5rem]">
                        <h3 class="text-xl font-bold mb-6">Mes Classes Actives</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($classes as $cls)
                            <div class="p-6 bg-slate-900 border border-white/5 rounded-2xl">
                                <h4 class="font-bold mb-2">{{ $cls }}</h4>
                                <div class="flex justify-between items-center text-xs text-slate-500">
                                    <span>24 Apprenants</span>
                                    <span class="text-emerald-400 font-bold">88% Progression</span>
                                </div>
                                <div class="w-full h-1 bg-slate-800 rounded-full mt-3 overflow-hidden">
                                    <div class="w-[88%] h-full bg-emerald-500"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Right: Active Briefs -->
                <div class="space-y-8">
                    <div class="glass p-8 rounded-[2.5rem]">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-bold">Briefs en cours</h3>
                            <button class="p-2 hover:bg-white/10 rounded-lg"><i data-lucide="plus" class="w-5 h-5"></i></button>
                        </div>
                        <div class="space-y-4">
                            @foreach($active_briefs as $br)
                            <div class="p-4 bg-indigo-500/5 border border-indigo-500/10 rounded-2xl relative overflow-hidden group">
                                <div class="absolute top-0 right-0 p-2"><span class="w-2 h-2 rounded-full bg-emerald-500 block"></span></div>
                                <h4 class="text-sm font-bold text-indigo-300">{{ $br }}</h4>
                                <p class="text-[10px] text-slate-500 mt-1">Fin dans 3 jours</p>
                                <div class="mt-4 flex justify-between items-center">
                                    <span class="text-[10px] font-bold text-slate-400">18/24 Rendus</span>
                                    <button class="text-[10px] font-bold text-white bg-indigo-500 px-2 py-1 rounded">Détails</button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="glass p-8 rounded-[2.5rem] bg-gradient-to-br from-indigo-500/10 to-transparent">
                        <i data-lucide="help-circle" class="text-indigo-400 mb-4"></i>
                        <h4 class="font-bold mb-2">Besoin d'aide ?</h4>
                        <p class="text-xs text-slate-400 leading-relaxed mb-4">Consultez le guide pédagogique pour apprendre à bien évaluer les compétences transversales.</p>
                        <button class="text-xs font-bold text-indigo-400">Lire le guide →</button>
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
