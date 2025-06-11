<?php
// Roep de getHighestBidsForUser-functie aan om de hoogste biedingen van de gebruiker op te halen
$bids = $users->getHighestBidsForUser($userId); // Zorg ervoor dat $userId is ingesteld op het ID van de gebruiker

// Controleer of er biedingen zijn
if (!empty($bids)) {
    // HTML-tabel genereren
    echo '<div class="container">';
    echo '<table class="table">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Afbeelding</th>';
    echo '<th>Kavel</th>';
    echo '<th>Product Titel</th>';
    echo '<th>Uw Bod</th>';
    echo '<th>Hoogste Bod</th>';
    echo '<th>Resterende Tijd</th>';
    echo '<th>Actie</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    // Loop door de biedingen en voeg rijen toe aan de tabel
    foreach ($bids as $bid) {
        
        $image = '<img src="/product_images/'.$bid['image'].'" alt="'.$bid['title'].'" class="img-fluid" style="max-height: 55px;">';
        echo '<tr>';
        echo '<td>' . $image . '</td>';
        echo '<td>' . $bid['lotid'] . '</td>';
        echo '<td>' . $bid['title'] . '</td>';
        echo '<td>' . $bid['user_bid'] . '</td>';
        echo '<td>' . $bid['highest_bid'] . '</td>';
        
        // Herformatteer de resterende tijd
        $remainingSeconds = $bid['remaining_seconds'];
        if ($remainingSeconds < 0) {
            $remainingTime = 'Kavel gesloten';
        } else {
            $days = floor($remainingSeconds / (3600 * 24));
            $hours = floor(($remainingSeconds - ($days * 3600 * 24)) / 3600);
            $minutes = floor(($remainingSeconds - ($days * 3600 * 24) - ($hours * 3600)) / 60);
            $seconds = $remainingSeconds % 60;
            $remainingTime = sprintf("%02dd %02dh %02dm %02ds", $days, $hours, $minutes, $seconds);
        }

        echo '<td>' . $remainingTime . '</td>';
        echo '<td><a href="/veiling/' . $bid['seoTitle'] . '/" class="btn btn-primary">Naar Kavel</a></td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div>';
} else {
    echo '<p>Er zijn geen actieve biedingen gevonden voor deze gebruiker.</p>';
}
?>
