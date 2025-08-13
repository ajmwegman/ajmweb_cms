document.addEventListener('DOMContentLoaded', function() {
  const tierBtn = document.getElementById('add-tier');
  const tierWrap = document.getElementById('tier-wrapper');
  const accountBtn = document.getElementById('add-account');
  const accountWrap = document.getElementById('account-wrapper');

  tierBtn.addEventListener('click', function() {
    const row = document.createElement('div');
    row.className = 'row mb-2';
    row.innerHTML = '<div class="col-md-6"><input type="number" name="tier_qty[]" class="form-control" placeholder="Aantal"></div>' +
                    '<div class="col-md-6"><input type="number" step="0.01" name="tier_price[]" class="form-control" placeholder="Prijs"></div>';
    tierWrap.appendChild(row);
  });

  accountBtn.addEventListener('click', function() {
    const row = document.createElement('div');
    row.className = 'row mb-2';
    row.innerHTML = '<div class="col-md-6"><input type="text" name="account_id[]" class="form-control" placeholder="Account ID"></div>' +
                    '<div class="col-md-6"><input type="number" step="0.01" name="account_price[]" class="form-control" placeholder="Prijs"></div>';
    accountWrap.appendChild(row);
  });
});
