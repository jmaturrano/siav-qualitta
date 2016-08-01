$(document).ready(function(){
	if($('#form_financiamiento').length > 0){

		var tipo_vista = ($('#form_financiamiento').attr('tipo_vista')).toUpperCase();
		if(tipo_vista === 'VER'){
			$(".datepicker").datepicker('remove');
			$('#form_financiamiento').find('input').attr('disabled', 'disabled');
			$('#form_financiamiento').find('select').attr('disabled', 'disabled');
			$('#form_financiamiento').find('input[type=checkbox]').checkboxX('refresh');
			$('#form_financiamiento').find('button').attr('disabled', 'disabled');
		}
	}

	$(document).on('click', '.btn_guardar', function(e){
        $('#form_financiamiento').submit();
		e.preventDefault();
	});



});