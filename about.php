<?php
// Include configuration file
require_once 'includes/config.php';

// Set page-specific variables
$page_title = get_page_title('about');
$additional_css = ["style.css"];
$additional_js = ["main.js"];
$show_hero = false;

// Include header
include_once 'includes/header.php';

// Include left sidebar
include_once 'includes/sidebar-left.php';

// Récupérer les sections à propos
$about_query = "SELECT * FROM about_sections WHERE is_active = 1 ORDER BY display_order";
$about_result = mysqli_query($conn, $about_query);

// Récupérer les valeurs
$values_query = "SELECT * FROM about_values WHERE is_active = 1 ORDER BY display_order";
$values_result = mysqli_query($conn, $values_query);

// Récupérer les axes de recherche
$research_query = "SELECT * FROM research_areas WHERE is_active = 1 ORDER BY display_order";
$research_result = mysqli_query($conn, $research_query);
?>

<!-- Main Content Area -->
<main class="lg:w-2/4">
    <article class="bg-white p-6 rounded-lg shadow-md mb-8" data-aos="fade-up">
        <h2 class="text-2xl font-bold mb-4 pb-2 border-b border-gray-200 flex items-center">
            <i class="fas fa-info-circle mr-3 text-primary-600"></i> À propos du GREFSO
        </h2>
        
        <?php if ($about_result && mysqli_num_rows($about_result) > 0): ?>
            <?php while ($section = mysqli_fetch_assoc($about_result)): ?>
                <div class="mb-6">
                    <h3 class="text-xl font-semibold mb-3 flex items-center">
                        <i class="<?php echo $section['icon']; ?> mr-2 text-primary-600"></i>
                        <?php echo htmlspecialchars($section['title']); ?>
                    </h3>
                    <div class="text-gray-700">
                        <?php echo $section['content']; ?>
                    </div>
                    
                    <?php if ($section['section_key'] == 'team_intro'): ?>
                        <div class="mt-4">
                            <a href="team.php" class="inline-block bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 rounded-lg font-medium transition duration-300">
                                <i class="fas fa-users mr-2"></i> Découvrir notre équipe
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
        
        <?php if ($values_result && mysqli_num_rows($values_result) > 0): ?>
            <div class="mb-6">
                <h3 class="text-xl font-semibold mb-3">Nos valeurs</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php while ($value = mysqli_fetch_assoc($values_result)): ?>
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <h4 class="font-bold text-<?php echo $value['color'] ?? 'primary-600'; ?> mb-2 flex items-center">
                                <i class="<?php echo $value['icon']; ?> mr-2"></i>
                                <?php echo htmlspecialchars($value['title']); ?>
                            </h4>
                            <p class="text-gray-700"><?php echo htmlspecialchars($value['description']); ?></p>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        <?php endif; ?>
    </article>
    
    <?php if ($research_result && mysqli_num_rows($research_result) > 0): ?>
    <article class="bg-white p-6 rounded-lg shadow-md" data-aos="fade-up" data-aos-delay="100">
        <h2 class="text-2xl font-bold mb-4 pb-2 border-b border-gray-200 flex items-center">
            <i class="fas fa-graduation-cap mr-3 text-primary-600"></i> Nos axes de recherche
        </h2>
        
        <div class="space-y-6">
            <?php while ($research = mysqli_fetch_assoc($research_result)): 
                $research_points = json_decode($research['research_points'], true);
            ?>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-xl font-semibold mb-2 text-<?php echo $research['color'] ?? 'primary-600'; ?> flex items-center">
                        <i class="<?php echo $research['icon']; ?> mr-2"></i>
                        <?php echo htmlspecialchars($research['title']); ?>
                    </h3>
                    <p class="text-gray-700 mb-3"><?php echo htmlspecialchars($research['description']); ?></p>
                    
                    <?php if (!empty($research['detailed_description'])): ?>
                        <p class="text-gray-600 mb-3 text-sm"><?php echo htmlspecialchars($research['detailed_description']); ?></p>
                    <?php endif; ?>
                    
                    <?php if (!empty($research_points) && is_array($research_points)): ?>
                        <ul class="list-disc pl-6 text-gray-700">
                            <?php foreach ($research_points as $point): ?>
                                <li><?php echo htmlspecialchars($point); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </article>
    <?php endif; ?>
</main>

<?php
// Include right sidebar
include_once 'includes/sidebar-right.php';

// Include footer
include_once 'includes/footer.php';
?>