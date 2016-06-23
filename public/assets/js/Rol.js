$(document).ready(function(){

	if($('#form_rol').length > 0){
		var tipo_vista = ($('#form_rol').attr('tipo_vista')).toUpperCase();
		if(tipo_vista === 'VER'){
			$('#form_rol').find('input').attr('disabled', 'disabled');
			$('#form_rol').find('input[type=checkbox]').checkboxX('refresh');
			$('#form_rol').find('#txt_buscar').removeAttr('disabled');
		}
	}


	$(document).on('click', '.btn_guardar', function(e){
		var td_control = '';
		var td_actions = '';
		$('#formcontrols table tbody tr').each(function(){
			td_control = $(this).find('.td-control');
			td_actions = $(this).find('.td-actions');

			//if(td_control.is(':checked')){
			if(!td_control.find('.mxro_accesa').is(':checked')){
				td_control.find('.mxro_accesa').prop('checked', true);
			}
			if(td_control.find('.mxro_accesa').val() === ''){
				td_control.find('.mxro_accesa').val('0');
			}
			if(!td_actions.find('.mxro_ingresa').is(':checked')){
				td_actions.find('.mxro_ingresa').prop('checked', true);
			}
			if(td_control.find('.mxro_ingresa').val() === ''){
				td_control.find('.mxro_ingresa').val('0');
			}
			if(!td_actions.find('.mxro_elimina').is(':checked')){
				td_actions.find('.mxro_elimina').prop('checked', true);
			}
			if(td_control.find('.mxro_elimina').val() === ''){
				td_control.find('.mxro_elimina').val('0');
			}
			if(!td_actions.find('.mxro_modifica').is(':checked')){
				td_actions.find('.mxro_modifica').prop('checked', true);
			}
			if(td_control.find('.mxro_modifica').val() === ''){
				td_control.find('.mxro_modifica').val('0');
			}
			if(!td_actions.find('.mxro_consulta').is(':checked')){
				td_actions.find('.mxro_consulta').prop('checked', true);
			}
			if(td_control.find('.mxro_consulta').val() === ''){
				td_control.find('.mxro_consulta').val('0');
			}
			if(!td_actions.find('.mxro_imprime').is(':checked')){
				td_actions.find('.mxro_imprime').prop('checked', true);
			}
			if(td_control.find('.mxro_imprime').val() === ''){
				td_control.find('.mxro_imprime').val('0');
			}
			if(!td_actions.find('.mxro_exporta').is(':checked')){
				td_actions.find('.mxro_exporta').prop('checked', true);
			}
			if(td_control.find('.mxro_exporta').val() === ''){
				td_control.find('.mxro_exporta').val('0');
			}
			//}

		});

		//setTimeout(function(){
			$('#form_rol').submit();
		//}, 500);
		e.preventDefault();
	});

	$(document).on('change', '#formcontrols table tbody tr .mxro_accesa', function(e){
		var $element_perm = $(this).parent().parent().parent().parent();
		if($(this).val() === '1'){
			$element_perm.find('.td-visible').find('.mxro_ingresa').val('1');
			$element_perm.find('.td-visible').find('.mxro_elimina').val('1');
			$element_perm.find('.td-visible').find('.mxro_modifica').val('1');
			$element_perm.find('.td-visible').find('.mxro_consulta').val('1');
			$element_perm.find('.td-visible').find('.mxro_imprime').val('1');
			$element_perm.find('.td-visible').find('.mxro_exporta').val('1');
		}else{
			$element_perm.find('.td-visible').find('.mxro_ingresa').val('0');
			$element_perm.find('.td-visible').find('.mxro_elimina').val('0');
			$element_perm.find('.td-visible').find('.mxro_modifica').val('0');
			$element_perm.find('.td-visible').find('.mxro_consulta').val('0');
			$element_perm.find('.td-visible').find('.mxro_imprime').val('0');
			$element_perm.find('.td-visible').find('.mxro_exporta').val('0');
		}
		$element_perm.find('.row_permisos').checkboxX('refresh');
	});


	$(document).on('keypress', '#formsearch #txt_buscar', function(e){
		if(e.which === 13){
			window.location.href = $('#formsearch').attr('data-formajax')+'/'+$(this).val();
		}
	});





});