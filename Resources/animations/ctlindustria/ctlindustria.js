$(document).on('click', '.btnAdd', function(event) {
	//console.log('add');
        $('.panel-heading').empty();
        $('.panel-heading').append('Add');
        $('#pnAdd').slideToggle();
	
	$('#txtName').focus();
        $('#id').val(-1);
});
