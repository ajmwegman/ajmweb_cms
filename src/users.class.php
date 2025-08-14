 <?php
class users {

    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getUserDataByUserId($hash)
    {
        // Controleer of de userId leeg is
        if (!$hash) {
            return "Er mag niet worden aangeroepen op deze wijze.";
        }

        $sql = "SELECT u.*, 
                       ba.company_name AS company_name,
                       ba.kvk_number AS kvk_number,
                       ba.vat_number AS vat_number,
                       ba.phone_number AS phone_number,
                       ba.street AS street,
                       ba.postal_code AS postal_code,
                       ba.city AS city,
                       ba.country AS country,
                       sa.company_name AS shipping_company_name,
                       sa.kvk_number AS shipping_kvk_number,
                       sa.vat_number AS shipping_vat_number,
                       sa.phone_number AS shipping_phone_number,
                       sa.street AS shipping_street,
                       sa.postal_code AS shipping_postal_code,
                       sa.city AS shipping_city,
                       sa.country AS shipping_country
                FROM site_users u
                LEFT JOIN user_addresses ba ON u.id = ba.user_id AND ba.type = 'billing'
                LEFT JOIN user_addresses sa ON u.id = sa.user_id AND sa.type = 'shipping'
                WHERE u.hash = :hash";

        $stmt = $this->pdo->prepare($sql);

        // Voer de query uit
        $success = $stmt->execute(['hash' => $hash]);

        // Controleer of de SQL-query succesvol werd uitgevoerd
        if (!$success) {
            return "Kon de SQL-query niet uitvoeren.";
        }

        // Haal de resultaten op
        $row = $stmt->fetch();

        // Als er geen resultaten zijn, retourneer false of een aangepaste foutmelding, afhankelijk van je gebruik
        if (!$row) {
            return "Geen gegevens gevonden voor deze userId.";
        }

        // Als er geen factuuradresgegevens zijn, dwing het invullen van het adresformulier
        if (empty($row['street']) || empty($row['postal_code'])) {
            return "address_form_first";
        }

        // Retourneer de gevonden gegevens
        return $row;
    }

    
    function updateLogin($userId, $newHash) {
    // Bereid de SQL-query voor
        $sql = "UPDATE site_users SET hash = :newHash, last_login = NOW() WHERE id = :userId";
        $stmt = $this->pdo->prepare($sql);

        // Controleer of de SQL-query voorbereid kon worden
        if(!$stmt) {
            throw new RuntimeException("Kon de SQL-query niet voorbereiden.");
        }

        // Voer de query uit
        $params = [
            'newHash' => $newHash,
            'userId' => $userId
        ];
    
        $success = $stmt->execute($params);

        // Controleer of de SQL-query succesvol werd uitgevoerd
        if(!$success) {
            throw new RuntimeException("Kon de SQL-query niet uitvoeren.");
        }
    }
    
    public function getUserIdByHash($hash) {
        // Bereid de SQL-query voor om het ID te krijgen van site_users waar hash gelijk is aan de gegeven hash
        $sql = "SELECT id FROM site_users WHERE hash = :hash";
        $stmt = $this->pdo->prepare($sql);

        // Voer de query uit
        $success = $stmt->execute(['hash' => $hash]);

        // Haal het resultaat op
        $row = $stmt->fetch();

        // Controleer of er een resultaat is
        if ($row) {
            return $row['id'];
        }

        // Als er geen resultaat is gevonden, retourneer een foutmelding of false, afhankelijk van je gebruik
        return false;
    }

