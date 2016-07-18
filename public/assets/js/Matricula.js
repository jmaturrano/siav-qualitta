$(document).ready(function(){
	if($('#form_matricula').length > 0){
		var tipo_vista = ($('#form_matricula').attr('tipo_vista')).toUpperCase();
		if(tipo_vista === 'VER'){
			$('#form_matricula').find('input').attr('disabled', 'disabled');
			$('#form_matricula').find('select').attr('disabled', 'disabled');
			$('#form_matricula').find('input[type=checkbox]').checkboxX('refresh');
		}
	}

	$(document).on('click', '.btn_guardar', function(e){
		$('input[type=checkbox]').prop('checked', true);
		$('input').removeAttr('disabled');
		$('#form_matricula').submit();
		e.preventDefault();
	});

	$(document).on('change', '#carr_id', function(e){
		console.log($(this).val());
		if($(this).val() != ''){
			$('#matr_costoreal').val(0);
		}else{
			$('#matr_costoreal').val('');
		}
	});

});