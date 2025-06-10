<?php
// Include configuration file
require_once '../includes/config.php';

// Require login
require_login();

// Set page-specific variables
$page_title = "Gestion des types d'activités";
$active_page = "activity_types";

// Process form submissions
$success_message = '';
$error_message = '';

// Handle adding new activity type
if (isset($_POST['add_type'])) {
    $name = sanitize($_POST['name']);
    $description = sanitize($_POST['description']);
    $icon = sanitize($_POST['icon']);
    $color = sanitize($_POST['color']);
    $display_order = intval($_POST['display_order']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    // Check if name already exists
    $check_query = "SELECT id FROM activity_types WHERE name = ?";
    $check_stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($check_stmt, "s", $name);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);
    
    if (mysqli_num_rows($check_result) > 0) {
        $error_message = "Un type d'activité avec ce nom existe déjà.";
    } else {
        $insert_query = "INSERT INTO activity_types (name, description, icon, color, display_order, is_active) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($stmt, "ssssii", $name, $description, $icon, $color, $display_order, $is_active);
        
        if (mysqli_stmt_execute($stmt)) {
            $success_message = "Nouveau type d'activité ajouté avec succès.";
        } else {
            $error_message = "Erreur lors de l'ajout du type d'activité: " . mysqli_error($conn);
        }
    }
}

// Handle updating activity type
if (isset($_POST['update_type'])) {
    $type_id = intval($_POST['type_id']);
    $name = sanitize($_POST['name']);
    $description = sanitize($_POST['description']);
    $icon = sanitize($_POST['icon']);
    $color = sanitize($_POST['color']);
    $display_order = intval($_POST['display_order']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    // Check if name already exists for other records
    $check_query = "SELECT id FROM activity_types WHERE name = ? AND id != ?";
    $check_stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($check_stmt, "si", $name, $type_id);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);
    
    if (mysqli_num_rows($check_result) > 0) {
        $error_message = "Un autre type d'activité avec ce nom existe déjà.";
    } else {
        $update_query = "UPDATE activity_types SET name = ?, description = ?, icon = ?, color = ?, display_order = ?, is_active = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt, "ssssiii", $name, $description, $icon, $color, $display_order, $is_active, $type_id);
        
        if (mysqli_stmt_execute($stmt)) {
            $success_message = "Type d'activité mis à jour avec succès.";
        } else {
            $error_message = "Erreur lors de la mise à jour du type d'activité: " . mysqli_error($conn);
        }
    }
}

// Handle deleting activity type
if (isset($_GET['delete'])) {
    $type_id = intval($_GET['delete']);
    
    // Check if type is used in activities
    $usage_query = "SELECT COUNT(*) as usage_count FROM activities WHERE type_id = ?";
    $usage_stmt = mysqli_prepare($conn, $usage_query);
    mysqli_stmt_bind_param($usage_stmt, "i", $type_id);
    mysqli_stmt_execute($usage_stmt);
    $usage_result = mysqli_stmt_get_result($usage_stmt);
    $usage_data = mysqli_fetch_assoc($usage_result);
    
    if ($usage_data['usage_count'] > 0) {
        $error_message = "Impossible de supprimer ce type d'activité car il est utilisé par {$usage_data['usage_count']} activité(s).";
    } else {
        $delete_query = "DELETE FROM activity_types WHERE id = ?";
        $stmt = mysqli_prepare($conn, $delete_query);
        mysqli_stmt_bind_param($stmt, "i", $type_id);
        
        if (mysqli_stmt_execute($stmt)) {
            $success_message = "Type d'activité supprimé avec succès.";
        } else {
            $error_message = "Erreur lors de la suppression du type d'activité: " . mysqli_error($conn);
        }
    }
}

// Get activity type for editing if ID is provided
$edit_type = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $edit_query = "SELECT * FROM activity_types WHERE id = ?";
    $stmt = mysqli_prepare($conn, $edit_query);
    mysqli_stmt_bind_param($stmt, "i", $edit_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $edit_type = mysqli_fetch_assoc($result);
    }
}

// Common FontAwesome icons for activity types
$common_icons = [
    'fas fa-calendar' => 'Calendrier',
    'fas fa-chalkboard-teacher' => 'Enseignement',
    'fas fa-users' => 'Groupe',
    'fas fa-tools' => 'Outils',
    'fas fa-graduation-cap' => 'Académique',
    'fas fa-handshake' => 'Partenariat',
    'fas fa-microphone' => 'Conférence',
    'fas fa-book' => 'Formation',
    'fas fa-flask' => 'Recherche',
    'fas fa-bullhorn' => 'Événement',
    'fas fa-laptop' => 'Technologie',
    'fas fa-globe' => 'International'
];

// Common colors
$common_colors = [
    'primary-600' => 'Bleu principal',
    'blue-600' => 'Bleu',
    'green-600' => 'Vert',
    'red-600' => 'Rouge',
    'yellow-600' => 'Jaune',
    'purple-600' => 'Violet',
    'pink-600' => 'Rose',
    'indigo-600' => 'Indigo',
    'gray-600' => 'Gris',
    'teal-600' => 'Sarcelle'
];

