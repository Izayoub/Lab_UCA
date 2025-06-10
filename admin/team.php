<?php
// Include configuration file
require_once '../includes/config.php';

// Require login
require_login();

// Set page-specific variables
$page_title = "Gestion de l'équipe";
$active_page = "team";

// Process form submissions
$success_message = '';
$error_message = '';

// Handle adding new team member
if (isset($_POST['add_member'])) {
    $name = sanitize($_POST['name']);
    $title = sanitize($_POST['title']);
    $specialization = sanitize($_POST['specialization']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $linkedin = sanitize($_POST['linkedin']);
    $orcid = sanitize($_POST['orcid']);
    $research_interests = sanitize($_POST['research_interests']);
    $category = $_POST['category'];
    $bio = sanitize($_POST['bio']);
    $display_order = intval($_POST['display_order']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    $insert_query = "INSERT INTO team_members (name, title, specialization, email, phone, linkedin, orcid, research_interests, category, bio, display_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($stmt, "ssssssssssii", $name, $title, $specialization, $email, $phone, $linkedin, $orcid, $research_interests, $category, $bio, $display_order, $is_active);
    
    if (mysqli_stmt_execute($stmt)) {
        $success_message = "Nouveau membre de l'équipe ajouté avec succès.";
    } else {
        $error_message = "Erreur lors de l'ajout du membre: " . mysqli_error($conn);
    }
}

// Handle updating team member
if (isset($_POST['update_member'])) {
    $member_id = $_POST['member_id'];
    $name = sanitize($_POST['name']);
    $title = sanitize($_POST['title']);
    $specialization = sanitize($_POST['specialization']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $linkedin = sanitize($_POST['linkedin']);
    $orcid = sanitize($_POST['orcid']);
    $research_interests = sanitize($_POST['research_interests']);
    $category = $_POST['category'];
    $bio = sanitize($_POST['bio']);
    $display_order = intval($_POST['display_order']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    $update_query = "UPDATE team_members SET name = ?, title = ?, specialization = ?, email = ?, phone = ?, linkedin = ?, orcid = ?, research_interests = ?, category = ?, bio = ?, display_order = ?, is_active = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "ssssssssssiij", $name, $title, $specialization, $email, $phone, $linkedin, $orcid, $research_interests, $category, $bio, $display_order, $is_active, $member_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $success_message = "Membre de l'équipe mis à jour avec succès.";
    } else {
        $error_message = "Erreur lors de la mise à jour du membre: " . mysqli_error($conn);
    }
}

// Handle deleting team member
if (isset($_GET['delete'])) {
    $member_id = intval($_GET['delete']);
    
    $delete_query = "DELETE FROM team_members WHERE id = ?";
    $stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($stmt, "i", $member_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $success_message = "Membre de l'équipe supprimé avec succès.";
    } else {
        $error_message = "Erreur lors de la suppression du membre: " . mysqli_error($conn);
    }
}

// Get team member for editing if ID is provided
$edit_member = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $edit_query = "SELECT * FROM team_members WHERE id = ?";
    $stmt = mysqli_prepare($conn, $edit_query);
    mysqli_stmt_bind_param($stmt, "i", $edit_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $edit_member = mysqli_fetch_assoc($result);
    }
}

// Include header
include_once 'includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Gestion de l'équipe</h1>
        <button id="showAddForm" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-plus mr-2"></i> Ajouter un membre
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
    
    <!-- Add/Edit Member Form -->
    <div id="memberForm" class="bg-white rounded-lg shadow-md p-6 mb-8 <?php echo ($edit_member || isset($_POST['add_member']) || isset($_POST['update_member'])) ? '' : 'hidden'; ?>">
        <h2 class="text-xl font-bold mb-4">
            <?php echo $edit_member ? 'Modifier le membre' : 'Ajouter un nouveau membre'; ?>
        </h2>
        
        <form method="POST" action="">
            <?php if ($edit_member): ?>
                <input type="hidden" name="member_id" value="<?php echo $edit_member['id']; ?>">
            <?php endif; ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom complet *</label>
                    <input type="text" id="name" name="name" value="<?php echo $edit_member ? $edit_member['name'] : ''; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
                </div>
                
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Titre/Poste *</label>
                    <input type="text" id="title" name="title" value="<?php echo $edit_member ? $edit_member['title'] : ''; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Catégorie *</label>
                    <select id="category" name="category" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        <option value="direction" <?php echo ($edit_member && $edit_member['category'] == 'direction') ? 'selected' : ''; ?>>Direction</option>
                        <option value="researcher" <?php echo ($edit_member && $edit_member['category'] == 'researcher') ? 'selected' : ''; ?>>Chercheur</option>
                        <option value="phd_student" <?php echo ($edit_member && $edit_member['category'] == 'phd_student') ? 'selected' : ''; ?>>Doctorant</option>
                    </select>
                </div>
                
                <div>
                    <label for="specialization" class="block text-sm font-medium text-gray-700 mb-1">Spécialisation</label>
                    <input type="text" id="specialization" name="specialization" value="<?php echo $edit_member ? $edit_member['specialization'] : ''; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo $edit_member ? $edit_member['email'] : ''; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                    <input type="text" id="phone" name="phone" value="<?php echo $edit_member ? $edit_member['phone'] : ''; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="linkedin" class="block text-sm font-medium text-gray-700 mb-1">LinkedIn</label>
                    <input type="url" id="linkedin" name="linkedin" value="<?php echo $edit_member ? $edit_member['linkedin'] : ''; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                
                <div>
                    <label for="orcid" class="block text-sm font-medium text-gray-700 mb-1">ORCID</label>
                    <input type="text" id="orcid" name="orcid" value="<?php echo $edit_member ? $edit_member['orcid'] : ''; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
            </div>
            
            <div class="mb-6">
                <label for="research_interests" class="block text-sm font-medium text-gray-700 mb-1">Intérêts de recherche</label>
                <textarea id="research_interests" name="research_interests" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"><?php echo $edit_member ? $edit_member['research_interests'] : ''; ?></textarea>
            </div>
            
            <div class="mb-6">
                <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">Biographie</label>
                <textarea id="bio" name="bio" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"><?php echo $edit_member ? $edit_member['bio'] : ''; ?></textarea>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="display_order" class="block text-sm font-medium text-gray-700 mb-1">Ordre d'affichage</label>
                    <input type="number" id="display_order" name="display_order" value="<?php echo $edit_member ? $edit_member['display_order'] : 0; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                
                <div class="flex items-center mt-6">
                    <input type="checkbox" id="is_active" name="is_active" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded" <?php echo (!$edit_member || $edit_member['is_active']) ? 'checked' : ''; ?>>
                    <label for="is_active" class="ml-2 block text-sm text-gray-700">Actif</label>
                </div>
            </div>
            
            <div class="flex justify-end space-x-4">
                <button type="button" id="cancelForm" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
                    Annuler
                </button>
                <button type="submit" name="<?php echo $edit_member ? 'update_member' : 'add_member'; ?>" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                    <?php echo $edit_member ? 'Mettre à jour' : 'Ajouter'; ?>
                </button>
            </div>
        </form>
    </div>
    
    <!-- Team Members List -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catégorie</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Spécialisation</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ordre</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                    $members_query = "SELECT * FROM team_members ORDER BY display_order ASC, name ASC";
                    $members_result = mysqli_query($conn, $members_query);
                    
                    if ($members_result && mysqli_num_rows($members_result) > 0) {
                        while ($member = mysqli_fetch_assoc($members_result)) {
                            $category_labels = [
                                'direction' => 'Direction',
                                'researcher' => 'Chercheur',
                                'phd_student' => 'Doctorant'
                            ];
                            ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?php echo $member['name']; ?></div>
                                    <?php if ($member['email']): ?>
                                        <div class="text-sm text-gray-500"><?php echo $member['email']; ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?php echo $member['title']; ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <?php echo $category_labels[$member['category']] ?? $member['category']; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-500"><?php echo substr($member['specialization'], 0, 50) . (strlen($member['specialization']) > 50 ? '...' : ''); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500"><?php echo $member['display_order']; ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($member['is_active']): ?>
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Actif</span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Inactif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="?edit=<?php echo $member['id']; ?>" class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>
                                    <a href="?delete=<?php echo $member['id']; ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce membre?')">
                                        <i class="fas fa-trash-alt"></i> Supprimer
                                    </a>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo '<tr><td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">Aucun membre disponible</td></tr>';
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
        const memberForm = document.getElementById('memberForm');
        
        if (showAddFormBtn) {
            showAddFormBtn.addEventListener('click', function() {
                memberForm.classList.remove('hidden');
                // Reset form if it was used for editing
                const form = memberForm.querySelector('form');
                if (form.querySelector('input[name="member_id"]')) {
                    form.reset();
                    form.action = '';
                    form.querySelector('input[name="member_id"]').remove();
                    form.querySelector('button[type="submit"]').name = 'add_member';
                    form.querySelector('button[type="submit"]').textContent = 'Ajouter';
                    memberForm.querySelector('h2').textContent = 'Ajouter un nouveau membre';
                }
            });
        }
        
        if (cancelFormBtn) {
            cancelFormBtn.addEventListener('click', function() {
                memberForm.classList.add('hidden');
                // Reset form
                memberForm.querySelector('form').reset();
            });
        }
    });
</script>

<?php
// Include footer
include_once 'includes/footer.php';
?>