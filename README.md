# 🎓 Debrief.me - Système de Débriefing Pédagogique

Une plateforme moderne de gestion de compétences et d'évaluation pour formateurs et apprenants, construite avec **PHP natif**, **PostgreSQL** et **TailwindCSS**.

---

## 📋 Table des Matières
- [Fonctionnalités](#-fonctionnalités)
- [Technologies](#-technologies)
- [Installation](#-installation)
- [Structure du Projet](#-structure-du-projet)
- [Utilisation](#-utilisation)
- [Base de Données](#-base-de-données)

---

## ✨ Fonctionnalités

### 👨‍🎓 Espace Étudiant
- **Dashboard Personnalisé**
  - Visualisation du dernier brief assigné
  - Historique de tous les briefs (avec statut de soumission)
  - Flux d'activité en temps réel (feedback reçus)
- **Gestion des Briefs**
  - Consultation des briefs assignés par la classe
  - Soumission de livrables (URL GitHub, etc.)
  - Support pour **multiples soumissions** (versioning/historique)
  - Indicateurs de deadline et d'état
- **Suivi de Progression**
  - Maillage des compétences validées et non-validées
  - Visualisation par niveaux (N1/N2/N3)
  - Historique des retours enseignants (commentaires détaillés)
  - Liste des briefs en attente de correction
  - **Couleurs distinctes** : Vert (validé) / Rouge (non-acquis)

### 👨‍🏫 Espace Formateur
- **Dashboard de Pilotage**
  - Vue d'ensemble des classes et sprints
  - Suivi des livrables (soumis/en retard)
  - Statistiques en temps réel
- **Création de Briefs**
  - Éditeur de briefs enrichi
  - Association de compétences multiples
  - Paramétrage de dates et type (individuel/groupe)
- **Débriefing Intelligent**
  - Sélection étudiant/brief dynamique
  - Vérification automatique : **Pas de livrable = Pas de débriefing**
  - **Verrouillage après évaluation** : Un brief ne peut être débriefé qu'une seule fois
  - Évaluation granulaire par compétence (N1/N2/N3)
  - Validation ou non-acquisition avec commentaire qualitatif
- **Suivi de Progression**
  - Historique complet par étudiant
  - Visualisation des compétences validées et invalidées par brief
  - Export de données pour analyse
- **Détails des Briefs**
  - Consultation complète (contenu, dates, compétences)
  - Statistiques de rendu en temps réel

### 🛡️ Espace Administrateur
- **Gestion des Utilisateurs**
  - Création d'utilisateurs (Admin/Formateur/Étudiant)
  - Affectation aux classes
- **Configuration Système**
  - Gestion des classes et sprints
  - Création de compétences réutilisables

---

## 🛠️ Technologies

| Couche | Technologie |
|--------|------------|
| **Backend** | PHP 8+ (Natif, sans framework) |
| **Base de Données** | PostgreSQL 14+ |
| **Frontend** | TailwindCSS 3 + Lucide Icons |
| **Template Engine** | BladeOne (Blade-like syntax) |
| **Architecture** | MVC Custom + Repository Pattern |

---

## 🚀 Installation

### Prérequis
- PHP 8.0+
- PostgreSQL 14+
- Serveur Web (Apache/Nginx) ou PHP Built-in Server

### Étapes

1. **Cloner le projet**
```bash
git clone <repository-url>
cd S5_B1_Systeme_de_debriefing
```

2. **Installer les dépendances**
```bash
composer install
```

3. **Configurer la base de données**
   - Créer une base de données PostgreSQL nommée `debriefing`
   - Exécuter le fichier `db.sql` pour créer les tables :
```bash
psql -U postgres -d debriefing -f db.sql
```

4. **Configuration**
   - Copier `.env.example` vers `.env` (si disponible)
   - Ou modifier directement `Core/Database.php` avec vos identifiants PostgreSQL

5. **Lancer le serveur**
```bash
cd Public
php -S localhost:8000
```

6. **Accéder à l'application**
   - Ouvrir `http://localhost:8000` dans votre navigateur

### Compte par défaut (à créer manuellement)
```sql
-- Exemple : Créer un administrateur
INSERT INTO users (email, password, role, first_name, last_name) 
VALUES ('admin@debrief.me', '$2y$10$...', 'ADMIN', 'Admin', 'System');
```

---

## 📁 Structure du Projet

```
S5_B1_Systeme_de_debriefing/
├── Public/              # Point d'entrée (index.php, assets)
├── Core/                # Noyau MVC (Router, Database, Controller)
├── app/
│   ├── Controllers/     # Logique métier (Student, Teacher, Admin, Auth)
│   └── Repositories/    # Accès aux données (StudentRepo, BriefRepo, etc.)
├── Views/
│   ├── layouts/         # Templates de base
│   └── pages/           # Vues par rôle (student, teacher, admin)
├── vendor/              # Dépendances Composer (BladeOne)
└── db.sql               # Schéma de base de données
```

---

## 🎯 Utilisation

### Workflow Complet

1. **Administrateur** : Crée les classes, sprints, compétences, et utilisateurs
2. **Formateur** : 
   - Crée un brief et l'associe à un sprint (donc à une classe)
   - Assigne des compétences au brief
3. **Étudiant** : 
   - Consulte les briefs assignés
   - Soumet un ou plusieurs livrables (URLs)
4. **Formateur** : 
   - Accède au débriefing
   - Sélectionne un étudiant et un brief
   - Le système vérifie automatiquement si un livrable existe
   - Évalue chaque compétence (N1/N2/N3, Validée/Non-acquise)
   - Ajoute un commentaire général
5. **Étudiant** : 
   - Consulte "Mon Parcours"
   - Voit les compétences validées (vert) et non-validées (rouge)
   - Lit les commentaires du formateur dans "Derniers Retours"

---

## 🗄️ Base de Données

### Tables Principales

- **users** : Utilisateurs (Admin, Formateur, Étudiant)
- **classe** : Classes pédagogiques
- **sprint** : Périodes d'apprentissage par classe
- **competence** : Référentiel de compétences
- **brief** : Projets assignés
- **brief_competence** : Association briefs → compétences
- **livrable** : Soumissions des étudiants (**support du versioning**)
- **debriefing** : Sessions d'évaluation (contrainte unique par étudiant/brief)
- **debriefing_competence** : Évaluations détaillées par compétence

### Contraintes Importantes

- `livrable` : **Pas de contrainte unique** → Permet multiples soumissions
- `debriefing` : **Contrainte unique (student_id, brief_id)** → Un seul débriefing par brief/étudiant
- `debriefing_competence` : **Contrainte unique (debriefing_id, competence_code)** → Évite doublons

---

## 🔐 Sécurité

- Middleware d'authentification sur toutes les routes protégées
- Vérification des rôles (RBAC : Admin, Teacher, Student)
- Hashage des mots de passe (bcrypt)
- Protection CSRF (à implémenter si besoin)

---

## 🎨 Design

- Interface moderne avec **glassmorphisme** et **gradients**
- Palette de couleurs cohérente (Indigo, Emerald, Rose)
- Icônes **Lucide** pour une iconographie claire
- Responsive design (Desktop first, adaptable mobile)

---

## 📝 Notes Techniques

### Règles Métier Implémentées
1. ✅ Un formateur ne peut pas débriefing un étudiant sans livrable
2. ✅ Un brief ne peut être débriefé qu'une seule fois par étudiant
3. ✅ Un étudiant peut soumettre plusieurs versions d'un même livrable
4. ✅ Les compétences invalidées sont visuellement distinctes (rouge vs vert)

### Améliorations Futures
- [ ] Export PDF des débriefings
- [ ] Notifications en temps réel
- [ ] Système de badges gamifiés
- [ ] Graphiques de progression avancés

---

## 👥 Contributeurs

- **Projet réalisé dans le cadre de** : Sprint 5 - Développement Web
- **Contact** : [Votre Email]

---

## 📄 License

Ce projet est développé à des fins pédagogiques.
