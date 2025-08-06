<?php
class photoslider {

    function __construct($pdo) {
        $this->pdo = $pdo;
    }

    function getAllImages() {
        $sql = "SELECT * FROM group_photoslider ORDER BY sortnum ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;    
    }
    
    function getImage($field, $id) {
        if(!$id) {
            return false;
        } else {
            $sql = "SELECT * FROM group_photoslider WHERE {$field} = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);
            $row = $stmt->fetch();
            if(!empty($row)) {
                return($row);
            } else {
                return false;
            }
        }
    }
    
    function getPhotosliderSettings($group_id) {
        $sql = "SELECT * FROM group_photoslider_settings WHERE group_id = :group_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['group_id' => $group_id]);
        $row = $stmt->fetch();
        if(!empty($row)) {
            return($row);
        } else {
            return false;
        }
    }

    function getAllSliders() {
        $sql = "SELECT id, name FROM group_photoslider_names ORDER BY name ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }
    
        // Functie om de naam van een slider op te halen op basis van ID
    function sliderName($id) {
        // Controleer of een geldig ID is doorgegeven
        if (empty($id)) {
            return false; // Of geef een geschikte foutmelding terug
        }

        // SQL-query om de naam van de slider op te halen
        $sql = "SELECT name FROM group_photoslider_names WHERE id = :id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        // Haal de naam op uit de resultaten
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Controleer of een rij is gevonden en geef de naam terug, anders geef false
        return $row ? $row['name'] : false;
    }
    
    function check_extension($filename, $allowed) {
        $filename = strtolower($filename); 
        $exts = pathinfo($filename, PATHINFO_EXTENSION);

        if ($filename != '') { 
            $exploded = explode(".", strtolower($filename)); // Eerst de explode functie uitvoeren
            $extension = end($exploded); // Daarna de end() functie gebruiken op de variabele

            if (!in_array($extension, $allowed)) { 
               return false;
            } else {
               return $exts; 
            } 
        } 
    }

    // Functie om één JSON bestand aan te maken voor alle sliders en op te slaan in de /api folder
    function generate_all_sliders_json() {
        global $path;

        // Zet het pad naar de /api folder
        $api_folder = $path . "/api";
        $output_path = $api_folder . "/sliders.json";

        // Controleer of de /api folder bestaat, maak deze anders aan
        if (!is_dir($api_folder)) {
            if (!mkdir($api_folder, 0777, true)) {
                return ['error' => 'Kon de /api folder niet aanmaken.'];
            }
        }

        // Gebruik een directe SQL-query om alle actieve sliders met een afbeelding op te halen, inclusief hun slider naam uit group_photoslider_names
        $sql = "SELECT p.id, p.hash, p.subject, p.category, p.sortnum, p.image, p.modified, p.active, p.slider_id, s.name AS slider_name
                FROM group_photoslider p
                JOIN group_photoslider_names s ON p.slider_id = s.id
                WHERE p.active = 'y' AND p.image != ''
                ORDER BY p.sortnum DESC";

        // Voer de query uit
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $all_sliders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Controleer of er sliders zijn gevonden
        if (empty($all_sliders)) {
            return ['error' => 'Geen sliders gevonden.'];
        }

        // Zet de data om naar JSON formaat
        $json_data = json_encode($all_sliders, JSON_PRETTY_PRINT);

        // Schrijf de JSON data naar het opgegeven pad
        if (file_put_contents($output_path, $json_data) === false) {
            return ['error' => 'Kon het JSON bestand niet schrijven naar de /api folder.'];
        }

        return ['success' => 'JSON bestand succesvol aangemaakt.', 'path' => $output_path];
    }    
}
?>
