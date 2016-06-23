$(document).ready(function(){

	if($('#form_menu').length > 0){
		var tipo_vista = ($('#form_menu').attr('tipo_vista')).toUpperCase();
		if(tipo_vista === 'VER'){
			$('#form_menu').find('input').attr('disabled', 'disabled');
			$('#form_menu').find('select').attr('disabled', 'disabled');
		}
	}

	if($('#menu_nivel').length > 0){
		if($('#menu_nivel').val() === '1'){
			$('#menu_idpadre').attr('disabled', 'disabled');
			$('#menu_formulario').attr('disabled', 'disabled');
		}
	}

	$(document).on('change', '#menu_nivel', function(e){
		if($(this).val() === '1'){
			$('#menu_idpadre').attr('disabled', 'disabled');
			$('#menu_formulario').attr('disabled', 'disabled');
		}else{
			$('#menu_idpadre').removeAttr('disabled');
			$('#menu_formulario').removeAttr('disabled');
		}
	});

	$(document).on('click', '.btn_guardar', function(e){
		if($('#menu_nivel').val() != '1'){
			if($('#menu_idpadre').val()===''){
				bootbox.alert("<span class=\"glyphicon glyphicon-exclamation-sign\"></span> Seleccione un nivel superior para la opción.");
				return false;
			}
		}//end if
		$('#form_menu').submit();
		e.preventDefault();
	});


});