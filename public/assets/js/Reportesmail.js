$(document).ready(function(){

	var tipo_vista = '';
	if($('#form_reportesmail').length > 0){
		tipo_vista = ($('#form_reportesmail').attr('tipo_vista')).toUpperCase();
		if(tipo_vista === 'VER'){
			$('#form_reportesmail').find('input').attr('disabled', 'disabled');
			$('#form_reportesmail').find('textarea').attr('disabled', 'disabled');
			$('#form_reportesmail').find('select').attr('disabled', 'disabled');
			$(".datepicker").datepicker('remove');
		}
		$('#rema_codigo').attr('disabled', 'disabled');
	}

	$(document).on('click', '.btn_guardar', function(e){
		$('#form_reportesmail').find('input').removeAttr('disabled');
		$('#form_reportesmail').find('select').removeAttr('disabled');
		$('#form_reportesmail').submit();
		e.preventDefault();
	});

	if($('#rema_descripcion').length > 0){
	    $('#rema_descripcion').trumbowyg({
	        btns: ['viewHTML',
	          '|', 'formatting',
	          '|', 'btnGrp-design',
	          '|', 'btnGrp-semantic',
	          '|', 'link',
	          '|', 'insertImage',
	          '|', 'btnGrp-justify',
	          '|', 'btnGrp-lists',
	          '|', 'horizontalRule', 
	          '|', 'strong', 
	          '|', 'em', 
	          '|', 'removeformat'],
	        btnsAdd: ['|', 'foreColor', 'backColor'],
	        fullscreenable: false,
	        closable: false,
	        semantic: false,
	        resetCss: true,
	        autogrow: true,
	        mobile: true,
	        tablet: true,
	        removeformatPasted: true
	    });
	}

});