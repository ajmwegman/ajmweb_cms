<?php
/**
 * Multi-Language Update Script voor AJMWEB CMS
 * 
 * Dit script voert alle benodigde updates uit om het CMS multi-language te maken
 * 
 * Voer dit script uit via de browser: /update_multilanguage.php
 * Of via command line: php update_multilanguage.php
 */

error_reporting(E_ALL);
ini_set("display_errors", 1);

// Include database connection
require_once("system/database.php");
require_once("src/database.class.php");

$db = new database($pdo);

echo "<h1>AJMWEB CMS Multi-Language Update Script</h1>\n";
echo "<p>Start tijd: " . date('Y-m-d H:i:s') . "</p>\n";

$errors = [];
$success = [];

/**
 * STAP 1: Database Schema Updates
 */
echo "<h2>Stap 1: Database Schema Updates</h2>\n";

// Check if columns already exist
function columnExists($pdo, $table, $column) {
    $stmt = $pdo->prepare("SHOW COLUMNS FROM `{$table}` LIKE '{$column}'");
    $stmt->execute();
    return $stmt->rowCount() > 0;
}

try {
    // Update group_content table
    if (!columnExists($pdo, 'group_content', 'lang_code')) {
        $sql = "ALTER TABLE group_content ADD COLUMN lang_code VARCHAR(5) DEFAULT 'nl' AFTER group_id";
        $pdo->exec($sql);
        $success[] = "✅ Kolom lang_code toegevoegd aan group_content";
        
        // Add index
        $pdo->exec("ALTER TABLE group_content ADD INDEX idx_lang_code (lang_code)");
        $success[] = "✅ Index toegevoegd voor group_content.lang_code";
        
        // Update existing records
        $pdo->exec("UPDATE group_content SET lang_code = 'nl' WHERE lang_code IS NULL OR lang_code = ''");
        $success[] = "✅ Bestaande content gemarkeerd als Nederlands";
    } else {
        $success[] = "ℹ️ group_content.lang_code bestaat al";
    }
    
    // Update group_menu table
    if (!columnExists($pdo, 'group_menu', 'lang_code')) {
        $sql = "ALTER TABLE group_menu ADD COLUMN lang_code VARCHAR(5) DEFAULT 'nl' AFTER group_id";
        $pdo->exec($sql);
        $success[] = "✅ Kolom lang_code toegevoegd aan group_menu";
        
        // Add index
        $pdo->exec("ALTER TABLE group_menu ADD INDEX idx_lang_code (lang_code)");
        $success[] = "✅ Index toegevoegd voor group_menu.lang_code";
        
        // Update existing records
        $pdo->exec("UPDATE group_menu SET lang_code = 'nl' WHERE lang_code IS NULL OR lang_code = ''");
        $success[] = "✅ Bestaande menu items gemarkeerd als Nederlands";
    } else {
        $success[] = "ℹ️ group_menu.lang_code bestaat al";
    }
    
    // Check if languages table exists and has data
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM languages");
    $stmt->execute();
    $langCount = $stmt->fetch()['count'];
    
    if ($langCount == 0) {
        // Insert basic languages
        $languages = [
            ['nl', 'Nederlands', 1],
            ['en', 'English', 2],
            ['de', 'Deutsch', 3],
            ['fr', 'Français', 4],
            ['es', 'Español', 5]
        ];
        
        $stmt = $pdo->prepare("INSERT INTO languages (locale, label, lang_id) VALUES (?, ?, ?)");
        foreach ($languages as $lang) {
            $stmt->execute($lang);
        }
        $success[] = "✅ Basis talen toegevoegd aan languages tabel";
    } else {
        $success[] = "ℹ️ Languages tabel bevat al data";
    }
    
} catch (Exception $e) {
    $errors[] = "❌ Database error: " . $e->getMessage();
}

/**
 * STAP 2: Config.php Backup en Update
 */
echo "<h2>Stap 2: Config.php Updates</h2>\n";

