$(document).on('click', '.btnAdd', function(event) {
	//console.log('add');
	var id = $('#txtId').val();
	var probability=$('#txtProbability');
	//Cambiar nombre del panel heading para add (Inserción)
	$('.panel-heading').html('Add');
	if (id!='') {
		$('#txtId').val('');
		$('#txtName').val('');
		$('#txtProbability').val('10');
		$('#pnAdd').slideDown();
		probability.slider('setValue', 10);
	}
	else{
		$('#pnAdd').slideToggle();
	}
	$('#txtName').focus();
});
