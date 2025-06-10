<?php
// Include configuration file
require_once '../includes/config.php';

// Require login
require_login();

// Set page-specific variables
$page_title = "Gestion des publications";
$active_page = "publications";

// Process form submissions
$success_message = '';
$error_message = '';

// Handle adding new publication
if (isset($_POST['add_publication'])) {
    $title = sanitize($_POST['title']);
    $authors = sanitize($_POST['authors']);
    $publication_date = $_POST['publication_date'];
    $journal = sanitize($_POST['journal']);
    $abstract = sanitize($_POST['abstract']);
    $link = sanitize($_POST['link']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    $insert_query = "INSERT INTO publications (title, authors, publication_date, journal, abstract, link, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($stmt, "ssssssi", $title, $authors, $publication_date, $journal, $abstract, $link, $is_active);
    
    if (mysqli_stmt_execute($stmt)) {
        $success_message = "Nouvelle publication ajoutée avec succès.";
    } else {
        $error_message = "Erreur lors de l'ajout de la publication: " . mysqli_error($conn);
    }
}

// Handle updating publication
if (isset($_POST['update_publication'])) {
    $publication_id = $_POST['publication_id'];
    $title = sanitize($_POST['title']);
    $authors = sanitize($_POST['authors']);
    $publication_date = $_POST['publication_date'];
    $journal = sanitize($_POST['journal']);
    $abstract = sanitize($_POST['abstract']);
    $link = sanitize($_POST['link']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    $update_query = "UPDATE publications SET title = ?, authors = ?, publication_date = ?, journal = ?, abstract = ?, link = ?, is_active = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "ssssssii", $title, $authors, $publication_date, $journal, $abstract, $link, $is_active, $publication_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $success_message = "Publication mise à jour avec succès.";
    } else {
        $error_message = "Erreur lors de la mise à jour de la publication: " . mysqli_error($conn);
    }
}

// Handle deleting publication
if (isset($_GET['delete'])) {
    $publication_id = intval($_GET['delete']);
    
    $delete_query = "DELETE FROM publications WHERE id = ?";
    $stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($stmt, "i", $publication_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $success_message = "Publication supprimée avec succès.";
    } else {
        $error_message = "Erreur lors de la suppression de la publication: " . mysqli_error($conn);
    }
}

// Get publication for editing if ID is provided
$edit_publication = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $edit_query = "SELECT * FROM publications WHERE id = ?";
    $stmt = mysqli_prepare($conn, $edit_query);
    mysqli_stmt_bind_param($stmt, "i", $edit_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $edit_publication = mysqli_fetch_assoc($result);
    }
}

// Include header
include_once 'includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Gestion des publications</h1>
        <button id="showAddForm" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-plus mr-2"></i> Ajouter une publication
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
    
    <!-- Add/Edit Publication Form -->
    <div id="publicationForm" class="bg-white rounded-lg shadow-md p-6 mb-8 <?php echo ($edit_publication || isset($_POST['add_publication']) || isset($_POST['update_publication'])) ? '' : 'hidden'; ?>">
        <h2 class="text-xl font-bold mb-4">
            <?php echo $edit_publication ? 'Modifier la publication' : 'Ajouter une nouvelle publication'; ?>
        </h2>
        
        <form method="POST" action="">
            <?php if ($edit_publication): ?>
                <input type="hidden" name="publication_id" value="<?php echo $edit_publication['id']; ?>">
            <?php endif; ?>
            
            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Titre *</label>
                <input type="text" id="title" name="title" value="<?php echo $edit_publication ? $edit_publication['title'] : ''; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500" required>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="authors" class="block text-sm font-medium text-gray-700 mb-1">Auteurs *</label>
                    <input type="text" id="authors" name="authors" value="<?php echo $edit_publication ? $edit_publication['authors'] : ''; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                </div>
                
                <div>
                    <label for="publication_date" class="block text-sm font-medium text-gray-700 mb-1">Date de publication *</label>
                    <input type="date" id="publication_date" name="publication_date" value="<?php echo $edit_publication ? $edit_publication['publication_date'] : date('Y-m-d'); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                </div>
            </div>
            
            <div class="mb-6">
                <label for="journal" class="block text-sm font-medium text-gray-700 mb-1">Journal/Revue</label>
                <input type="text" id="journal" name="journal" value="<?php echo $edit_publication ? $edit_publication['journal'] : ''; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            
            <div class="mb-6">
                <label for="abstract" class="block text-sm font-medium text-gray-700 mb-1">Résumé</label>
                <textarea id="abstract" name="abstract" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"><?php echo $edit_publication ? $edit_publication['abstract'] : ''; ?></textarea>
            </div>
            
            <div class="mb-6">
                <label for="link" class="block text-sm font-medium text-gray-700 mb-1">Lien vers la publication</label>
                <input type="url" id="link" name="link" value="<?php echo $edit_publication ? $edit_publication['link'] : ''; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            
            <div class="mb-6">
                <div class="flex items-center">
                    <input type="checkbox" id="is_active" name="is_active" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded" <?php echo (!$edit_publication || $edit_publication['is_active']) ? 'checked' : ''; ?>>
                    <label for="is_active" class="ml-2 block text-sm text-gray-700">Actif</label>
                </div>
            </div>
            
            <div class="flex justify-end space-x-4">
                <button type="button" id="cancelForm" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
                    Annuler
                </button>
                <button type="submit" name="<?php echo $edit_publication ? 'update_publication' : 'add_publication'; ?>" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg">
                    <?php echo $edit_publication ? 'Mettre à jour' : 'Ajouter'; ?>
                </button>
            </div>
        </form>
    </div>
    
    <!-- Publications List -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Auteurs</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Journal</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                    $publications_query = "SELECT * FROM publications ORDER BY publication_date DESC";
                    $publications_result = mysqli_query($conn, $publications_query);
                    
                    if ($publications_result && mysqli_num_rows($publications_result) > 0) {
                        while ($publication = mysqli_fetch_assoc($publications_result)) {
                            $pub_date = new DateTime($publication['publication_date']);
                            ?>
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900"><?php echo $publication['title']; ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-500"><?php echo $publication['authors']; ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500"><?php echo $pub_date->format('d/m/Y'); ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-500"><?php echo $publication['journal']; ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($publication['is_active']): ?>
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Actif</span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Inactif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="?edit=<?php echo $publication['id']; ?>" class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>
                                    <a href="?delete=<?php echo $publication['id']; ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette publication?')">
                                        <i class="fas fa-trash-alt"></i> Supprimer
                                    </a>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo '<tr><td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Aucune publication disponible</td></tr>';
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
        const publicationForm = document.getElementById('publicationForm');
        
        if (showAddFormBtn) {
            showAddFormBtn.addEventListener('click', function() {
                publicationForm.classList.remove('hidden');
                // Reset form if it was used for editing
                const form = publicationForm.querySelector('form');
                if (form.querySelector('input[name="publication_id"]')) {
                    form.reset();
                    form.action = '';
                    form.querySelector('input[name="publication_id"]').remove();
                    form.querySelector('button[type="submit"]').name = 'add_publication';
                    form.querySelector('button[type="submit"]').textContent = 'Ajouter';
                    publicationForm.querySelector('h2').textContent = 'Ajouter une nouvelle publication';
                }
            });
        }
        
        if (cancelFormBtn) {
            cancelFormBtn.addEventListener('click', function() {
                publicationForm.classList.add('hidden');
                // Reset form
                publicationForm.querySelector('form').reset();
            });
        }
    });
</script>

<?php
// Include footer
include_once 'includes/footer.php';
?>
