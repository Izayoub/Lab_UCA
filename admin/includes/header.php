<?php
// Prevent direct access
if (!defined('BASEPATH')) {
    define('BASEPATH', true);
}

// Get current page
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - <?php echo $page_title; ?></title>
    
    <!-- Favicon -->
    <link rel="icon" href="../assets/img/favicon.ico" type="image/x-icon">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                },
            },
        }
    </script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        .sidebar-link {
            @apply flex items-center py-2 px-4 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition-colors duration-200;
        }
        
        .sidebar-link.active {
            @apply bg-gray-700 text-white;
        }
        
        .sidebar-link i {
            @apply mr-3 text-lg;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white hidden md:block">
            <div class="p-4 border-b border-gray-700">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-university text-2xl text-primary-400"></i>
                    <div>
                        <h1 class="text-xl font-bold">LIRE-RMD</h1>
                        <p class="text-xs text-gray-400">Administration</p>
                    </div>
                </div>
            </div>
            
            <nav class="p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="dashboard.php" class="sidebar-link <?php echo $active_page == 'dashboard' ? 'active' : ''; ?>">
                            <i class="fas fa-tachometer-alt"></i> Tableau de bord
                        </a>
                    </li>
                    <li>
                        <a href="news.php" class="sidebar-link <?php echo $active_page == 'news' ? 'active' : ''; ?>">
                            <i class="fas fa-newspaper"></i> Actualités
                        </a>
                    </li>
                    <li>
                        <a href="team.php" class="sidebar-link <?php echo $active_page == 'news' ? 'active' : ''; ?>">
                            <i class="fas fa-users"></i> equipe
                        </a>
                    </li>
                    <li>
                        <a href="about.php" class="sidebar-link <?php echo $active_page == 'news' ? 'active' : ''; ?>">
                            <i class="fas fa-info-circle"></i> A propos
                        </a>
                    </li>
                    <li>
                        <a href="activities.php" class="sidebar-link <?php echo $active_page == 'news' ? 'active' : ''; ?>">
                            <i class="fas fa-tasks"></i> Activities
                        </a>
                    </li>
                    <li>
                        <a href="events.php" class="sidebar-link <?php echo $active_page == 'events' ? 'active' : ''; ?>">
                            <i class="fas fa-calendar-alt"></i> Événements
                        </a>
                    </li>
                    <li>
                        <a href="publications.php" class="sidebar-link <?php echo $active_page == 'publications' ? 'active' : ''; ?>">
                            <i class="fas fa-book"></i> Publications
                        </a>
                    </li>
                    <li>
                        <a href="messages.php" class="sidebar-link <?php echo $active_page == 'messages' ? 'active' : ''; ?>">
                            <i class="fas fa-envelope"></i> Messages
                            <?php
                            // Count unread messages
                            $unread_query = "SELECT COUNT(*) as count FROM contact_messages WHERE is_read = 0";
                            $unread_result = mysqli_query($conn, $unread_query);
                            $unread_count = mysqli_fetch_assoc($unread_result)['count'];
                            
                            if ($unread_count > 0):
                            ?>
                            <span class="ml-2 px-2 py-1 bg-red-500 text-white text-xs rounded-full"><?php echo $unread_count; ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li>
                        <a href="settings.php" class="sidebar-link <?php echo $active_page == 'settings' ? 'active' : ''; ?>">
                            <i class="fas fa-cog"></i> Paramètres
                        </a>
                    </li>
                </ul>
                
                <div class="mt-8 pt-4 border-t border-gray-700">
                    <a href="../index.php" class="sidebar-link">
                        <i class="fas fa-home"></i> Voir le site
                    </a>
                    <a href="../logout.php" class="sidebar-link text-red-300 hover:text-red-100">
                        <i class="fas fa-sign-out-alt"></i> Déconnexion
                    </a>
                </div>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <header class="bg-white shadow-md">
                <div class="flex items-center justify-between p-4">
                    <div class="flex items-center">
                        <button id="sidebar-toggle" class="text-gray-600 mr-4 md:hidden">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h2 class="text-xl font-semibold"><?php echo $page_title; ?></h2>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <button id="user-menu-button" class="flex items-center space-x-2 focus:outline-none">
                                <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center">
                                    <i class="fas fa-user text-gray-600"></i>
                                </div>
                                <span class="hidden md:block"><?php echo $_SESSION['user_name']; ?></span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            
                            <div id="user-dropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden">
                                <a href="settings.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-cog mr-2"></i> Paramètres
                                </a>
                                <a href="../logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Mobile Sidebar -->
            <div id="mobile-sidebar" class="fixed inset-0 bg-gray-800 text-white z-50 transform -translate-x-full transition-transform duration-300 md:hidden">
                <div class="flex justify-end p-4">
                    <button id="close-sidebar" class="text-white">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div class="p-4 border-b border-gray-700">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-university text-2xl text-primary-400"></i>
                        <div>
                            <h1 class="text-xl font-bold">LIRE-RMD</h1>
                            <p class="text-xs text-gray-400">Administration</p>
                        </div>
                    </div>
                </div>
                
                <nav class="p-4">
                    <ul class="space-y-2">
                        <li>
                            <a href="dashboard.php" class="sidebar-link <?php echo $active_page == 'dashboard' ? 'active' : ''; ?>">
                                <i class="fas fa-tachometer-alt"></i> Tableau de bord
                            </a>
                        </li>
                        <li>
                            <a href="news.php" class="sidebar-link <?php echo $active_page == 'news' ? 'active' : ''; ?>">
                                <i class="fas fa-newspaper"></i> Actualités
                            </a>
                        </li>
                        <li>
                            <a href="events.php" class="sidebar-link <?php echo $active_page == 'events' ? 'active' : ''; ?>">
                                <i class="fas fa-calendar-alt"></i> Événements
                            </a>
                        </li>
                        <li>
                            <a href="publications.php" class="sidebar-link <?php echo $active_page == 'publications' ? 'active' : ''; ?>">
                                <i class="fas fa-book"></i> Publications
                            </a>
                        </li>
                        <li>
                            <a href="messages.php" class="sidebar-link <?php echo $active_page == 'messages' ? 'active' : ''; ?>">
                                <i class="fas fa-envelope"></i> Messages
                                <?php if ($unread_count > 0): ?>
                                <span class="ml-2 px-2 py-1 bg-red-500 text-white text-xs rounded-full"><?php echo $unread_count; ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li>
                            <a href="settings.php" class="sidebar-link <?php echo $active_page == 'settings' ? 'active' : ''; ?>">
                                <i class="fas fa-cog"></i> Paramètres
                            </a>
                        </li>
                    </ul>
                    
                    <div class="mt-8 pt-4 border-t border-gray-700">
                        <a href="../index.php" class="sidebar-link">
                            <i class="fas fa-home"></i> Voir le site
                        </a>
                        <a href="../logout.php" class="sidebar-link text-red-300 hover:text-red-100">
                            <i class="fas fa-sign-out-alt"></i> Déconnexion
                        </a>
                    </div>
                </nav>
            </div>
            
            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto bg-gray-100">
