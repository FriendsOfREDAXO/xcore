$(document).on('rex:ready', function() {
	// panel toggler
	$('select.rexx-panel-toggler').each(function() {
		var options = $(this).find('option');

		$(this).on('change', function() {
			var values = $.map(options ,function(option) {
				$('[data-rexx-panel="' + option.value + '"]').hide();
			});

			$('[data-rexx-panel="' + this.value + '"]').show();
		}); 

		$(this).change();
	});

	// persistent tabs
	$('.rexx-persistent-tabs').each(function() {
		var jsTab = $(this).find('a[data-toggle="tab"]');

		jsTab.on("shown.bs.tab", function (e) {
			var id = $(e.target).attr("href");
			localStorage.setItem('selectedTab', id)
		});

		var selectedTab = localStorage.getItem('selectedTab');

		if (selectedTab != null) {
			$('a[data-toggle="tab"][href="' + selectedTab + '"]').tab('show');
		}
	});

	// labels with optional style
	$('.rexx-optional').each(function() {
		jLabel = $("label[for='" + $(this).attr('id') + "']");
		jLabelHtml = jLabel.html();

		jLabel.html(jLabelHtml + ' <span class="rexx-optional-msg"></span>');
	});
});

