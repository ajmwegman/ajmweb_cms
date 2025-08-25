<?php
echo "<h1>Debug HTML Output</h1>";

// Check if we have a session
if (!isset($_SESSION)) {
    session_start();
}

echo "<h2>Session Info:</h2>";
echo "Session group_id: " . (isset($_SESSION['group_id']) ? $_SESSION['group_id'] : 'NOT SET') . "<br>";

// Check if we can load the menu class
$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path."/admin/src/menulist.class.php");

try {
    $db = new database($pdo);
    $menu = new menu($pdo);
    
    if (isset($_SESSION['group_id'])) {
        $group_id = $_SESSION['group_id'];
        echo "Group ID: $group_id<br>";
        
        $list = $menu->getMenuItems($group_id);
        echo "Menu items found: " . count($list) . "<br>";
        
        if (count($list) > 0) {
            echo "<h3>First menu item data:</h3>";
            echo "<pre>" . print_r($list[0], true) . "</pre>";
            
            echo "<h3>Generated HTML:</h3>";
            echo "<div style='border: 1px solid #ccc; padding: 10px; background: #f9f9f9;'>";
            
            $link = $list[0];
            echo '<input type="text" name="title[]" value="' . htmlspecialchars($link['title']) . '" id="title' . $link['id'] . '" class="form-control autosave" data-field="title" data-set="' . $link['hash'] . '">';
            
            echo "</div>";
            
            echo "<h3>All menu items:</h3>";
            foreach ($list as $row => $link) {
                echo "ID: {$link['id']}, Hash: {$link['hash']}, Title: {$link['title']}, Location: {$link['location']}<br>";
            }
        }
    } else {
        echo "No group_id in session<br>";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}

echo "<h2>Test autosave element:</h2>";
echo '<input type="text" class="form-control autosave" data-field="test" data-set="test_hash" value="Test Value">';
echo "<p>This should have the autosave class and data attributes.</p>";
?>
