$(function() {
  const placeholderImage = 'https://via.placeholder.com/60x60?text=No+Image';

  let cart = [
    { id: 1, name: 'Product A', price: 10.00, quantity: 1, image: '' },
    { id: 2, name: 'Product B', price: 15.50, quantity: 2, image: 'https://via.placeholder.com/60' }
  ];

  function renderCart() {
    const $tbody = $('#cartTable tbody');
    $tbody.empty();
    let grandTotal = 0;

    cart.forEach(item => {
      const itemTotal = item.price * item.quantity;
      grandTotal += itemTotal;
      const $row = $(`
        <tr data-id="${item.id}">
          <td class="d-flex align-items-center">
            <img src="${item.image || placeholderImage}" alt="${item.name}" class="me-2" width="60" height="60">
            <span>${item.name}</span>
          </td>
          <td>
            <div class="input-group input-group-sm">
              <button class="btn btn-outline-secondary btn-decrease" type="button">-</button>
              <input type="text" class="form-control text-center quantity" value="${item.quantity}">
              <button class="btn btn-outline-secondary btn-increase" type="button">+</button>
            </div>
          </td>
          <td>€${item.price.toFixed(2)}</td>
          <td class="item-total">€${itemTotal.toFixed(2)}</td>
          <td><button class="btn btn-sm btn-danger btn-remove" title="Remove"><i class="bi bi-trash"></i></button></td>
        </tr>
      `);
      $tbody.append($row);
    });

    $('#grandTotal').text('€' + grandTotal.toFixed(2));
  }

  function updateQuantity(id, delta) {
    const item = cart.find(i => i.id === id);
    if (!item) return;
    item.quantity = Math.max(1, item.quantity + delta);
    renderCart();
  }

  function removeItem(id) {
    cart = cart.filter(i => i.id !== id);
    renderCart();
  }

  $(document).on('click', '.btn-increase', function() {
    const id = parseInt($(this).closest('tr').data('id'));
    updateQuantity(id, 1);
  });

  $(document).on('click', '.btn-decrease', function() {
    const id = parseInt($(this).closest('tr').data('id'));
    updateQuantity(id, -1);
  });

  $(document).on('click', '.btn-remove', function() {
    const id = parseInt($(this).closest('tr').data('id'));
    removeItem(id);
  });

  $(document).on('change', '.quantity', function() {
    const id = parseInt($(this).closest('tr').data('id'));
    const val = parseInt($(this).val(), 10);
    if (isNaN(val) || val < 1) {
      renderCart();
      return;
    }
    const item = cart.find(i => i.id === id);
    item.quantity = val;
    renderCart();
  });

  renderCart();
});
