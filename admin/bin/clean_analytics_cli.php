<?php
// Command-line versie van de analytics database cleanup
// Gebruik: php clean_analytics_cli.php

// Zorg ervoor dat we in CLI mode zijn
if (php_sapi_name() !== 'cli') {
    die("Dit script moet via command line worden uitgevoerd\n");
}

require_once("../../system/database.php");

try {
    echo "=== Analytics Database Cleanup ===\n\n";
    
    // Eerst tellen hoeveel records er zijn die gefilterd worden
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as total_records 
        FROM analytics 
        WHERE page_url LIKE '%?e=%' 
           OR page_url LIKE '%?channel=%' 
           OR page_url LIKE '%?from=%' 
           OR page_url LIKE '%?utm_%' 
           OR page_url LIKE '%?fbclid=%' 
           OR page_url LIKE '%?gclid=%' 
           OR LENGTH(page_url) > 200
    ");
    $stmt->execute();
    $spamRecords = $stmt->fetch()['total_records'];
    
    // Totaal aantal records
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM analytics");
    $stmt->execute();
    $totalRecords = $stmt->fetch()['total'];
    
    echo "Database Status:\n";
    echo "- Totale records: " . $totalRecords . "\n";
    echo "- Spam/Bot records: " . $spamRecords . "\n";
    echo "- Percentage spam: " . round(($spamRecords / $totalRecords) * 100, 2) . "%\n\n";
    
    if ($spamRecords > 0) {
        echo "Verwijderen van spam data...\n";
        
        // Verwijder spam records
        $stmt = $pdo->prepare("
            DELETE FROM analytics 
            WHERE page_url LIKE '%?e=%' 
               OR page_url LIKE '%?channel=%' 
               OR page_url LIKE '%?from=%' 
               OR page_url LIKE '%?utm_%' 
               OR page_url LIKE '%?fbclid=%' 
               OR page_url LIKE '%?gclid=%' 
               OR LENGTH(page_url) > 200
        ");
        $stmt->execute();
        $deletedRecords = $stmt->rowCount();
        
        echo "✓ " . $deletedRecords . " spam records verwijderd\n";
        
        // Toon nieuwe database status
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM analytics");
        $stmt->execute();
        $newTotalRecords = $stmt->fetch()['total'];
        
        echo "\nNieuwe Database Status:\n";
        echo "- Overgebleven records: " . $newTotalRecords . "\n";
        echo "- Ruimte bespaard: " . ($totalRecords - $newTotalRecords) . " records\n";
        
    } else {
        echo "✓ Geen spam data gevonden om te verwijderen\n";
    }
    
    // Database optimalisatie
    echo "\nDatabase optimalisatie...\n";
    $stmt = $pdo->prepare("OPTIMIZE TABLE analytics");
    $stmt->execute();
    echo "✓ Database geoptimaliseerd\n";
    
    // Toon tabel grootte
    $stmt = $pdo->prepare("
        SELECT 
            table_name,
            ROUND(((data_length + index_length) / 1024 / 1024), 2) AS 'Size (MB)'
        FROM information_schema.tables 
        WHERE table_schema = DATABASE() 
        AND table_name = 'analytics'
    ");
    $stmt->execute();
    $tableInfo = $stmt->fetch();
    
    if ($tableInfo) {
        echo "- Analytics tabel grootte: " . $tableInfo['Size (MB)'] . " MB\n";
    }
    
    echo "\n=== Cleanup voltooid ===\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
