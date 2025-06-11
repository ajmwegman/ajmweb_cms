  <section>

  <div class="row">
    <div class="col">
        <a class="btn btn-primary" href="/users/index.php">Terug</a>
        </div>
    </div>
<?php
// Roep de functie aan om de gewonnen veilingen van de gebruiker op te halen
$wonAuctions = $users->getUserWonAuctions($userId); // Zorg ervoor dat $userId is ingesteld op het ID van de gebruiker

// Controleer of er gewonnen veilingen zijn
if (!empty($wonAuctions)) {
    // HTML-tabel genereren
    echo '<div class="container mt-5">';
    echo '<h2>Gewonnen Veilingen</h2>';
    echo '<table class="table">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Afbeelding</th>';
    echo '<th>Kavel</th>';
    echo '<th>Naam</th>'; // Vervang 'userid' met 'Gebruikers ID'
    echo '<th>Hoogste Bod</th>';
    echo '<th>Commissie</th>';
    echo '<th>Verzendkosten</th>';
    echo '<th>Totaal</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    // Loop door de gewonnen veilingen en voeg rijen toe aan de tabel
    foreach ($wonAuctions as $auction) {
        // Afbeelding HTML-code
        $image = '<img src="/product_images/' . $auction['image'] . '" alt="' . $auction['title'] . '" class="img-fluid" style="max-height: 55px;">';
        $total = $auction['highestBid'] + $auction['commission'] + $auction['shippingCost'];
        // HTML-rij toevoegen aan de tabel
        echo '<tr>';
        echo '<td>' . $image . '</td>'; // Voeg de afbeelding toe
        echo '<td>' . $auction['lotid'] . '</td>'; // Voeg de kavel toe
        echo '<td>' . $auction['title'] . '</td>';
        echo '<td>&euro; ' . $auction['highestBid'] . '</td>';
        echo '<td>&euro; ' . $auction['commission'] . '</td>';
        echo '<td>&euro; ' . $auction['shippingCost'] . '</td>';
        echo '<td>&euro; ' . $total . '</td>';
        echo '</tr>';
    }

echo '</tbody>';
echo '</table>';
echo '</div>';
} else {
    echo '<p>Er zijn geen gewonnen veilingen gevonden voor deze gebruiker.</p>';
}
?>
      </section>