    public function insertOrUpdateUserData($userId, $bedrijfsnaam, $kvknummer, $btwnummer, $telefoonnummer, $straat, $postcode, $stad, $land, $type = 'billing')
    {
        try {
            $sql = "INSERT INTO user_addresses (user_id, type, company_name, kvk_number, vat_number, phone_number, street, postal_code, city, country)
                    VALUES (:user_id, :type, :company_name, :kvk_number, :vat_number, :phone_number, :street, :postal_code, :city, :country)
                    ON DUPLICATE KEY UPDATE
                    company_name = VALUES(company_name),
                    kvk_number = VALUES(kvk_number),
                    vat_number = VALUES(vat_number),
                    phone_number = VALUES(phone_number),
                    street = VALUES(street),
                    postal_code = VALUES(postal_code),
                    city = VALUES(city),
                    country = VALUES(country)";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':company_name', $bedrijfsnaam);
            $stmt->bindParam(':kvk_number', $kvknummer);
            $stmt->bindParam(':vat_number', $btwnummer);
            $stmt->bindParam(':phone_number', $telefoonnummer);
            $stmt->bindParam(':street', $straat);
            $stmt->bindParam(':postal_code', $postcode);
            $stmt->bindParam(':city', $stad);
            $stmt->bindParam(':country', $land);

            $stmt->execute();

            return "Gegevens zijn bijgewerkt of ingevoegd.";

        } catch (PDOException $e) {
            return "Fout: " . $e->getMessage();
        }
    }

 public function getHighestBidsForUser ($userId) {
        // Bereid de SQL-query voor om het hoogste bod per product op te halen voor de opgegeven gebruiker, gesorteerd op tijd (aflopend)
        $sql = "SELECT gp.title, gp.seoTitle, lb.bid AS user_bid, lb.lotid,
                (SELECT MAX(lb2.bid) FROM lotbids lb2) AS highest_bid, 
                MAX(CONCAT(ga.endDate, ' ', ga.endTime)) AS auction_end_datetime,
                TIMESTAMPDIFF(SECOND, NOW(), CONCAT(ga.endDate, ' ', ga.endTime)) AS remaining_seconds,
                gpi.image
                FROM lotbids lb
                INNER JOIN group_auctions ga ON lb.lotid = ga.id
                INNER JOIN group_products gp ON ga.productId = gp.id
                LEFT JOIN (
                    SELECT hash, MIN(sort_num) AS min_sort_num
                    FROM group_product_images
                    GROUP BY hash
                ) AS min_images ON gp.hash = min_images.hash
                LEFT JOIN group_product_images gpi ON min_images.hash = gpi.hash AND min_images.min_sort_num = gpi.sort_num
                WHERE lb.userid = :userid 
                AND NOW() < CONCAT(ga.endDate, ' ', ga.endTime)  -- Alleen actieve biedingen op niet verlopen kavels
                GROUP BY ga.productId 
                ORDER BY auction_end_datetime DESC";
        $stmt = $this->pdo->prepare($sql);

        // Voer de query uit
        $success = $stmt->execute(['userid' => $userId]);

        // Controleer of de query succesvol was
        if (!$success) {
            // Er is een fout opgetreden, retourneer een lege array of handel de fout op een andere manier
            return [];
        }

        // Haal de resultaten op
        $bids = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Retourneer de biedingen van de opgegeven gebruiker met bijbehorende afbeeldingen
        return $bids;
    }
    
