<div class="main">
	<div class="main-inner">
	    <div class="container">
	      <div class="row">
	      	<div class="span12">
	      		<div class="widget">
	      			<div class="widget-header">
	      				<i class="<?= $header_icon; ?>"></i>
	      				<h3><?= $header_title; ?></h3>
	                    <ul class="breadcrumb custom-breadcrumb">
	                        <li><a href="<?php echo base_url('panel/principal') ?>"><i class="<?php echo ICONO_HOME ?>"></i>&nbsp; Inicio</a></li>
	                        <li>&nbsp; /</li>
	                        <li class="active"><i class="<?= $header_icon; ?>"></i>&nbsp; <?= $header_title; ?></li>
	                    </ul>
	  				</div> <!-- /widget-header -->
					<div class="widget-content">
						<div id="formcontrols" class="">
							<?php
							$attributes = array(
							  'id'      => 'form_configuracion',
							  'name'    => 'form_configuracion',
							  'class'	=> 'form-horizontal',
							  'tipo_vista' 	=> $tipo_vista
							  );
							$ruta = 'seguridad/configuracion/actualizar/'.str_encrypt($data_conf->conf_id, KEY_ENCRYPT);
							echo form_open($ruta, $attributes);
							?>
							<fieldset>
								<div class="control-group form-group">
									<label for="conf_nombre" class="control-label">Nombre</label>
									<div class="controls">
										<input type="text" value="<?= $data_conf->conf_nombre; ?>" id="conf_nombre" name="conf_nombre" class="span8 form-control" required maxlength="80">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

								<div class="control-group form-group">
									<label for="conf_ruc" class="control-label">RUC</label>
									<div class="controls">
										<input type="text" value="<?= $data_conf->conf_ruc; ?>" id="conf_ruc" name="conf_ruc" class="span8 form-control" required maxlength="80">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

								<div class="control-group">
									<label for="conf_direccion" class="control-label">Dirección</label>
									<div class="controls">
										<input type="text" value="<?= $data_conf->conf_direccion; ?>" id="conf_direccion" name="conf_direccion" class="span8" required maxlength="120">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->
								<div class="control-group">
									<label for="conf_email" class="control-label">Email</label>
									<div class="controls">
										<input type="email" value="<?= $data_conf->conf_email; ?>" id="conf_email" name="conf_email" class="span8" required maxlength="250">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->
								<div class="control-group" style="display: none;">
									<label for="conf_diasexpclave" class="control-label">Días de expiración claves</label>
									<div class="controls">
										<input type="number" value="<?= $data_conf->conf_diasexpclave; ?>" id="conf_diasexpclave" name="conf_diasexpclave" class="span8">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->
								<div class="control-group">
									<label for="conf_temacolor" class="control-label">Color del Tema : </label>
									<div class="controls">
									    <input id="cp2"  type="text" value="<?=($data_conf->conf_temacolor) ?>" class="span8" name="conf_temacolor"/>
									<script>
									    $(function() {
									        $('#cp2').colorpicker();
									    });
									</script>
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

								<div class="control-group">
									<label for="conf_fecha_registro" class="control-label">Fecha de registro</label>
									<div class="controls">
								    <div class="input-append date datepicker" data-date="<?= (isset($data_conf))?date('d/m/Y', strtotime($data_conf->conf_fecha_registro)):date('d/m/Y'); ?>" data-date-format="dd/mm/yyyy">
								      <input class="span2" size="16" type="text" value="<?= (isset($data_conf))?date('d/m/Y', strtotime($data_conf->conf_fecha_registro)):date('d/m/Y'); ?>" id="conf_fecha_registro" name="conf_fecha_registro" required>
								      <span class="add-on"><i class="icon-th"></i></span>
								    </div>
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

							</fieldset>
					       	<?php
					            echo form_close();
					        ?>
						</div>
					</div> <!-- /widget-content -->
				</div> <!-- /widget -->
		    </div> <!-- /span8 -->
	      </div> <!-- /row -->
	    </div> <!-- /container -->
	</div> <!-- /main-inner -->
</div> <!-- /main -->

<script src="<?php echo base_url('public/assets/js/Configuracion.js') ?>"></script>















