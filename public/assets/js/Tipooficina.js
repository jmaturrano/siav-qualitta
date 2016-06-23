$(document).ready(function(){

	if($('#form_tipooficina').length > 0){
		var tipo_vista = ($('#form_tipooficina').attr('tipo_vista')).toUpperCase();
		if(tipo_vista === 'VER'){
			$('#form_tipooficina').find('input').attr('disabled', 'disabled');
			$('#form_tipooficina').find('select').attr('disabled', 'disabled');
		}
	}

	$(document).on('click', '.btn_guardar', function(e){
		$('#form_tipooficina').submit();
		e.preventDefault();
	});

});