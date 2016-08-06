$(document).ready(function(){

	var tipo_vista = '';
	if($('#form_alumno').length > 0){
		tipo_vista = ($('#form_alumno').attr('tipo_vista')).toUpperCase();
		if(tipo_vista === 'VER'){
			$('#form_alumno').find('input').attr('disabled', 'disabled');
			$('#form_alumno').find('select').attr('disabled', 'disabled');
			$(".datepicker").datepicker('remove');
		}//end else


		/* REPORTE POR REGISTRO DE ALUMNO */
		var reporte_mail 	= $('#form_alumno').attr('reporte_mail');
		var mail_confirm 	= $('#form_alumno').attr('mail_confirm');
		var mail_cancel 	= $('#form_alumno').attr('mail_cancel');
		if(reporte_mail){
			setTimeout(function(){
		        bootbox.dialog({
		            title: '<h3 class="dialog-alternativo"><span class=\"glyphicon glyphicon-envelope\"></span> Reportar nuevo ingreso</h3>',
		            message: "El registro se ha completado correctamente. ¿Desea reportar via email?",
		            onEscape: function(){
		            	bootbox.hideAll();
		            	window.top.location = mail_cancel;
		            },
		            animate: false,
		            className: 'bootbox-custom',
		            buttons: {
			            cancel: {
						    label: 'Cancelar',
						    className: "btn-default",
						    callback: function(){
						    	bootbox.hideAll();
						    	window.top.location = mail_cancel;
						    }
			            },
			            confirm: {
						    label: 'Ok',
						    className: "btn-warning",
						    callback: function(){
						    	window.top.location = mail_confirm;
						    }
			            }
		            }
		        });
			}, 500);
		}//end else
		/* REPORTE POR REGISTRO DE ALUMNO - END */

	}//end if

	$(document).on('change', '#dide_id', function(e){
		var max_length = parseInt($('option:selected', this).attr('data-length'));
		$('#alum_numero_documento').attr('maxlength', max_length);
		$('#dideid_maxlength').html('('+max_length + ' dig.)');
		e.preventDefault();
	});

	$(document).on('click', '.btn_guardar', function(e){
		$('#form_alumno').find('input').removeAttr('disabled');
		$('#form_alumno').find('select').removeAttr('disabled');
		$('#form_alumno').submit();
		e.preventDefault();
	});



	/* TELEFONOS POR ALUMNO */

	$(document).on('click', '#btn_agregartelefono', function(e){
		var data_urlnew 	= $(this).attr('data-url');
		$('#form_telefonoxalumno').attr('action', data_urlnew);
		$('#opte_id').val('');
		$('#opte_id').selectpicker('refresh');
		$('#txal_numero').val('');
		$('#txal_principal').val('0');
		$('#txal_principal').checkboxX('refresh');
	});

	$(document).on('change', '#opte_id', function(e){
		if($(this).val() != ''){
			$('#txal_numero').focus();
		}//end else
	});

	$(document).on('click', '#btn_agregar_telefono', function(e){
		$('input[type=checkbox]').prop('checked', true);
		$('#form_telefonoxalumno').submit();
		e.preventDefault();
	});

	$(document).on('click', '.item-asignado .item-edit', function(e){
		var $element 		= $(this).parent();
		var data_urledit 	= $element.attr('data-urledit');
		var opte_id 		= $element.attr('data-opteid');
		var txal_numero 	= $element.attr('data-txalnumero');
		var txal_principal 	= $element.attr('data-txalprincipal');

		$('#form_telefonoxalumno').attr('action', data_urledit);
		$('#opte_id').val(opte_id);
		$('#opte_id').selectpicker('refresh');
		$('#txal_numero').val(txal_numero);
		$('#txal_principal').val((txal_principal === 'S') ? '1' : '0');
		$('#txal_principal').checkboxX('refresh');
		$('#agregar_telefono').modal('show');
		e.preventDefault();
	});

	/* TELEFONOS POR ALUMNO - END */



	/* APODERADOS POR ALUMNO */

	$(document).on('click', '#btn_agregarapoderado', function(e){
		var data_urlnew 	= $(this).attr('data-url');
		$('#form_apoderadousuario').attr('action', data_urlnew);
		$('#apoa_nombre').val('');
		$('#apoa_apellido').val('');
		$('#apoa_direccion').val('');
		$('#apoa_telefijo').val('');
		$('#apoa_telemovil').val('');
		$('#apoa_email').val('');
	});

	$(document).on('click', '.apoderado-edit', function(e){
		var data_urledit 	= $(this).attr('data-urledit');
		var apoa_nombre 	= $(this).attr('data-apoanombre');
		var apoa_apellido 	= $(this).attr('data-apoaapellido');
		var apoa_direccion 	= $(this).attr('data-apoadireccion');
		var apoa_telefijo 	= $(this).attr('data-apoatelefijo');
		var apoa_telemovil 	= $(this).attr('data-apoatelemovil');
		var apoa_email 		= $(this).attr('data-apoaemail');

		$('#form_apoderadousuario').attr('action', data_urledit);
		$('#apoa_nombre').val(apoa_nombre);
		$('#apoa_apellido').val(apoa_apellido);
		$('#apoa_direccion').val(apoa_direccion);
		$('#apoa_telefijo').val(apoa_telefijo);
		$('#apoa_telemovil').val(apoa_telemovil);
		$('#apoa_email').val(apoa_email);
		$('#agregar_apoderado').modal('show');
	});

	$(document).on('click', '#btn_agregar_apoderado', function(e){
		$('#form_apoderadousuario').submit();
		e.preventDefault();
	});

	/* APODERADOS POR ALUMNO - END */



});