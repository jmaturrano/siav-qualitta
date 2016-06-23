$(document).ready(function(){

	if($('#form_docidentidad').length > 0){
		var tipo_vista = ($('#form_docidentidad').attr('tipo_vista')).toUpperCase();
		if(tipo_vista === 'VER'){
			$('#form_docidentidad').find('input').attr('disabled', 'disabled');
		}
	}

	$(document).on('click', '.btn_guardar', function(e){
		$('#form_docidentidad').submit();
		e.preventDefault();
	});

});