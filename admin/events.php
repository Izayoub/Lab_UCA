<?php
// Include configuration file
require_once '../includes/config.php';

// Require login
require_login();

// Set page-specific variables
$page_title = "Gestion des événements";
$active_page = "events";

// Process form submissions
$success_message = '';
$error_message = '';

// Handle adding new event
if (isset($_POST['add_event'])) {
    $title = sanitize($_POST['title']);
    $description = sanitize($_POST['description']);
    $event_date = $_POST['event_date'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    $insert_query = "INSERT INTO events (title, description, event_date, is_active) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($stmt, "sssi", $title, $description, $event_date, $is_active);
    
    if (mysqli_stmt_execute($stmt)) {
        $success_message = "Nouvel événement ajouté avec succès.";
    } else {
        $error_message = "Erreur lors de l'ajout de l'événement: " . mysqli_error($conn);
    }
}

// Handle updating event
if (isset($_POST['update_event'])) {
    $event_id = $_POST['event_id'];
    $title = sanitize($_POST['title']);
    $description = sanitize($_POST['description']);
    $event_date = $_POST['event_date'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    $update_query = "UPDATE events SET title = ?, description = ?, event_date = ?, is_active = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "sssii", $title, $description, $event_date, $is_active, $event_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $success_message = "Événement mis à jour avec succès.";
    } else {
        $error_message = "Erreur lors de la mise à jour de l'événement: " . mysqli_error($conn);
    }
}

// Handle deleting event
if (isset($_GET['delete'])) {
    $event_id = intval($_GET['delete']);
    
    $delete_query = "DELETE FROM events WHERE id = ?";
    $stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($stmt, "i", $event_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $success_message = "Événement supprimé avec succès.";
    } else {
        $error_message = "Erreur lors de la suppression de l'événement: " . mysqli_error($conn);
    }
}

// Get event for editing if ID is provided
$edit_event = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $edit_query = "SELECT * FROM events WHERE id = ?";
    $stmt = mysqli_prepare($conn, $edit_query);
    mysqli_stmt_bind_param($stmt, "i", $edit_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $edit_event = mysqli_fetch_assoc($result);
    }
}

// Include header
include_once 'includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Gestion des événements</h1>
        <button id="showAddForm" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-plus mr-2"></i> Ajouter un événement
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
    
    <!-- Add/Edit Event Form -->
    <div id="eventForm" class="bg-white rounded-lg shadow-md p-6 mb-8 <?php echo ($edit_event || isset($_POST['add_event']) || isset($_POST['update_event'])) ? '' : 'hidden'; ?>">
        <h2 class="text-xl font-bold mb-4">
            <?php echo $edit_event ? 'Modifier l\'événement' : 'Ajouter un nouvel événement'; ?>
        </h2>
        
        <form method="POST" action="">
            <?php if ($edit_event): ?>
                <input type="hidden" name="event_id" value="<?php echo $edit_event['id']; ?>">
            <?php endif; ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Titre *</label>
                    <input type="text" id="title" name="title" value="<?php echo $edit_event ? $edit_event['title'] : ''; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
                </div>
                
                <div>
                    <label for="event_date" class="block text-sm font-medium text-gray-700 mb-1">Date *</label>
                    <input type="date" id="event_date" name="event_date" value="<?php echo $edit_event ? $edit_event['event_date'] : date('Y-m-d'); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
                </div>
            </div>
            
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                <textarea id="description" name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required><?php echo $edit_event ? $edit_event['description'] : ''; ?></textarea>
            </div>
            
            <div class="mb-6">
                <div class="flex items-center">
                    <input type="checkbox" id="is_active" name="is_active" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded" <?php echo (!$edit_event || $edit_event['is_active']) ? 'checked' : ''; ?>>
                    <label for="is_active" class="ml-2 block text-sm text-gray-700">Actif</label>
                </div>
            </div>
            
            <div class="flex justify-end space-x-4">
                <button type="button" id="cancelForm" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
                    Annuler
                </button>
                <button type="submit" name="<?php echo $edit_event ? 'update_event' : 'add_event'; ?>" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                    <?php echo $edit_event ? 'Mettre à jour' : 'Ajouter'; ?>
                </button>
            </div>
        </form>
    </div>
    
    <!-- Events List -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                    $events_query = "SELECT * FROM events ORDER BY event_date DESC";
                    $events_result = mysqli_query($conn, $events_query);
                    
                    if ($events_result && mysqli_num_rows($events_result) > 0) {
                        while ($event = mysqli_fetch_assoc($events_result)) {
                            $event_date = new DateTime($event['event_date']);
                            ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?php echo $event['title']; ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500"><?php echo $event_date->format('d/m/Y'); ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-500"><?php echo substr($event['description'], 0, 100) . (strlen($event['description']) > 100 ? '...' : ''); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($event['is_active']): ?>
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Actif</span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Inactif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="?edit=<?php echo $event['id']; ?>" class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>
                                    <a href="?delete=<?php echo $event['id']; ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet événement?')">
                                        <i class="fas fa-trash-alt"></i> Supprimer
                                    </a>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo '<tr><td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Aucun événement disponible</td></tr>';
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
        const eventForm = document.getElementById('eventForm');
        
        if (showAddFormBtn) {
            showAddFormBtn.addEventListener('click', function() {
                eventForm.classList.remove('hidden');
                // Reset form if it was used for editing
                const form = eventForm.querySelector('form');
                if (form.querySelector('input[name="event_id"]')) {
                    form.reset();
                    form.action = '';
                    form.querySelector('input[name="event_id"]').remove();
                    form.querySelector('button[type="submit"]').name = 'add_event';
                    form.querySelector('button[type="submit"]').textContent = 'Ajouter';
                    eventForm.querySelector('h2').textContent = 'Ajouter un nouvel événement';
                }
            });
        }
        
        if (cancelFormBtn) {
            cancelFormBtn.addEventListener('click', function() {
                eventForm.classList.add('hidden');
                // Reset form
                eventForm.querySelector('form').reset();
            });
        }
    });
</script>

<?php
// Include footer
include_once 'includes/footer.php';
?>
