<?php
// Include configuration file
require_once 'includes/config.php';

// Set page-specific variables
$page_title = get_page_title('team');
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
            <i class="fas fa-users mr-3 text-primary-600"></i> Notre équipe
        </h2>
        
        <p class="mb-6 text-gray-700">Le GREFSO est composé d'une équipe pluridisciplinaire d'enseignants-chercheurs et de doctorants spécialisés dans différents domaines du management et de la gestion des entreprises.</p>
        
        <?php
        // Récupérer les membres de l'équipe par catégorie
        $categories = [
            'direction' => 'Direction',
            'researcher' => 'Chercheurs associés',
            'phd_student' => 'Doctorants'
        ];
        
        foreach ($categories as $category_key => $category_name):
            $team_query = "SELECT * FROM team_members WHERE category = ? AND is_active = 1 ORDER BY display_order";
            $stmt = mysqli_prepare($conn, $team_query);
            mysqli_stmt_bind_param($stmt, "s", $category_key);
            mysqli_stmt_execute($stmt);
            $team_result = mysqli_stmt_get_result($stmt);
            
            if ($team_result && mysqli_num_rows($team_result) > 0):
        ?>
        
        <h3 class="text-xl font-semibold mb-4 text-primary-700"><?php echo $category_name; ?></h3>
        
        <?php if ($category_key == 'direction'): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <?php while ($member = mysqli_fetch_assoc($team_result)): ?>
            <div class="bg-gray-50 rounded-lg p-4 flex flex-col items-center text-center">
                <div class="w-32 h-32 rounded-full overflow-hidden mb-4">
                    <img src="<?php echo !empty($member['photo']) ? $member['photo'] : 'assets/images/default-avatar.jpg'; ?>" 
                         alt="<?php echo htmlspecialchars($member['name']); ?>" 
                         class="w-full h-full object-cover">
                </div>
                <h4 class="text-lg font-semibold"><?php echo htmlspecialchars($member['name']); ?></h4>
                <p class="text-primary-600 mb-2"><?php echo htmlspecialchars($member['title']); ?></p>
                <?php if (!empty($member['specialization'])): ?>
                <p class="text-sm text-gray-500 mb-2 font-medium"><?php echo htmlspecialchars($member['specialization']); ?></p>
                <?php endif; ?>
                <p class="text-sm text-gray-600 mb-3"><?php echo htmlspecialchars($member['bio']); ?></p>
                <?php if (!empty($member['research_interests'])): ?>
                <div class="mb-3">
                    <p class="text-xs text-gray-500 font-semibold mb-1">Domaines de recherche :</p>
                    <p class="text-xs text-gray-600"><?php echo htmlspecialchars($member['research_interests']); ?></p>
                </div>
                <?php endif; ?>
                <div class="flex space-x-3">
                    <?php if (!empty($member['email'])): ?>
                    <a href="mailto:<?php echo htmlspecialchars($member['email']); ?>" 
                       class="text-primary-600 hover:text-primary-700 transition duration-200" 
                       title="Email">
                        <i class="fas fa-envelope"></i>
                    </a>
                    <?php endif; ?>
                    <?php if (!empty($member['phone'])): ?>
                    <a href="tel:<?php echo htmlspecialchars($member['phone']); ?>" 
                       class="text-primary-600 hover:text-primary-700 transition duration-200" 
                       title="Téléphone">
                        <i class="fas fa-phone"></i>
                    </a>
                    <?php endif; ?>
                    <?php if (!empty($member['linkedin'])): ?>
                    <a href="<?php echo htmlspecialchars($member['linkedin']); ?>" 
                       target="_blank" 
                       class="text-primary-600 hover:text-primary-700 transition duration-200" 
                       title="LinkedIn">
                        <i class="fab fa-linkedin"></i>
                    </a>
                    <?php endif; ?>
                    <?php if (!empty($member['orcid'])): ?>
                    <a href="https://orcid.org/<?php echo htmlspecialchars($member['orcid']); ?>" 
                       target="_blank" 
                       class="text-primary-600 hover:text-primary-700 transition duration-200" 
                       title="ORCID">
                        <i class="fab fa-orcid"></i>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        
        <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
            <?php while ($member = mysqli_fetch_assoc($team_result)): ?>
            <div class="bg-gray-50 rounded-lg p-4 flex flex-col items-center text-center">
                <div class="w-24 h-24 rounded-full overflow-hidden mb-3">
                    <img src="<?php echo !empty($member['photo']) ? $member['photo'] : 'assets/images/default-avatar.jpg'; ?>" 
                         alt="<?php echo htmlspecialchars($member['name']); ?>" 
                         class="w-full h-full object-cover">
                </div>
                <h4 class="text-md font-semibold"><?php echo htmlspecialchars($member['name']); ?></h4>
                <p class="text-primary-600 text-sm mb-1"><?php echo htmlspecialchars($member['title']); ?></p>
                <?php if (!empty($member['specialization'])): ?>
                <p class="text-xs text-gray-500 mb-2"><?php echo htmlspecialchars($member['specialization']); ?></p>
                <?php endif; ?>
                <?php if (!empty($member['research_interests'])): ?>
                <p class="text-xs text-gray-600 mb-2"><?php echo htmlspecialchars($member['research_interests']); ?></p>
                <?php endif; ?>
                <div class="flex space-x-2">
                    <?php if (!empty($member['email'])): ?>
                    <a href="mailto:<?php echo htmlspecialchars($member['email']); ?>" 
                       class="text-primary-600 hover:text-primary-700 transition duration-200 text-sm" 
                       title="Email">
                        <i class="fas fa-envelope"></i>
                    </a>
                    <?php endif; ?>
                    <?php if (!empty($member['linkedin'])): ?>
                    <a href="<?php echo htmlspecialchars($member['linkedin']); ?>" 
                       target="_blank" 
                       class="text-primary-600 hover:text-primary-700 transition duration-200 text-sm" 
                       title="LinkedIn">
                        <i class="fab fa-linkedin"></i>
                    </a>
                    <?php endif; ?>
                    <?php if (!empty($member['orcid'])): ?>
                    <a href="https://orcid.org/<?php echo htmlspecialchars($member['orcid']); ?>" 
                       target="_blank" 
                       class="text-primary-600 hover:text-primary-700 transition duration-200 text-sm" 
                       title="ORCID">
                        <i class="fab fa-orcid"></i>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <?php endif; ?>
        
        <?php 
            elseif ($category_key == 'phd_student'): 
                // Section spéciale pour les doctorants s'il n'y en a pas
        ?>
        <h3 class="text-xl font-semibold mb-4 text-primary-700"><?php echo $category_name; ?></h3>
        <div class="bg-primary-50 rounded-lg p-6 border-l-4 border-primary-600 mb-8">
            <h4 class="text-lg font-semibold mb-3">Vous êtes doctorant ?</h4>
            <p class="mb-4">Si vous êtes intéressé par nos thématiques de recherche et souhaitez rejoindre notre équipe en tant que doctorant, n'hésitez pas à nous contacter.</p>
            <a href="contact.php" class="inline-block bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg font-medium transition duration-300 text-sm">
                <i class="fas fa-paper-plane mr-2"></i> Nous contacter
            </a>
        </div>
        <?php 
            endif;
            mysqli_stmt_close($stmt);
        endforeach; 
        ?>
    </article>
    
    <!-- Section axes de recherche -->
    <article class="bg-white p-6 rounded-lg shadow-md" data-aos="fade-up" data-aos-delay="100">
        <h2 class="text-2xl font-bold mb-4 pb-2 border-b border-gray-200 flex items-center">
            <i class="fas fa-search mr-3 text-primary-600"></i> Nos axes de recherche
        </h2>
        
        <?php
        // Récupérer les axes de recherche
        $research_query = "SELECT * FROM research_areas WHERE is_active = 1 ORDER BY display_order";
        $research_result = mysqli_query($conn, $research_query);
        
        if ($research_result && mysqli_num_rows($research_result) > 0):
        ?>
        <div class="space-y-6">
            <?php while ($area = mysqli_fetch_assoc($research_result)): 
                $research_points = json_decode($area['research_points'], true);
            ?>
            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition duration-300">
                <div class="flex items-start mb-4">
                    <div class="flex-shrink-0 mr-4">
                        <div class="w-12 h-12 bg-<?php echo $area['color']; ?> rounded-lg flex items-center justify-center text-white">
                            <i class="<?php echo $area['icon']; ?> text-lg"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2"><?php echo htmlspecialchars($area['title']); ?></h3>
                        <p class="text-gray-700 mb-3"><?php echo htmlspecialchars($area['description']); ?></p>
                        <?php if (!empty($area['detailed_description'])): ?>
                        <p class="text-gray-600 text-sm mb-3"><?php echo htmlspecialchars($area['detailed_description']); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($research_points) && is_array($research_points)): ?>
                        <div>
                            <h4 class="font-medium text-gray-800 mb-2">Points de recherche :</h4>
                            <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                                <?php foreach ($research_points as $point): ?>
                                <li><?php echo htmlspecialchars($point); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-8">
            <p class="text-gray-500">Aucun axe de recherche trouvé.</p>
        </div>
        <?php endif; ?>
    </article>
</main>

<?php
// Include right sidebar
include_once 'includes/sidebar-right.php';

// Include footer
include_once 'includes/footer.php';
?>