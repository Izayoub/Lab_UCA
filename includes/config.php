<?php
// Database configuration
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'lire_rmd_db';

// Try to connect to database
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to get site settings
function get_site_settings($conn) {
    $settings = [];
    $query = "SELECT setting_name, setting_value FROM site_settings";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $settings[$row['setting_name']] = $row['setting_value'];
        }
    }
    
    return $settings;
}

// Get site settings
$site_settings = get_site_settings($conn);

// Site configuration
$site_name = $site_settings['site_name'] ?? "LIREMD";
$site_description = $site_settings['site_description'] ?? "Groupe de Recherche sur les Entreprises Familiales et les Stratégies des Organisations";
$site_university = $site_settings['site_university'] ?? "LABORATOIRE INTERDISCIPLINAIRE DE RECHERCHES ET D’ÉTUDES EN MANAGEMENT DES ORGANISATIONS ET DROIT DE L’ENTREPRISE";
$site_email = $site_settings['site_email'] ?? "contact@grefso.ma";
$site_phone = $site_settings['site_phone'] ?? "+212 5 24 30 48 50";
$hero_image = $site_settings['hero_image'] ?? "https://images.unsplash.com/photo-1454165804606-3c6e0d1d5ab9";

// Navigation menu items
$nav_items = [
    'index.php' => ['title' => 'Accueil', 'icon' => 'fas fa-home'],
    'about.php' => ['title' => 'À propos', 'icon' => 'fas fa-info-circle'],
    'team.php' => ['title' => 'Équipe', 'icon' => 'fas fa-users'],
    'activities.php' => ['title' => 'Activités', 'icon' => 'fas fa-calendar-alt'],
    'publications.php' => ['title' => 'Publications', 'icon' => 'fas fa-book'],
    'contact.php' => ['title' => 'Contact', 'icon' => 'fas fa-envelope']
];

// Function to get page title
function get_page_title($page) {
    global $nav_items;
    
    foreach ($nav_items as $url => $item) {
        $page_name = basename($url, '.php');
        if ($page_name === $page) {
            return $item['title'];
        }
    }
    
    $custom_titles = [
        'admin' => 'Administration',
        'dashboard' => 'Tableau de bord',
        'login' => 'Connexion',
        'jdm' => 'Journées Doctoriales en Management',
        'actualites' => 'Actualités',
        'colloque2010' => 'Colloque 2010'
    ];
    
    return isset($custom_titles[$page]) ? $custom_titles[$page] : 'LIREMD';
}

// Start session
session_start();

// Check if user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Redirect if not logged in
function require_login() {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

// Function to sanitize input data
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to get current page name
function get_current_page() {
    $page = basename($_SERVER['PHP_SELF']);
    return str_replace('.php', '', $page);
}

function get_month_fr_short($month_number) {
    $months = [
        '01' => 'Janv',
        '02' => 'Févr',
        '03' => 'Mars',
        '04' => 'Avr',
        '05' => 'Mai',
        '06' => 'Juin',
        '07' => 'Juil',
        '08' => 'Août',
        '09' => 'Sept',
        '10' => 'Oct',
        '11' => 'Nov',
        '12' => 'Déc'
    ];

    return $months[$month_number] ?? '';
}

?>
