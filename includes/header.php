

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $site_name . ' | ' . $page_title; ?></title>
    
    <!-- Favicon -->
    <link rel="icon" href="assets/img/favicon.ico" type="image/x-icon">
    
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
                        serif: ['Merriweather', 'serif'],
                    },
                },
            },
        }
    </script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Merriweather:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AOS Animation Library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    
    <!-- Base CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    
    <!-- Additional CSS files -->
    <?php if (!empty($additional_css)): ?>
        <?php foreach ($additional_css as $css_file): ?>
            <link rel="stylesheet" href="assets/css/<?php echo $css_file; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
 <script src="assets/js/main.js"></script>           
           
<body class="bg-gray-50 font-sans">
    <!-- Top Bar -->
    <div class="bg-primary-800 text-white text-sm py-2 px-4">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <span><i class="fas fa-phone-alt mr-1"></i> <?php echo $site_phone; ?></span>
                <span><i class="fas fa-envelope mr-1"></i> <?php echo $site_email; ?></span>
            </div>
            <div class="flex items-center space-x-4">
                <?php if(is_logged_in()): ?>
                    <div class="relative group">
                        <button class="flex items-center space-x-1 focus:outline-none" id="user-menu-button">
                            <span><?php echo $_SESSION['user_name']; ?></span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div class="dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                            <a href="admin/dashboard.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary-50">
                                <i class="fas fa-tachometer-alt mr-2"></i> Tableau de bord
                            </a>
                            <a href="logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary-50">
                                <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="hover:text-primary-200 transition duration-200">
                        <i class="fas fa-sign-in-alt mr-1"></i> Connexion Admin
                    </a>
                <?php endif; ?>
                <div class="flex space-x-2">
                    <a href="#" class="hover:text-primary-200 transition duration-200"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="hover:text-primary-200 transition duration-200"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="hover:text-primary-200 transition duration-200"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Header -->
    <header class="bg-white shadow-md sticky top-0 z-30">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <img src="../images/logoLIRERDM.jpeg" alt="" class="w-20 h-auto object-contain">
                
            </div>
            <div class="hidden md:flex space-x-8">
                <?php 
                $current_page = get_current_page();
                foreach ($nav_items as $url => $item): 
                    $is_active = (basename($url, '.php') === $current_page);
                ?>
                    <a href="<?php echo $url; ?>" class="<?php echo $is_active ? 'text-primary-600 font-medium' : 'text-gray-700 hover:text-primary-600'; ?> transition duration-200">
                        <?php echo $item['title']; ?>
                    </a>
                <?php endforeach; ?>
            </div>
            <button class="md:hidden text-gray-700" id="mobile-menu-button">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>
        <!-- Mobile menu -->
        <div class="md:hidden hidden bg-white py-3 px-4 shadow-lg" id="mobile-menu">
            <?php foreach ($nav_items as $url => $item): 
                $is_active = (basename($url, '.php') === $current_page);
            ?>
                <a href="<?php echo $url; ?>" class="block py-2 <?php echo $is_active ? 'text-primary-600 font-medium' : 'text-gray-700 hover:text-primary-600'; ?>">
                    <i class="<?php echo $item['icon']; ?> mr-2"></i> <?php echo $item['title']; ?>
                </a>
            <?php endforeach; ?>
            <?php if(is_logged_in()): ?>
                <div class="border-t border-gray-200 mt-2 pt-2">
                    <a href="admin/dashboard.php" class="block py-2 text-gray-700 hover:text-primary-600">
                        <i class="fas fa-tachometer-alt mr-2"></i> Tableau de bord
                    </a>
                    <a href="logout.php" class="block py-2 text-gray-700 hover:text-primary-600">
                        <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <?php if (isset($show_hero) && $show_hero): ?>
    <!-- Hero Section -->
    <section class="relative py-24 md:py-36 overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('<?php echo $hero_image; ?>');">
            <div class="absolute inset-0 bg-gradient-to-r from-primary-900/90 to-primary-700/80"></div>
        </div>
        <div class="container mx-auto px-4 text-center relative z-10" data-aos="fade-up" data-aos-duration="1000">
            <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight text-white"><?php echo $site_name; ?></h1>
            <h2 class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto font-light text-white"><?php echo $site_description; ?></h2>
            <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-6">
                <a href="#content" class="bg-primary-600 hover:bg-primary-700 px-8 py-3 rounded-lg font-medium transition duration-300 shadow-lg text-white">
                    <i class="fas fa-arrow-down mr-2"></i> Découvrir
                </a>
                <a href="contact.php" class="bg-transparent border-2 border-white hover:bg-white hover:text-primary-700 px-8 py-3 rounded-lg font-medium transition duration-300 text-white">
                    <i class="fas fa-envelope mr-2"></i> Contact
                </a>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8 md:py-12" id="content">
        <div class="flex flex-col lg:flex-row gap-8">
