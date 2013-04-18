jQuery(document).ready(function() {
	if(jQuery('select#explore_type').length > 0 && jQuery('select#explore_type').val() != 'all') {
		
		jQuery('table.form-table tr:eq(3)').hide();
		jQuery('table.form-table tr:eq(4)').hide();
		jQuery('table.form-table tr:eq(5)').hide();
	}
	
	jQuery('select#explore_type').change(function() {
		if (this.value == 'all') {
			jQuery('table.form-table tr').show(100);
		} else {

			jQuery('table.form-table tr:eq(3)').hide(100);
			jQuery('table.form-table tr:eq(4)').hide(100);
			jQuery('table.form-table tr:eq(5)').hide(100);
		}
	});
	
	jQuery('select#sort').change(function() {
		if(jQuery('select#sort_direction').val() == 0 ) {
			jQuery('select#sort_direction').val('asc');
		} else if (jQuery('select#sort').val() == 0) {
			jQuery('select#sort_direction').val(0);
		}
	});
	
});