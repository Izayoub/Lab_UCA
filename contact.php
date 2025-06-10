<?php
// Include configuration file
require_once 'includes/config.php';

// Set page-specific variables
$page_title = get_page_title('contact');
$additional_css = ["style.css"];
$additional_js = ["main.js"];
$show_hero = false;

// Process contact form
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $subject = sanitize($_POST['subject']);
    $message = sanitize($_POST['message']);
    
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error_message = 'Veuillez remplir tous les champs.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Veuillez entrer une adresse email valide.';
    } else {
        // Insert message into database
        $insert_query = "INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $subject, $message);
        
        if (mysqli_stmt_execute($stmt)) {
            $success_message = 'Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.';
            
            // Reset form fields
            $name = $email = $subject = $message = '';
        } else {
            $error_message = 'Une erreur est survenue lors de l\'envoi du message. Veuillez réessayer.';
        }
    }
}

// Include header
include_once 'includes/header.php';

// Include left sidebar
include_once 'includes/sidebar-left.php';
?>

<!-- Main Content Area -->
<main class="lg:w-2/4">
    <article class="bg-white p-6 rounded-lg shadow-md mb-8" data-aos="fade-up">
        <h2 class="text-2xl font-bold mb-4 pb-2 border-b border-gray-200 flex items-center">
            <i class="fas fa-envelope mr-3 text-primary-600"></i> Contactez-nous
        </h2>
        
        <p class="mb-6 text-gray-700">Vous avez des questions sur nos activités de recherche ? Vous souhaitez collaborer avec nous ? N'hésitez pas à nous contacter en utilisant le formulaire ci-dessous.</p>
        
        <?php if (!empty($success_message)): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p><i class="fas fa-check-circle mr-2"></i> <?php echo $success_message; ?></p>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($error_message)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p><i class="fas fa-exclamation-triangle mr-2"></i> <?php echo $error_message; ?></p>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nom complet *</label>
                    <input type="text" id="name" name="name" value="<?php echo isset($name) ? $name : ''; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" required>
                </div>
                <div>
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email *</label>
                    <input type="email" id="email" name="email" value="<?php echo isset($email) ? $email : ''; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" required>
                </div>
            </div>
            
            <div>
                <label for="subject" class="block text-gray-700 text-sm font-bold mb-2">Sujet *</label>
                <input type="text" id="subject" name="subject" value="<?php echo isset($subject) ? $subject : ''; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" required>
            </div>
            
            <div>
                <label for="message" class="block text-gray-700 text-sm font-bold mb-2">Message *</label>
                <textarea id="message" name="message" rows="5" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" required><?php echo isset($message) ? $message : ''; ?></textarea>
            </div>
            
            <div>
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white py-2 px-6 rounded-md font-medium transition duration-300">
                    <i class="fas fa-paper-plane mr-2"></i> Envoyer
                </button>
            </div>
        </form>
    </article>
    
    <article class="bg-white p-6 rounded-lg shadow-md" data-aos="fade-up" data-aos-delay="100">
        <h2 class="text-2xl font-bold mb-4 pb-2 border-b border-gray-200 flex items-center">
            <i class="fas fa-map-marker-alt mr-3 text-primary-600"></i> Nous trouver
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-semibold mb-3">Coordonnées</h3>
                <address class="not-italic space-y-2 text-gray-700">
                    <p><i class="fas fa-building mr-2 text-primary-600"></i> GREFSO - Laboratoire LIRE-RMD</p>
                    <p><i class="fas fa-university mr-2 text-primary-600"></i> Faculté des Sciences Juridiques, Économiques et Sociales</p>
                    <p><i class="fas fa-map-marker-alt mr-2 text-primary-600"></i> Université Cadi Ayyad, Marrakech, Maroc</p>
                    <p><i class="fas fa-phone mr-2 text-primary-600"></i> <?php echo $site_phone; ?></p>
                    <p><i class="fas fa-envelope mr-2 text-primary-600"></i> <?php echo $site_email; ?></p>
                </address>
                
                <h3 class="text-lg font-semibold mt-6 mb-3">Horaires</h3>
                <ul class="space-y-1 text-gray-700">
                    <li><span class="font-medium">Lundi - Vendredi:</span> 9h00 - 17h00</li>
                    <li><span class="font-medium">Samedi - Dimanche:</span> Fermé</li>
                </ul>
            </div>
            <div>
                <div class="h-64 bg-gray-200 rounded-lg overflow-hidden">
                    <!-- Remplacer par une carte Google Maps intégrée -->
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3398.9512434874253!2d-8.013899684906332!3d31.6294361813301!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xdafee9eb5090999%3A0x2dca05e37a64f83b!2sFacult%C3%A9%20des%20Sciences%20Juridiques%20Economiques%20et%20Sociales%20-%20Marrakech!5e0!3m2!1sfr!2sma!4v1621345678901!5m2!1sfr!2sma" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
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
