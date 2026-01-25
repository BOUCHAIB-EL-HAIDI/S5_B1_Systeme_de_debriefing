<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Utilisateurs - Debrief.me</title>
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
                <a href="{{ $baseUrl }}/admin/dashboard" class="flex items-center gap-3 p-3 rounded-xl hover:bg-white/5 transition-all text-slate-400">
                    <i data-lucide="layout-dashboard"></i> <span>Dashboard</span>
                </a>
                <a href="{{ $baseUrl }}/admin/users" class="flex items-center gap-3 p-3 rounded-xl text-white bg-indigo-500 shadow-lg shadow-indigo-500/20">
                    <i data-lucide="users"></i> <span>Utilisateurs</span>
                </a>
                <a href="{{ $baseUrl }}/admin/classes" class="flex items-center gap-3 p-3 rounded-xl hover:bg-white/5 transition-all text-slate-400">
                    <i data-lucide="book-open"></i> <span>Classes</span>
                </a>
                <a href="{{ $baseUrl }}/admin/competences" class="flex items-center gap-3 p-3 rounded-xl hover:bg-white/5 transition-all text-slate-400">
                    <i data-lucide="target"></i> <span>Compétences</span>
                </a>
                <a href="{{ $baseUrl }}/admin/sprints" class="flex items-center gap-3 p-3 rounded-xl hover:bg-white/5 transition-all text-slate-400">
                    <i data-lucide="layers"></i> <span>Sprints</span>
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
                    <h1 class="text-3xl font-extrabold">Gestion des Utilisateurs</h1>
                    <p class="text-slate-400 mt-1">Créez et gérez les comptes apprenants et formateurs</p>
                </div>
                <a href="{{ $baseUrl }}/admin/users/createuser" class="bg-indigo-500 hover:bg-indigo-600 text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 transition-all shadow-lg shadow-indigo-500/20 text-sm">
                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                    Nouvel Utilisateur
                </a>
            </header>

            <!-- Filters -->
            <div class="glass p-6 rounded-3xl mb-8 flex flex-wrap gap-4 items-center">
                <div class="flex-1 min-w-[200px] relative">
                    <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500"></i>
                    <input type="text" placeholder="Rechercher par nom ou email..." class="w-full bg-slate-900/50 border border-white/10 rounded-xl py-2 pl-12 pr-4 outline-none focus:border-indigo-500 transition-all text-sm">
                </div>
                <select class="bg-slate-900/50 border border-white/10 rounded-xl px-4 py-2 text-sm outline-none">
                    <option>Tous les rôles</option>
                    <option>Apprenants</option>
                    <option>Formateurs</option>
                </select>
                <select class="bg-slate-900/50 border border-white/10 rounded-xl px-4 py-2 text-sm outline-none">
                    <option>Toutes les classes</option>
                    <option>WEB-2024-A</option>
                    <option>WEB-2024-B</option>
                </select>
            </div>

            <!-- Users Table -->
            <div class="glass rounded-3xl overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-white/5 border-b border-white/10">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-400">Utilisateur</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-400">Rôle</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-400">Classe</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-400">Statut</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-400 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($users as $user)
                        <tr class="hover:bg-white/5 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center font-bold">
                                        {{ substr($user['first_name'], 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold">{{ $user['first_name'] }} {{ $user['last_name'] }}</p>
                                        <p class="text-xs text-slate-500">{{ $user['email'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-md text-[10px] font-bold uppercase {{ $user['role'] === 'TEACHER' ? 'bg-indigo-500/20 text-indigo-400' : ($user['role'] === 'ADMIN' ? 'bg-rose-500/20 text-rose-400' : 'bg-emerald-500/20 text-emerald-400') }}">
                                    {{ $user['role'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-400">
                                {{ $user['class_name'] ?? '—' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="flex items-center gap-2 text-xs text-emerald-400">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-lg shadow-emerald-500/50"></span>
                                    Actif
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button class="p-2 hover:bg-indigo-500/20 text-indigo-400 rounded-lg transition-colors"><i data-lucide="edit-3" class="w-4 h-4"></i></button>
                                    <button class="p-2 hover:bg-rose-500/20 text-rose-400 rounded-lg transition-colors"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
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
