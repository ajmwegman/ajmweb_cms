$(function() {
  function getCart() {
    return JSON.parse(localStorage.getItem('cart')) || [];
  }
  function saveCart(cart) {
    localStorage.setItem('cart', JSON.stringify(cart));
  }
  function getFavorites() {
    return JSON.parse(localStorage.getItem('favorites')) || [];
  }
  function saveFavorites(favs) {
    localStorage.setItem('favorites', JSON.stringify(favs));
  }

  $('.btn-cart').on('click', function() {
    const $btn = $(this);
    const product = {
      id: $btn.data('id'),
      name: $btn.data('name'),
      price: parseFloat($btn.data('price')),
      image: $btn.data('image'),
      quantity: 1
    };
    let cart = getCart();
    const existing = cart.find(i => i.id === product.id);
    if (existing) {
      existing.quantity += 1;
    } else {
      cart.push(product);
    }
    saveCart(cart);
    $btn.addClass('clicked').text('Toegevoegd');
  });

  $('.btn-fav').on('click', function() {
    const $btn = $(this);
    const id = $btn.data('id');
    let favs = getFavorites();
    const index = favs.indexOf(id);
    if (index === -1) {
      favs.push(id);
      $btn.addClass('clicked');
    } else {
      favs.splice(index, 1);
      $btn.removeClass('clicked');
    }
    saveFavorites(favs);
  });

  function filterProducts() {
    const search = $('#search').val().toLowerCase();
    const categories = $('.category-filter:checked').map(function(){return this.value;}).get();
    $('.product-card').each(function(){
      const $card = $(this);
      const title = $card.find('.card-title').text().toLowerCase();
      const category = $card.data('category');
      const matchSearch = title.includes(search);
      const matchCategory = categories.length === 0 || categories.includes(category);
      $card.toggle(matchSearch && matchCategory);
    });
  }

  $('#search').on('keyup', filterProducts);
  $('.category-filter').on('change', filterProducts);
});
