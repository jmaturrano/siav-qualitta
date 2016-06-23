$(document).ready(function(){

	var tipo_vista = '';
	if($('#form_usuario').length > 0){
		tipo_vista = ($('#form_usuario').attr('tipo_vista')).toUpperCase();
		if(tipo_vista === 'VER'){
			$('#form_usuario').find('input').attr('disabled', 'disabled');
			$('#form_usuario').find('select').attr('disabled', 'disabled');
			$(".datepicker").datepicker('remove');
		}

	}

	$(document).on('change', '#dide_id', function(e){
		var max_length = parseInt($('option:selected', this).attr('data-length'));
		$('#usua_numero_documento').attr('maxlength', max_length);
		$('#dideid_maxlength').html('('+max_length + ' dig.)');
		e.preventDefault();
	});

	$(document).on('change', '#cambiar_clave', function(e){
		if($(this).val() === '1'){
			$('#usua_clave').removeAttr('disabled');
			$('#usua_clave').focus();
		}else{
			$('#usua_clave').val('');
			$('#usua_clave').attr('disabled', 'disabled');
		}
	});

	$(document).on('click', '.btn_guardar', function(e){
		$('#form_usuario').find('input').removeAttr('disabled');
		$('#form_usuario').find('select').removeAttr('disabled');
		$('#form_usuario').submit();
		e.preventDefault();
	});



	/* OFICINAS POR USUARIO */
	$(document).on('click', '.oficina-grupo', function(e){
		$('#btn_asignaroficina').attr('disabled', 'disabled');
		if($(this).hasClass('active')){
			$('#oficina-grupo').find('.oficina-grupo').removeClass('active');
		}else{
			if(tipo_vista === 'VER' || tipo_vista === 'EDITAR'){
				$('#oficina-grupo').find('.oficina-grupo').removeClass('active');
				$(this).addClass('active');
				var ofic_id 	= $(this).attr('data-oficid');
				var data_url 	= $(this).attr('data-url');
				var count_oficid = 0;
				$('#oficinas_asignadas').find('a.list-group-item').each(function(){
					if($(this).attr('data-oficid') == ofic_id){
						count_oficid++;
					}//end if
				});
				if(count_oficid === 0){
					$('#form_oficinausuario').attr('action', data_url);
					$('#btn_asignaroficina').removeAttr('disabled');
					$('#ofic_id').html('<option value="'+ofic_id+'">'+$(this).html()+'</option>');
					$('#ofic_id').selectpicker('refresh');
					$('#uxof_estadodefecto').val('N');
					$('#uxof_estadodefecto').selectpicker('refresh');
				}//end if
			}//end if
		}//end else
		e.preventDefault();
	});

	$(document).on('click', '#btn_asignar_oficina', function(e){
		$('#ofic_id').removeAttr('disabled');
		$('#form_oficinausuario').submit();
		e.preventDefault();
	});

	$(document).on('click', '.item-asignado .oficina-edit', function(e){
		$('#btn_asignaroficina').attr('disabled', 'disabled');
		$('#oficina-grupo').find('.oficina-grupo').removeClass('active');
		var $element = $(this).parent();
		var data_urledit = $element.attr('data-urledit');
		$('#form_oficinausuario').attr('action', data_urledit);
		var ofic_id = $element.attr('data-oficid');
		var ofic_nombre = $element.attr('data-oficname');
		var uxof_estadodefecto = $element.attr('data-uxofdefecto');
		$('#ofic_id').html('<option value="'+ofic_id+'">'+ofic_nombre+'</option>');
		$('#ofic_id').selectpicker('refresh');
		$('#uxof_estadodefecto').val(uxof_estadodefecto);
		$('#uxof_estadodefecto').selectpicker('refresh');
		$('#asignar_oficina').modal('show');
	});

	/* OFICINAS POR USUARIO - END*/


	/* ROLES POR OFICINA POR USUARIO */
	$(document).on('click', '.oficina_x_usuario', function(e){

		$('#btn_asignarrol').attr('disabled', 'disabled');
		$('#rol_id').attr('disabled', 'disabled');
		if($(this).hasClass('active')){
			$('#oficina_x_usuario').find('a.oficina_x_usuario').removeClass('active');
		}else{
			if(tipo_vista === 'VER' || tipo_vista === 'EDITAR'){

				$('#oficina_x_usuario').find('a.oficina_x_usuario').removeClass('active');
				$(this).addClass('active');

				var uxof_id 	= $(this).attr('data-uxofid');
				var data_url 	= $(this).attr('data-url');
				$('#uxof_id').val(uxof_id);
				$('#uxof_id').selectpicker('refresh');

				$('#form_rolusuario').attr('action', data_url);
				var rol_active = $('#roles_item').find('a.active').length;
				if(rol_active > 0){
					$('#btn_asignarrol').removeAttr('disabled');
				}//end if

			}//end if
		}//end else
	});

	$(document).on('click', '.roles_item', function(e){

		$('#btn_asignarrol').attr('disabled', 'disabled');
		$('#rol_id').attr('disabled', 'disabled');
		if($(this).hasClass('active')){
			$('#roles_item').find('a.roles_item').removeClass('active');
		}else{
			if(tipo_vista === 'VER' || tipo_vista === 'EDITAR'){

				$('#roles_item').find('a.roles_item').removeClass('active');
				$(this).addClass('active');

				var rol_id 		= $(this).attr('data-rolid');
				var data_url 	= $(this).attr('data-url');
				$('#rol_id').val(rol_id);
				$('#rol_id').selectpicker('refresh');

				$('#form_rolusuario').attr('action', data_url);
				var oficina_active = $('#oficina_x_usuario').find('a.active').length;
				if(oficina_active > 0){
					$('#btn_asignarrol').removeAttr('disabled');
				}//end if

			}//end if
		}//end else
	});

	$(document).on('click', '.item-asignado .rol-edit', function(e){
		$('#btn_asignarrol').attr('disabled', 'disabled');
		$('#oficina_x_usuario').find('a.oficina_x_usuario').removeClass('active');
		$('#roles_item').find('a.roles_item').removeClass('active');
		var $element = $(this).parent();
		var data_urledit = $element.attr('data-urledit');
		$('#form_rolusuario').attr('action', data_urledit);
		var uxof_id 	= $element.attr('data-uxofid');
		var rol_id 		= $element.attr('data-rolid');
		$('#uxof_id').val(uxof_id);
		$('#uxof_id').selectpicker('refresh');
		$('#rol_id').val(rol_id);
		$('#rol_id').removeAttr('disabled');
		$('#rol_id').selectpicker('refresh');
		$('#asignar_rol').modal('show');
	});

	$(document).on('click', '#btn_asignar_rol', function(e){
		$('#uxof_id').removeAttr('disabled');
		$('#rol_id').removeAttr('disabled');
		$('#form_rolusuario').submit();
		e.preventDefault();
	});


	/* ROLES POR OFICINA POR USUARIO - END */


});