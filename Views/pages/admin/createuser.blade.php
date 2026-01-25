<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Utilisateur - Debrief.me</title>
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

        <main class="flex-1 ml-64 p-8 flex justify-center">
            <div class="w-full max-w-2xl">
                <header class="mb-10 flex items-center gap-4">
                    <a href="{{ $baseUrl }}/admin/users" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-white/10 transition-all">
                        <i data-lucide="chevron-left" class="w-5 h-5"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-extrabold">Nouvel Utilisateur</h1>
                        <p class="text-slate-400 mt-1">Créez un compte pour un apprenant, formateur ou admin</p>
                    </div>
                </header>

                @if(isset($errors))
                    <div class="mb-6 p-4 bg-rose-500/20 border border-rose-500/50 rounded-2xl text-rose-300 text-sm">
                        <ul class="list-disc ml-5">
                            @foreach($errors as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="glass p-8 rounded-[2.5rem] shadow-2xl">
                    <form action="{{ $baseUrl }}/admin/users/store" method="POST" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Prénom</label>
                                <input type="text" name="first_name" required 
                                       placeholder="ex : bouchaib"
                                       value="{{ $old['first_name'] ?? '' }}"
                                       class="w-full bg-slate-900/50 border border-white/10 rounded-2xl px-5 py-3.5 focus:ring-2 focus:ring-indigo-500 outline-none transition-all placeholder:text-slate-600">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Nom</label>
                                <input type="text" name="last_name" required 
                                       placeholder="ex : El Haidi"
                                       value="{{ $old['last_name'] ?? '' }}"
                                       class="w-full bg-slate-900/50 border border-white/10 rounded-2xl px-5 py-3.5 focus:ring-2 focus:ring-indigo-500 outline-none transition-all placeholder:text-slate-600">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Email</label>
                            <input type="email" name="email" required 
                                   placeholder="saad@debrief.me"
                                   value="{{ $old['email'] ?? '' }}"
                                   class="w-full bg-slate-900/50 border border-white/10 rounded-2xl px-5 py-3.5 focus:ring-2 focus:ring-indigo-500 outline-none transition-all placeholder:text-slate-600">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Mot de passe</label>
                            <input type="password" name="password" required 
                                   placeholder="••••••••"
                                   class="w-full bg-slate-900/50 border border-white/10 rounded-2xl px-5 py-3.5 focus:ring-2 focus:ring-indigo-500 outline-none transition-all placeholder:text-slate-600">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Rôle</label>
                            <select id="roleSelect" name="role" required 
                                    class="w-full bg-slate-900/50 border border-white/10 rounded-2xl px-5 py-3.5 focus:ring-2 focus:ring-indigo-500 outline-none transition-all text-slate-300">
                                <option value="STUDENT" {{ (isset($old['role']) && $old['role'] === 'STUDENT') ? 'selected' : '' }}>Apprenant</option>
                                <option value="TEACHER" {{ (isset($old['role']) && $old['role'] === 'TEACHER') ? 'selected' : '' }}>Formateur</option>
                                <option value="ADMIN" {{ (isset($old['role']) && $old['role'] === 'ADMIN') ? 'selected' : '' }}>Administrateur</option>
                            </select>
                        </div>

                        <div id="classField">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1" id="classLabel">Classe</label>
                            
                            <!-- Student Select -->
                            <select name="classe_id" id="studentClassSelect"
                                    class="w-full bg-slate-900/50 border border-white/10 rounded-2xl px-5 py-3.5 focus:ring-2 focus:ring-indigo-500 outline-none transition-all text-slate-300">
                                <option value="">Choisir une classe...</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class['id'] }}" {{ (isset($old['classe_id']) && $old['classe_id'] == $class['id']) ? 'selected' : '' }}>
                                        {{ $class['name'] }} ({{ $class['year'] }})
                                    </option>
                                @endforeach
                            </select>

                            <!-- Teacher (Available Only) Select -->
                            <select name="classe_id" id="teacherClassSelect" class="hidden w-full bg-slate-900/50 border border-white/10 rounded-2xl px-5 py-3.5 focus:ring-2 focus:ring-indigo-500 outline-none transition-all text-slate-300">
                                <option value="">Choisir une classe disponible...</option>
                                @foreach($available_classes as $class)
                                    <option value="{{ $class['id'] }}" {{ (isset($old['classe_id']) && $old['classe_id'] == $class['id']) ? 'selected' : '' }}>
                                        {{ $class['name'] }} ({{ $class['year'] }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div id="backupClassesField" class="hidden">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Classes de renfort (Backup)</label>
                            <select name="backup_classes[]" multiple size="4"
                                    class="w-full bg-slate-900/50 border border-white/10 rounded-2xl px-5 py-3.5 focus:ring-2 focus:ring-indigo-500 outline-none transition-all text-slate-300 scrollbar-thin scrollbar-thumb-indigo-500">
                                @foreach($classes as $class)
                                    <option value="{{ $class['id'] }}">
                                        {{ $class['name'] }} ({{ $class['year'] }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-[10px] text-slate-500 mt-2 ml-1 italic">Maintenez Ctrl (ou Cmd) pour sélectionner plusieurs classes.</p>
                        </div>

                        <div class="pt-6">
                            <button type="submit" class="w-full bg-indigo-500 hover:bg-indigo-600 text-white py-4 rounded-2xl font-bold transition-all shadow-lg shadow-indigo-500/20 flex items-center justify-center gap-2">
                                <i data-lucide="user-plus" class="w-5 h-5"></i>
                                Créer l'utilisateur
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
        lucide.createIcons();

        const roleSelect = document.getElementById('roleSelect');
        const classField = document.getElementById('classField');
        const classLabel = document.getElementById('classLabel');
        const backupField = document.getElementById('backupClassesField');
        const studentSelect = document.getElementById('studentClassSelect');
        const teacherSelect = document.getElementById('teacherClassSelect');

        function updateFields() {
            const role = roleSelect.value;
            if (role === 'STUDENT' || role === 'TEACHER') {
                classField.classList.remove('hidden');
                classLabel.innerText = role === 'TEACHER' ? 'Classe Principale (Disponible)' : 'Classe';
                
                if (role === 'TEACHER') {
                    backupField.classList.remove('hidden');
                    teacherSelect.classList.remove('hidden');
                    teacherSelect.disabled = false;
                    studentSelect.classList.add('hidden');
                    studentSelect.disabled = true;
                    updateBackupOptions(); // Ensure options are correct on load/change
                } else {
                    backupField.classList.add('hidden');
                    studentSelect.classList.remove('hidden');
                    studentSelect.disabled = false;
                    teacherSelect.classList.add('hidden');
                    teacherSelect.disabled = true;
                }
            } else {
                classField.classList.add('hidden');
                backupField.classList.add('hidden');
            }
        }

        function updateBackupOptions() {
            const selectedPrimaryId = teacherSelect.value;
            const backupSelect = document.querySelector('select[name="backup_classes[]"]');
            const options = backupSelect.options;

            for (let i = 0; i < options.length; i++) {
                if (options[i].value === selectedPrimaryId) {
                    options[i].disabled = true;
                    options[i].selected = false; // Deselect if it was selected
                    options[i].style.display = 'none';
                } else {
                    options[i].disabled = false;
                    options[i].style.display = 'block';
                }
            }
        }

        roleSelect.addEventListener('change', updateFields);
        teacherSelect.addEventListener('change', updateBackupOptions);
        window.addEventListener('load', updateFields);
    </script>
</body>
</html>
