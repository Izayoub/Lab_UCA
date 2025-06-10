<?php
// Include configuration file
require_once '../includes/config.php';

// Require login
require_login();

// Set page-specific variables
$page_title = "Tableau de bord";
$active_page = "dashboard";

// Get counts for dashboard stats
$news_count_query = "SELECT COUNT(*) as count FROM news";
$news_count_result = mysqli_query($conn, $news_count_query);
$news_count = mysqli_fetch_assoc($news_count_result)['count'];

$events_count_query = "SELECT COUNT(*) as count FROM events";
$events_count_result = mysqli_query($conn, $events_count_query);
$events_count = mysqli_fetch_assoc($events_count_result)['count'];

$publications_count_query = "SELECT COUNT(*) as count FROM publications";
$publications_count_result = mysqli_query($conn, $publications_count_query);
$publications_count = mysqli_fetch_assoc($publications_count_result)['count'];

$messages_count_query = "SELECT COUNT(*) as count FROM contact_messages WHERE is_read = 0";
$messages_count_result = mysqli_query($conn, $messages_count_query);
$messages_count = mysqli_fetch_assoc($messages_count_result)['count'];

// Include header
include_once 'includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Tableau de bord</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Actualités -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-500 mr-4">
                    <i class="fas fa-newspaper text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Actualités</p>
                    <p class="text-2xl font-bold"><?php echo $news_count; ?></p>
                </div>
            </div>
            <a href="news.php" class="block mt-4 text-sm text-blue-500 hover:underline">
                <i class="fas fa-arrow-right mr-1"></i> Gérer les actualités
            </a>
        </div>
        
        <!-- Événements -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-500 mr-4">
                    <i class="fas fa-calendar-alt text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Événements</p>
                    <p class="text-2xl font-bold"><?php echo $events_count; ?></p>
                </div>
            </div>
            <a href="events.php" class="block mt-4 text-sm text-green-500 hover:underline">
                <i class="fas fa-arrow-right mr-1"></i> Gérer les événements
            </a>
        </div>
        
        <!-- Publications -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-500 mr-4">
                    <i class="fas fa-book text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Publications</p>
                    <p class="text-2xl font-bold"><?php echo $publications_count; ?></p>
                </div>
            </div>
            <a href="publications.php" class="block mt-4 text-sm text-purple-500 hover:underline">
                <i class="fas fa-arrow-right mr-1"></i> Gérer les publications
            </a>
        </div>
        
        <!-- Messages -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-500 mr-4">
                    <i class="fas fa-envelope text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Messages non lus</p>
                    <p class="text-2xl font-bold"><?php echo $messages_count; ?></p>
                </div>
            </div>
            <a href="messages.php" class="block mt-4 text-sm text-red-500 hover:underline">
                <i class="fas fa-arrow-right mr-1"></i> Voir les messages
            </a>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Dernières actualités -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-4 flex items-center">
                <i class="fas fa-newspaper text-blue-500 mr-2"></i> Dernières actualités
            </h2>
            
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Titre</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Date</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php
                        $recent_news_query = "SELECT * FROM news ORDER BY news_date DESC LIMIT 5";
                        $recent_news_result = mysqli_query($conn, $recent_news_query);
                        
                        if ($recent_news_result && mysqli_num_rows($recent_news_result) > 0) {
                            while ($news = mysqli_fetch_assoc($recent_news_result)) {
                                $news_date = new DateTime($news['news_date']);
                                ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm"><?php echo $news['title']; ?></td>
                                    <td class="px-4 py-3 text-sm"><?php echo $news_date->format('d/m/Y'); ?></td>
                                    <td class="px-4 py-3 text-sm">
                                        <?php if ($news['is_active']): ?>
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Actif</span>
                                        <?php else: ?>
                                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">Inactif</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo '<tr><td colspan="3" class="px-4 py-3 text-sm text-center text-gray-500">Aucune actualité disponible</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4 text-right">
                <a href="news.php" class="text-blue-500 hover:underline text-sm">
                    Voir toutes les actualités <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
        
        <!-- Prochains événements -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-4 flex items-center">
                <i class="fas fa-calendar-alt text-green-500 mr-2"></i> Prochains événements
            </h2>
            
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Titre</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Date</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php
                        $upcoming_events_query = "SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date LIMIT 5";
                        $upcoming_events_result = mysqli_query($conn, $upcoming_events_query);
                        
                        if ($upcoming_events_result && mysqli_num_rows($upcoming_events_result) > 0) {
                            while ($event = mysqli_fetch_assoc($upcoming_events_result)) {
                                $event_date = new DateTime($event['event_date']);
                                ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm"><?php echo $event['title']; ?></td>
                                    <td class="px-4 py-3 text-sm"><?php echo $event_date->format('d/m/Y'); ?></td>
                                    <td class="px-4 py-3 text-sm">
                                        <?php if ($event['is_active']): ?>
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Actif</span>
                                        <?php else: ?>
                                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">Inactif</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo '<tr><td colspan="3" class="px-4 py-3 text-sm text-center text-gray-500">Aucun événement à venir</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4 text-right">
                <a href="events.php" class="text-green-500 hover:underline text-sm">
                    Voir tous les événements <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer
include_once 'includes/footer.php';
?>
