-- Création de la base de données
CREATE DATABASE IF NOT EXISTS lire_rmd_db;
USE lire_rmd_db;

-- Table des utilisateurs (admin uniquement)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertion de l'utilisateur admin par défaut (mot de passe: admin123)
INSERT INTO users (email, password, name)
SELECT 'admin@admin.com', 'admin123', 'Administrateur'
WHERE NOT EXISTS (
    SELECT 1 FROM users WHERE email = 'admin@admin.com'
);


-- Table pour le contenu de la page d'accueil
CREATE TABLE IF NOT EXISTS home_content (
    id INT AUTO_INCREMENT PRIMARY KEY,
    section_name VARCHAR(50) NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


-- Table pour les événements
CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    event_date DATE NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);



-- Table pour les actualités
CREATE TABLE IF NOT EXISTS news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    news_date DATE NOT NULL,
    is_new BOOLEAN DEFAULT TRUE,
    link VARCHAR(255),
    link_text VARCHAR(100),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table pour les appels à communication
CREATE TABLE IF NOT EXISTS calls (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    is_new BOOLEAN DEFAULT TRUE,
    link VARCHAR(255),
    link_text VARCHAR(100),
    icon VARCHAR(50) DEFAULT 'fas fa-download',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table pour les liens utiles
CREATE TABLE IF NOT EXISTS useful_links (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    url VARCHAR(255) NOT NULL,
    icon VARCHAR(50) DEFAULT 'fas fa-link',
    is_active BOOLEAN DEFAULT TRUE,
    display_order INT DEFAULT 0
);


-- Table pour les paramètres du site
CREATE TABLE IF NOT EXISTS site_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_name VARCHAR(50) NOT NULL UNIQUE,
    setting_value TEXT NOT NULL,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table pour l'équipe
CREATE TABLE IF NOT EXISTS team_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    title VARCHAR(100) NOT NULL,
    bio TEXT,
    photo VARCHAR(255) DEFAULT 'assets/img/default-profile.jpg',
    email VARCHAR(100),
    is_active BOOLEAN DEFAULT TRUE,
    display_order INT DEFAULT 0
);

-- Table pour les publications
CREATE TABLE IF NOT EXISTS publications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    authors VARCHAR(255) NOT NULL,
    publication_date DATE NOT NULL,
    journal VARCHAR(255),
    abstract TEXT,
    link VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- Table pour les contacts
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table pour les pages de contenu
CREATE TABLE IF NOT EXISTS page_content (
    id INT AUTO_INCREMENT PRIMARY KEY,
    page_name VARCHAR(50) NOT NULL,
    section_name VARCHAR(50) NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


-- Table pour les médias
CREATE TABLE IF NOT EXISTS media (
    id INT AUTO_INCREMENT PRIMARY KEY,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_type VARCHAR(50) NOT NULL,
    file_size INT NOT NULL,
    title VARCHAR(255),
    description TEXT,
    uploaded_by INT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Table pour les catégories de publications
CREATE TABLE IF NOT EXISTS publication_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    display_order INT DEFAULT 0
);



-- Table pour associer les publications aux catégories
CREATE TABLE IF NOT EXISTS publication_category_relations (
    publication_id INT NOT NULL,
    category_id INT NOT NULL,
    PRIMARY KEY (publication_id, category_id),
    FOREIGN KEY (publication_id) REFERENCES publications(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES publication_categories(id) ON DELETE CASCADE
);

-- Table pour les partenaires
CREATE TABLE IF NOT EXISTS partners (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    logo VARCHAR(255),
    website VARCHAR(255),
    description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    display_order INT DEFAULT 0
);

-- Table pour les témoignages
CREATE TABLE IF NOT EXISTS testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    position VARCHAR(100),
    organization VARCHAR(100),
    content TEXT NOT NULL,
    photo VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    display_order INT DEFAULT 0
);

-- Table pour les newsletters
CREATE TABLE IF NOT EXISTS newsletters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    name VARCHAR(100),
    is_active BOOLEAN DEFAULT TRUE,
    subscription_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table pour les statistiques du site
CREATE TABLE IF NOT EXISTS site_statistics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    page_views INT DEFAULT 0,
    unique_visitors INT DEFAULT 0,
    total_publications INT DEFAULT 0,
    total_events INT DEFAULT 0,
    total_members INT DEFAULT 0,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);



-- Tables pour la page À propos (About)
CREATE TABLE IF NOT EXISTS about_sections (
    id INT AUTO_INCREMENT PRIMARY KEY,
    section_key VARCHAR(50) NOT NULL UNIQUE,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    icon VARCHAR(50) DEFAULT 'fas fa-info-circle',
    display_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table pour les valeurs de l'entreprise (partie de la page à propos)
CREATE TABLE IF NOT EXISTS about_values (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    icon VARCHAR(50) DEFAULT 'fas fa-star',
    color VARCHAR(20) DEFAULT 'primary-600',
    display_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table pour les axes de recherche
CREATE TABLE IF NOT EXISTS research_areas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    detailed_description TEXT,
    icon VARCHAR(50) DEFAULT 'fas fa-search',
    color VARCHAR(20) DEFAULT 'primary-600',
    research_points JSON, -- Pour stocker les points de recherche
    display_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table mise à jour pour l'équipe (déjà existante mais on ajoute des champs)
ALTER TABLE team_members 
ADD COLUMN IF NOT EXISTS category ENUM('direction', 'researcher', 'phd_student') DEFAULT 'researcher',
ADD COLUMN IF NOT EXISTS specialization VARCHAR(255),
ADD COLUMN IF NOT EXISTS linkedin VARCHAR(255),
ADD COLUMN IF NOT EXISTS orcid VARCHAR(100),
ADD COLUMN IF NOT EXISTS research_interests TEXT,
ADD COLUMN IF NOT EXISTS phone VARCHAR(20);

-- Table pour les types d'activités
CREATE TABLE IF NOT EXISTS activity_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    icon VARCHAR(50) DEFAULT 'fas fa-calendar',
    color VARCHAR(20) DEFAULT 'primary-600',
    is_active BOOLEAN DEFAULT TRUE,
    display_order INT DEFAULT 0
);

-- Table pour les activités
CREATE TABLE IF NOT EXISTS activities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    content TEXT, -- Contenu détaillé
    start_date DATE,
    end_date DATE,
    location VARCHAR(255),
    organizer VARCHAR(255),
    target_audience VARCHAR(255),
    registration_required BOOLEAN DEFAULT FALSE,
    registration_link VARCHAR(255),
    external_link VARCHAR(255),
    image VARCHAR(255),
    status ENUM('upcoming', 'ongoing', 'completed', 'cancelled') DEFAULT 'upcoming',
    is_featured BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (type_id) REFERENCES activity_types(id) ON DELETE CASCADE
);

-- Table pour les partenariats liés aux activités
CREATE TABLE IF NOT EXISTS activity_partnerships (
    id INT AUTO_INCREMENT PRIMARY KEY,
    activity_id INT NOT NULL,
    partner_id INT NOT NULL,
    partnership_type ENUM('organizer', 'sponsor', 'supporter', 'participant') DEFAULT 'supporter',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (activity_id) REFERENCES activities(id) ON DELETE CASCADE,
    FOREIGN KEY (partner_id) REFERENCES partners(id) ON DELETE CASCADE
);



