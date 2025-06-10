<?php
// Include configuration file
require_once '../includes/config.php';

// Require login
require_login();

// Set page-specific variables
$page_title = "Gestion des sections À propos";
$active_page = "about";

// Process form submissions
$success_message = '';
$error_message = '';

// Handle adding new about section
if (isset($_POST['add_section'])) {
    $section_key = sanitize($_POST['section_key']);
    $title = sanitize($_POST['title']);
    $content = $_POST['content']; // HTML content
    $icon = sanitize($_POST['icon']);
    $display_order = intval($_POST['display_order']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    $insert_query = "INSERT INTO about_sections (section_key, title, content, icon, display_order, is_active) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($stmt, "ssssii", $section_key, $title, $content, $icon, $display_order, $is_active);
    
    if (mysqli_stmt_execute($stmt)) {
        $success_message = "Nouvelle section ajoutée avec succès.";
    } else {
        $error_message = "Erreur lors de l'ajout de la section: " . mysqli_error($conn);
    }
}

// Handle updating section
if (isset($_POST['update_section'])) {
    $section_id = $_POST['section_id'];
    $section_key = sanitize($_POST['section_key']);
    $title = sanitize($_POST['title']);
    $content = $_POST['content']; // HTML content
    $icon = sanitize($_POST['icon']);
    $display_order = intval($_POST['display_order']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    $update_query = "UPDATE about_sections SET section_key = ?, title = ?, content = ?, icon = ?, display_order = ?, is_active = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "ssssiii", $section_key, $title, $content, $icon, $display_order, $is_active, $section_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $success_message = "Section mise à jour avec succès.";
    } else {
        $error_message = "Erreur lors de la mise à jour de la section: " . mysqli_error($conn);
    }
}

// Handle deleting section
if (isset($_GET['delete'])) {
    $section_id = intval($_GET['delete']);
    
    $delete_query = "DELETE FROM about_sections WHERE id = ?";
    $stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($stmt, "i", $section_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $success_message = "Section supprimée avec succès.";
    } else {
        $error_message = "Erreur lors de la suppression de la section: " . mysqli_error($conn);
    }
}

// Get section for editing if ID is provided
$edit_section = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $edit_query = "SELECT * FROM about_sections WHERE id = ?";
    $stmt = mysqli_prepare($conn, $edit_query);
    mysqli_stmt_bind_param($stmt, "i", $edit_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $edit_section = mysqli_fetch_assoc($result);
    }
}

// Include header
include_once 'includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Gestion des sections À propos</h1>
        <button id="showAddForm" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-plus mr-2"></i> Ajouter une section
        </button>
    </div>
    
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
    
    <!-- Add/Edit Section Form -->
    <div id="sectionForm" class="bg-white rounded-lg shadow-md p-6 mb-8 <?php echo ($edit_section || isset($_POST['add_section']) || isset($_POST['update_section'])) ? '' : 'hidden'; ?>">
        <h2 class="text-xl font-bold mb-4">
            <?php echo $edit_section ? 'Modifier la section' : 'Ajouter une nouvelle section'; ?>
        </h2>
        
        <form method="POST" action="">
            <?php if ($edit_section): ?>
                <input type="hidden" name="section_id" value="<?php echo $edit_section['id']; ?>">
            <?php endif; ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="section_key" class="block text-sm font-medium text-gray-700 mb-1">Clé de section *</label>
                    <input type="text" id="section_key" name="section_key" value="<?php echo $edit_section ? $edit_section['section_key'] : ''; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
                    <p class="text-xs text-gray-500 mt-1">Identifiant unique (ex: history, mission)</p>
                </div>
                
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Titre *</label>
                    <input type="text" id="title" name="title" value="<?php echo $edit_section ? $edit_section['title'] : ''; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="icon" class="block text-sm font-medium text-gray-700 mb-1">Icône FontAwesome</label>
                    <input type="text" id="icon" name="icon" value="<?php echo $edit_section ? $edit_section['icon'] : 'fas fa-info-circle'; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                    <p class="text-xs text-gray-500 mt-1">Ex: fas fa-history, fas fa-bullseye</p>
                </div>
                
                <div>
                    <label for="display_order" class="block text-sm font-medium text-gray-700 mb-1">Ordre d'affichage</label>
                    <input type="number" id="display_order" name="display_order" value="<?php echo $edit_section ? $edit_section['display_order'] : '0'; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
            </div>
            
            <div class="mb-6">
                <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Contenu (HTML) *</label>
                <textarea id="content" name="content" rows="8" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required><?php echo $edit_section ? $edit_section['content'] : ''; ?></textarea>
                <p class="text-xs text-gray-500 mt-1">Vous pouvez utiliser du HTML pour formater le contenu</p>
            </div>
            
            <div class="mb-6">
                <div class="flex items-center">
                    <input type="checkbox" id="is_active" name="is_active" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded" <?php echo (!$edit_section || $edit_section['is_active']) ? 'checked' : ''; ?>>
                    <label for="is_active" class="ml-2 block text-sm text-gray-700">Actif</label>
                </div>
            </div>
            
            <div class="flex justify-end space-x-4">
                <button type="button" id="cancelForm" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
                    Annuler
                </button>
                <button type="submit" name="<?php echo $edit_section ? 'update_section' : 'add_section'; ?>" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                    <?php echo $edit_section ? 'Mettre à jour' : 'Ajouter'; ?>
                </button>
            </div>
        </form>
    </div>
    
    <!-- Sections List -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ordre</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Clé</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Icône</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contenu</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                    $sections_query = "SELECT * FROM about_sections ORDER BY display_order ASC, id ASC";
                    $sections_result = mysqli_query($conn, $sections_query);
                    
                    if ($sections_result && mysqli_num_rows($sections_result) > 0) {
                        while ($section = mysqli_fetch_assoc($sections_result)) {
                            ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?php echo $section['display_order']; ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?php echo $section['section_key']; ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?php echo $section['title']; ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">
                                        <i class="<?php echo $section['icon']; ?> mr-2"></i><?php echo $section['icon']; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-500"><?php echo substr(strip_tags($section['content']), 0, 100) . (strlen(strip_tags($section['content'])) > 100 ? '...' : ''); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($section['is_active']): ?>
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Actif</span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Inactif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="?edit=<?php echo $section['id']; ?>" class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>
                                    <a href="?delete=<?php echo $section['id']; ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette section?')">
                                        <i class="fas fa-trash-alt"></i> Supprimer
                                    </a>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo '<tr><td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">Aucune section disponible</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const showAddFormBtn = document.getElementById('showAddForm');
        const cancelFormBtn = document.getElementById('cancelForm');
        const sectionForm = document.getElementById('sectionForm');
        
        if (showAddFormBtn) {
            showAddFormBtn.addEventListener('click', function() {
                sectionForm.classList.remove('hidden');
                // Reset form if it was used for editing
                const form = sectionForm.querySelector('form');
                if (form.querySelector('input[name="section_id"]')) {
                    form.reset();
                    form.action = '';
                    form.querySelector('input[name="section_id"]').remove();
                    form.querySelector('button[type="submit"]').name = 'add_section';
                    form.querySelector('button[type="submit"]').textContent = 'Ajouter';
                    sectionForm.querySelector('h2').textContent = 'Ajouter une nouvelle section';
                }
            });
        }
        
        if (cancelFormBtn) {
            cancelFormBtn.addEventListener('click', function() {
                sectionForm.classList.add('hidden');
                // Reset form
                sectionForm.querySelector('form').reset();
            });
        }
    });
</script>

<?php
// Include footer
include_once 'includes/footer.php';
?>