<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Débriefing Pédagogique - Debrief.me</title>
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
                <a href="#" class="flex items-center gap-3 p-3 rounded-xl hover:bg-indigo-500 transition-all text-slate-400 hover:text-white">
                    <i data-lucide="layout-dashboard"></i> <span>Dashboard</span>
                </a>
                <a href="#" class="flex items-center gap-3 p-3 rounded-xl hover:bg-indigo-500 transition-all text-white bg-indigo-500 shadow-lg shadow-indigo-500/20">
                    <i data-lucide="check-square"></i> <span>Débriefing</span>
                </a>
                <a href="#" class="flex items-center gap-3 p-3 rounded-xl hover:bg-indigo-500 transition-all text-slate-400 hover:text-white">
                    <i data-lucide="file-text"></i> <span>Briefs</span>
                </a>
            </nav>

            <div class="pt-6 border-t border-white/10">
                <a href="#" class="flex items-center gap-3 p-3 rounded-xl text-rose-400 hover:bg-rose-500/10 transition-all">
                    <i data-lucide="log-out"></i> <span>Déconnexion</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 ml-64 p-8">
            <header class="flex justify-between items-center mb-10">
                <div>
                    <h1 class="text-3xl font-extrabold">Débriefing Pédagogique</h1>
                    <p class="text-slate-400 mt-1">Évaluez les compétences par apprenant</p>
                </div>
                <div class="glass px-4 py-2 rounded-2xl flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-sm font-bold">{{ $user_name ?? 'Instructor Ahmed' }}</p>
                        <p class="text-[10px] text-slate-400 uppercase tracking-wider">Teacher</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center font-bold">A</div>
                </div>
            </header>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Selection Column -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="glass p-6 rounded-3xl">
                        <h3 class="text-lg font-bold mb-4">Sélection</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="text-xs text-slate-400 mb-2 block uppercase tracking-widest">Le Brief</label>
                                <select class="w-full bg-slate-900/50 border border-white/10 rounded-xl p-3 outline-none focus:border-indigo-500 transition-all">
                                    <option>PixelQuest - Intégration HTML/CSS</option>
                                    <option>DOM Gamification</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-xs text-slate-400 mb-2 block uppercase tracking-widest">L'Apprenant</label>
                                <select class="w-full bg-slate-900/50 border border-white/10 rounded-xl p-3 outline-none focus:border-indigo-500 transition-all">
                                    <option>Saad El Haidi</option>
                                    <option>Imane Bennani</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-white/10 flex items-center justify-between">
                            <span class="text-sm text-slate-400">Statut Livrable</span>
                            <span class="px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-400 text-xs font-bold uppercase">Soumis ✓</span>
                        </div>
                    </div>
                </div>

                <!-- Evaluation Column -->
                <div class="lg:col-span-2 glass p-8 rounded-3xl">
                    <div class="flex justify-between items-center mb-8">
                        <h3 class="text-xl font-bold">Évaluation des compétences</h3>
                        <span class="text-sm text-slate-400">Brief: <span class="text-white font-bold">PixelQuest</span></span>
                    </div>

                    <div class="space-y-10">
                        <!-- Compétence Item 1 -->
                        <div class="pb-8 border-b border-white/10">
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <h4 class="text-indigo-400 font-bold text-lg">C1: Planifier le travail</h4>
                                    <p class="text-sm text-slate-400">Organiser les tâches et gérer le temps de réalisation.</p>
                                </div>
                                <select class="bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 rounded-lg px-3 py-1 text-xs font-bold uppercase">
                                    <option>Validée</option>
                                    <option>Invalide</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-3 gap-4">
                                <!-- Level 1 (Validated in Previous) -->
                                <div class="bg-emerald-500/10 border-2 border-emerald-500/30 p-4 rounded-2xl flex flex-col items-center opacity-60">
                                    <i data-lucide="check-circle-2" class="text-emerald-400 w-5 h-5 mb-2"></i>
                                    <span class="text-[10px] text-slate-400 uppercase font-bold">Niveau 1</span>
                                    <span class="text-xs font-bold">Imiter</span>
                                </div>
                                <!-- Level 2 (Selected Now) -->
                                <div class="bg-indigo-500/20 border-2 border-indigo-500 p-4 rounded-2xl flex flex-col items-center ring-4 ring-indigo-500/10 relative">
                                    <div class="absolute -top-2 -right-2 bg-indigo-500 w-5 h-5 rounded-full flex items-center justify-center">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                    </div>
                                    <span class="text-[10px] text-indigo-300 uppercase font-bold mt-1">Niveau 2</span>
                                    <span class="text-xs font-bold">S'adapter</span>
                                </div>
                                <!-- Level 3 -->
                                <div class="bg-slate-900 border-2 border-white/5 p-4 rounded-2xl flex flex-col items-center grayscale hover:grayscale-0 hover:bg-slate-800 transition-all cursor-pointer">
                                    <span class="text-[10px] text-slate-400 uppercase font-bold">Niveau 3</span>
                                    <span class="text-xs font-bold">Transposer</span>
                                </div>
                            </div>
                        </div>

                        <!-- Commentaire -->
                        <div class="space-y-4">
                            <label class="text-xs text-slate-400 uppercase tracking-widest block">Feedback Constructif</label>
                            <textarea class="w-full bg-slate-900/50 border border-white/10 rounded-2xl p-4 outline-none focus:border-indigo-500 transition-all h-32" placeholder="Ex: Excellent travail sur la planification, attention aux délais sur la phase de tests..."></textarea>
                        </div>

                        <div class="flex justify-end gap-4">
                            <button class="px-6 py-3 rounded-xl border border-white/10 text-slate-400 hover:bg-white/5 transition-all font-bold">Annuler</button>
                            <button class="px-8 py-3 rounded-xl bg-indigo-500 hover:bg-indigo-600 shadow-lg shadow-indigo-500/20 transition-all font-bold">Enregistrer le débrief</button>
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
