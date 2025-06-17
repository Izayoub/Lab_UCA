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

// Define upload directory
$upload_dir = '../uploads/hero/';
$hero_filename = 'hero-image';

// Create upload directory if it doesn't exist
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// Handle site settings update
if (isset($_POST['update_settings'])) {
    $site_name = sanitize($_POST['site_name']);
    $site_description = sanitize($_POST['site_description']);
    $site_university = sanitize($_POST['site_university']);
    $site_email = sanitize($_POST['site_email']);
    $site_phone = sanitize($_POST['site_phone']);
    
    // Handle hero image upload
    $hero_image_path = '';
    $current_hero = isset($settings['hero_image']) ? $settings['hero_image'] : '';
    
    if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] == 0) {
        $file = $_FILES['hero_image'];
        $file_name = $file['name'];
        $file_tmp = $file['tmp_name'];
        $file_size = $file['size'];
        $file_error = $file['error'];
        
        // Get file extension
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // Allowed extensions
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        // Validate file
        if (in_array($file_ext, $allowed_extensions)) {
            // Check file size (max 5MB)
            if ($file_size <= 5 * 1024 * 1024) {
                // Delete old hero image if exists
                $old_files = glob($upload_dir . $hero_filename . '.*');
                foreach ($old_files as $old_file) {
                    if (file_exists($old_file)) {
                        unlink($old_file);
                    }
                }
                
                // New filename with extension
                $new_filename = $hero_filename . '.' . $file_ext;
                $upload_path = $upload_dir . $new_filename;
                
                // Move uploaded file
                if (move_uploaded_file($file_tmp, $upload_path)) {
                    $hero_image_path = 'uploads/hero/' . $new_filename;
                } else {
                    $error_message = "Erreur lors de l'upload de l'image.";
                }
            } else {
                $error_message = "L'image est trop volumineuse. Taille maximum: 5MB.";
            }
        } else {
            $error_message = "Format d'image non autorisé. Formats acceptés: " . implode(', ', $allowed_extensions);
        }
    } else {
        // Keep current hero image if no new upload
        $hero_image_path = $current_hero;
    }
    
    // Only proceed if no upload errors
    if (empty($error_message)) {
        // Update each setting
        $settings_data = [
            'site_name' => $site_name,
            'site_description' => $site_description,
            'site_university' => $site_university,
            'site_email' => $site_email,
            'site_phone' => $site_phone,
            'hero_image' => $hero_image_path
        ];
        
        $success = true;
        foreach ($settings_data as $name => $value) {
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
            // Refresh settings after update
            $settings_query = "SELECT * FROM site_settings";
            $settings_result = mysqli_query($conn, $settings_query);
            $settings = [];
            if ($settings_result && mysqli_num_rows($settings_result) > 0) {
                while ($row = mysqli_fetch_assoc($settings_result)) {
                    $settings[$row['setting_name']] = $row['setting_value'];
                }
            }
        }
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
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="site_name" class="block text-sm font-medium text-gray-700 mb-1">Nom du site *</label>
                    <input type="text" id="site_name" name="site_name" value="<?php echo isset($settings['site_name']) ? htmlspecialchars($settings['site_name']) : ''; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div>
                    <label for="site_university" class="block text-sm font-medium text-gray-700 mb-1">Université *</label>
                    <input type="text" id="site_university" name="site_university" value="<?php echo isset($settings['site_university']) ? htmlspecialchars($settings['site_university']) : ''; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>
            
            <div class="mb-6">
                <label for="site_description" class="block text-sm font-medium text-gray-700 mb-1">Description du site *</label>
                <textarea id="site_description" name="site_description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required><?php echo isset($settings['site_description']) ? htmlspecialchars($settings['site_description']) : ''; ?></textarea>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="site_email" class="block text-sm font-medium text-gray-700 mb-1">Email de contact *</label>
                    <input type="email" id="site_email" name="site_email" value="<?php echo isset($settings['site_email']) ? htmlspecialchars($settings['site_email']) : ''; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div>
                    <label for="site_phone" class="block text-sm font-medium text-gray-700 mb-1">Téléphone de contact *</label>
                    <input type="text" id="site_phone" name="site_phone" value="<?php echo isset($settings['site_phone']) ? htmlspecialchars($settings['site_phone']) : ''; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>
            
            <div class="mb-6">
                <label for="hero_image" class="block text-sm font-medium text-gray-700 mb-1">Image d'en-tête *</label>
                
                <?php if (isset($settings['hero_image']) && !empty($settings['hero_image']) && file_exists('../' . $settings['hero_image'])): ?>
                    <div class="mb-3">
                        <p class="text-sm text-gray-600 mb-2">Image actuelle :</p>
                        <img src="../<?php echo htmlspecialchars($settings['hero_image']); ?>" alt="Image d'en-tête actuelle" class="max-w-xs h-32 object-cover rounded-lg border border-gray-300">
                    </div>
                <?php endif; ?>
                
                <input type="file" id="hero_image" name="hero_image" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="mt-1 text-sm text-gray-500">
                    Formats acceptés: JPG, PNG, GIF, WebP. Taille maximum: 5MB.
                    <?php if (!isset($settings['hero_image']) || empty($settings['hero_image'])): ?>
                        <span class="text-red-500">* Image requise</span>
                    <?php else: ?>
                        Laissez vide pour conserver l'image actuelle.
                    <?php endif; ?>
                </p>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" name="update_settings" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Preview image before upload
document.getElementById('hero_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Remove existing preview if any
            const existingPreview = document.getElementById('image-preview');
            if (existingPreview) {
                existingPreview.remove();
            }
            
            // Create new preview
            const preview = document.createElement('div');
            preview.id = 'image-preview';
            preview.className = 'mt-3';
            preview.innerHTML = `
                <p class="text-sm text-gray-600 mb-2">Aperçu de la nouvelle image :</p>
                <img src="${e.target.result}" alt="Aperçu" class="max-w-xs h-32 object-cover rounded-lg border border-gray-300">
            `;
            
            // Insert after the file input
            document.getElementById('hero_image').parentNode.insertBefore(preview, document.getElementById('hero_image').nextSibling);
        };
        reader.readAsDataURL(file);
    }
});
</script>

<?php
// Include footer
include_once 'includes/footer.php';
?>