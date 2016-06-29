$(document).ready(function(){

	if($('#form_certificadoslegales').length > 0){
		var tipo_vista = ($('#form_certificadoslegales').attr('tipo_vista')).toUpperCase();
		if(tipo_vista === 'VER'){
			$('#form_certificadoslegales').find('input').attr('disabled', 'disabled');
			$('#form_certificadoslegales').find('select').attr('disabled', 'disabled');
		}
	}

	$(document).on('click', '.btn_guardar', function(e){
		$('#form_certificadoslegales').submit();
		e.preventDefault();
	});

});