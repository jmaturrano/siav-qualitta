$(document).ready(function(){



	if($('#form_carrera').length > 0){

		var tipo_vista = ($('#form_carrera').attr('tipo_vista')).toUpperCase();

		if(tipo_vista === 'VER'){

			$('#form_carrera').find('input').attr('disabled', 'disabled');

			$('#form_carrera').find('select').attr('disabled', 'disabled');

		}

	}



	$(document).on('click', '.btn_guardar', function(e){

		$('#form_carrera').submit();

		e.preventDefault();

	});



});