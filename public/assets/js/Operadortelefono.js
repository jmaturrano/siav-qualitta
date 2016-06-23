$(document).ready(function(){

	if($('#form_operadortelefono').length > 0){
		var tipo_vista = ($('#form_operadortelefono').attr('tipo_vista')).toUpperCase();
		if(tipo_vista === 'VER'){
			$('#form_operadortelefono').find('input').attr('disabled', 'disabled');
			$('#form_operadortelefono').find('select').attr('disabled', 'disabled');
		}
	}

	$(document).on('click', '.btn_guardar', function(e){
		$('#form_operadortelefono').submit();
		e.preventDefault();
	});

});