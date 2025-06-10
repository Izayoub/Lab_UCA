<?php
// Include configuration file
require_once '../includes/config.php';

// Require login
require_login();

// Set page-specific variables
$page_title = "Gestion des activités";
$active_page = "activities";

// Process form submissions
$success_message = '';
$error_message = '';

// Handle adding new activity
if (isset($_POST['add_activity'])) {
    $type_id = intval($_POST['type_id']);
    $title = sanitize($_POST['title']);
    $description = sanitize($_POST['description']);
    $content = sanitize($_POST['content']);
    $start_date = $_POST['start_date'] ?: null;
    $end_date = $_POST['end_date'] ?: null;
    $location = sanitize($_POST['location']);
    $organizer = sanitize($_POST['organizer']);
    $target_audience = sanitize($_POST['target_audience']);
    $registration_required = isset($_POST['registration_required']) ? 1 : 0;
    $registration_link = sanitize($_POST['registration_link']);
    $external_link = sanitize($_POST['external_link']);
    $status = $_POST['status'];
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    $insert_query = "INSERT INTO activities (type_id, title, description, content, start_date, end_date, location, organizer, target_audience, registration_required, registration_link, external_link, status, is_featured, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($stmt, "issssssssisssii", $type_id, $title, $description, $content, $start_date, $end_date, $location, $organizer, $target_audience, $registration_required, $registration_link, $external_link, $status, $is_featured, $is_active);
    
    if (mysqli_stmt_execute($stmt)) {
        $success_message = "Nouvelle activité ajoutée avec succès.";
    } else {
        $error_message = "Erreur lors de l'ajout de l'activité: " . mysqli_error($conn);
    }
}

// Handle updating activity
if (isset($_POST['update_activity'])) {
    $activity_id = intval($_POST['activity_id']);
    $type_id = intval($_POST['type_id']);
    $title = sanitize($_POST['title']);
    $description = sanitize($_POST['description']);
    $content = sanitize($_POST['content']);
    $start_date = $_POST['start_date'] ?: null;
    $end_date = $_POST['end_date'] ?: null;
    $location = sanitize($_POST['location']);
    $organizer = sanitize($_POST['organizer']);
    $target_audience = sanitize($_POST['target_audience']);
    $registration_required = isset($_POST['registration_required']) ? 1 : 0;
    $registration_link = sanitize($_POST['registration_link']);
    $external_link = sanitize($_POST['external_link']);
    $status = $_POST['status'];
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    $update_query = "UPDATE activities SET type_id = ?, title = ?, description = ?, content = ?, start_date = ?, end_date = ?, location = ?, organizer = ?, target_audience = ?, registration_required = ?, registration_link = ?, external_link = ?, status = ?, is_featured = ?, is_active = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "issssssssisssiii", $type_id, $title, $description, $content, $start_date, $end_date, $location, $organizer, $target_audience, $registration_required, $registration_link, $external_link, $status, $is_featured, $is_active, $activity_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $success_message = "Activité mise à jour avec succès.";
    } else {
        $error_message = "Erreur lors de la mise à jour de l'activité: " . mysqli_error($conn);
    }
}

// Handle deleting activity
if (isset($_GET['delete'])) {
    $activity_id = intval($_GET['delete']);
    
    $delete_query = "DELETE FROM activities WHERE id = ?";
    $stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($stmt, "i", $activity_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $success_message = "Activité supprimée avec succès.";
    } else {
        $error_message = "Erreur lors de la suppression de l'activité: " . mysqli_error($conn);
    }
}

// Get activity for editing if ID is provided
$edit_activity = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $edit_query = "SELECT * FROM activities WHERE id = ?";
    $stmt = mysqli_prepare($conn, $edit_query);
    mysqli_stmt_bind_param($stmt, "i", $edit_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $edit_activity = mysqli_fetch_assoc($result);
    }
}

// Get activity types for dropdown
$types_query = "SELECT * FROM activity_types WHERE is_active = 1 ORDER BY display_order, name";
$types_result = mysqli_query($conn, $types_query);

