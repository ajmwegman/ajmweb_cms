<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Shopping Cart</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa+RmXQjaeP4bI5sE5Rq9DyvOMsv02HX7i/uhR8P8Bz1kzI" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" integrity="sha384-7QXc9E+u6KDAdAm8ZI1sC3yRyvE4s46HoPazTA/gkGEXUXMaLLq5yRvCNrI6V3GA" crossorigin="anonymous">
</head>
<body>
  <div class="container py-5">
    <h1 class="mb-4">Winkelwagen</h1>
    <table class="table" id="cartTable">
      <thead>
        <tr>
          <th>Product</th>
          <th style="width:150px">Aantal</th>
          <th>Prijs</th>
          <th>Totaal</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <!-- Rijen worden dynamisch toegevoegd -->
      </tbody>
    </table>
    <div class="text-end fw-bold">Grand Total: <span id="grandTotal">€0,00</span></div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJ+Y4W36P6fKLDg0oa0Uj2T9g8v+vi3D6Z8wM=" crossorigin="anonymous"></script>
  <script src="js/cart.js"></script>
</body>
</html>
