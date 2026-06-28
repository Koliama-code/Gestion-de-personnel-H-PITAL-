-- Création de la base
CREATE DATABASE IF NOT EXISTS hospital_jmk;
USE hospital_jmk;

-- Table des services
CREATE TABLE services (
    id_service INT PRIMARY KEY AUTO_INCREMENT,
    nom_service VARCHAR(100) NOT NULL UNIQUE
);

-- Table des catégories de personnel
CREATE TABLE categories_personnel (
    id_categorie INT PRIMARY KEY AUTO_INCREMENT,
    nom_categorie VARCHAR(50) NOT NULL UNIQUE,
    description TEXT
);

-- Table des spécialités
CREATE TABLE specialites (
    id_specialite INT PRIMARY KEY AUTO_INCREMENT,
    nom_specialite VARCHAR(100) NOT NULL,
    id_categorie INT,
    FOREIGN KEY (id_categorie) REFERENCES categories_personnel(id_categorie) ON DELETE CASCADE
);

-- Table principale des employés (avec photo)
CREATE TABLE employes (
    id_employe INT PRIMARY KEY AUTO_INCREMENT,
    matricule VARCHAR(20) UNIQUE NOT NULL,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    date_naissance DATE,
    sexe ENUM('M', 'F'),
    email VARCHAR(100) UNIQUE NOT NULL,
    telephone VARCHAR(20),
    adresse TEXT,
    numero_ordre VARCHAR(50) NULL,
    date_embauche DATE NOT NULL,
    poste_occupe VARCHAR(100) NOT NULL,
    id_service INT,
    id_categorie INT,
    id_specialite INT NULL,
    statut_employe ENUM('Actif', 'En Congé', 'En Arrêt Maladie', 'Suspendu', 'Démissionné') DEFAULT 'Actif',
    salaire_base DECIMAL(10,2) DEFAULT 0,
    photo VARCHAR(255) NULL,
    FOREIGN KEY (id_service) REFERENCES services(id_service) ON DELETE SET NULL,
    FOREIGN KEY (id_categorie) REFERENCES categories_personnel(id_categorie) ON DELETE SET NULL,
    FOREIGN KEY (id_specialite) REFERENCES specialites(id_specialite) ON DELETE SET NULL
);

-- Table des utilisateurs (authentification)
CREATE TABLE utilisateurs (
    id_utilisateur INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('admin', 'rh', 'chef_service', 'employe', 'directeur') DEFAULT 'employe',
    id_employe INT NULL,
    FOREIGN KEY (id_employe) REFERENCES employes(id_employe) ON DELETE SET NULL
);

-- ------------------------------------------------------------
-- INSERTION DES DONNÉES DE BASE
-- ------------------------------------------------------------

INSERT INTO services (nom_service) VALUES 
('Direction Générale'),
('Ressources Humaines'),
('Médecine Générale'),
('Urgences'),
('Pédiatrie'),
('Chirurgie'),
('Gynécologie-Obstétrique'),
('Laboratoire'),
('Imagerie Médicale');

INSERT INTO categories_personnel (nom_categorie, description) VALUES 
('Médical', 'Médecins, Chirurgiens, Spécialistes'),
('Paramédical', 'Infirmiers, Sage-femmes, Techniciens'),
('Administratif', 'Secrétaires, Comptables, Gestionnaires'),
('Technique', 'Maintenance, Laborantins, Manipulateurs radio');

INSERT INTO specialites (nom_specialite, id_categorie) VALUES 
('Cardiologie', 1),
('Pédiatrie', 1),
('Chirurgie Générale', 1),
('Gynécologie', 1),
('Médecine Interne', 1),
('Radiologie', 1),
('Soins Intensifs', 2),
('Pédiatrie', 2),
('Bloc Opératoire', 2),
('Santé Publique', 2);

-- Comptes de test (mot de passe : admin123 et rh123)
-- Le hash est généré avec password_hash('admin123', PASSWORD_DEFAULT)
INSERT INTO utilisateurs (username, mot_de_passe, role) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('rh', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'rh');