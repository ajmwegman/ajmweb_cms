<?php
@session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $path = $_SERVER['DOCUMENT_ROOT'];
    require_once($path . "/system/database.php");
    require_once($path . "/admin/src/database.class.php");
    require_once($path . "/admin/modules/newsletter_memberlist/src/module.class.php");

    echo "<h3>Testing Database Connection</h3>";
    
    // Test database connection
    if (isset($pdo)) {
        echo "<p>✓ Database connection established</p>";
        
        // Test if table exists
        $stmt = $pdo->query("SHOW TABLES LIKE 'group_newslettermembers'");
        if ($stmt->rowCount() > 0) {
            echo "<p>✓ Table 'group_newslettermembers' exists</p>";
            
            // Test table structure
            $stmt = $pdo->query("DESCRIBE group_newslettermembers");
            $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "<p>✓ Table structure:</p><ul>";
            foreach ($columns as $column) {
                echo "<li>{$column['Field']} - {$column['Type']}</li>";
            }
            echo "</ul>";
            
            // Test data retrieval
            $memberlist = new newsletter_memberlist($pdo);
            $allMembers = $memberlist->getAllMembers();
            echo "<p>✓ Found " . count($allMembers) . " members in database</p>";
            
            // Test search functionality
            $searchResults = $memberlist->searchMembers('test');
            echo "<p>✓ Search function working (found " . count($searchResults) . " results for 'test')</p>";
            
        } else {
            echo "<p>✗ Table 'group_newslettermembers' does not exist</p>";
        }
    } else {
        echo "<p>✗ Database connection failed</p>";
    }
    
} catch (Exception $e) {
    echo "<p>✗ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Stack trace: " . htmlspecialchars($e->getTraceAsString()) . "</p>";
}
?> 