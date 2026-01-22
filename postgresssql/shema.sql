CREATE DATABASE debriefing;

\c debriefing;

CREATE TYPE role_enum AS ENUM (
    'STUDENT',
    'TEACHER',
    'ADMIN',
    'BACKUP_TEACHER'
);

CREATE TYPE niveau_enum AS ENUM (
    'IMITER',
    'S_ADAPTER',
    'TRANSPOSER'
);

CREATE TYPE brief_type_enum AS ENUM (
    'INDIVIDUAL',
    'COLLECTIVE'
);

CREATE TABLE classe (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role role_enum NOT NULL,
    classe_id INT REFERENCES classe(id) ON DELETE SET NULL,
    CHECK (
      (role = 'ADMIN' AND classe_id IS NULL)
      OR
      (role <> 'ADMIN' AND classe_id IS NOT NULL)
    )
);

CREATE TABLE sprint (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    duration INT NOT NULL,
    "order" INT NOT NULL,
    classe_id INT NOT NULL REFERENCES classe(id) ON DELETE CASCADE
);

CREATE TABLE brief (
    id SERIAL PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    deadline TIMESTAMP NOT NULL,
    type brief_type_enum NOT NULL,
    sprint_id INT NOT NULL REFERENCES sprint(id) ON DELETE CASCADE
);

CREATE TABLE competence (
    code VARCHAR(20) PRIMARY KEY,
    libelle VARCHAR(150) NOT NULL
);

CREATE TABLE brief_competence (
    brief_id INT REFERENCES brief(id) ON DELETE CASCADE,
    competence_code VARCHAR(20) REFERENCES competence(code) ON DELETE CASCADE,
    PRIMARY KEY (brief_id, competence_code)
);

CREATE TABLE evaluation (
    id SERIAL PRIMARY KEY,
    niveau niveau_enum NOT NULL,
    status VARCHAR(20) NOT NULL,
    comment TEXT,
    student_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    teacher_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    brief_id INT NOT NULL REFERENCES brief(id) ON DELETE CASCADE,
    competence_code VARCHAR(20) NOT NULL REFERENCES competence(code) ON DELETE CASCADE,
    UNIQUE (student_id, brief_id, competence_code)
);