try {
    // Backup original config
    if (!file_exists('config.php.backup')) {
        copy('config.php', 'config.php.backup');
        $success[] = "✅ Backup gemaakt van config.php";
    }
    
    // Read current config
    $configContent = file_get_contents('config.php');
    
    // Check if language detection code already exists
    if (strpos($configContent, 'Language detection') === false) {
        // Add language detection code before the closing ?>
        $languageCode = '
// Language detection en loading
$default_lang = \'nl\';
$available_languages = [];

// Haal beschikbare talen op uit database
$available_lang_query = $db->runQuery("SELECT locale FROM language_link WHERE site_id = ?", [$shop_id]);
if ($available_lang_query) {
    while($lang_row = $available_lang_query->fetch()) {
        $available_languages[] = $lang_row[\'locale\'];
    }
}

// Als geen talen geconfigureerd, gebruik default
if(empty($available_languages)) {
    $available_languages = [$default_lang];
}

// Taal detectie logica
$current_lang = $default_lang;

// 1. Check URL parameter (?lang=en)
if(isset($_GET[\'lang\']) && in_array($_GET[\'lang\'], $available_languages)) {
    $current_lang = $_GET[\'lang\'];
    setcookie(\'site_lang\', $current_lang, time() + (365*24*3600), \'/\');
}
// 2. Check cookie
elseif(isset($_COOKIE[\'site_lang\']) && in_array($_COOKIE[\'site_lang\'], $available_languages)) {
    $current_lang = $_COOKIE[\'site_lang\'];
}
// 3. Browser language detection
elseif(isset($_SERVER[\'HTTP_ACCEPT_LANGUAGE\'])) {
    $browser_langs = explode(\',\', $_SERVER[\'HTTP_ACCEPT_LANGUAGE\']);
    foreach($browser_langs as $browser_lang) {
        $lang_code = substr(trim($browser_lang), 0, 2);
        if(in_array($lang_code, $available_languages)) {
            $current_lang = $lang_code;
            break;
        }
    }
}

// Load language file
if(file_exists($_SERVER[\'DOCUMENT_ROOT\'] . "/lang/" . $current_lang . ".php")) {
    require_once($_SERVER[\'DOCUMENT_ROOT\'] . "/lang/" . $current_lang . ".php");
} else {
    require_once($_SERVER[\'DOCUMENT_ROOT\'] . "/lang/" . $default_lang . ".php");
}

// Update menu en sections met current language
$menu = $site->getActiveMenuItems($group_id, $current_lang);
$sections = $site->getActiveContent($group_id, $current_lang);

';
        
        // Insert before the closing tag or at the end
        $configContent = str_replace('?>', $languageCode . '?>', $configContent);
        
        // If no closing tag, just append
        if (strpos($configContent, '?>') === false) {
            $configContent .= $languageCode;
        }
        
        file_put_contents('config.php', $configContent);
        $success[] = "✅ Language detection code toegevoegd aan config.php";
    } else {
        $success[] = "ℹ️ Language detection code bestaat al in config.php";
    }
    
} catch (Exception $e) {
    $errors[] = "❌ Config update error: " . $e->getMessage();
}

/**
 * STAP 3: Site.class.php Updates
 */
echo "<h2>Stap 3: Site.class.php Updates</h2>\n";

try {
    // Backup original site class
    if (!file_exists('src/site.class.php.backup')) {
        copy('src/site.class.php', 'src/site.class.php.backup');
        $success[] = "✅ Backup gemaakt van site.class.php";
    }
    
    $siteClassContent = file_get_contents('src/site.class.php');
    
    // Check if multi-language methods already exist
    if (strpos($siteClassContent, 'getAvailableLanguages') === false) {
        
        // New methods to add
        $newMethods = '
    
    // Multi-language methods
    function getActiveMenuItems($groupid, $lang_code = \'nl\') {
        if(!$groupid) {
            return "E01";
        } else {
            $sql = "SELECT * FROM group_menu WHERE group_id = :groupid AND status = \'y\' AND lang_code = :lang_code ORDER BY sortnum ASC";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([\'groupid\' => $groupid, \'lang_code\' => $lang_code]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
    
    function getActiveContent($groupid, $lang_code = \'nl\') {
        if(!$groupid) {
            return "E01";
        } else {
            $sql = "SELECT * FROM group_content WHERE group_id = :groupid AND status = \'y\' AND lang_code = :lang_code ORDER BY sortnum ASC";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([\'groupid\' => $groupid, \'lang_code\' => $lang_code]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
    
    function getAvailableLanguages($site_id) {
        $sql = "SELECT l.locale, l.label FROM languages l 
                INNER JOIN language_link ll ON l.locale = ll.locale 
                WHERE ll.site_id = :site_id ORDER BY l.lang_id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([\'site_id\' => $site_id]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    function MenuItemsByLocation($groupid, $location, $lang_code = \'nl\') {
        if(!$groupid || !$location) {
            return "E01";
        } else {
            $sql = "SELECT * FROM group_content WHERE group_id = :groupid AND location = :location AND status = \'y\' AND lang_code = :lang_code ORDER BY sortnum ASC";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([\'groupid\' => $groupid, \'location\' => $location, \'lang_code\' => $lang_code]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
    
    function getCategoryContent($group_id, $location, $limit, $lang_code = \'nl\') {
        $sql = "SELECT * FROM group_content WHERE group_id = :group_id AND location = :location AND lang_code = :lang_code ORDER BY sortnum ASC LIMIT ".$limit;
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([\'group_id\' => $group_id, \'location\' => $location, \'lang_code\' => $lang_code]);
        
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return($row);
    }
';
        
        // Insert before the closing class bracket
        $siteClassContent = str_replace('}?>', $newMethods . '}?>', $siteClassContent);
        
        // If no ?>
        if (strpos($siteClassContent, '}?>') === false) {
            $siteClassContent = str_replace('}\n?>', $newMethods . '}\n?>', $siteClassContent);
        }
        
        file_put_contents('src/site.class.php', $siteClassContent);
        $success[] = "✅ Multi-language methods toegevoegd aan site.class.php";
    } else {
        $success[] = "ℹ️ Multi-language methods bestaan al in site.class.php";
    }
    
} catch (Exception $e) {
    $errors[] = "❌ Site class update error: " . $e->getMessage();
}

/**
 * STAP 4: Language Files Updates
 */
echo "<h2>Stap 4: Language Files Updates</h2>\n";

try {
    // Backup existing language files
    if (file_exists('lang/nl.php') && !file_exists('lang/nl.php.backup')) {
        copy('lang/nl.php', 'lang/nl.php.backup');
        $success[] = "✅ Backup gemaakt van nl.php";
    }
    
    if (file_exists('lang/en.php') && !file_exists('lang/en.php.backup')) {
        copy('lang/en.php', 'lang/en.php.backup');
        $success[] = "✅ Backup gemaakt van en.php";
    }
    
    // Additional strings to add
    $additionalStrings = [
        'LOCATION' => ['nl' => 'Locatie', 'en' => 'Location'],
        'HOME' => ['nl' => 'Home', 'en' => 'Home'],
        'ABOUT' => ['nl' => 'Over ons', 'en' => 'About Us'],
        'SERVICES' => ['nl' => 'Diensten', 'en' => 'Services'],
        'PORTFOLIO' => ['nl' => 'Portfolio', 'en' => 'Portfolio'],
        'TESTIMONIALS' => ['nl' => 'Testimonials', 'en' => 'Testimonials'],
        'TEAM' => ['nl' => 'Team', 'en' => 'Team'],
        'BLOG' => ['nl' => 'Blog', 'en' => 'Blog'],
        'NEWS' => ['nl' => 'Nieuws', 'en' => 'News'],
        'GALLERY' => ['nl' => 'Galerij', 'en' => 'Gallery'],
        'FAQ' => ['nl' => 'Veelgestelde vragen', 'en' => 'Frequently Asked Questions'],
        'PRIVACY' => ['nl' => 'Privacy', 'en' => 'Privacy'],
        'TERMS' => ['nl' => 'Algemene voorwaarden', 'en' => 'Terms & Conditions'],
        'SEARCH' => ['nl' => 'Zoeken', 'en' => 'Search'],
        'MENU' => ['nl' => 'Menu', 'en' => 'Menu'],
        'CLOSE' => ['nl' => 'Sluiten', 'en' => 'Close'],
        'OPEN' => ['nl' => 'Openen', 'en' => 'Open'],
        'MORE_INFO' => ['nl' => 'Meer informatie', 'en' => 'More information'],
        'DOWNLOAD' => ['nl' => 'Download', 'en' => 'Download'],
        'SHARE' => ['nl' => 'Delen', 'en' => 'Share'],
        'PRINT' => ['nl' => 'Printen', 'en' => 'Print'],
        'BACK' => ['nl' => 'Terug', 'en' => 'Back'],
        'NEXT' => ['nl' => 'Volgende', 'en' => 'Next'],
        'PREVIOUS' => ['nl' => 'Vorige', 'en' => 'Previous'],
        'LOADING' => ['nl' => 'Laden...', 'en' => 'Loading...'],
        'ERROR' => ['nl' => 'Fout', 'en' => 'Error'],
        'SUCCESS' => ['nl' => 'Succes', 'en' => 'Success'],
        'WARNING' => ['nl' => 'Waarschuwing', 'en' => 'Warning'],
        'INFO' => ['nl' => 'Informatie', 'en' => 'Information']
    ];
    
    // Update Dutch language file
    if (file_exists('lang/nl.php')) {
        $nlContent = file_get_contents('lang/nl.php');
        
        foreach ($additionalStrings as $key => $values) {
            if (strpos($nlContent, "'{$key}'") === false) {
                // Add before the last entry
                $nlContent = str_replace(
                    "'LAST' => 'Laatste'",
                    "'{$key}' => '{$values['nl']}',\n\t'LAST' => 'Laatste'",
                    $nlContent
                );
            }
        }
        
        file_put_contents('lang/nl.php', $nlContent);
        $success[] = "✅ Nederlandse taalstrings uitgebreid";
    }
    
    // Update English language file
    if (file_exists('lang/en.php')) {
        $enContent = file_get_contents('lang/en.php');
        
        foreach ($additionalStrings as $key => $values) {
            if (strpos($enContent, "'{$key}'") === false) {
                // Add before the last entry
                $enContent = str_replace(
                    "'LAST' => 'Last'",
                    "'{$key}' => '{$values['en']}',\n\t'LAST' => 'Last'",
                    $enContent
                );
            }
        }
        
        file_put_contents('lang/en.php', $enContent);
        $success[] = "✅ Engelse taalstrings uitgebreid";
    }
    
} catch (Exception $e) {
    $errors[] = "❌ Language files update error: " . $e->getMessage();
}

/**
 * STAP 5: Create Language Switcher Component
 */
echo "<h2>Stap 5: Language Switcher Component</h2>\n";

try {
    $languageSwitcherContent = '<?php
/**
 * Language Switcher Component
 * Include this in your theme templates where you want the language switcher
 */
if (!isset($site) || !isset($shop_id) || !isset($current_lang)) {
    echo "<!-- Language switcher: Required variables not available -->";
    return;
}

$available_languages = $site->getAvailableLanguages($shop_id);
if (empty($available_languages)) {
    return; // No languages configured
}
?>
<div class="language-switcher">
    <?php foreach($available_languages as $lang): 
        $active_class = ($current_lang == $lang['locale']) ? 'active' : '';
        // Get the current URL without query string (for cleaner language switching)
        $current_url = strtok($_SERVER['REQUEST_URI'], '?');
        
        // Remove existing lang parameter
        $current_url = preg_replace('/[?&]lang=[^&]*/', '', $current_url);
        
        $separator = (strpos($current_url, '?') !== false) ? '&' : '?';
        $lang_url = $current_url . $separator . 'lang=' . $lang['locale'];
    ?>
        <a href="<?php echo htmlspecialchars($lang_url); ?>" 
           class="lang-link <?php echo $active_class; ?>" 
           title="<?php echo htmlspecialchars($lang['label']); ?>">
            <?php echo strtoupper($lang['locale']); ?>
        </a>
    <?php endforeach; ?>
</div>

<style>
.language-switcher {
    display: inline-flex;
    gap: 10px;
    margin: 10px 0;
}

.lang-link {
    padding: 5px 10px;
    text-decoration: none;
    border: 1px solid #ddd;
    border-radius: 3px;
    color: #333;
    font-size: 12px;
    font-weight: bold;
    transition: all 0.3s ease;
}

.lang-link:hover {
    background-color: #f5f5f5;
    text-decoration: none;
    color: #000;
}

.lang-link.active {
    background-color: #007cba;
    color: white;
    border-color: #007cba;
}
</style>';
    
    // Create themes directory if it doesn't exist
    if (!is_dir('themes/components')) {
        mkdir('themes/components', 0755, true);
    }
    
    file_put_contents('themes/components/language_switcher.php', $languageSwitcherContent);
    $success[] = "✅ Language switcher component aangemaakt";
    
} catch (Exception $e) {
    $errors[] = "❌ Language switcher creation error: " . $e->getMessage();
}

/**
 * STAP 6: Create Admin Language Management Enhancement
 */
echo "<h2>Stap 6: Admin Language Management</h2>\n";

try {
    // Create enhanced language management for content
    $adminLanguageScript = <<<'PHP'
<?php
/**
 * Enhanced Language Management for Admin
 * Add this to your admin content forms
 */

// Get available languages for the site
function getLanguageSelector($current_lang = 'nl', $field_name = 'lang_code') {
    global $site, $shop_id;
    
    if (!isset($site) || !isset($shop_id)) {
        return '<input type="hidden" name="' . $field_name . '" value="nl">';
    }
    
    $available_languages = $site->getAvailableLanguages($shop_id);
    
    if (empty($available_languages)) {
        return '<input type="hidden" name="' . $field_name . '" value="nl">';
    }
    
    $html = '<div class="form-group">';
    $html .= '<label for="' . $field_name . '">Taal:</label>';
    $html .= '<select name="' . $field_name . '" id="' . $field_name . '" class="form-control">';
    
    foreach($available_languages as $lang) {
        $selected = ($current_lang == $lang['locale']) ? 'selected' : '';
        $html .= '<option value="' . htmlspecialchars($lang['locale']) . '" ' . $selected . '>';
        $html .= htmlspecialchars($lang['label']) . ' (' . strtoupper($lang['locale']) . ')';
        $html .= '</option>';
    }
    
    $html .= '</select>';
    $html .= '</div>';
    
    return $html;
}

// Function to duplicate content for translation
function duplicateContentForTranslation($content_id, $target_lang, $table = 'group_content') {
    global $pdo;
    
    try {
        // Get original content
        $stmt = $pdo->prepare("SELECT * FROM {$table} WHERE id = ?");
        $stmt->execute([$content_id]);
        $original = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$original) {
            return false;
        }
        
        // Remove ID and set new language
        unset($original['id']);
        $original['lang_code'] = $target_lang;
        $original['title'] = '[TRANSLATE] ' . $original['title'];
        $original['status'] = 'n'; // Set as inactive until translated
        
        // Insert new record
        $fields = array_keys($original);
        $placeholders = str_repeat('?,', count($fields) - 1) . '?';
        
        $sql = "INSERT INTO {$table} (" . implode(',', $fields) . ") VALUES ({$placeholders})";
        $stmt = $pdo->prepare($sql);
        
        return $stmt->execute(array_values($original));
        
    } catch (Exception $e) {
        error_log("Translation duplication error: " . $e->getMessage());
        return false;
    }
}
?>
PHP;
    
    file_put_contents('admin/functions/language_management.php', $adminLanguageScript);
    $success[] = "✅ Admin language management functies aangemaakt";
    
} catch (Exception $e) {
    $errors[] = "❌ Admin language management error: " . $e->getMessage();
}

/**
 * STAP 7: Create Migration Script for Existing Content
 */
echo "<h2>Stap 7: Content Migration Script</h2>\n";

try {
    $migrationScript = '<?php
/**
 * Content Migration Script
 * Run this to duplicate existing content for other languages
 */

require_once("../system/database.php");
require_once("../src/database.class.php");

$db = new database($pdo);

function migrateContentToLanguages($target_languages = ['en']) {
    global $pdo;
    
    $migrated = [];
    $errors = [];
    
    foreach ($target_languages as $lang) {
        echo "<h3>Migreren naar: " . strtoupper($lang) . "</h3>";
        
        // Migrate content
        try {
            $stmt = $pdo->prepare("SELECT * FROM group_content WHERE lang_code = 'nl'");
            $stmt->execute();
            
            while ($content = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Check if translation already exists
                $checkStmt = $pdo->prepare("SELECT id FROM group_content WHERE group_id = ? AND location = ? AND lang_code = ?");
                $checkStmt->execute([$content['group_id'], $content['location'], $lang]);
                
                if ($checkStmt->rowCount() == 0) {
                    // Create translation
                    unset($content['id']);
                    $content['lang_code'] = $lang;
                    $content['title'] = '[TRANSLATE] ' . $content['title'];
                    $content['status'] = 'n';
                    
                    $fields = array_keys($content);
                    $placeholders = str_repeat('?,', count($fields) - 1) . '?';
                    
                    $insertSql = "INSERT INTO group_content (" . implode(',', $fields) . ") VALUES ({$placeholders})";
                    $insertStmt = $pdo->prepare($insertSql);
                    $insertStmt->execute(array_values($content));
                    
                    $migrated[] = "Content: " . $content['title'];
                }
            }
        } catch (Exception $e) {
            $errors[] = "Content migration error: " . $e->getMessage();
        }
        
        // Migrate menu items
        try {
            $stmt = $pdo->prepare("SELECT * FROM group_menu WHERE lang_code = 'nl'");
            $stmt->execute();
            
            while ($menu = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Check if translation already exists
                $checkStmt = $pdo->prepare("SELECT id FROM group_menu WHERE group_id = ? AND location = ? AND lang_code = ?");
                $checkStmt->execute([$menu['group_id'], $menu['location'], $lang]);
                
                if ($checkStmt->rowCount() == 0) {
                    // Create translation
                    unset($menu['id']);
                    $menu['lang_code'] = $lang;
                    $menu['title'] = '[TRANSLATE] ' . $menu['title'];
                    $menu['status'] = 'n';
                    
                    $fields = array_keys($menu);
                    $placeholders = str_repeat('?,', count($fields) - 1) . '?';
                    
                    $insertSql = "INSERT INTO group_menu (" . implode(',', $fields) . ") VALUES ({$placeholders})";
                    $insertStmt = $pdo->prepare($insertSql);
                    $insertStmt->execute(array_values($menu));
                    
                    $migrated[] = "Menu: " . $menu['title'];
                }
            }
        } catch (Exception $e) {
            $errors[] = "Menu migration error: " . $e->getMessage();
        }
    }
    
    return ['migrated' => $migrated, 'errors' => $errors];
}

// Uncomment the line below and specify target languages when ready to migrate
// $result = migrateContentToLanguages(['en', 'de']);
// print_r($result);
?>';
    
    file_put_contents('admin/migrate_content.php', $migrationScript);
    $success[] = "✅ Content migration script aangemaakt";
    
} catch (Exception $e) {
    $errors[] = "❌ Migration script creation error: " . $e->getMessage();
}

// Mark first todo as completed
$todo_write_result = [
    ["id" => "database_updates", "content" => "Database schema updates voor multi-language ondersteuning", "status" => "completed"],
    ["id" => "config_updates", "content" => "Config.php uitbreiden met language detection logica", "status" => "completed"], 
    ["id" => "site_class_updates", "content" => "Site.class.php methods uitbreiden voor multi-language", "status" => "completed"],
    ["id" => "admin_updates", "content" => "Admin interface uitbreiden voor language management", "status" => "completed"],
    ["id" => "language_files", "content" => "Taalbestanden uitbreiden met meer strings", "status" => "completed"],
    ["id" => "frontend_components", "content" => "Language switcher en template updates", "status" => "completed"]
];

/**
 * RESULTATEN WEERGEVEN
 */
echo "<h2>Update Resultaten</h2>\n";

echo "<h3>✅ Succesvol uitgevoerd:</h3>\n";
echo "<ul>\n";
foreach ($success as $item) {
    echo "<li>{$item}</li>\n";
}
echo "</ul>\n";

if (!empty($errors)) {
    echo "<h3>❌ Fouten:</h3>\n";
    echo "<ul>\n";
    foreach ($errors as $error) {
        echo "<li>{$error}</li>\n";
    }
    echo "</ul>\n";
}

echo "<h2>Volgende Stappen</h2>\n";
echo "<ol>\n";
echo "<li>Test de language detection door ?lang=en toe te voegen aan de URL</li>\n";
echo "<li>Ga naar de admin en configureer welke talen actief zijn</li>\n";
echo "<li>Voeg de language switcher toe aan je templates: <code>include 'themes/components/language_switcher.php';</code></li>\n";
echo "<li>Update je template bestanden om lang() functies te gebruiken voor teksten</li>\n";
echo "<li>Gebruik admin/migrate_content.php om bestaande content te dupliceren voor andere talen</li>\n";
echo "<li>Vertaal de gedupliceerde content via de admin interface</li>\n";
echo "</ol>\n";

echo "<p><strong>Eind tijd:</strong> " . date('Y-m-d H:i:s') . "</p>\n";
echo "<p><strong>Status:</strong> " . (empty($errors) ? "✅ Volledig succesvol" : "⚠️ Met waarschuwingen") . "</p>\n";

?>