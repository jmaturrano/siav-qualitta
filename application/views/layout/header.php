<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta charset="utf-8" />
        <title><?= TITULO ?></title>
        <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
		<meta name="apple-mobile-web-app-capable" content="yes">
        <meta content="" name="description" />
        <meta content="" name="author" />
        <link rel="shortcut icon" type="image/x-icon" href="<?= base_url(ICON_EMPRESA); ?>" />

		<!-- ================== BEGIN BASE JS ================== -->
		<script src="<?= base_url('public/assets/themes/jquery-1.11.3/jquery-1.11.3.js') ?>"></script>
        <!-- <script src="<?= base_url('public/assets/themes/jquery-1.12.3/jquery-1.12.3.min.js') ?>"></script> -->
        <script src="<?= base_url('public/assets/plugins/jquerymin/jquery.min.js') ?>"></script>
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
        <link rel="stylesheet" href="<?= base_url('public/assets/themes/jqueryui-1.11.4/smoothness/jquery-ui.css') ?>">
        
		<script src="<?= base_url('public/assets/themes/bootstrap-3.3.5-dist/js/bootstrap.min.js') ?>"></script>
        
		<!--<script src="<?php echo base_url('public/assets/themes/bt-admin-tpl2013/js/bootstrap.js') ?>"></script>-->
		<!-- ================== END BASE JS ================== -->

        <!-- ================== PLUGINS ================== -->
        <script src="<?= base_url('public/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') ?>"></script>
        <script src="<?= base_url('public/assets/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.es.js') ?>" charset="UTF-8"></script>
        <link href="<?= base_url('public/assets/plugins/bootstrap-datepicker/css/datepicker.css') ?>" rel="stylesheet" />
        <link href="<?= base_url('public/assets/plugins/bootstrap-datepicker/css/datepicker3.css') ?>" rel="stylesheet" />
        
        <script src="<?= base_url('public/assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js') ?>"></script>
        <link href="<?= base_url('public/assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css') ?>" rel="stylesheet" />

        <script src="<?= base_url('public/assets/plugins/bootstrap-typeahead/js/bootstrap-typeahead.js') ?>"></script>

        <script src="<?= base_url('public/assets/plugins/bootstrap-switch/js/bootstrap-switch.js') ?>"></script>
        <link href="<?= base_url('public/assets/plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.css') ?>" rel="stylesheet" />

        <script src="<?= base_url('public/assets/plugins/datatables/js/jquery.dataTables.js') ?>"></script>
        <script src="<?= base_url('public/assets/plugins/datatables/js/dataTables.bootstrap.js') ?>"></script>
        
        <link href="<?= base_url('public/assets/plugins/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet" />
        
        <script src="<?= base_url('public/assets/plugins/bootbox-confirm/js/bootbox.min.js') ?>" ></script>
        <link href="<?= base_url('public/assets/plugins/bootstrap-select-1.9.3/dist/css/bootstrap-select.min.css') ?>" rel="stylesheet" />
        <script src="<?= base_url('public/assets/plugins/bootstrap-select-1.9.3/dist/js/bootstrap-select.min.js') ?>" ></script>
        <link href="<?= base_url('public/assets/plugins/jqxTree4.1.1/jqwidgets/styles/jqx.base.css') ?>" rel="stylesheet" />
        <script src="<?= base_url('public/assets/plugins/jqxTree4.1.1/jqwidgets/jqxcore.js') ?>" ></script>
        <script src="<?= base_url('public/assets/plugins/jqxTree4.1.1/jqwidgets/jqxbuttons.js') ?>" ></script>
        <script src="<?= base_url('public/assets/plugins/jqxTree4.1.1/jqwidgets/jqxscrollbar.js') ?>" ></script>
        <script src="<?= base_url('public/assets/plugins/jqxTree4.1.1/jqwidgets/jqxpanel.js') ?>" ></script>
        <script src="<?= base_url('public/assets/plugins/jqxTree4.1.1/jqwidgets/jqxtree.js') ?>" ></script>
        <script src="<?= base_url('public/assets/plugins/jqxTree4.1.1/jqwidgets/jqxcheckbox.js') ?>" ></script>
        <link href="<?= base_url('public/assets/plugins/bootstrap-checkbox-x/css/checkbox-x.min.css') ?>" rel="stylesheet" />
        <script src="<?= base_url('public/assets/plugins/bootstrap-checkbox-x/js/checkbox-x.min.js') ?>" ></script>

        <!-- Toastr -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('public/assets/plugins/toastr/toastr.css') ?>">
        <script src="<?php echo base_url('public/assets/plugins/toastr/toastr.js') ?>"></script>
        <!-- FancyBox -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('public/assets/plugins/fancybox2/jquery.fancybox.css') ?>" media="screen">
        <script src="<?php echo base_url('public/assets/plugins/fancybox2/jquery.mousewheel-3.0.6.pack.js') ?>"></script>
        <script src="<?php echo base_url('public/assets/plugins/fancybox2/jquery.fancybox.js') ?>"></script>
        <!-- TagInput -->
        <script src="<?php echo base_url('public/assets/plugins/tags-input/angular.min.js') ?>"></script><!--v.1.2.20-->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('public/assets/plugins/tags-input/bootstrap-tagsinput.css') ?>" media="screen">
        <script src="<?php echo base_url('public/assets/plugins/tags-input/bootstrap-tagsinput.min.js') ?>"></script>
        <script src="<?php echo base_url('public/assets/plugins/tags-input/bootstrap-tagsinput-angular.js') ?>"></script>
        <!-- Bootstrap time picker -->
        <script src="<?= base_url('public/assets/plugins/date-time-picker/js/moment.js'); ?>"></script>
        <script src="<?= base_url('public/assets/plugins/date-time-picker/js/bootstrap-datetimepicker.js'); ?>"></script>
        <link rel="stylesheet" href="<?= base_url('public/assets/plugins/date-time-picker/css/bootstrap-datetimepicker.css'); ?>">
        <!-- ================== END PLUGINS ================== -->


        <!-- ================== JS ADDS ================== -->
        <script src="<?= base_url('public/assets/themes/bt-admin-tpl2013/js/signin.js') ?>"></script>
        <script src="<?= base_url('public/assets/js/MainTheme.js') ?>"></script>
        <!-- ================== END JS ADDS ================== -->


        <!-- ================== BEGIN BASE CSS STYLE ================== -->
        <link href="<?= base_url('public/assets/themes/bt-admin-tpl2013/css/bootstrap.min.css') ?>" rel="stylesheet" /><!--v3.0.0-->
        <!--NO-VA-2016-05-12-JM<link href="<?= base_url('public/assets/themes/bootstrap-3.3.5-dist/css/bootstrap.css') ?>" rel="stylesheet" />v3.3.5-->
		<link href="<?= base_url('public/assets/themes/bt-admin-tpl2013/css/bootstrap-responsive.min.css') ?>" rel="stylesheet" /><!--v2013-->

		<link href="<?= base_url('public/assets/themes/bt-admin-tpl2013/css/style.css') ?>" rel="stylesheet" type="text/css"> 
        <link href="<?= base_url('style/admin') ?>" rel="stylesheet" type="text/css"> 
        
        <!--EGrappler.com v2013-->
		<link href="<?= base_url('public/assets/themes/bt-admin-tpl2013/css/pages/signin.css') ?>" rel="stylesheet" type="text/css"><!--EGrappler.com v2013-->
        <link href="<?= base_url('public/assets/css/MainTheme.css') ?>" rel="stylesheet" type="text/css"><!--MainTheme-->
        <link href="<?= base_url('style/admin') ?>" rel="stylesheet" type="text/css">
        <!-- ================== END BASE CSS STYLE ================== -->

        <!-- ================== BEGIN FONT STYLES ================== -->
		<link href="<?= base_url('public/assets/themes/bt-admin-tpl2013/css/font-awesome.css') ?>" rel="stylesheet">
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600" rel="stylesheet">
        <!-- ================== END FONT STYLES ================== -->

    </head>

    <body>

    <script>
        var arr_permisos = $.parseJSON('<?= (isset($PERMISOS)?json_encode($PERMISOS):'{"mxro_accesa":"0","mxro_ingresa":"0","mxro_elimina":"0","mxro_modifica":"0","mxro_consulta":"0","mxro_imprime":"0","mxro_exporta":"0"}'); ?>');
    </script>
    <noscript>
        <p>Bienvenido al sistema</p>
        <p>La aplicación que está viendo requiere para su funcionamiento el uso de JavaScript. Si lo has deshabilitado intencionadamente, por favor vuelve a activarlo.</p>
    </noscript>