$(document).ready(function(){
	
	if($('#form_aeronave').length > 0){
		var tipo_vista = ($('#form_aeronave').attr('tipo_vista')).toUpperCase();
		if(tipo_vista === 'VER'){
			$('#form_aeronave').find('input').attr('disabled', 'disabled');
			$('#form_aeronave').find('select').attr('disabled', 'disabled');
		}
	}

	$(document).on('click', '.btn_guardar', function(e){
		$('#form_aeronave').submit();
		e.preventDefault();
	});


});