$( document ).ready( function () {
	// Set the correct location for this module
	var location = '/admin/modules/menu/';
	
	// Generate unique hash for new menu items
	var hash = generate_token(16) + '_' + Date.now();
	$("#hash").val(hash);
	
	// Global variables for delete functionality
	window.currentDeleteButton = null;
	window.pendingDeleteData = null;

	// Attach event handlers
	attachEventHandlers();

	// Function to re-attach event handlers after AJAX content loads
	function attachEventHandlers() {
		// Autosave functionality
		$(document).off('keyup change', '[data-field]').on('keyup change', '[data-field]', function() {
			var $this = $(this);
			var field = $this.data('field');
			var set = $this.data('set');
			var hash = $this.data('hash');
			var message = $this.data('message');
			
			if (field && set && hash) {
				clearTimeout($this.data('autosaveTimer'));
				var timer = setTimeout(function() {
					autosaveField($this, field, set, hash, message);
				}, 1000);
				$this.data('autosaveTimer', timer);
			}
		});

		// Add form submission
		$(document).off('submit', '#menuform').on('submit', '#menuform', function(e) {
			e.preventDefault();
			submitAddForm($(this));
		});

		// Delete button click (outside modal)
		$(document).off('click', '.btn-delete').on('click', '.btn-delete', function(e) {
			if (!$(this).closest('.modal').length) {
				handleDeleteButtonClick($(this));
			}
		});

		// Modal events
		$(document).off('shown.bs.modal', '#dialogModal').on('shown.bs.modal', '#dialogModal', function() {
			handleModalShown();
		});

		$(document).off('hidden.bs.modal', '#dialogModal').on('hidden.bs.modal', '#dialogModal', function() {
			window.pendingDeleteData = null;
		});

		// Delete confirmation button in modal
		$(document).off('click', '#dialogModal .btn-delete').on('click', '#dialogModal .btn-delete', function(e) {
			handleDeleteConfirmation();
		});
	}

	// Autosave function
	function autosaveField($field, field, set, hash, message) {
		var value = $field.val();
		
		$.ajax({
			url: location + 'bin/autosave.php',
			type: 'POST',
			data: {
				id: hash,
				field: field,
				value: value
			},
			success: function(response) {
				if (response.includes('spinner-grow')) {
					// Success - spinner shows
				} else {
					showMessage('Fout bij opslaan', 'error');
				}
			},
			error: function() {
				showMessage('Fout bij opslaan', 'error');
			}
		});
	}

	// Add form submission
	function submitAddForm($form) {
		var formData = $form.serialize();
		
		$.ajax({
			url: location + 'bin/add.php',
			type: 'POST',
			data: formData,
			success: function(response) {
				console.log('Add response received:', response);
				if (response.includes('toegevoegd')) {
					showMessage('Menu item toegevoegd', 'success');
					
					// Reset the form
					$form[0].reset();
					
					// Close the modal
					$('#modal').modal('hide');
					
					// Simple approach: just reload the page to show the new item
					window.location.reload();
				} else {
					showMessage('Fout bij toevoegen', 'error');
				}
			},
			error: function() {
				showMessage('Fout bij toevoegen', 'error');
			}
		});
	}

	// Handle delete button click
	function handleDeleteButtonClick($button) {
		var id = $button.val();
		var hash = $button.data('hash');
		var message = $button.data('message');
		
		if (id || hash) {
			window.pendingDeleteData = {
				id: id,
				hash: hash,
				message: message,
				button: $button
			};
			
			// Set modal data
			var $modal = $('#dialogModal');
			$modal.attr('data-id', id || '');
			$modal.attr('data-hash', hash || '');
			$modal.attr('data-message', message || '');
			
			// Update modal content
			$modal.find('.modal-body').html('<p>' + (message || 'Weet je zeker dat je dit item wilt verwijderen?') + '</p>');
			
			// Set delete button data
			var $deleteBtn = $modal.find('.btn-delete');
			$deleteBtn.attr('data-delete-id', id || '');
			$deleteBtn.attr('data-delete-hash', hash || '');
		}
	}

	// Handle modal shown
	function handleModalShown() {
		var $modal = $('#dialogModal');
		var $deleteBtn = $modal.find('.btn-delete');
		
		// Focus the delete button for accessibility
		$deleteBtn.focus();
	}

	// Handle delete confirmation
	function handleDeleteConfirmation() {
		var $modal = $('#dialogModal');
		var id = $modal.attr('data-id');
		var hash = $modal.attr('data-hash');
		
		if (!id && !hash) {
			showMessage('Geen ID of hash gevonden voor delete', 'error');
			return;
		}

		var deleteData = hash ? { id: hash } : { id: id };
		
		// Debug: log what we're sending
		console.log('Sending delete request to:', location + 'bin/delete.php');
		console.log('Delete data:', deleteData);
		
		$.ajax({
			url: location + 'bin/delete.php',
			type: 'POST',
			data: deleteData,
			dataType: 'text',
			success: function(response) {
				console.log('Delete response received:', response);
				// Check if response contains success message
				if (response.includes('success') || response.includes('verwijderd')) {
					showMessage('Item succesvol verwijderd', 'success');
					
					// Find and remove the correct row from DOM using multiple strategies
					var $rowToRemove = findRowToRemove(id, hash);
					
					if ($rowToRemove && $rowToRemove.length > 0) {
						// Successfully found the row - remove it with animation
						$rowToRemove.fadeOut(300, function() {
							$(this).remove();
						});
					} else {
						// Could not find the row - show warning but don't reload
						showMessage('Item verwijderd uit database. De lijst wordt bijgewerkt.', 'warning');
						// Use a more gentle approach - just hide the item temporarily
						hideItemTemporarily(id, hash);
					}
					
					// Close modal
					$modal.modal('hide');
				} else {
					showMessage('Fout bij verwijderen: ' + response, 'error');
				}
			},
			error: function(xhr, status, error) {
				console.error('Delete AJAX error:', {xhr, status, error});
				showMessage('Fout bij verwijderen: ' + error, 'error');
			}
		});
	}

	// Function to find the row to remove using multiple strategies
	function findRowToRemove(id, hash) {
		var $row = null;
		
		// Strategy 1: Look for the delete button and find its container
		if (id) {
			var $deleteBtn = $('#btn' + id);
			if ($deleteBtn.length > 0) {
				$row = $deleteBtn.closest('tr, .row, .menu-item, [class*="item"]');
			}
		}
		
		// Strategy 2: Look for data attributes on rows
		if (!$row && hash) {
			$row = $('[data-hash="' + hash + '"]').closest('tr, .row, .menu-item, [class*="item"]');
		}
		
		// Strategy 3: Look for data attributes on rows
		if (!$row && id) {
			$row = $('[data-id="' + id + '"]').closest('tr, .row, .menu-item, [class*="item"]');
		}
		
		// Strategy 4: Look for the button in the global pending data
		if (!$row && window.pendingDeleteData && window.pendingDeleteData.button) {
			$row = window.pendingDeleteData.button.closest('tr, .row, .menu-item, [class*="item"]');
		}
		
		// Strategy 5: Look for any element containing the button
		if (!$row && id) {
			$row = $('#btn' + id).parents().filter(function() {
				return $(this).has('button, input, select').length > 0;
			}).first();
		}
		
		return $row;
	}

	// Function to hide item temporarily instead of removing it
	function hideItemTemporarily(id, hash) {
		var $item = findRowToRemove(id, hash);
		if ($item && $item.length > 0) {
			$item.css('opacity', '0.3').css('pointer-events', 'none');
		}
	}

	// Show message function
	function showMessage(message, type) {
		var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
		var $alert = $('<div class="alert ' + alertClass + ' alert-dismissible fade show" role="alert">' +
			message +
			'<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
			'</div>');
		
		$('.container').first().prepend($alert);
		
		// Auto-hide after 5 seconds
		setTimeout(function() {
			$alert.fadeOut(300, function() {
				$(this).remove();
			});
		}, 5000);
	}

	// Sort functionality
	$(document).off('click', '.sort-btn').on('click', '.sort-btn', function() {
		var direction = $(this).data('direction');
		var hash = $(this).closest('tr').data('hash');
		
		$.ajax({
			url: location + 'bin/sort.php',
			type: 'POST',
			data: {
				direction: direction,
				hash: hash
			},
			success: function(response) {
				if (response.includes('success')) {
					location.reload();
				} else {
					showMessage('Fout bij sorteren', 'error');
				}
			},
			error: function() {
				showMessage('Fout bij sorteren', 'error');
			}
		});
	});

	// Activate/deactivate functionality
	$(document).off('click', '.activate-btn').on('click', '.activate-btn', function() {
		var $this = $(this);
		var hash = $this.closest('tr').data('hash');
		var action = $this.data('action');
		
		$.ajax({
			url: location + 'bin/activate.php',
			type: 'POST',
			data: {
				action: action,
				hash: hash
			},
			success: function(response) {
				if (response.includes('success')) {
					location.reload();
				} else {
					showMessage('Fout bij ' + action, 'error');
				}
			},
			error: function() {
				showMessage('Fout bij ' + action, 'error');
			}
		});
	});
});

// Hash generation function
function generate_token(length) {
	var result = '';
	var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	var charactersLength = characters.length;
	for (var i = 0; i < length; i++) {
		result += characters.charAt(Math.floor(Math.random() * charactersLength));
	}
	return result;
}

