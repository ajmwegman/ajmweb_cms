<?php
// Simple demo product page module with sidebar filters and four products
?>
<div class="container py-5">
  <div class="row">
    <aside class="col-md-3 mb-4">
      <h4 class="mb-3">Zoek</h4>
      <input type="text" id="search" class="form-control mb-4" placeholder="Zoek...">
      <h5>Categorieën</h5>
      <div class="form-check">
        <input class="form-check-input category-filter" type="checkbox" value="electronics" id="cat1">
        <label class="form-check-label" for="cat1">Elektronica</label>
      </div>
      <div class="form-check">
        <input class="form-check-input category-filter" type="checkbox" value="books" id="cat2">
        <label class="form-check-label" for="cat2">Boeken</label>
      </div>
      <div class="form-check">
        <input class="form-check-input category-filter" type="checkbox" value="home" id="cat3">
        <label class="form-check-label" for="cat3">Huis</label>
      </div>
    </aside>
    <div class="col-md-9">
      <div class="row" id="product-list">
        <div class="col-md-6 mb-4 product-card" data-category="electronics">
          <div class="card h-100">
            <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Product 1">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title">Product 1</h5>
              <p class="card-text mb-4">&euro;10,00</p>
              <button class="btn btn-primary btn-cart btn-interactive w-100 mb-2" data-id="1" data-name="Product 1" data-price="10" data-image="https://via.placeholder.com/300x200">In winkelmand</button>
              <button class="btn btn-outline-secondary btn-fav btn-interactive w-100" data-id="1">Favoriet</button>
            </div>
          </div>
        </div>
        <div class="col-md-6 mb-4 product-card" data-category="books">
          <div class="card h-100">
            <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Product 2">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title">Product 2</h5>
              <p class="card-text mb-4">&euro;15,50</p>
              <button class="btn btn-primary btn-cart btn-interactive w-100 mb-2" data-id="2" data-name="Product 2" data-price="15.5" data-image="https://via.placeholder.com/300x200">In winkelmand</button>
              <button class="btn btn-outline-secondary btn-fav btn-interactive w-100" data-id="2">Favoriet</button>
            </div>
          </div>
        </div>
        <div class="col-md-6 mb-4 product-card" data-category="home">
          <div class="card h-100">
            <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Product 3">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title">Product 3</h5>
              <p class="card-text mb-4">&euro;7,25</p>
              <button class="btn btn-primary btn-cart btn-interactive w-100 mb-2" data-id="3" data-name="Product 3" data-price="7.25" data-image="https://via.placeholder.com/300x200">In winkelmand</button>
              <button class="btn btn-outline-secondary btn-fav btn-interactive w-100" data-id="3">Favoriet</button>
            </div>
          </div>
        </div>
        <div class="col-md-6 mb-4 product-card" data-category="electronics">
          <div class="card h-100">
            <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Product 4">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title">Product 4</h5>
              <p class="card-text mb-4">&euro;22,00</p>
              <button class="btn btn-primary btn-cart btn-interactive w-100 mb-2" data-id="4" data-name="Product 4" data-price="22" data-image="https://via.placeholder.com/300x200">In winkelmand</button>
              <button class="btn btn-outline-secondary btn-fav btn-interactive w-100" data-id="4">Favoriet</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
