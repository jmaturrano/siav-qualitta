$(document).ready(function(){

	if($('#form_oficina').length > 0){
		if(($('#form_oficina').attr('tipo_vista')).toUpperCase() === 'VER'){
			$('#form_oficina').find('input').attr('disabled', 'disabled');
			$('#form_oficina').find('select').attr('disabled', 'disabled');
		}
		$('#form_oficina').find('#ogru_id').attr('disabled', 'disabled');
	}

	if($('#form_departamento').length > 0){
		if(($('#form_departamento').attr('tipo_vista')).toUpperCase() === 'VER'){
			$('#form_departamento').find('input').attr('disabled', 'disabled');
			$('#form_departamento').find('select').attr('disabled', 'disabled');
		}
	}

	$(document).on('click', '.btn_guardar', function(e){
		if($('#form_departamento').length > 0){
			$('#form_departamento').submit();
		}
		if($('#form_oficina').length > 0){
			$('#form_oficina').submit();
		}
		e.preventDefault();
	});

});