// Include header
include_once 'includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold">Gestion des types d'activités</h1>
            <p class="text-gray-600 mt-1">Gérez les catégories d'activités de votre organisation</p>
        </div>
        <div class="flex space-x-2">
            <a href="activities.php" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-calendar-alt mr-2"></i> Activités
            </a>
            <button id="showAddForm" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-plus mr-2"></i> Ajouter un type
            </button>
        </div>
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
    
    <!-- Add/Edit Activity Type Form -->
    <div id="typeForm" class="bg-white rounded-lg shadow-md p-6 mb-8 <?php echo ($edit_type || isset($_POST['add_type']) || isset($_POST['update_type'])) ? '' : 'hidden'; ?>">
        <h2 class="text-xl font-bold mb-4">
            <?php echo $edit_type ? 'Modifier le type d\'activité' : 'Ajouter un nouveau type d\'activité'; ?>
        </h2>
        
        <form method="POST" action="">
            <?php if ($edit_type): ?>
                <input type="hidden" name="type_id" value="<?php echo $edit_type['id']; ?>">
            <?php endif; ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom du type *</label>
                    <input type="text" id="name" name="name" value="<?php echo $edit_type ? htmlspecialchars($edit_type['name']) : ''; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required maxlength="100">
                </div>
                
                <div>
                    <label for="display_order" class="block text-sm font-medium text-gray-700 mb-1">Ordre d'affichage</label>
                    <input type="number" id="display_order" name="display_order" value="<?php echo $edit_type ? $edit_type['display_order'] : '0'; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" min="0">
                </div>
            </div>
            
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea id="description" name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Description du type d'activité..."><?php echo $edit_type ? htmlspecialchars($edit_type['description']) : ''; ?></textarea>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="icon" class="block text-sm font-medium text-gray-700 mb-1">Icône FontAwesome</label>
                    <select id="icon" name="icon" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        <?php foreach ($common_icons as $icon_class => $icon_name): ?>
                            <option value="<?php echo $icon_class; ?>" <?php echo ($edit_type && $edit_type['icon'] == $icon_class) ? 'selected' : ''; ?>>
                                <?php echo $icon_name; ?> (<?php echo $icon_class; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="mt-2 flex items-center text-sm text-gray-500">
                        <i id="iconPreview" class="<?php echo $edit_type ? $edit_type['icon'] : 'fas fa-calendar'; ?> mr-2"></i>
                        <span>Aperçu de l'icône</span>
                    </div>
                </div>
                
                <div>
                    <label for="color" class="block text-sm font-medium text-gray-700 mb-1">Couleur</label>
                    <select id="color" name="color" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        <?php foreach ($common_colors as $color_class => $color_name): ?>
                            <option value="<?php echo $color_class; ?>" <?php echo ($edit_type && $edit_type['color'] == $color_class) ? 'selected' : ''; ?>>
                                <?php echo $color_name; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="mt-2 flex items-center text-sm text-gray-500">
                        <div id="colorPreview" class="w-4 h-4 rounded mr-2 bg-blue-600"></div>
                        <span>Aperçu de la couleur</span>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center mb-6">
                <input type="checkbox" id="is_active" name="is_active" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded" <?php echo (!$edit_type || $edit_type['is_active']) ? 'checked' : ''; ?>>
                <label for="is_active" class="ml-2 block text-sm text-gray-700">Type actif</label>
            </div>
            
            <div class="flex justify-end space-x-4">
                <button type="button" id="cancelForm" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
                    Annuler
                </button>
                <button type="submit" name="<?php echo $edit_type ? 'update_type' : 'add_type'; ?>" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                    <?php echo $edit_type ? 'Mettre à jour' : 'Ajouter'; ?>
                </button>
            </div>
        </form>
    </div>
    
    <!-- Activity Types List -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Liste des types d'activités</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ordre</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activités</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">État</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                    // Get activity types with activity count
                    $types_query = "SELECT at.*, COUNT(a.id) as activity_count 
                                   FROM activity_types at 
                                   LEFT JOIN activities a ON at.id = a.type_id 
                                   GROUP BY at.id 
                                   ORDER BY at.display_order, at.name";
                    $types_result = mysqli_query($conn, $types_query);
                    
                    if ($types_result && mysqli_num_rows($types_result) > 0) {
                        while ($type = mysqli_fetch_assoc($types_result)) {
                            ?>
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8 flex items-center justify-center rounded-full bg-gray-100">
                                            <i class="<?php echo htmlspecialchars($type['icon']); ?> text-blue-600"></i>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($type['name']); ?></div>
                                            <div class="text-sm text-gray-500">Couleur: <?php echo htmlspecialchars($type['color']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        <?php echo $type['description'] ? htmlspecialchars(substr($type['description'], 0, 80)) . (strlen($type['description']) > 80 ? '...' : '') : '<em class="text-gray-400">Aucune description</em>'; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?php echo $type['display_order']; ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <?php if ($type['activity_count'] > 0): ?>
                                            <a href="activities.php?type=<?php echo $type['id']; ?>" class="text-blue-600 hover:text-blue-900">
                                                <?php echo $type['activity_count']; ?> activité<?php echo $type['activity_count'] > 1 ? 's' : ''; ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-gray-400">Aucune activité</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($type['is_active']): ?>
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i> Actif
                                        </span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            <i class="fas fa-times mr-1"></i> Inactif
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="?edit=<?php echo $type['id']; ?>" class="text-blue-600 hover:text-blue-900 mr-3" title="Modifier">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>
                                    <?php if ($type['activity_count'] == 0): ?>
                                        <a href="?delete=<?php echo $type['id']; ?>" 
                                           class="text-red-600 hover:text-red-900" 
                                           title="Supprimer"
                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce type d\'activité?')">
                                            <i class="fas fa-trash-alt"></i> Supprimer
                                        </a>
                                    <?php else: ?>
                                        <span class="text-gray-400" title="Impossible de supprimer - Type utilisé par des activités">
                                            <i class="fas fa-trash-alt"></i> Supprimer
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo '<tr><td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Aucun type d\'activité disponible</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Statistics Card -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-tags text-2xl text-blue-600"></i>
                </div>
                <div class="ml-5">
                    <p class="text-sm font-medium text-gray-500 truncate">Types d'activités</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        <?php
                        $count_query = "SELECT COUNT(*) as total FROM activity_types WHERE is_active = 1";
                        $count_result = mysqli_query($conn, $count_query);
                        $count_data = mysqli_fetch_assoc($count_result);
                        echo $count_data['total'];
                        ?>
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-calendar-alt text-2xl text-green-600"></i>
                </div>
                <div class="ml-5">
                    <p class="text-sm font-medium text-gray-500 truncate">Total activités</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        <?php
                        $activities_count_query = "SELECT COUNT(*) as total FROM activities WHERE is_active = 1";
                        $activities_count_result = mysqli_query($conn, $activities_count_query);
                        $activities_count_data = mysqli_fetch_assoc($activities_count_result);
                        echo $activities_count_data['total'];
                        ?>
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-clock text-2xl text-yellow-600"></i>
                </div>
                <div class="ml-5">
                    <p class="text-sm font-medium text-gray-500 truncate">Activités à venir</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        <?php
                        $upcoming_query = "SELECT COUNT(*) as total FROM activities WHERE status = 'upcoming' AND is_active = 1";
                        $upcoming_result = mysqli_query($conn, $upcoming_query);
                        $upcoming_data = mysqli_fetch_assoc($upcoming_result);
                        echo $upcoming_data['total'];
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const showAddFormBtn = document.getElementById('showAddForm');
        const cancelFormBtn = document.getElementById('cancelForm');
        const typeForm = document.getElementById('typeForm');
        const iconSelect = document.getElementById('icon');
        const iconPreview = document.getElementById('iconPreview');
        const colorSelect = document.getElementById('color');
        const colorPreview = document.getElementById('colorPreview');
        
        // Show/Hide form
        if (showAddFormBtn) {
            showAddFormBtn.addEventListener('click', function() {
                typeForm.classList.remove('hidden');
                // Reset form if it was used for editing
                const form = typeForm.querySelector('form');
                if (form.querySelector('input[name="type_id"]')) {
                    form.reset();
                    form.action = '';
                    const typeIdInput = form.querySelector('input[name="type_id"]');
                    if (typeIdInput) {
                        typeIdInput.remove();
                    }
                    form.querySelector('button[type="submit"]').name = 'add_type';
                    form.querySelector('button[type="submit"]').textContent = 'Ajouter';
                    typeForm.querySelector('h2').textContent = 'Ajouter un nouveau type d\'activité';
                    // Reset previews
                    iconPreview.className = 'fas fa-calendar mr-2';
                    colorPreview.className = 'w-4 h-4 rounded mr-2 bg-blue-600';
                }
            });
        }
        
        if (cancelFormBtn) {
            cancelFormBtn.addEventListener('click', function() {
                typeForm.classList.add('hidden');
                // Reset form
                typeForm.querySelector('form').reset();
            });
        }
        
        // Icon preview
        if (iconSelect && iconPreview) {
            iconSelect.addEventListener('change', function() {
                iconPreview.className = this.value + ' mr-2';
            });
        }
        
        // Color preview
        if (colorSelect && colorPreview) {
            const colorMap = {
                'primary-600': 'bg-blue-600',
                'blue-600': 'bg-blue-600',
                'green-600': 'bg-green-600',
                'red-600': 'bg-red-600',
                'yellow-600': 'bg-yellow-600',
                'purple-600': 'bg-purple-600',
                'pink-600': 'bg-pink-600',
                'indigo-600': 'bg-indigo-600',
                'gray-600': 'bg-gray-600',
                'teal-600': 'bg-teal-600'
            };
            
            colorSelect.addEventListener('change', function() {
                const colorClass = colorMap[this.value] || 'bg-blue-600';
                colorPreview.className = 'w-4 h-4 rounded mr-2 ' + colorClass;
            });
        }
    });
</script>

<?php
// Include footer
include_once 'includes/footer.php';
?>