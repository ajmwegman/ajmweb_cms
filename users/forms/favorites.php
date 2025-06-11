<?php
// Roep de getUserFavorites-functie aan om de favorieten van de gebruiker op te halen
$favorites = $users->getUserFavorites($userId); // Zorg ervoor dat $userId is ingesteld op het ID van de gebruiker

// Controleer of er favorieten zijn
if (!empty($favorites)) {
    // HTML-tabel genereren
    echo '<div class="container">';
    echo '<table class="table">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Afbeelding</th>';
    echo '<th>Kavel</th>';
    echo '<th>Product Titel</th>';
    echo '<th>Hoogste Bod</th>';
    echo '<th>Resterende Tijd</th>';
    echo '<th>Actie</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    // Loop door de favorieten en voeg rijen toe aan de tabel
    foreach ($favorites as $favorite) {
        // Afbeelding HTML-code
        $image = '<img src="/product_images/' . $favorite['image'] . '" alt="' . $favorite['product_title'] . '" class="img-fluid" style="max-height: 55px;">';

        // Herformatteer de resterende tijd
        $remainingSeconds = $favorite['remaining_seconds'];
        if ($remainingSeconds < 0) {
            $remainingTime = 'Kavel gesloten';
        } else {
            $days = floor($remainingSeconds / (3600 * 24));
            $hours = floor(($remainingSeconds - ($days * 3600 * 24)) / 3600);
            $minutes = floor(($remainingSeconds - ($days * 3600 * 24) - ($hours * 3600)) / 60);
            $seconds = $remainingSeconds % 60;
            $remainingTime = sprintf("%02dd %02dh %02dm %02ds", $days, $hours, $minutes, $seconds);
        }

        // HTML-rij toevoegen aan de tabel
        echo '<tr>';
        echo '<td>' . $image . '</td>';
        echo '<td>' . $favorite['product_id'] . '</td>';
        echo '<td>' . $favorite['product_title'] . '</td>';
        echo '<td>' . $favorite['highest_bid'] . '</td>';
        echo '<td>' . $remainingTime . '</td>';
        echo '<td><a href="/veiling/' . $favorite['seoTitle'] . '/" class="btn btn-primary">Naar Kavel</a></td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div>';
} else {
    echo '<p>Er zijn geen favorieten gevonden voor deze gebruiker.</p>';
}
