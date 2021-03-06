var base_url;
var ruta;

$(document).ready(function(){
    base_url = $("#base_url").val();
    ruta = $("#ruta").val();





	/* VERIFICAR PERMISOS */
	if(typeof(arr_permisos) != "undefined"){
		//arr_permisos.mxro_ingresa;
		//arr_permisos.mxro_modifica;
		//arr_permisos.mxro_consulta;
		//arr_permisos.mxro_elimina;
		//arr_permisos.mxro_imprime;
		//arr_permisos.mxro_exporta;
		console.log(arr_permisos);
		$(document).on('click', '.btn_nuevo', function(e){
			if(arr_permisos.mxro_ingresa === '0'){
				e.stopPropagation();
				bootbox.alert("<span class=\"glyphicon glyphicon-exclamation-sign\"></span> Lo sentimos. Usted no cuenta con un perfil asignado para realizar esta operación.");
				return false;
			}
		});
		$(document).on('click', '.btn_editar', function(e){
			if(arr_permisos.mxro_modifica === '0'){
				e.stopPropagation();
				bootbox.alert("<span class=\"glyphicon glyphicon-exclamation-sign\"></span> Lo sentimos. Usted no cuenta con un perfil asignado para realizar esta operación.");
				return false;
			}
		});
		if(arr_permisos.mxro_modifica === '0'){
			$(".btn-editar").attr("disabled","disabled");
		}
		$(document).on('click', '.btn_consulta', function(e){
			if(arr_permisos.mxro_consulta === '0'){
				e.stopPropagation();
				bootbox.alert("<span class=\"glyphicon glyphicon-exclamation-sign\"></span> Lo sentimos. Usted no cuenta con un perfil asignado para realizar esta operación.");
				return false;
			}
		});
		$(document).on('click', '.btn_delete', function(e){
			if(arr_permisos.mxro_elimina === '0'){
				bootbox.alert("<span class=\"glyphicon glyphicon-exclamation-sign\"></span> Lo sentimos. Usted no cuenta con un perfil asignado para realizar esta operación.");
				return false;
			}
		});
		$(document).on('click', '.btn_imprimir', function(e){
			if(arr_permisos.mxro_imprime === '0'){
				bootbox.alert("<span class=\"glyphicon glyphicon-exclamation-sign\"></span> Lo sentimos. Usted no cuenta con un perfil asignado para realizar esta operación.");
				return false;
			}
		});
		$(document).on('click', '.btn_exportar', function(e){
			if(arr_permisos.mxro_exporta === '0'){
				bootbox.alert("<span class=\"glyphicon glyphicon-exclamation-sign\"></span> Lo sentimos. Usted no cuenta con un perfil asignado para realizar esta operación.");
				return false;
			}
		});

	}
	/* VERIFICAR PERMISOS */


	/* ELIMINAR FILA DE UN ELEMENTO LI > A */
	$(document).on('click', '.item-asignado .btn_delete', function(e){
		var $element = $(this).parent();
		if($element.attr('disabled')){
			return false;
		}
		var data_url = $element.attr('data-url');
		$element.css('background-color', '#FFF0F5');
        bootbox.dialog({
            title: '<h3 class="dialog-delete"><span class=\"btn-icon-only icon-trash\"></span> Eliminar un elemento</h3>',
            message: "¿Está seguro de eliminar este elemento?",
            onEscape: function(){
            	bootbox.hideAll();
            	$element.css('background-color', '');
            },
            animate: false,
            className: 'bootbox-custom',
            buttons: {
	            cancel: {
				    label: 'Cancelar',
				    className: "btn-default",
				    callback: function(){
				    	bootbox.hideAll();
				    	$element.css('background-color', '');
				    }
	            },
	            confirm: {
				    label: 'Ok',
				    className: "btn-danger",
				    callback: function(){
				    	window.top.location = data_url;
				    }
	            }
            }
        });
		e.preventDefault();
	});

	/* ELIMINAR FILA DE UNA TABLA GENERAL */
	if($('#formcontrols').find('table').length > 0){
		$(document).on('click', '#formcontrols table .tr_delete', function(e){
			if($(this).attr('disabled')){
				return false;
			}
			if(typeof(arr_permisos) != "undefined"){
				if(arr_permisos.mxro_elimina === '0'){
					bootbox.alert("<span class=\"glyphicon glyphicon-exclamation-sign\"></span> Lo sentimos. Usted no cuenta con un perfil asignado para realizar esta operación.");
					return false;
				}
			}
			var data_url = $(this).attr('data-url');
			var $row = $(this).closest("tr");       // Finds the closest row <tr> 
			var $tds = $row.find("td");             // Finds all children <td> elements

			$.each($tds, function() {               // Visits every single <td> element
			     $(this).css("background-color", "#FFF0F5");
			    //console.log($(this).text());        // Prints out the text within the <td>
			});
	        bootbox.dialog({
	            title: '<h3 class="dialog-delete"><span class=\"btn-icon-only icon-trash\"></span> Eliminar un elemento</h3>',
	            message: "¿Está seguro de eliminar este elemento?",
	            onEscape: function(){ 
	            	bootbox.hideAll();
	            	$.each($tds, function() {               // Visits every single <td> element
					    $(this).css("background-color", "");
							    //console.log($(this).text());        // Prints out the text within the <td>
					}); 
	            },
	            animate: false,
	            className: 'bootbox-custom',
	            buttons: {
		            cancel: {
					    label: 'Cancelar',
					    className: "btn-default",
					    callback: function(){
					    	bootbox.hideAll();
					    	$.each($tds, function() {               // Visits every single <td> element
							    $(this).css("background-color", "");
									    //console.log($(this).text());        // Prints out the text within the <td>
							});
					    }
		            },
		            confirm: {
					    label: 'Ok',
					    className: "btn-danger",
					    callback: function(){
					    	window.top.location = data_url;
					    }
		            }
	            }
	        });
			e.preventDefault();
		});
	}
	/* FIN - ELIMINAR FILA DE UNA TABLA GENERAL */


	/* DATEPICKER GENERAL */
	$('.datepickerx input').datepicker({
        format: 'dd/mm/yyyy',
        language: "es",
        todayBtn: true,
        keyboardNavigation: false
    });
	/* FIN - DATEPICKER GENERAL */


	/* CHECKBOX */
	$(".chkbx_x").checkboxX();
	/* FIN - CHECKBOX */



	/* TIMEPICKER */
	$('.timepicker').datetimepicker({
		format: 'LT'
	});
	/* FIN - TIMEPICKER */




	/* UBIGEO */
	$(document).on('change', '#depa_id.selectpicker-ubig', function(e){
		var item_subruta 				= $(this).attr('data-subruta');
		var depa_id 					= $(this).val();
        $.getJSON(item_subruta.replace('{SELECTED}', depa_id),function(data){
        	$('#prov_id').html('<option value="">Seleccione</option>');
        	$('#dist_id').html('<option value="">Seleccione</option>');
        	$('#cepo_id').html('<option value="">Seleccione</option>');
			for (var i = 0; i <= data.length - 1; i++) {
				var prov_id 			= data[i].prov_id;
				var prov_descripcion 	= data[i].prov_descripcion;
				$('#prov_id').append('<option value="'+prov_id+'">'+prov_descripcion+'</option>');
			}//end for
			$('.selectpicker-ubig').selectpicker('refresh');
        })
        .fail(function(){
            bootbox.alert("<span class=\"glyphicon glyphicon-remove\"></span> No se han podido cargar las provincias");
        });
	});
	$(document).on('change', '#prov_id.selectpicker-ubig', function(e){
		var item_subruta 				= $(this).attr('data-subruta');
		var prov_id 					= $(this).val();
        $.getJSON(item_subruta.replace('{SELECTED}', prov_id),function(data){
        	$('#dist_id').html('<option value="">Seleccione</option>');
        	$('#cepo_id').html('<option value="">Seleccione</option>');
			for (var i = 0; i <= data.length - 1; i++) {
				var dist_id 			= data[i].dist_id;
				var dist_descripcion 	= data[i].dist_descripcion;
				$('#dist_id').append('<option value="'+dist_id+'">'+dist_descripcion+'</option>');
			}//end for
			$('.selectpicker-ubig').selectpicker('refresh');
        })
        .fail(function(){
            bootbox.alert("<span class=\"glyphicon glyphicon-remove\"></span> No se han podido cargar los distritos");
        });
	});
	$(document).on('change', '#dist_id.selectpicker-ubig', function(e){
		var item_subruta 				= $(this).attr('data-subruta');
		var dist_id 					= $(this).val();
		/*
        $.getJSON(item_subruta.replace('{SELECTED}', dist_id),function(data){
        	$('#cepo_id').html('<option value="">Seleccione</option>');
			for (var i = 0; i <= data.length - 1; i++) {
				var cepo_id 			= data[i].cepo_id;
				var cepo_descripcion 	= data[i].cepo_descripcion;
				$('#cepo_id').append('<option value="'+cepo_id+'">'+cepo_descripcion+'</option>');
			}//end for
			$('#cepo_id').selectpicker('refresh');
        })
        .fail(function(){
            bootbox.alert("<span class=\"glyphicon glyphicon-remove\"></span> No se han podido cargar los Centro Poblados");
        });
        */
	});
	/* FIN - UBIGEO */


	/* CARRERAS CURSOS */
	$(document).on('change', '#carr_id.selectpicker-carrera', function(e){
		var item_subruta 				= $(this).attr('data-subruta');
		var carr_id 					= $(this).val();
		var html						= '';

		if($('#modu_costo').length > 0){
			$('#modu_costo').attr('disabled', 'disabled');
			$('#modu_costo').val('');
    		html += '<tr>';
    		html += '<td class="texto-centrado" colspan="5">';
    		html += '<span>No se encontraron registros...</span>';
    		html += '</td>';
    		html += '</tr>';
    		$('#table_modxcurso').find('tbody').empty().html(html);
		}//end if

    	$('#modu_id').html('<option value="" data-moducosto="">Seleccione</option>');
    	$('#curs_id').html('<option value="">Todos</option>');
		if(carr_id != ''){
	        $.getJSON(item_subruta.replace('{SELECTED}', carr_id),function(data){
	        	if(data){
					for (var i = 0; i <= data.length - 1; i++) {
						var modu_id 			= data[i].modu_id;
						var modu_codigo 		= data[i].modu_codigo;
						var modu_descripcion 	= data[i].modu_descripcion;
						var modu_costo 			= data[i].modu_costo;
						$('#modu_id').append('<option value="'+modu_id+'" data-moducosto="'+modu_costo+'">'+modu_codigo+' - '+modu_descripcion+'</option>');
					}//end for
	        	}
				$('.selectpicker-carrera').selectpicker('refresh');
	        })
	        .fail(function(){
	            bootbox.alert("<span class=\"glyphicon glyphicon-remove\"></span> No se han podido cargar los módulos...");
	        });
		}else{
			$('.selectpicker-carrera').selectpicker('refresh');
		}
	});
	$(document).on('change', '#modu_id.selectpicker-carrera', function(e){
		var item_subruta 				= $(this).attr('data-subruta');
		var modu_id 					= $(this).val();
		var modu_costo					= $('option:selected', this).attr('data-moducosto');
		var html						= '';

		if($('#modu_costo').length > 0){
			$('#modu_costo').attr('disabled', 'disabled');
			$('#modu_costo').val(modu_costo);
    		html += '<tr>';
    		html += '<td class="texto-centrado" colspan="5">';
    		html += '<span>Presione buscar cursos para ver la lista. Si no encuentra ninguno considere agregar curso o revisar la opción: Programa de instrucción.</span>';
    		html += '</td>';
    		html += '</tr>';
    		$('#table_modxcurso').find('tbody').empty().html(html);
		}//end if
    	$('#curs_id').html('<option value="">Todos</option>');
		if(modu_id != ''){
	        $.getJSON(item_subruta.replace('{SELECTED}', modu_id),function(data){
	        	if(data){
					for (var i = 0; i <= data.length - 1; i++) {
						var curs_id 			= data[i].curs_id;
						var curs_codigo 		= data[i].curs_codigo;
						var curs_descripcion 	= data[i].curs_descripcion;
						$('#curs_id').append('<option value="'+curs_id+'">'+curs_codigo+' - '+curs_descripcion+'</option>');
					}//end for
	        	}
				$('.selectpicker-carrera').selectpicker('refresh');
	        })
	        .fail(function(){
	            bootbox.alert("<span class=\"glyphicon glyphicon-remove\"></span> No se han podido cargar los cursos...");
	        });
		}else{
			$('.selectpicker-carrera').selectpicker('refresh');
		}
	});
	/* FIN - CARRERAS CURSOS */




	/* OFICINA POR USUARIO */
	$(document).on('change', '#otip_central', function(e){
		var data_function 				= $(this).attr('data-function');
		var ofic_id 					= $(this).val();
		var uxof_id						= $('option:selected', this).attr('data-uxof');
	    $.post(data_function.replace('{UXOF_ID}', uxof_id).replace('{SELECTED}', ofic_id), {}, function(resp) {
	        var req = resp.split('_|_');
	        //if(req[0] === '1'){
	        	location.reload();
	        //}
	    });
	});
	/* FIN - OFICINA POR USUARIO */


	$(document).on('click', 'a', function(e){
		if($(this).attr('disabled')){
			console.log('disabled');
			return false;
		}
	});

});

function notificacion(tipo, texto) {
    if (texto) {
        toastr.options = {
            closeButton: true,
            progressBar: true
        };
        tipo == 0 ? toastr.success(texto) : toastr.error(texto);
    }
}

function validarnumero($form){
	var temp_numeric = 0;
	$form.find('.custom-numeric').each(function(e){
		if(($(this).val()).indexOf(',')){
			temp_numeric = ($(this).val()).replace(',', '');
			$(this).val(temp_numeric);
		}
		if(isNaN(temp_numeric)){
			console.log('not numeric: '+$(this).attr('id')+'->'+temp_numeric);
		}
	});
	
}
