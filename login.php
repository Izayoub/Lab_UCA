<?php
// Include configuration file
require_once 'includes/config.php';

// Set page-specific variables
$page_title = get_page_title('login');
$additional_css = ["style.css"];
$additional_js = ["main.js"];
$show_hero = false;


// Check if already logged in
if (is_logged_in()) {
    header('Location: dashboard.php');
    exit;
}

// Process login form
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = 'Veuillez remplir tous les champs.';
    } else {
        // Check if user exists
        $query = "SELECT id, email, password, name FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            
            // Verify password (simple comparison for now)
            if ($password === $user['password']) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                
                // Redirect to dashboard
                header('Location: ../admin/dashboard.php');
                exit;
            } else {
                $error = 'Mot de passe incorrect.';
            }
        } else {
            $error = 'Cet utilisateur n\'existe pas.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $site_name; ?> | <?php echo $page_title; ?></title>
    
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
    
    <!-- AOS Animation Library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    
    <!-- Base CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-gray-100 font-sans">
    <div class="min-h-screen flex flex-col">
        <!-- Header with logo only -->
        <header class="bg-white shadow-md py-4">
            <div class="container mx-auto px-4 flex justify-center">
                <a href="index.php" class="flex items-center space-x-3">
                    <i class="fas fa-university text-3xl text-primary-600"></i>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800"><?php echo $site_name; ?></h1>
                        <p class="text-sm text-gray-500"><?php echo $site_university; ?></p>
                    </div>
                </a>
            </div>
        </header>

        <!-- Main Content -->
        <div class="flex-grow flex items-center justify-center py-12 px-4">
            <div class="max-w-md w-full" data-aos="fade-up" data-aos-duration="800">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-primary-700 to-primary-600 text-white p-6 text-center">
                        <h2 class="text-2xl font-bold">Connexion Administrateur</h2>
                        <p class="text-primary-100 text-sm mt-1">Accédez au tableau de bord</p>
                    </div>
                    
                    <div class="p-6">
                        <?php if (!empty($error)): ?>
                            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                                <p><i class="fas fa-exclamation-triangle mr-2"></i> <?php echo $error; ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="space-y-6">
                            <div>
                                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-envelope text-gray-400"></i>
                                    </div>
                                    <input type="email" id="email" name="email" value="<?php echo isset($email) ? $email : ''; ?>" class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="admin@admin.com" required>
                                </div>
                            </div>
                            
                            <div>
                                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Mot de passe</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-lock text-gray-400"></i>
                                    </div>
                                    <input type="password" id="password" name="password" class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="Votre mot de passe" required>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <input type="checkbox" id="remember" name="remember" class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                    <label for="remember" class="ml-2 block text-sm text-gray-700">Se souvenir de moi</label>
                                </div>
                            </div>
                            
                            <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white py-2 px-4 rounded-md font-medium transition duration-300 flex items-center justify-center">
                                <i class="fas fa-sign-in-alt mr-2"></i> Se connecter
                            </button>
                        </form>
                        
                        <div class="mt-6 text-center text-sm text-gray-500">
                            <p>Accès réservé à l'administration du site</p>
                            <p class="mt-2">Email: admin@admin.com | Mot de passe: admin123</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 text-center">
                    <a href="index.php" class="text-primary-600 hover:text-primary-700 inline-flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Retour à l'accueil
                    </a>
                </div>
            </div>
        </div>

        <!-- Simple Footer -->
        <footer class="bg-gray-800 text-white py-6">
            <div class="container mx-auto px-4 text-center">
                <p>&copy; <?php echo date('Y'); ?> <?php echo $site_name; ?> - <?php echo $site_university; ?>. Tous droits réservés.</p>
            </div>
        </footer>
    </div>

    <!-- AOS Animation Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            once: true,
            offset: 100,
            duration: 800
        });
    </script>
</body>
</html>
