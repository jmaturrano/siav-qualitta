$(document).ready(function(){
	if($('#form_modalidad').length > 0){
		var tipo_vista = ($('#form_modalidad').attr('tipo_vista')).toUpperCase();
		if(tipo_vista === 'VER'){
			$('#form_modalidad').find('input').attr('disabled', 'disabled');
			$('#form_modalidad').find('select').attr('disabled', 'disabled');
		}
	}

	$(document).on('click', '.btn_guardar', function(e){
		$('#form_modalidad').submit();
		e.preventDefault();
	});


});