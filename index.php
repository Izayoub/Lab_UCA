<?php
// Include configuration file
require_once 'includes/config.php';

// Set page-specific variables
$page_title = "Accueil"; // Ne pas utiliser get_page_title() s'il pose problème
$additional_css = ["style.css"];
$additional_js = ["main.js"];
$show_hero = true; // Active la section héro en haut de page

// Include header
include_once 'includes/header.php';

// Include left sidebar
include_once 'includes/sidebar-left.php';
?>

<!-- Hero Section si activée -->
<?php if ($show_hero): ?>
<div class="hero-section bg-primary-600 text-white py-20" style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('assets/images/hero-bg.jpg') no-repeat center center; background-size: cover;">
    <div class="container mx-auto text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">LIRE-RMD</h1>
        <p class="text-xl md:text-2xl mb-8">Groupe de Recherche sur les Entreprises Familiales et les Stratégies des Organisations</p>
        <div class="flex justify-center space-x-4">
            <a href="#" class="bg-white text-primary-600 hover:bg-gray-100 px-6 py-3 rounded-lg font-medium transition">Découvrir</a>
            <a href="contact.php" class="bg-transparent border-2 border-white hover:bg-white hover:text-primary-600 text-white px-6 py-3 rounded-lg font-medium transition">Contact</a>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Main Content Area -->