    public function getUserWonAuctions($userId)
    {
        try {
            // Bereid de SQL-query voor om de gewonnen veilingen van de gebruiker op te halen
            $sql = "SELECT gaf.*, gpi.image AS image, gp.title AS title
                    FROM group_auctions_finished gaf
                    INNER JOIN group_products gp ON gaf.lotid = gp.id
                    LEFT JOIN (
                        SELECT hash, MIN(sort_num) AS min_sort_num
                        FROM group_product_images
                        GROUP BY hash
                    ) AS min_images ON gp.hash = min_images.hash
                    LEFT JOIN group_product_images gpi ON min_images.hash = gpi.hash AND min_images.min_sort_num = gpi.sort_num
                    WHERE gaf.userid = :userid";

            // Voorbereiden en uitvoeren van de query
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['userid' => $userId]);

            // Resultaten ophalen
            $wonAuctions = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Retourneer de gewonnen veilingen
            return $wonAuctions;
        } catch (PDOException $e) {
            // Vang eventuele fouten op en geef een lege array terug om problemen in de aanroepende code te voorkomen
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    public function getUserFavorites($userId) {
        // Bereid de SQL-query voor om alle favorieten van de opgegeven gebruiker op te halen, samen met het hoogste bod per favoriet
$sql = "SELECT gf.*, gp.title AS product_title, gp.seoTitle, gp.productCode,
        (SELECT MAX(lb.bid) FROM lotbids lb
         WHERE lb.lotid = (SELECT id FROM group_auctions ga WHERE ga.productId = gf.product_id LIMIT 1)) AS highest_bid,
        (SELECT CONCAT(ga.endDate, ' ', ga.endTime) FROM group_auctions ga
         WHERE ga.productId = gf.product_id
         ORDER BY CONCAT(ga.endDate, ' ', ga.endTime) DESC LIMIT 1) AS auction_end_datetime,
        TIMESTAMPDIFF(SECOND, NOW(), (SELECT CONCAT(ga.endDate, ' ', ga.endTime) FROM group_auctions ga
                                       WHERE ga.productId = gf.product_id
                                       ORDER BY CONCAT(ga.endDate, ' ', ga.endTime) DESC LIMIT 1)) AS remaining_seconds,
        gpi.image
        FROM group_products_favorites gf
        INNER JOIN group_products gp ON gf.product_id = gp.id
        LEFT JOIN (
            SELECT hash, MIN(sort_num) AS min_sort_num
            FROM group_product_images
            GROUP BY hash
        ) AS min_images ON gp.hash = min_images.hash
        LEFT JOIN group_product_images gpi ON min_images.hash = gpi.hash AND min_images.min_sort_num = gpi.sort_num
        WHERE gf.user_id = :user_id 
        ORDER BY gf.created_at DESC";

            $stmt = $this->pdo->prepare($sql);

            // Voer de query uit
            $success = $stmt->execute(['user_id' => $userId]);

        // Haal de resultaten op
        $favorites = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $favorites[] = $row;
        }

        // Retourneer de lijst met favorieten en het hoogste bod per favoriet
        return $favorites;
    }
    
    public function updateUsername($userId, $newUsername) {
        try {
            // Bereid het SQL-statement voor
            $sql = "UPDATE site_users SET email = :newUsername WHERE id = :userId";
            $stmt = $this->pdo->prepare($sql);

            // Bind de parameters
            $stmt->bindParam(':newUsername', $newUsername, PDO::PARAM_STR);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);

            // Voer het SQL-statement uit
            $stmt->execute();

            // Controleer het aantal bijgewerkte rijen
            if ($stmt->rowCount() > 0) {
                return true; // Gebruikersnaam is succesvol bijgewerkt
            } else {
                return false; // Gebruikersnaam is niet bijgewerkt
            }
        } catch (PDOException $e) {
            // Vang en toon eventuele databasefouten
            error_log('Fout bij het bijwerken van de gebruikersnaam: ' . $e->getMessage());
            return false;
        }
    }
    
    public function getUserByUsername($username) {
    $query = "SELECT * FROM site_users WHERE email = ?";
    $stmt = $this->pdo->prepare($query);
    $stmt->execute([$username]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
    
    public function updatePassword($userId, $newPassword) {
        // Bereid de SQL-query voor
        $sql = "UPDATE site_users SET password = :newPassword, last_login = NOW() WHERE id = :userId";
        $stmt = $this->pdo->prepare($sql);

        // Controleer of de SQL-query voorbereid kon worden
        if(!$stmt) {
            throw new RuntimeException("Kon de SQL-query niet voorbereiden.");
        }

        // Voer de query uit
        $params = [
            'newPassword' => $newPassword,
            'userId' => $userId
        ];
    
        $success = $stmt->execute($params);

        // Controleer of de SQL-query succesvol werd uitgevoerd
        if(!$success) {
            throw new RuntimeException("Kon de SQL-query niet uitvoeren.");
        }
    }
}