<?php
// Script om sites te beheren voor multi-site analytics
require_once("../../system/database.php");

try {
    echo "<h2>Site Management voor Multi-Site Analytics</h2>";
    
    // Check if sites table exists
    $stmt = $pdo->prepare("SHOW TABLES LIKE 'sites'");
    $stmt->execute();
    
    if ($stmt->rowCount() == 0) {
        echo "<p style='color: red;'>❌ Sites tabel bestaat niet. Voer eerst het update script uit.</p>";
        echo "<p><a href='update_analytics_multi_site.php' class='btn btn-primary'>Database Update Uitvoeren</a></p>";
        exit;
    }
    
    // Handle form submissions
    if ($_POST) {
        if (isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'add':
                    $domain = trim($_POST['domain']);
                    $name = trim($_POST['name']);
                    $description = trim($_POST['description']);
                    
                    if (empty($domain) || empty($name)) {
                        echo "<p style='color: red;'>❌ Domain en naam zijn verplicht</p>";
                    } else {
                        $stmt = $pdo->prepare("INSERT INTO sites (domain, name, description) VALUES (?, ?, ?)");
                        $stmt->execute([$domain, $name, $description]);
                        echo "<p style='color: green;'>✓ Site toegevoegd: " . htmlspecialchars($name) . "</p>";
                    }
                    break;
                    
                case 'delete':
                    $siteId = (int)$_POST['site_id'];
                    if ($siteId > 1) { // Don't delete default site
                        $stmt = $pdo->prepare("DELETE FROM sites WHERE id = ?");
                        $stmt->execute([$siteId]);
                        echo "<p style='color: green;'>✓ Site verwijderd</p>";
                    } else {
                        echo "<p style='color: red;'>❌ Kan default site niet verwijderen</p>";
                    }
                    break;
                    
                case 'update':
                    $siteId = (int)$_POST['site_id'];
                    $domain = trim($_POST['domain']);
                    $name = trim($_POST['name']);
                    $description = trim($_POST['description']);
                    $status = $_POST['status'];
                    
                    if (empty($domain) || empty($name)) {
                        echo "<p style='color: red;'>❌ Domain en naam zijn verplicht</p>";
                    } else {
                        $stmt = $pdo->prepare("UPDATE sites SET domain = ?, name = ?, description = ?, status = ? WHERE id = ?");
                        $stmt->execute([$domain, $name, $description, $status, $siteId]);
                        echo "<p style='color: green;'>✓ Site bijgewerkt: " . htmlspecialchars($name) . "</p>";
                    }
                    break;
            }
        }
    }
    
    // Get all sites
    $stmt = $pdo->prepare("SELECT * FROM sites ORDER BY name");
    $stmt->execute();
    $sites = $stmt->fetchAll();
    
    echo "<h3>Huidige Sites:</h3>";
    
    if (empty($sites)) {
        echo "<p>Geen sites gevonden.</p>";
    } else {
        echo "<table class='table table-striped'>";
        echo "<thead><tr><th>ID</th><th>Domain</th><th>Naam</th><th>Beschrijving</th><th>Status</th><th>Acties</th></tr></thead>";
        echo "<tbody>";
        
        foreach ($sites as $site) {
            echo "<tr>";
            echo "<td>" . $site['id'] . "</td>";
            echo "<td>" . htmlspecialchars($site['domain']) . "</td>";
            echo "<td>" . htmlspecialchars($site['name']) . "</td>";
            echo "<td>" . htmlspecialchars($site['description'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars($site['status']) . "</td>";
            echo "<td>";
            if ($site['id'] > 1) {
                echo "<button onclick='editSite(" . $site['id'] . ")' class='btn btn-sm btn-primary'>Bewerken</button> ";
                echo "<button onclick='deleteSite(" . $site['id'] . ")' class='btn btn-sm btn-danger'>Verwijderen</button>";
            } else {
                echo "<span class='text-muted'>Default site</span>";
            }
            echo "</td>";
            echo "</tr>";
        }
        
        echo "</tbody></table>";
    }
    
    // Add new site form
    echo "<h3>Nieuwe Site Toevoegen:</h3>";
    echo "<form method='POST' class='mb-4'>";
    echo "<input type='hidden' name='action' value='add'>";
    echo "<div class='row'>";
    echo "<div class='col-md-4'>";
    echo "<div class='form-group'>";
    echo "<label for='domain'>Domain:</label>";
    echo "<input type='text' name='domain' id='domain' class='form-control' placeholder='example.com' required>";
    echo "</div>";
    echo "</div>";
    echo "<div class='col-md-4'>";
    echo "<div class='form-group'>";
    echo "<label for='name'>Naam:</label>";
    echo "<input type='text' name='name' id='name' class='form-control' placeholder='Site Naam' required>";
    echo "</div>";
    echo "</div>";
    echo "<div class='col-md-4'>";
    echo "<div class='form-group'>";
    echo "<label for='description'>Beschrijving:</label>";
    echo "<input type='text' name='description' id='description' class='form-control' placeholder='Optionele beschrijving'>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "<button type='submit' class='btn btn-success'>Site Toevoegen</button>";
    echo "</form>";
    
    // Edit site form (hidden by default)
    echo "<div id='editForm' style='display: none;'>";
    echo "<h3>Site Bewerken:</h3>";
    echo "<form method='POST' class='mb-4'>";
    echo "<input type='hidden' name='action' value='update'>";
    echo "<input type='hidden' name='site_id' id='edit_site_id'>";
    echo "<div class='row'>";
    echo "<div class='col-md-3'>";
    echo "<div class='form-group'>";
    echo "<label for='edit_domain'>Domain:</label>";
    echo "<input type='text' name='domain' id='edit_domain' class='form-control' required>";
    echo "</div>";
    echo "</div>";
    echo "<div class='col-md-3'>";
    echo "<div class='form-group'>";
    echo "<label for='edit_name'>Naam:</label>";
    echo "<input type='text' name='name' id='edit_name' class='form-control' required>";
    echo "</div>";
    echo "</div>";
    echo "<div class='col-md-3'>";
    echo "<div class='form-group'>";
    echo "<label for='edit_description'>Beschrijving:</label>";
    echo "<input type='text' name='description' id='edit_description' class='form-control'>";
    echo "</div>";
    echo "</div>";
    echo "<div class='col-md-3'>";
    echo "<div class='form-group'>";
    echo "<label for='edit_status'>Status:</label>";
    echo "<select name='status' id='edit_status' class='form-control'>";
    echo "<option value='active'>Active</option>";
    echo "<option value='inactive'>Inactive</option>";
    echo "</select>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "<button type='submit' class='btn btn-primary'>Site Bijwerken</button>";
    echo "<button type='button' onclick='cancelEdit()' class='btn btn-secondary'>Annuleren</button>";
    echo "</form>";
    echo "</div>";
    
    // JavaScript for edit functionality
    echo "<script>";
    echo "function editSite(siteId) {";
    echo "  // Get site data and populate form";
    echo "  var row = event.target.closest('tr');";
    echo "  var cells = row.cells;";
    echo "  document.getElementById('edit_site_id').value = cells[0].textContent;";
    echo "  document.getElementById('edit_domain').value = cells[1].textContent;";
    echo "  document.getElementById('edit_name').value = cells[2].textContent;";
    echo "  document.getElementById('edit_description').value = cells[3].textContent;";
    echo "  document.getElementById('edit_status').value = cells[4].textContent;";
    echo "  document.getElementById('editForm').style.display = 'block';";
    echo "}";
    echo "function cancelEdit() {";
    echo "  document.getElementById('editForm').style.display = 'none';";
    echo "}";
    echo "function deleteSite(siteId) {";
    echo "  if (confirm('Weet je zeker dat je deze site wilt verwijderen?')) {";
    echo "    var form = document.createElement('form');";
    echo "    form.method = 'POST';";
    echo "    form.innerHTML = '<input type=\"hidden\" name=\"action\" value=\"delete\"><input type=\"hidden\" name=\"site_id\" value=\"' + siteId + '\">';";
    echo "    document.body.appendChild(form);";
    echo "    form.submit();";
    echo "  }";
    echo "}";
    echo "</script>";
    
    echo "<h3>Instructies:</h3>";
    echo "<ul>";
    echo "<li>Voeg sites toe met hun domein naam (bijv. example.com)</li>";
    echo "<li>De analytics data wordt automatisch gefilterd op basis van de geselecteerde site</li>";
    echo "<li>Alleen actieve sites worden getoond in de analytics interface</li>";
    echo "<li>De default site (ID 1) kan niet worden verwijderd</li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<h3>Error:</h3>";
    echo "<p style='color: red;'>" . $e->getMessage() . "</p>";
}
?>
