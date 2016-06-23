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
	                        <li><a href="<?php echo base_url('seguridad/oficina') ?>"><i class="<?= $header_icon; ?>"></i>&nbsp; <?= $header_title; ?></a></li>
	                        <li>&nbsp; /</li>
	                        <li class="active"><i class="<?= ICON_FORM; ?>"></i>&nbsp; Formulario</li>
	                    </ul>

	  				</div> <!-- /widget-header -->

					<div class="widget-content">
						<div id="formcontrols" class="">
							<?php
							$attributes = array(
							  'id'      	=> 'form_oficina',
							  'name'    	=> 'form_oficina',
							  'class'		=> 'form-horizontal',
							  'tipo_vista' 	=> $tipo_vista
							  );
							$ruta = 'seguridad/oficina/guardar/'.((isset($data_ofic))?str_encrypt($data_ofic->ofic_id,KEY_ENCRYPT):'');
							echo form_open($ruta, $attributes);
							?>
							<fieldset>
								<div class="control-group">
									<label for="otip_id" class="control-label">Tipo</label>
									<div class="controls">
					                    <select class="selectpicker span8" name="otip_id" id="otip_id">
					                    <?php
					                    if(isset($data_otip)){
					                    	foreach ($data_otip as $item => $tipooficina) {
					                    ?>
											<option value="<?= $tipooficina->otip_id; ?>" <?= (isset($data_ofic) && $data_ofic->otip_id === $tipooficina->otip_id)?'selected': set_select('otip_id', $tipooficina->otip_id); ?>><?= $tipooficina->otip_nombre; ?></option>
					                    <?php
					                    	}//end foreach
					                    }//end if
					                    ?>
					                    </select>
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

								<div class="control-group">
									<label for="ofic_codigo" class="control-label">Código</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_ofic))?$data_ofic->ofic_codigo:set_value('ofic_codigo'); ?>" id="ofic_codigo" name="ofic_codigo" class="span8" maxlength="5">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

								<div class="control-group">
									<label for="ofic_nombre" class="control-label">Nombre oficina</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_ofic))?$data_ofic->ofic_nombre:set_value('ofic_nombre'); ?>" id="ofic_nombre" name="ofic_nombre" class="span8" maxlength="80">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->
								<div class="control-group">
									<label for="ofic_abreviatura" class="control-label">Abreviatura</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_ofic))?$data_ofic->ofic_abreviatura:set_value('ofic_abreviatura'); ?>" id="ofic_abreviatura" name="ofic_abreviatura" class="span8" maxlength="10">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->
								<div class="control-group">
									<label for="ofic_direccion" class="control-label">Dirección</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_ofic))?$data_ofic->ofic_direccion:set_value('ofic_direccion'); ?>" id="ofic_direccion" name="ofic_direccion" class="span8" maxlength="120">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->
								<div class="control-group">
									<label for="ofic_email" class="control-label">Email</label>
									<div class="controls">
										<input type="email" value="<?= (isset($data_ofic))?$data_ofic->ofic_email:set_value('ofic_email'); ?>" id="ofic_email" name="ofic_email" class="span8" maxlength="250">
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

<script src="<?php echo base_url('public/assets/js/Oficina.js') ?>"></script>