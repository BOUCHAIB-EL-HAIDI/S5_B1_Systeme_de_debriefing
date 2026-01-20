<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - Debrief.me</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#0f172a] text-slate-100 min-h-screen flex flex-col items-center justify-center p-6 bg-[radial-gradient(at_0%_0%,rgba(99,102,241,0.15)_0px,transparent_50%),radial-gradient(at_100%_0%,rgba(16,185,129,0.1)_00px,transparent_50%)]">

    <div class="text-center max-w-2xl px-4">
        <div class="w-20 h-20 bg-indigo-500 rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-2xl shadow-indigo-500/20 animate-bounce">
            <i data-lucide="graduation-cap" class="text-white w-12 h-12"></i>
        </div>
        
        <h1 class="text-5xl font-black tracking-tight mb-6">Plateforme de Debriefing</h1>
        <p class="text-slate-400 text-lg mb-10 leading-relaxed">Simplifiez l'évaluation pédagogique, suivez la progression des compétences en temps réel et offrez des feedbacks constructifs à vos apprenants.</p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="/login" class="bg-indigo-500 hover:bg-indigo-600 text-white px-10 py-4 rounded-2xl font-black text-lg transition-all shadow-xl shadow-indigo-500/20 hover:scale-105 active:scale-95">
                Accéder à l'Espace
            </a>
            <a href="#" class="bg-slate-800 hover:bg-slate-700 text-white px-10 py-4 rounded-2xl font-black text-lg transition-all hover:scale-105 active:scale-95">
                En savoir plus
            </a>
        </div>
    </div>

    <footer class="mt-20 text-slate-500 text-xs font-bold uppercase tracking-widest">
        &copy; 2024 Debrief.me • Excellence Pédagogique
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
