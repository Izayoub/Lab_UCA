<?php
// Include configuration file
require_once 'includes/config.php';

// Set page-specific variables
$page_title = get_page_title('publications');
$additional_css = ["style.css"];
$additional_js = ["main.js"];
$show_hero = false;

// Include header
include_once 'includes/header.php';

// Include left sidebar
include_once 'includes/sidebar-left.php';
?>

<!-- Main Content Area -->
<main class="lg:w-2/4">
    <article class="bg-white p-6 rounded-lg shadow-md mb-8" data-aos="fade-up">
        <h2 class="text-2xl font-bold mb-4 pb-2 border-b border-gray-200 flex items-center">
            <i class="fas fa-book mr-3 text-primary-600"></i> Nos publications
        </h2>
        
        <p class="mb-6 text-gray-700">Les membres du GREFSO publient régulièrement des articles dans des revues scientifiques nationales et internationales, ainsi que des ouvrages collectifs. Découvrez ci-dessous nos principales publications.</p>
        
        <div class="mb-8">
            <h3 class="text-xl font-semibold mb-4 flex items-center">
                <i class="fas fa-search text-primary-600 mr-2"></i> Rechercher une publication
            </h3>
            
            <form action="" method="GET" class="bg-gray-50 p-4 rounded-lg mb-6">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-grow">
                        <input type="text" name="search" placeholder="Rechercher par titre, auteur..." class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>
                    <div>
                        <button type="submit" class="w-full md:w-auto bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 rounded-md font-medium transition duration-300">
                            <i class="fas fa-search mr-2"></i> Rechercher
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
        <?php
        // Récupérer les publications
        $search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
        
        $publications_query = "SELECT * FROM publications WHERE is_active = 1";
        
        if (!empty($search)) {
            $publications_query .= " AND (title LIKE '%$search%' OR authors LIKE '%$search%' OR journal LIKE '%$search%' OR abstract LIKE '%$search%')";
        }
        
        $publications_query .= " ORDER BY publication_date DESC";
        $publications_result = mysqli_query($conn, $publications_query);
        
        if ($publications_result && mysqli_num_rows($publications_result) > 0):
        ?>
        <div class="space-y-6">
            <?php while ($publication = mysqli_fetch_assoc($publications_result)): 
                $pub_date = new DateTime($publication['publication_date']);
            ?>
            <div class="border-b border-gray-200 pb-6 last:border-0">
                <h3 class="text-lg font-semibold text-primary-700"><?php echo $publication['title']; ?></h3>
                <p class="text-gray-600 mb-2">
                    <span class="font-medium"><?php echo $publication['authors']; ?></span>
                    <?php if (!empty($publication['journal'])): ?>
                    <span class="mx-1">|</span> <?php echo $publication['journal']; ?>
                    <?php endif; ?>
                    <span class="mx-1">|</span> <?php echo $pub_date->format('Y'); ?>
                </p>
                <?php if (!empty($publication['abstract'])): ?>
                <div class="mb-3">
                    <button class="text-primary-600 hover:underline text-sm toggle-abstract" data-target="abstract-<?php echo $publication['id']; ?>">
                        <i class="fas fa-plus-circle mr-1"></i> Voir le résumé
                    </button>
                    <div id="abstract-<?php echo $publication['id']; ?>" class="hidden mt-2 text-gray-700 text-sm bg-gray-50 p-3 rounded-lg">
                        <?php echo $publication['abstract']; ?>
                    </div>
                </div>
                <?php endif; ?>
                <?php if (!empty($publication['link'])): ?>
                <a href="<?php echo $publication['link']; ?>" target="_blank" class="text-primary-600 hover:underline text-sm inline-flex items-center">
                    <i class="fas fa-external-link-alt mr-1"></i> Accéder à la publication
                </a>
                <?php endif; ?>
            </div>
            <?php endwhile; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-8">
            <i class="fas fa-book-open text-gray-400 text-4xl mb-3"></i>
            <p class="text-gray-500">Aucune publication trouvée<?php echo !empty($search) ? ' pour votre recherche.' : '.'; ?></p>
        </div>
        <?php endif; ?>
    </article>
    
    <article class="bg-white p-6 rounded-lg shadow-md" data-aos="fade-up" data-aos-delay="100">
        <h2 class="text-2xl font-bold mb-4 pb-2 border-b border-gray-200 flex items-center">
            <i class="fas fa-book-reader mr-3 text-primary-600"></i> Ouvrages collectifs
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="font-semibold text-primary-700 mb-2">Management des PME marocaines</h3>
                <p class="text-sm text-gray-600 mb-2">Ouvrage collectif sous la direction de Dr. Mohammed Alami, publié en 2020.</p>
                <p class="text-sm text-gray-600 mb-3">Cet ouvrage explore les spécificités du management des PME dans le contexte marocain.</p>
                <a href="#" class="text-primary-600 hover:underline text-sm">En savoir plus</a>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="font-semibold text-primary-700 mb-2">Entreprises familiales : défis et opportunités</h3>
                <p class="text-sm text-gray-600 mb-2">Ouvrage collectif sous la direction de Dr. Fatima Zahra Benali, publié en 2018.</p>
                <p class="text-sm text-gray-600 mb-3">Une analyse approfondie des enjeux spécifiques aux entreprises familiales marocaines.</p>
                <a href="#" class="text-primary-600 hover:underline text-sm">En savoir plus</a>
            </div>
        </div>
    </article>
</main>

<?php
// Include right sidebar
include_once 'includes/sidebar-right.php';

// Include footer
include_once 'includes/footer.php';
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle abstract visibility
    const toggleButtons = document.querySelectorAll('.toggle-abstract');
    
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const targetElement = document.getElementById(targetId);
            
            if (targetElement.classList.contains('hidden')) {
                targetElement.classList.remove('hidden');
                this.innerHTML = '<i class="fas fa-minus-circle mr-1"></i> Masquer le résumé';
            } else {
                targetElement.classList.add('hidden');
                this.innerHTML = '<i class="fas fa-plus-circle mr-1"></i> Voir le résumé';
            }
        });
    });
});
</script>
