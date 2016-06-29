$(document).ready(function(){
	if($('#form_curso').length > 0){
		var tipo_vista = ($('#form_curso').attr('tipo_vista')).toUpperCase();
		if(tipo_vista === 'VER'){
			$('#form_curso').find('input').attr('disabled', 'disabled');
			$('#form_curso').find('select').attr('disabled', 'disabled');
		}
	}

	$(document).on('click', '.btn_guardar', function(e){
		$('#form_curso').submit();
		e.preventDefault();
	});

});