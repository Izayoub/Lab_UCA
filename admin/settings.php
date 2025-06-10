<?php
// Include configuration file
require_once '../includes/config.php';

// Require login
require_login();

// Set page-specific variables
$page_title = "Paramètres du site";
$active_page = "settings";

// Process form submissions
$success_message = '';
$error_message = '';

// Handle site settings update
if (isset($_POST['update_settings'])) {
    $site_name = sanitize($_POST['site_name']);
    $site_description = sanitize($_POST['site_description']);
    $site_university = sanitize($_POST['site_university']);
    $site_email = sanitize($_POST['site_email']);
    $site_phone = sanitize($_POST['site_phone']);
    $hero_image = sanitize($_POST['hero_image']);
    
    // Update each setting
    $settings = [
        'site_name' => $site_name,
        'site_description' => $site_description,
        'site_university' => $site_university,
        'site_email' => $site_email,
        'site_phone' => $site_phone,
        'hero_image' => $hero_image
    ];
    
    $success = true;
    foreach ($settings as $name => $value) {
        $update_query = "UPDATE site_settings SET setting_value = ? WHERE setting_name = ?";
        $stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt, "ss", $value, $name);
        
        if (!mysqli_stmt_execute($stmt)) {
            $success = false;
            $error_message = "Erreur lors de la mise à jour des paramètres: " . mysqli_error($conn);
            break;
        }
    }
    
    if ($success) {
        $success_message = "Paramètres du site mis à jour avec succès.";
    }
}

// Get current settings
$settings_query = "SELECT * FROM site_settings";
$settings_result = mysqli_query($conn, $settings_query);
$settings = [];

if ($settings_result && mysqli_num_rows($settings_result) > 0) {
    while ($row = mysqli_fetch_assoc($settings_result)) {
        $settings[$row['setting_name']] = $row['setting_value'];
    }
}

// Include header
include_once 'includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Paramètres du site</h1>
    
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
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="POST" action="">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="site_name" class="block text-sm font-medium text-gray-700 mb-1">Nom du site *</label>
                    <input type="text" id="site_name" name="site_name" value="<?php echo isset($settings['site_name']) ? $settings['site_name'] : ''; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div>
                    <label for="site_university" class="block text-sm font-medium text-gray-700 mb-1">Université *</label>
                    <input type="text" id="site_university" name="site_university" value="<?php echo isset($settings['site_university']) ? $settings['site_university'] : ''; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>
            
            <div class="mb-6">
                <label for="site_description" class="block text-sm font-medium text-gray-700 mb-1">Description du site *</label>
                <textarea id="site_description" name="site_description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required><?php echo isset($settings['site_description']) ? $settings['site_description'] : ''; ?></textarea>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="site_email" class="block text-sm font-medium text-gray-700 mb-1">Email de contact *</label>
                    <input type="email" id="site_email" name="site_email" value="<?php echo isset($settings['site_email']) ? $settings['site_email'] : ''; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div>
                    <label for="site_phone" class="block text-sm font-medium text-gray-700 mb-1">Téléphone de contact *</label>
                    <input type="text" id="site_phone" name="site_phone" value="<?php echo isset($settings['site_phone']) ? $settings['site_phone'] : ''; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>
            
            <div class="mb-6">
                <label for="hero_image" class="block text-sm font-medium text-gray-700 mb-1">URL de l'image d'en-tête *</label>
                <input type="url" id="hero_image" name="hero_image" value="<?php echo isset($settings['hero_image']) ? $settings['hero_image'] : ''; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <p class="mt-1 text-sm text-gray-500">URL de l'image qui apparaît en arrière-plan de la section d'en-tête.</p>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" name="update_settings" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>

<?php
// Include footer
include_once 'includes/footer.php';
?>
