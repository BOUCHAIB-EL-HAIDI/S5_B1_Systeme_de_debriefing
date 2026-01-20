-- ============================================
-- DATABASE CREATION
-- ============================================
-- Note: If running this manually, ensure the database 'debriefing' exists or is created first.
-- DROP DATABASE IF EXISTS debriefing;
-- CREATE DATABASE debriefing;
-- \c debriefing;

-- ============================================
-- ENUMS
-- ============================================
DO $$ BEGIN
    CREATE TYPE role_enum AS ENUM ('STUDENT', 'TEACHER', 'ADMIN');
EXCEPTION
    WHEN duplicate_object THEN null;
END $$;

DO $$ BEGIN
    CREATE TYPE niveau_enum AS ENUM ('NIVEAU_1', 'NIVEAU_2', 'NIVEAU_3');
EXCEPTION
    WHEN duplicate_object THEN null;
END $$;

DO $$ BEGIN
    CREATE TYPE brief_type_enum AS ENUM ('INDIVIDUAL', 'COLLECTIVE');
EXCEPTION
    WHEN duplicate_object THEN null;
END $$;

DO $$ BEGIN
    CREATE TYPE evaluation_status_enum AS ENUM ('VALIDEE', 'INVALIDE');
EXCEPTION
    WHEN duplicate_object THEN null;
END $$;

-- ============================================
-- TABLE: classe
-- ============================================
CREATE TABLE IF NOT EXISTS classe (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    year INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- TABLE: users
-- ============================================
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role role_enum NOT NULL,
    classe_id INT REFERENCES classe(id) ON DELETE SET NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT chk_role_classe CHECK (
        (role = 'ADMIN' AND classe_id IS NULL)
        OR
        (role = 'STUDENT' AND classe_id IS NOT NULL)
        OR
        (role = 'TEACHER' AND classe_id IS NULL)
    )
);

-- ============================================
-- TABLE: teacher_classe (Many-to-Many)
-- ============================================
CREATE TABLE IF NOT EXISTS teacher_classe (
    teacher_id INT REFERENCES users(id) ON DELETE CASCADE,
    classe_id INT REFERENCES classe(id) ON DELETE CASCADE,
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (teacher_id, classe_id)
);

-- ============================================
-- TABLE: sprint
-- ============================================
CREATE TABLE IF NOT EXISTS sprint (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    duration INT NOT NULL,
    "order" INT NOT NULL,
    classe_id INT NOT NULL REFERENCES classe(id) ON DELETE CASCADE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- TABLE: brief
-- ============================================
CREATE TABLE IF NOT EXISTS brief (
    id SERIAL PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    content TEXT,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    type brief_type_enum NOT NULL,
    is_assigned BOOLEAN DEFAULT FALSE,
    sprint_id INT NOT NULL REFERENCES sprint(id) ON DELETE CASCADE,
    teacher_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT chk_dates CHECK (end_date >= start_date)
);

-- ============================================
-- TABLE: competence
-- ============================================
CREATE TABLE IF NOT EXISTS competence (
    code VARCHAR(20) PRIMARY KEY,
    label VARCHAR(150) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- TABLE: brief_competence (Many-to-Many)
-- ============================================
CREATE TABLE IF NOT EXISTS brief_competence (
    brief_id INT REFERENCES brief(id) ON DELETE CASCADE,
    competence_code VARCHAR(20) REFERENCES competence(code) ON DELETE CASCADE,
    PRIMARY KEY (brief_id, competence_code)
);

-- ============================================
-- TABLE: livrable
-- ============================================
CREATE TABLE IF NOT EXISTS livrable (
    id SERIAL PRIMARY KEY,
    content TEXT NOT NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    student_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    brief_id INT NOT NULL REFERENCES brief(id) ON DELETE CASCADE,
    UNIQUE (student_id, brief_id)
);

-- ============================================
-- TABLE: debriefing
-- ============================================
CREATE TABLE IF NOT EXISTS debriefing (
    id SERIAL PRIMARY KEY,
    comment TEXT,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    student_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    teacher_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    brief_id INT NOT NULL REFERENCES brief(id) ON DELETE CASCADE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE (student_id, brief_id)
);

-- ============================================
-- TABLE: debriefing_competence (Evaluation)
-- ============================================
CREATE TABLE IF NOT EXISTS debriefing_competence (
    debriefing_id INT REFERENCES debriefing(id) ON DELETE CASCADE,
    competence_code VARCHAR(20) REFERENCES competence(code) ON DELETE CASCADE,
    niveau niveau_enum NOT NULL,
    status evaluation_status_enum NOT NULL,
    PRIMARY KEY (debriefing_id, competence_code)
);
