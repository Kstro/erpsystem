$(document).on('click', '.btAdd', function(event) {
	//console.log('add');
	var id = $('#txtId').val();
	//var probability=$('#txtProbability');

	if (id!='') {
		$('#txtId').val('');
		$('#txtName').val('');
		//$('#txtProbability').val('10');
		$('#pnAdd').slideDown();
		//probability.slider('setValue', 10);
	}
	else{
		$('#pnAdd').slideToggle();
	}
	$('#txtName').focus();
});
