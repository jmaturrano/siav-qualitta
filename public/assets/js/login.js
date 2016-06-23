

$(document).ready(function(){
    var inputDNI 		= $('#usua_numero_documento');
    var inputUSER 		= $('#usua_nombre');
    var inputPASS 		= $('#usua_clave');
    var formLOGIN       = $('#form_login');
    var formRUTABASE    = formLOGIN.attr('action');


	/*INIT PAGE*/
	inputDNI.focus();
	/*VERIFICAR DOC. IDENTIDAD*/
	$(document).on('keypress', '#usua_numero_documento', function(e){
		if(e.which === 13){
			if(inputDNI.val().trim() === ''){
				inputUSER.attr('disabled', 'disabled');
				inputPASS.attr('disabled', 'disabled');
			}else{
                $.getJSON(formRUTABASE.replace('{FUNCTION}', 'verificaDocIdent/')+inputDNI.val().trim(),function(data){
                    if(data.usua_estado === ''){
                        bootbox.alert("<span class=\"glyphicon glyphicon-remove\"></span> DNI Incorrecto!");
                    }else if(data.usua_estado !== 'AC'){
                            bootbox.alert("<span class=\"glyphicon glyphicon-remove\"></span> El usuario no se encuentra activo!");
                    }else{
                        inputUSER.val(data.usua_nombre + ' ' + data.usua_apellido);
                        inputPASS.removeAttr('disabled');
                        inputPASS.focus();
                    }
                })
                .fail(function(){
                    bootbox.alert("<span class=\"glyphicon glyphicon-remove\"></span> Error verificando el DNI en la Base de Datos");
                });
			}//end else
		}//end if
	});

    /*TRIGGER PASSWORD*/
    $(document).on('keypress', '#usua_clave', function(e){
        if(e.which === 13){
            if(inputPASS.val() === ''){
                return false;
            }else{
                $('#usua_submit').trigger('click');
            }
        }
    });

    /*VERIFICAR PASSWORD*/
    $(document).on('click', '#usua_submit', function(e){
        if(inputDNI.val().trim() === ''){
            inputDNI.focus();
            return false;
        }
        if(inputPASS.val().trim() === ''){
            inputPASS.focus();
            return false;
        }
        var functionsubmit = formRUTABASE.replace('{FUNCTION}', 'accesslogin');
        formLOGIN.attr('action', functionsubmit);
        formLOGIN.submit();
        e.preventDefault();
    });

    if(typeof error_msg != 'undefined'){
        bootbox.alert("<span class=\"glyphicon glyphicon-remove\"></span> "+error_msg);
    }

    /*RESTABLECER PASSWORD*/
    $(document).on('click', '#linkPASS', function(e){
        if(inputDNI.val().trim() === ''){
            inputDNI.focus();
            bootbox.alert("<span class=\"glyphicon glyphicon-remove\"></span> Debe ingresar el DNI del usuario!"); 
            
        }else{
            $.getJSON(formRUTABASE.replace('{FUNCTION}', 'verificaDocIdent/')+inputDNI.val().trim(),function(data){
                    if(data.usua_estado === ''){
                        bootbox.alert("<span class=\"glyphicon glyphicon-remove\"></span> DNI Incorrecto!");
                    }else{
                        if(data.usua_estado !== 'AC'){
                            bootbox.alert("<span class=\"glyphicon glyphicon-remove\"></span> El usuario no se encuentra activo!");
                        }else{
                            bootbox.alert("<span class=\"glyphicon glyphicon-remove\"></span> Se envi&oacute; la solicitud al Administrador del Sistema"); 
                        }
                    }
                })
                .fail(function(){
                    bootbox.alert("<span class=\"glyphicon glyphicon-remove\"></span> Error verificando el DNI en la Base de Datos");
                });
            
        }
        
        e.preventDefault();
    });

});