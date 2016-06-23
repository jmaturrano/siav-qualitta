function sendingForm(){
				
		var peri_id = $('#peri_id').selectpicker('val');
		var fecha_reporte = $('#fecha_reporte').val();
		
		console.log(fecha_reporte);
		if(peri_id){
			$( ".array" ).remove();
			if($('#ogru_id').selectpicker('val')){
				for (var g = 0; g <= $('#ogru_id').selectpicker('val').length - 1; g++) {            	                
	            	$('#form_reporte').append('<input class="array" type="hidden" name="ogru_id_array[]" value="'+$('#ogru_id').selectpicker('val')[g]+'" />');
	        	}
			}
			if($('#ofic_id').selectpicker('val')){
				for (var h = 0; h <= $('#ofic_id').selectpicker('val').length - 1; h++) {            	                
	            	$('#form_reporte').append('<input class="array" type="hidden" name="ofic_id_array[]" value="'+$('#ofic_id').selectpicker('val')[h]+'" />');
	        	}
			}
			if($('#poli_id').selectpicker('val')){
				for (var i = 0; i <= $('#poli_id').selectpicker('val').length - 1; i++) {            	                
	            	$('#form_reporte').append('<input class="array" type="hidden" name="poli_id_array[]" value="'+$('#poli_id').selectpicker('val')[i]+'" />');
	        	}
			}
			
			if($('#ppre_id').selectpicker('val')){
		        for (var j = 0; j <= $('#ppre_id').selectpicker('val').length - 1; j++) {            	                
		            $('#form_reporte').append('<input class="array" type="hidden" name="ppre_id_array[]" value="'+$('#ppre_id').selectpicker('val')[j]+'" />');
		        }
		    }    
		    if($('#prod_id').selectpicker('val')){
		        for (var k = 0; k <= $('#prod_id').selectpicker('val').length - 1; k++) {            	                
		            $('#form_reporte').append('<input class="array" type="hidden" name="prod_id_array[]" value="'+$('#prod_id').selectpicker('val')[k]+'" />');
		        }
			}
			if($('#acti_id').selectpicker('val')){
		        for (var l = 0; l <= $('#acti_id').selectpicker('val').length - 1; l++) {            	                
		            $('#form_reporte').append('<input class="array" type="hidden" name="acti_id_array[]" value="'+$('#acti_id').selectpicker('val')[l]+'" />');
		        }
			}
			if($('#sact_id').selectpicker('val')){
		        for (var m = 0; m <= $('#sact_id').selectpicker('val').length - 1; m++) {            	                
		            $('#form_reporte').append('<input class="array" type="hidden" name="sact_id_array[]" value="'+$('#sact_id').selectpicker('val')[m]+'" />');
		        }
			}
			if($('#estr_id').selectpicker('val')){
		        for (var n = 0; n <= $('#estr_id').selectpicker('val').length - 1; n++) {            	                
		            $('#form_reporte').append('<input class="array" type="hidden" name="estr_id_array[]" value="'+$('#estr_id').selectpicker('val')[n]+'" />');
		        }
			}
			$('#form_reporte').submit();
		}else{
			bootbox.alert("<span class=\"glyphicon glyphicon-remove\"></span> Debe seleccionar el Periodo");
		}

}


