<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Briefs - Debrief.me</title>
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
                <a href="/teacher/dashboard" class="flex items-center gap-3 p-3 rounded-xl hover:bg-white/5 transition-all text-slate-400">
                    <i data-lucide="layout-dashboard"></i> <span>Dashboard</span>
                </a>
                <a href="/teacher/briefs" class="flex items-center gap-3 p-3 rounded-xl text-white bg-indigo-500 shadow-lg shadow-indigo-500/20">
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
                    <h1 class="text-3xl font-extrabold">Gestion des Briefs</h1>
                    <p class="text-slate-400 mt-1">Créez des projets et assignez des compétences</p>
                </div>
                <button class="bg-indigo-500 hover:bg-indigo-600 text-white px-6 py-3 rounded-2xl font-bold flex items-center gap-2 transition-all shadow-lg shadow-indigo-500/20">
                    <i data-lucide="plus" class="w-5 h-5"></i>
                    Nouveau Brief
                </button>
            </header>

            <!-- Briefs Table -->
            <div class="glass rounded-3xl overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-white/5 border-b border-white/10">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-400">Titre du Brief</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-400">Type</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-400">Compétences</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-400">Statut</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-400 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($briefs as $brief)
                        <tr class="hover:bg-white/5 transition-colors group">
                            <td class="px-6 py-4 font-bold text-sm">{{ $brief['title'] }}</td>
                            <td class="px-6 py-4 text-xs">
                                <span class="bg-slate-800 px-2 py-1 rounded text-slate-400">{{ $brief['type'] }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-1 flex-wrap">
                                    @foreach($brief['comps'] as $c)
                                    <span class="text-[9px] bg-indigo-500/10 text-indigo-400 px-1.5 py-0.5 rounded border border-indigo-500/20">{{ $c }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-md text-[10px] font-bold uppercase {{ $brief['status'] === 'Assigné' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-slate-500/10 text-slate-400' }}">
                                    {{ $brief['status'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button class="p-2 hover:bg-white/10 rounded-lg"><i data-lucide="eye" class="w-4 h-4 text-slate-400"></i></button>
                                    <button class="p-2 hover:bg-indigo-500/20 text-indigo-400 rounded-lg transition-colors"><i data-lucide="edit-3" class="w-4 h-4"></i></button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