<main class="lg:w-2/4">
    <article class="bg-white p-6 rounded-lg shadow-md mb-8" data-aos="fade-up">
        <h2 class="text-2xl font-bold mb-4 pb-2 border-b border-gray-200 flex items-center">
            <i class="fas fa-quote-left mr-3 text-primary-600"></i> Mot du Directeur
        </h2>
        
        <div class="prose max-w-none text-gray-700">
            <?php
            // Récupérer le contenu de la section "Mot du Directeur"
            $director_content = null;
            if (function_exists('mysqli_query') && isset($conn)) {
                $director_query = "SELECT * FROM home_content WHERE section_name = 'director_word' AND is_active = 1 LIMIT 1";
                $director_result = mysqli_query($conn, $director_query);
                
                if ($director_result && mysqli_num_rows($director_result) > 0) {
                    $director_content = mysqli_fetch_assoc($director_result);
                }
            }
            
            if ($director_content && isset($director_content['content'])) {
                echo $director_content['content'];
            } else {
                // Texte par défaut si aucun contenu n'est trouvé
                ?>
                <p>Le Groupe de Recherche sur les Entreprises Familiales et les Stratégies des Organisations (GREFSO) a été créé en 2007. Il constitue l'une des structures de recherche en gestion au sein de la Faculté des Sciences Juridiques, Economiques et Sociales de l'Université Cadi Ayyad.</p>
                
                <p>Le GREFSO se compose d'enseignants-chercheurs et de doctorants des disciplines du management. Il a pour vocation de :</p>
                
                <ul>
                    <li>Développer des approches méthodologiques et des cadres conceptuels pour l'étude des comportements stratégiques des entreprises marocaines, notamment PME et PMI, de type familiales, en intégrant l'ensemble des paramètres internes et externes aux organisations.</li>
                    <li>Stimuler le développement de travaux empiriques sous forme de colloques, séminaires, recherches qualitatives, quantitatives ou de monographies sur les pratiques stratégiques des entreprises marocaines en général et celles des PME-PMI et des entreprises familiales en particulier.</li>
                    <li>Contribuer au développement des recherches empiriques et conceptuelles sur le management des entreprises marocaines en général et celles de la région du Haouz en particulier en mettant en place une base de données sur les pratiques stratégiques de ces firmes.</li>
                </ul>
                <?php
            }
            ?>
        </div>
        
        <div class="mt-6">
            <a href="about.php" class="inline-flex items-center bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 rounded-lg font-medium transition duration-300 shadow-sm">
                <i class="fas fa-arrow-right mr-2"></i> En savoir plus
            </a>
        </div>
    </article>

    <article class="bg-white p-6 rounded-lg shadow-md" data-aos="fade-up" data-aos-delay="100">
        <h2 class="text-2xl font-bold mb-4 pb-2 border-b border-gray-200 flex items-center">
            <i class="fas fa-newspaper mr-3 text-primary-600"></i> Actualités du GREFSO
        </h2>
        
        <div class="space-y-6">
            <?php
            // Récupérer les actualités depuis la base de données
            $news_result = null;
            if (function_exists('mysqli_query') && isset($conn)) {
                $news_query = "SELECT * FROM news WHERE is_active = 1 ORDER BY news_date DESC LIMIT 3";
                $news_result = mysqli_query($conn, $news_query);
            }
            
            if ($news_result && mysqli_num_rows($news_result) > 0) {
                while ($news = mysqli_fetch_assoc($news_result)) {
                    $news_date = new DateTime($news['news_date']);
                    ?>
                    <div class="border-b border-gray-200 pb-6 last:border-0">
                        <?php if (isset($news['is_new']) && $news['is_new']): ?>
                            <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full mb-2 inline-block">NOUVEAU</span>
                        <?php endif; ?>
                        <h3 class="text-xl font-semibold mb-1"><?php echo $news['title']; ?></h3>
                        <p class="text-sm text-gray-500 mb-3">
                            <i class="far fa-calendar-alt mr-2"></i> 
                            <?php 
                            if (function_exists('format_date_fr')) {
                                echo format_date_fr($news['news_date']); 
                            } else {
                                echo date('d/m/Y', strtotime($news['news_date']));
                            }
                            ?>
                        </p>
                        <p class="text-gray-700 mb-3"><?php echo $news['content']; ?></p>
                        <?php if (!empty($news['link'])): ?>
                            <a href="<?php echo $news['link']; ?>" class="text-primary-600 hover:underline font-medium inline-flex items-center">
                                <i class="fas fa-info-circle mr-1"></i> <?php echo isset($news['link_text']) ? $news['link_text'] : 'En savoir plus'; ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    <?php
                }
            } else {
                // Afficher des actualités par défaut si aucune n'est trouvée dans la base de données
                ?>
                <div class="border-b border-gray-200 pb-6">
                    <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full mb-2 inline-block">NOUVEAU</span>
                    <h3 class="text-xl font-semibold mb-1">Atelier méthodologique sous-régional</h3>
                    <p class="text-sm text-gray-500 mb-3">
                        <i class="far fa-calendar-alt mr-2"></i> 15 octobre 2023
                    </p>
                    <p class="text-gray-700 mb-3">Sciences sociales en Afrique, session 2023 pour l'Afrique du Nord. Appel à candidatures ouvert jusqu'au 30 octobre.</p>
                    <a href="#" class="text-primary-600 hover:underline font-medium inline-flex items-center">
                        <i class="fas fa-info-circle mr-1"></i> Voir l'appel à candidatures
                    </a>
                </div>
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-xl font-semibold mb-1">Colloque international sur les PME</h3>
                    <p class="text-sm text-gray-500 mb-3">
                        <i class="far fa-calendar-alt mr-2"></i> 5 septembre 2023
                    </p>
                    <p class="text-gray-700 mb-3">Le GREFSO organise son colloque bisannuel sur les problématiques des PME marocaines et méditerranéennes les 15-16 décembre 2023.</p>
                    <a href="#" class="text-primary-600 hover:underline font-medium inline-flex items-center">
                        <i class="fas fa-info-circle mr-1"></i> Programme et inscription
                    </a>
                </div>
                <div class="pb-6">
                    <h3 class="text-xl font-semibold mb-1">Publication d'un ouvrage collectif</h3>
                    <p class="text-sm text-gray-500 mb-3">
                        <i class="far fa-calendar-alt mr-2"></i> 20 août 2023
                    </p>
                    <p class="text-gray-700 mb-3">Les membres du GREFSO ont contribué à un ouvrage collectif sur "Les défis de la gouvernance des entreprises familiales au Maroc" publié aux éditions L'Harmattan.</p>
                    <a href="#" class="text-primary-600 hover:underline font-medium inline-flex items-center">
                        <i class="fas fa-info-circle mr-1"></i> Détails de la publication
                    </a>
                </div>
                <?php
            }
            ?>
        </div>
        
        <div class="mt-8 text-center">
            <a href="actualites.php" class="inline-flex items-center bg-gray-100 hover:bg-gray-200 text-gray-800 px-6 py-2 rounded-lg font-medium transition duration-300">
                <i class="fas fa-list mr-2"></i> Toutes les actualités
            </a>
        </div>
    </article>
</main>

<?php
// Include right sidebar
include_once 'includes/sidebar-right.php';

// Include footer
include_once 'includes/footer.php';
?>