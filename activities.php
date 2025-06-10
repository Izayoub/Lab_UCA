<?php
// Include configuration file
require_once 'includes/config.php';

// Set page-specific variables
$page_title = get_page_title('activities');
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
            <i class="fas fa-calendar-alt mr-3 text-primary-600"></i> Nos activités
        </h2>
        
        <p class="mb-6 text-gray-700">Le GREFSO organise et participe à diverses activités académiques et professionnelles tout au long de l'année. Découvrez nos principales activités ci-dessous.</p>
        
        <?php
        // Récupérer les types d'activités
        $types_query = "SELECT * FROM activity_types WHERE is_active = 1 ORDER BY display_order";
        $types_result = mysqli_query($conn, $types_query);
        
        if ($types_result && mysqli_num_rows($types_result) > 0):
            while ($type = mysqli_fetch_assoc($types_result)):
        ?>
        
        <div class="mb-8">
            <h3 class="text-xl font-semibold mb-4 flex items-center">
                <i class="<?php echo $type['icon']; ?> text-primary-600 mr-2"></i> <?php echo htmlspecialchars($type['name']); ?>
            </h3>
            <?php if (!empty($type['description'])): ?>
            <p class="mb-4 text-gray-700"><?php echo htmlspecialchars($type['description']); ?></p>
            <?php endif; ?>
            
            <?php
            // Récupérer les activités de ce type
            $activities_query = "SELECT a.*, at.name as type_name, at.icon as type_icon 
                               FROM activities a 
                               JOIN activity_types at ON a.type_id = at.id 
                               WHERE a.type_id = ? AND a.is_active = 1 
                               ORDER BY a.start_date DESC, a.created_at DESC";
            $stmt = mysqli_prepare($conn, $activities_query);
            mysqli_stmt_bind_param($stmt, "i", $type['id']);
            mysqli_stmt_execute($stmt);
            $activities_result = mysqli_stmt_get_result($stmt);
            
            if ($activities_result && mysqli_num_rows($activities_result) > 0):
            ?>
            
            <div class="space-y-4">
                <?php while ($activity = mysqli_fetch_assoc($activities_result)): ?>
                <div class="bg-gray-50 rounded-lg p-4 <?php echo $activity['is_featured'] ? 'border-l-4 border-primary-600' : ''; ?>">
                    <div class="flex flex-col md:flex-row md:items-start gap-4">
                        <?php if (!empty($activity['start_date'])): 
                            $activity_date = new DateTime($activity['start_date']);
                        ?>
                        <div class="flex-shrink-0">
                            <div class="bg-primary-600 text-white text-center rounded-lg p-3 min-w-[80px]">
                                <span class="block font-bold text-2xl"><?php echo $activity_date->format('d'); ?></span>
                                <span class="block text-sm"><?php echo strftime('%b', $activity_date->getTimestamp()); ?></span>
                                <span class="block text-xs"><?php echo $activity_date->format('Y'); ?></span>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="flex-1">
                            <div class="flex items-start justify-between mb-2">
                                <h4 class="font-semibold text-lg text-primary-700"><?php echo htmlspecialchars($activity['title']); ?></h4>
                                <?php if ($activity['is_featured']): ?>
                                <span class="bg-primary-100 text-primary-700 text-xs px-2 py-1 rounded-full font-medium">À la une</span>
                                <?php endif; ?>
                            </div>
                            
                            <p class="text-gray-600 mb-3"><?php echo htmlspecialchars($activity['description']); ?></p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600 mb-3">
                                <?php if (!empty($activity['location'])): ?>
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2 text-primary-600"></i>
                                    <span><?php echo htmlspecialchars($activity['location']); ?></span>
                                </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($activity['organizer'])): ?>
                                <div class="flex items-center">
                                    <i class="fas fa-user-tie mr-2 text-primary-600"></i>
                                    <span><?php echo htmlspecialchars($activity['organizer']); ?></span>
                                </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($activity['target_audience'])): ?>
                                <div class="flex items-center">
                                    <i class="fas fa-users mr-2 text-primary-600"></i>
                                    <span><?php echo htmlspecialchars($activity['target_audience']); ?></span>
                                </div>
                                <?php endif; ?>
                                
                                <div class="flex items-center">
                                    <i class="fas fa-info-circle mr-2 text-primary-600"></i>
                                    <span class="capitalize">
                                        <?php 
                                        $status_labels = [
                                            'upcoming' => 'À venir',
                                            'ongoing' => 'En cours',
                                            'completed' => 'Terminé',
                                            'cancelled' => 'Annulé'
                                        ];
                                        echo $status_labels[$activity['status']] ?? $activity['status'];
                                        ?>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="flex flex-wrap gap-2">
                                <?php if ($activity['registration_required'] && !empty($activity['registration_link'])): ?>
                                <a href="<?php echo htmlspecialchars($activity['registration_link']); ?>" 
                                   target="_blank" 
                                   class="inline-flex items-center px-3 py-1 bg-primary-600 text-white text-sm rounded-lg hover:bg-primary-700 transition duration-200">
                                    <i class="fas fa-user-plus mr-1"></i> S'inscrire
                                </a>
                                <?php endif; ?>
                                
                                <?php if (!empty($activity['external_link'])): ?>
                                <a href="<?php echo htmlspecialchars($activity['external_link']); ?>" 
                                   target="_blank" 
                                   class="inline-flex items-center px-3 py-1 bg-gray-600 text-white text-sm rounded-lg hover:bg-gray-700 transition duration-200">
                                    <i class="fas fa-external-link-alt mr-1"></i> En savoir plus
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            
            <?php else: ?>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-gray-500 text-center">Aucune activité de ce type pour le moment.</p>
            </div>
            <?php endif; ?>
            <?php mysqli_stmt_close($stmt); ?>
        </div>
        
        <?php 
            endwhile;
        else:
        ?>
        <div class="text-center py-8">
            <p class="text-gray-500">Aucun type d'activité configuré.</p>
        </div>
        <?php endif; ?>
        
        <!-- Section partenariats -->
        <div class="mt-8">
            <h3 class="text-xl font-semibold mb-4 flex items-center">
                <i class="fas fa-handshake text-primary-600 mr-2"></i> Nos partenaires
            </h3>
            <p class="mb-4 text-gray-700">Le GREFSO développe des partenariats avec des institutions académiques et des organisations professionnelles au niveau national et international.</p>
            
            <?php
            // Récupérer les partenaires
            $partners_query = "SELECT * FROM partners WHERE is_active = 1 ORDER BY name";
            $partners_result = mysqli_query($conn, $partners_query);
            
            if ($partners_result && mysqli_num_rows($partners_result) > 0):
            ?>
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="font-semibold text-primary-700 mb-3">Nos partenaires</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php while ($partner = mysqli_fetch_assoc($partners_result)): ?>
                    <div class="flex items-center p-3 bg-white rounded-lg">
                        <?php if (!empty($partner['logo'])): ?>
                        <img src="<?php echo htmlspecialchars($partner['logo']); ?>" 
                             alt="<?php echo htmlspecialchars($partner['name']); ?>" 
                             class="w-12 h-12 object-contain mr-3">
                        <?php endif; ?>
                        <div>
                            <h5 class="font-medium text-gray-900"><?php echo htmlspecialchars($partner['name']); ?></h5>
                            <?php if (!empty($partner['description'])): ?>
                            <p class="text-sm text-gray-600"><?php echo htmlspecialchars($partner['description']); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <?php else: ?>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-gray-500 text-center">Information sur les partenaires à venir.</p>
            </div>
            <?php endif; ?>
        </div>
    </article>
    
    <!-- Calendrier des événements à venir -->
    <article class="bg-white p-6 rounded-lg shadow-md" data-aos="fade-up" data-aos-delay="100">
        <h2 class="text-2xl font-bold mb-4 pb-2 border-b border-gray-200 flex items-center">
            <i class="fas fa-calendar-check mr-3 text-primary-600"></i> Événements à venir
        </h2>
        
        <?php
        // Récupérer les activités à venir
        $upcoming_query = "SELECT a.*, at.name as type_name, at.icon as type_icon 
                          FROM activities a 
                          JOIN activity_types at ON a.type_id = at.id 
                          WHERE a.start_date >= CURDATE() AND a.status = 'upcoming' AND a.is_active = 1 
                          ORDER BY a.start_date ASC LIMIT 10";
        $upcoming_result = mysqli_query($conn, $upcoming_query);
        
        if ($upcoming_result && mysqli_num_rows($upcoming_result) > 0):
        ?>
        <div class="space-y-4">
            <?php 
            $current_month = '';
            while ($event = mysqli_fetch_assoc($upcoming_result)):
                $event_date = new DateTime($event['start_date']);
                $event_month = $event_date->format('F Y');
                
                // Afficher le mois si c'est un nouveau mois
                if ($event_month != $current_month):
                    $current_month = $event_month;
            ?>
            <h3 class="text-lg font-semibold text-primary-700 mt-6"><?php echo strftime('%B %Y', $event_date->getTimestamp()); ?></h3>
            <?php endif; ?>
            
            <div class="flex items-start p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition duration-200">
                <div class="bg-primary-600 text-white text-center rounded-lg p-2 mr-3 min-w-[60px]">
                    <span class="block font-bold text-xl"><?php echo $event_date->format('d'); ?></span>
                    <span class="block text-xs"><?php echo strftime('%b', $event_date->getTimestamp()); ?></span>
                </div>
                <div class="flex-1">
                    <div class="flex items-center mb-1">
                        <i class="<?php echo $event['type_icon']; ?> text-primary-600 mr-2"></i>
                        <span class="text-sm text-primary-600 font-medium"><?php echo htmlspecialchars($event['type_name']); ?></span>
                    </div>
                    <h4 class="font-medium mb-1"><?php echo htmlspecialchars($event['title']); ?></h4>
                    <p class="text-sm text-gray-600 mb-2"><?php echo htmlspecialchars($event['description']); ?></p>
                    <?php if (!empty($event['location'])): ?>
                    <p class="text-xs text-gray-500">
                        <i class="fas fa-map-marker-alt mr-1"></i> <?php echo htmlspecialchars($event['location']); ?>
                    </p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-8">
            <i class="far fa-calendar-times text-gray-400 text-4xl mb-3"></i>
            <p class="text-gray-500">Aucun événement à venir pour le moment.</p>
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