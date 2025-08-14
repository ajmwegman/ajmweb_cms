<section class="mt-5">
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
    <div class="text-end mt-4"><a href="/modules/checkout.php" class="btn btn-primary">Afrekenen</a></div>
  </div>
</section>
<script src="/js/cart.js"></script>
