

	Event.observe(window, 'load', function() {
		Event.observe('generate-admin', 'click',  function () {
			if (this.checked) {
				$('moduleadmin_container').show();
				if (undefined != $('name').value && $('name').value != '')
					$('moduleadmin').value = $('name').value + 'admin';
			} else {
				$('moduleadmin_container').hide();
				$('moduleadmin').value = '';
			}
		});
		
		Event.observe('name', 'keyup', function() { $('moduleadmin').value = $('name').value + 'admin'; });
		
	});