// Include header
include_once 'includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Gestion des activités</h1>
        <div class="flex space-x-2">
            <a href="activity_types.php" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-tags mr-2"></i> Types d'activités
            </a>
            <button id="showAddForm" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-plus mr-2"></i> Ajouter une activité
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
    
    <!-- Add/Edit Activity Form -->
    <div id="activityForm" class="bg-white rounded-lg shadow-md p-6 mb-8 <?php echo ($edit_activity || isset($_POST['add_activity']) || isset($_POST['update_activity'])) ? '' : 'hidden'; ?>">
        <h2 class="text-xl font-bold mb-4">
            <?php echo $edit_activity ? 'Modifier l\'activité' : 'Ajouter une nouvelle activité'; ?>
        </h2>
        
        <form method="POST" action="">
            <?php if ($edit_activity): ?>
                <input type="hidden" name="activity_id" value="<?php echo $edit_activity['id']; ?>">
            <?php endif; ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="type_id" class="block text-sm font-medium text-gray-700 mb-1">Type d'activité *</label>
                    <select id="type_id" name="type_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        <option value="">Sélectionner un type</option>
                        <?php
                        mysqli_data_seek($types_result, 0);
                        while ($type = mysqli_fetch_assoc($types_result)) {
                            $selected = ($edit_activity && $edit_activity['type_id'] == $type['id']) ? 'selected' : '';
                            echo "<option value='{$type['id']}' {$selected}>{$type['name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut *</label>
                    <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        <option value="upcoming" <?php echo ($edit_activity && $edit_activity['status'] == 'upcoming') ? 'selected' : ''; ?>>À venir</option>
                        <option value="ongoing" <?php echo ($edit_activity && $edit_activity['status'] == 'ongoing') ? 'selected' : ''; ?>>En cours</option>
                        <option value="completed" <?php echo ($edit_activity && $edit_activity['status'] == 'completed') ? 'selected' : ''; ?>>Terminé</option>
                        <option value="cancelled" <?php echo ($edit_activity && $edit_activity['status'] == 'cancelled') ? 'selected' : ''; ?>>Annulé</option>
                    </select>
                </div>
            </div>
            
            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Titre *</label>
                <input type="text" id="title" name="title" value="<?php echo $edit_activity ? htmlspecialchars($edit_activity['title']) : ''; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
            </div>
            
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                <textarea id="description" name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required><?php echo $edit_activity ? htmlspecialchars($edit_activity['description']) : ''; ?></textarea>
            </div>
            
            <div class="mb-6">
                <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Contenu détaillé</label>
                <textarea id="content" name="content" rows="5" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"><?php echo $edit_activity ? htmlspecialchars($edit_activity['content']) : ''; ?></textarea>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Date de début</label>
                    <input type="date" id="start_date" name="start_date" value="<?php echo $edit_activity ? $edit_activity['start_date'] : ''; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Date de fin</label>
                    <input type="date" id="end_date" name="end_date" value="<?php echo $edit_activity ? $edit_activity['end_date'] : ''; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Lieu</label>
                    <input type="text" id="location" name="location" value="<?php echo $edit_activity ? htmlspecialchars($edit_activity['location']) : ''; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                
                <div>
                    <label for="organizer" class="block text-sm font-medium text-gray-700 mb-1">Organisateur</label>
                    <input type="text" id="organizer" name="organizer" value="<?php echo $edit_activity ? htmlspecialchars($edit_activity['organizer']) : ''; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
            </div>
            
            <div class="mb-6">
                <label for="target_audience" class="block text-sm font-medium text-gray-700 mb-1">Public cible</label>
                <input type="text" id="target_audience" name="target_audience" value="<?php echo $edit_activity ? htmlspecialchars($edit_activity['target_audience']) : ''; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="registration_link" class="block text-sm font-medium text-gray-700 mb-1">Lien d'inscription</label>
                    <input type="url" id="registration_link" name="registration_link" value="<?php echo $edit_activity ? htmlspecialchars($edit_activity['registration_link']) : ''; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                
                <div>
                    <label for="external_link" class="block text-sm font-medium text-gray-700 mb-1">Lien externe</label>
                    <input type="url" id="external_link" name="external_link" value="<?php echo $edit_activity ? htmlspecialchars($edit_activity['external_link']) : ''; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
            </div>
            
            <div class="flex flex-wrap gap-4 mb-6">
                <div class="flex items-center">
                    <input type="checkbox" id="registration_required" name="registration_required" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded" <?php echo ($edit_activity && $edit_activity['registration_required']) ? 'checked' : ''; ?>>
                    <label for="registration_required" class="ml-2 block text-sm text-gray-700">Inscription requise</label>
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" id="is_featured" name="is_featured" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded" <?php echo ($edit_activity && $edit_activity['is_featured']) ? 'checked' : ''; ?>>
                    <label for="is_featured" class="ml-2 block text-sm text-gray-700">Mise en avant</label>
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" id="is_active" name="is_active" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded" <?php echo (!$edit_activity || $edit_activity['is_active']) ? 'checked' : ''; ?>>
                    <label for="is_active" class="ml-2 block text-sm text-gray-700">Actif</label>
                </div>
            </div>
            
            <div class="flex justify-end space-x-4">
                <button type="button" id="cancelForm" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
                    Annuler
                </button>
                <button type="submit" name="<?php echo $edit_activity ? 'update_activity' : 'add_activity'; ?>" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                    <?php echo $edit_activity ? 'Mettre à jour' : 'Ajouter'; ?>
                </button>
            </div>
        </form>
    </div>
    
    <!-- Activities List -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">État</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                    $activities_query = "SELECT a.*, at.name as type_name FROM activities a 
                                       LEFT JOIN activity_types at ON a.type_id = at.id 
                                       ORDER BY a.start_date DESC, a.created_at DESC";
                    $activities_result = mysqli_query($conn, $activities_query);
                    
                    if ($activities_result && mysqli_num_rows($activities_result) > 0) {
                        while ($activity = mysqli_fetch_assoc($activities_result)) {
                            $start_date = $activity['start_date'] ? new DateTime($activity['start_date']) : null;
                            ?>
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($activity['title']); ?></div>
                                    <div class="text-sm text-gray-500"><?php echo htmlspecialchars(substr($activity['description'], 0, 60)) . (strlen($activity['description']) > 60 ? '...' : ''); ?></div>
                                    <?php if ($activity['is_featured']): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 mt-1">
                                            <i class="fas fa-star mr-1"></i> Mise en avant
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?php echo htmlspecialchars($activity['type_name'] ?: 'Non défini'); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">
                                        <?php echo $start_date ? $start_date->format('d/m/Y') : 'Non définie'; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php
                                    $status_classes = [
                                        'upcoming' => 'bg-blue-100 text-blue-800',
                                        'ongoing' => 'bg-green-100 text-green-800',
                                        'completed' => 'bg-gray-100 text-gray-800',
                                        'cancelled' => 'bg-red-100 text-red-800'
                                    ];
                                    $status_labels = [
                                        'upcoming' => 'À venir',
                                        'ongoing' => 'En cours',
                                        'completed' => 'Terminé',
                                        'cancelled' => 'Annulé'
                                    ];
                                    $status_class = $status_classes[$activity['status']] ?? 'bg-gray-100 text-gray-800';
                                    $status_label = $status_labels[$activity['status']] ?? $activity['status'];
                                    ?>
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $status_class; ?>">
                                        <?php echo $status_label; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($activity['is_active']): ?>
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Actif</span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Inactif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="?edit=<?php echo $activity['id']; ?>" class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>
                                    <a href="?delete=<?php echo $activity['id']; ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette activité?')">
                                        <i class="fas fa-trash-alt"></i> Supprimer
                                    </a>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo '<tr><td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Aucune activité disponible</td></tr>';
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
        const activityForm = document.getElementById('activityForm');
        
        if (showAddFormBtn) {
            showAddFormBtn.addEventListener('click', function() {
                activityForm.classList.remove('hidden');
                // Reset form if it was used for editing
                const form = activityForm.querySelector('form');
                if (form.querySelector('input[name="activity_id"]')) {
                    form.reset();
                    form.action = '';
                    const activityIdInput = form.querySelector('input[name="activity_id"]');
                    if (activityIdInput) {
                        activityIdInput.remove();
                    }
                    form.querySelector('button[type="submit"]').name = 'add_activity';
                    form.querySelector('button[type="submit"]').textContent = 'Ajouter';
                    activityForm.querySelector('h2').textContent = 'Ajouter une nouvelle activité';
                }
            });
        }
        
        if (cancelFormBtn) {
            cancelFormBtn.addEventListener('click', function() {
                activityForm.classList.add('hidden');
                // Reset form
                activityForm.querySelector('form').reset();
            });
        }
    });
</script>

<?php
// Include footer
include_once 'includes/footer.php';
?>