<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivi des Classes - Debrief.me</title>
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
                <a href="/teacher/briefs" class="flex items-center gap-3 p-3 rounded-xl hover:bg-white/5 transition-all text-slate-400">
                    <i data-lucide="file-text"></i> <span>Briefs</span>
                </a>
                <a href="/teacher/debriefing" class="flex items-center gap-3 p-3 rounded-xl hover:bg-white/5 transition-all text-slate-400">
                    <i data-lucide="check-square"></i> <span>Débriefing</span>
                </a>
                <a href="/teacher/progression" class="flex items-center gap-3 p-3 rounded-xl text-white bg-indigo-500 shadow-lg shadow-indigo-500/20">
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
                    <h1 class="text-3xl font-extrabold">Suivi de Progression</h1>
                    <p class="text-slate-400 mt-1">Analysez l'évolution des compétences de vos classes</p>
                </div>
                <div class="flex gap-4">
                    <select class="glass px-4 py-2 rounded-xl text-sm outline-none border border-white/10">
                        <option>Classe: WEB-2024-A</option>
                        <option>Classe: WEB-2024-B</option>
                    </select>
                </div>
            </header>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-10">
                @foreach($stats as $stat)
                <div class="glass p-6 rounded-3xl border-l-4 border-{{ $stat['color'] }}-500">
                    <p class="text-[10px] text-slate-500 uppercase font-bold tracking-widest mb-1">{{ $stat['label'] }}</p>
                    <h2 class="text-3xl font-extrabold text-white">{{ $stat['value'] }}</h2>
                </div>
                @endforeach
            </div>

            <div class="glass rounded-[2.5rem] p-8">
                <h3 class="text-xl font-bold mb-8">Performance par Apprenant</h3>
                <div class="space-y-6">
                    @foreach($students as $std)
                    <div class="flex items-center gap-6">
                        <div class="w-1/4">
                            <p class="text-sm font-bold">{{ $std }}</p>
                            <p class="text-[10px] text-slate-500 uppercase">Dernier Brief: PixelQuest</p>
                        </div>
                        <div class="flex-1 flex gap-2">
                            @for($i=0; $i<10; $i++)
                            <div class="h-8 flex-1 rounded-md {{ $i < 7 ? 'bg-emerald-500/40 border border-emerald-500/20' : ($i < 8 ? 'bg-indigo-500/40 border border-indigo-500/20' : 'bg-slate-800') }} relative group">
                                <div class="absolute -top-10 left-1/2 -translate-x-1/2 bg-slate-900 border border-white/10 px-2 py-1 rounded text-[8px] opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10">C{{$i+1}}: Validé L{{rand(1,3)}}</div>
                            </div>
                            @endfor
                        </div>
                        <div class="w-16 text-right">
                            <span class="text-xs font-bold text-slate-300">70%</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </main>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