$(document).ready(function(){

	
	$(document).on('click', '#btn_imprimir', function(e){
		if(arr_permisos.mxro_imprime === '0'){
				e.stopPropagation();
				bootbox.alert("<span class=\"glyphicon glyphicon-exclamation-sign\"></span> Lo sentimos. Usted no cuenta con un perfil asignado para realizar esta operación.");
				return false;
			}
		$("#extension").val('PDF');
		sendingForm();
		
		e.preventDefault();
	});

	$(document).on('click', '#btn_exportar', function(e){
		
			if(arr_permisos.mxro_exporta === '0'){
				e.stopPropagation();
				bootbox.alert("<span class=\"glyphicon glyphicon-exclamation-sign\"></span> Lo sentimos. Usted no cuenta con un perfil asignado para realizar esta operación.");
				return false;
			}
		
		$("#extension").val('XLSX');
		sendingForm();
		
		e.preventDefault();
	});

	$(document).on('change', '#ogru_id', function(e){
        //$('#form_metas').submit();
        var item_subruta                = $(this).attr('data-subruta');       
        var ogru_id                     = encodeURIComponent($(this).val());
        if(ogru_id && ogru_id !='null'){
        	 console.log(item_subruta.replace('{OGRU_ID}', ogru_id));

        var request = $.ajax({
			  url: item_subruta.replace('{OGRU_ID}', ogru_id),
			  method: "POST",
			  data: { ogru_id_array : $('#ogru_id').selectpicker('val') },
			  dataType: "json"
			});
			 
			request.done(function( data , textStatus, jqXHR ) {
				console.log(jqXHR);
				//console.log(textStatus);
			    //$('.selectpicker-poli').html('<option value="">Seleccione</option>');
			    //console.log(data);
	            $('#ofic_id').html('<option value="" disabled="disabled">Seleccione</option>');
	            for (var i = 0; i <= data.length - 1; i++) {
	            	//console.log(data[i]);
	                var ofic_id             = data[i].ofic_id;
	                var ofic_nombre         = data[i].ofic_nombre;
	                $('#ofic_id').append('<option value="'+ofic_id+'">'+ofic_nombre+'</option>');
	            }//end for
	            $('#ofic_id').selectpicker('refresh');
			  });
			 
			request.fail(function( jqXHR, textStatus ) {
				//console.log(jqXHR);
				//console.log(textStatus);
			  bootbox.alert("<span class=\"glyphicon glyphicon-remove\"></span> No se han podido cargar la oficina");
			});
		}else{
			$('#ofic_id').html('');
            $('#ofic_id').selectpicker('refresh');
        }
       
        
    });


     $(document).on('change', '#poli_id', function(e){
        //$('#form_metas').submit();
        var item_subruta                = $(this).attr('data-subruta');
        var poli_id                     = encodeURIComponent($(this).val());
        //alert(poli_id);
       
        if(poli_id && poli_id !='null'){
            
        var request = $.ajax({
			  url: item_subruta.replace('{SELECTED}', poli_id),
			  method: "POST",
			  data: { poli_id_array : $('#poli_id').selectpicker('val') },
			  dataType: "json"
			});
			 
			request.done(function( data ) {
			    //$('.selectpicker-poli').html('<option value="">Seleccione</option>');
	            $('#prod_id').html('<option value="" disabled="disabled">Seleccione</option>');
	            $('#ppre_id').html('<option value="" disabled="disabled">Seleccione</option>');
	            $('#acti_id').html('<option value="" disabled="disabled">Seleccione</option>');
	            $('#sact_id').html('<option value="" disabled="disabled">Seleccione</option>'); 
	            console.log(data);
	            
	            for (var i = 0; i <= data.length - 1; i++) {
	                var ppre_id             = data[i].ppre_id;
	                var ppre_nombre         = data[i].ppre_nombre;
	                var ppre_codref         = data[i].ppre_codref;
	                $('#ppre_id').append('<option value="'+ppre_id+'">'+ppre_codref+'-'+ppre_nombre+'</option>');
	            }//end for
	            
	            //$('.selectpicker-poli').selectpicker('refresh');
	            $('#prod_id').selectpicker('refresh');
	            $('#ppre_id').selectpicker('refresh');
	            $('#acti_id').selectpicker('refresh');
	            $('#sact_id').selectpicker('refresh');
			  });
			 
			request.fail(function( jqXHR, textStatus ) {
			  bootbox.alert("<span class=\"glyphicon glyphicon-remove\"></span> No se han podido cargar los items de Programa Presupuestal");
			});
		}else{
			$('#prod_id').html('<option value="" disabled="disabled">Seleccione</option>');
            $('#ppre_id').html('<option value="" disabled="disabled">Seleccione</option>');
            $('#acti_id').html('<option value="" disabled="disabled">Seleccione</option>');
            $('#sact_id').html('<option value="" disabled="disabled">Seleccione</option>'); 
            $('#prod_id').selectpicker('refresh');
            $('#ppre_id').selectpicker('refresh');
           $('#acti_id').selectpicker('refresh');
           $('#sact_id').selectpicker('refresh');
        }
       
    });


     $(document).on('change', '#ppre_id', function(e){
        //$('#form_metas').submit();
        var item_subruta                = $(this).attr('data-subruta');
        var poli_id                     = encodeURIComponent($('#poli_id').val());
        var ppre_id                     = encodeURIComponent($(this).val());

        if(ppre_id && ppre_id !='null'){
            
        
        var request = $.ajax({
			  url: item_subruta.replace('{POLI_ID}', poli_id).replace('{SELECTED}', ppre_id),
			  method: "POST",
			  data: { poli_id_array : $('#poli_id').selectpicker('val') , 
			          ppre_id_array : $('#ppre_id').selectpicker('val') },
			  dataType: "json"
			});
			 
			request.done(function( data ) {
			    //$('.selectpicker-poli').html('<option value="">Seleccione</option>');
	            $('#prod_id').html('<option value="" disabled="disabled">Seleccione</option>');
	            $('#acti_id').html('<option value="" disabled="disabled">Seleccione</option>');
	            $('#sact_id').html('<option value="" disabled="disabled">Seleccione</option>'); 
	            console.log(data);
	            
	            for (var i = 0; i <= data.length - 1; i++) {
	                var prod_id             = data[i].prod_id;
	                var prod_nombre         = data[i].prod_nombre;
	                var prod_codref         = data[i].prod_codref;
	                $('#prod_id').append('<option value="'+prod_id+'">'+prod_codref+'-'+prod_nombre+'</option>');
	            }//end for
	            
	            //$('.selectpicker-poli').selectpicker('refresh');
	            $('#prod_id').selectpicker('refresh');	            
	            $('#acti_id').selectpicker('refresh');
	            $('#sact_id').selectpicker('refresh');
			  });
			 
			request.fail(function( jqXHR, textStatus ) {
			  bootbox.alert("<span class=\"glyphicon glyphicon-remove\"></span> No se han podido cargar los items de Producto");
			});

			}else{
			$('#prod_id').html('<option value="" disabled="disabled">Seleccione</option>');           
            $('#acti_id').html('<option value="" disabled="disabled">Seleccione</option>');
            $('#sact_id').html('<option value="" disabled="disabled">Seleccione</option>'); 
            $('#prod_id').selectpicker('refresh');            
            $('#acti_id').selectpicker('refresh');
            $('#sact_id').selectpicker('refresh');
        }
	
    });
    $(document).on('change', '#prod_id', function(e){
        //$('#form_metas').submit();
        var item_subruta                = $(this).attr('data-subruta');
        var poli_id                     = encodeURIComponent($('#poli_id').val());
        var ppre_id                     = encodeURIComponent($('#ppre_id').val());
        var prod_id                     = encodeURIComponent($(this).val());
        if(prod_id && prod_id !='null'){         
            
        var request = $.ajax({
			  url: item_subruta.replace('{POLI_ID}', poli_id).replace('{PPRE_ID}', ppre_id).replace('{SELECTED}', prod_id),
			  method: "POST",
			  data: { poli_id_array : $('#poli_id').selectpicker('val') , 
			          ppre_id_array : $('#ppre_id').selectpicker('val') , 
			          prod_id_array : $('#prod_id').selectpicker('val') },
			  dataType: "json"
			});
			 
			request.done(function( data ) {
			    //$('.selectpicker-poli').html('<option value="">Seleccione</option>');
	            
	            $('#acti_id').html('<option value="" disabled="disabled">Seleccione</option>');
	            $('#sact_id').html('<option value="" disabled="disabled">Seleccione</option>'); 
	            //console.log(data);
	            
	            for (var i = 0; i <= data.length - 1; i++) {
	                var acti_id             = data[i].acti_id;
	                var acti_nombre         = data[i].acti_nombre;
	                var acti_codref         = data[i].acti_codref;
	                $('#acti_id').append('<option value="'+acti_id+'">'+acti_codref+'-'+acti_nombre+'</option>');
	            }//end for
	            
	            //$('.selectpicker-poli').selectpicker('refresh');
	            
	            $('#acti_id').selectpicker('refresh');
	            $('#sact_id').selectpicker('refresh');
			  });
			 
			request.fail(function( jqXHR, textStatus ) {
			  bootbox.alert("<span class=\"glyphicon glyphicon-remove\"></span> No se han podido cargar los items de Actividad");
			});

		}else{
			$('#acti_id').html('<option value="" disabled="disabled">Seleccione</option>');         
            $('#sact_id').html('<option value="" disabled="disabled">Seleccione</option>');         
            $('#acti_id').selectpicker('refresh');
            $('#sact_id').selectpicker('refresh');
        
        }

        
    });

    $(document).on('change', '#acti_id', function(e){
		//$('#form_metas').submit();
		var item_subruta 				= $(this).attr('data-subruta');
		var poli_id 					= encodeURIComponent($('#poli_id').val());
		var ppre_id 					= encodeURIComponent($('#ppre_id').val());
		var prod_id 					= encodeURIComponent($('#prod_id').val());
		var acti_id 					= encodeURIComponent($(this).val());
		console.log('acti_id='+acti_id);
		if(acti_id && acti_id !='null'){                     
            
		var request = $.ajax({
			  url: item_subruta.replace('{POLI_ID}', poli_id).replace('{PPRE_ID}', ppre_id).replace('{PROD_ID}', prod_id).replace('{SELECTED}', acti_id),
			  method: "POST",
			  data: { poli_id_array : $('#poli_id').selectpicker('val') , 
			          ppre_id_array : $('#ppre_id').selectpicker('val') , 
			          prod_id_array : $('#prod_id').selectpicker('val') ,
			          acti_id_array : $('#acti_id').selectpicker('val') },
			  dataType: "json"
			});
			 
			request.done(function( data ) {
			    //$('.selectpicker-poli').html('<option value="">Seleccione</option>');
	            
	            
	            $('#sact_id').html('<option value="" disabled="disabled">Seleccione</option>'); 
	            //console.log(data);
	            if(data){
		            for (var i = 0; i <= data.length - 1; i++) {
						var sact_id 			= data[i].sact_id;
						var sact_nombre 		= data[i].sact_nombre;
						var cact_id 			= data[i].cact_id;
						$('#sact_id').append('<option value="'+sact_id+'" data-cact_id="'+cact_id+'">'+sact_nombre+'</option>');
					}//end for
	            }
	            //$('.selectpicker-poli').selectpicker('refresh');
	            
	            
	            $('#sact_id').selectpicker('refresh');
			  });
			 
			request.fail(function( jqXHR, textStatus ) {
			  bootbox.alert("<span class=\"glyphicon glyphicon-remove\"></span> No se han podido cargar los items de Actividad");
			});
		}else{
			$('#sact_id').html('<option value="" disabled="disabled">Seleccione</option>');                     
            $('#sact_id').selectpicker('refresh');
            
        }
	});



});