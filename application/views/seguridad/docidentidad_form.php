<div class="main">
	<div class="main-inner">
	    <div class="container">
	      <div class="row">
	      	<div class="span12">
	      		<div class="widget">
	      			<div class="widget-header">
	      				<i class="<?= $header_icon; ?>"></i>
	      				<h2><?= $header_title; ?></h2>
	                    <ul class="breadcrumb custom-breadcrumb">
	                        <li><a href="<?php echo base_url('panel/principal') ?>"><i class="<?php echo ICONO_HOME ?>"></i>&nbsp; Inicio</a></li>
	                        <li>&nbsp; /</li>
	                        <li><a href="<?php echo base_url('seguridad/docidentidad') ?>"><i class="<?= $header_icon; ?>"></i>&nbsp; Documentos de Identidad</a></li>
	                        <li>&nbsp; /</li>
	                        <li class="active"><i class="<?= ICON_FORM; ?>"></i>&nbsp; Formulario</li>
	                    </ul>
	  				</div> <!-- /widget-header -->
					<div class="widget-content">
						<div id="formcontrols" class="">
							<?php
							$attributes = array(
							  'id'      	=> 'form_docidentidad',
							  'name'    	=> 'form_docidentidad',
							  'class'		=> 'form-horizontal',
							  'tipo_vista' 	=> $tipo_vista
							  );
							$ruta = 'seguridad/docidentidad/guardar/'.((isset($data_doid))?str_encrypt($data_doid->dide_id, KEY_ENCRYPT):'');
							echo form_open($ruta, $attributes);
							?>
							<fieldset>
								<div class="control-group">
									<label for="dide_descripcion" class="control-label">Descripción</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_doid))?$data_doid->dide_descripcion:set_value('dide_descripcion'); ?>" id="dide_descripcion" name="dide_descripcion" class="span8" maxlength="50">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->
								<div class="control-group">
									<label for="dide_caracteres" class="control-label">Cant. Caracteres</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_doid))?$data_doid->dide_caracteres:set_value('dide_caracteres'); ?>" id="dide_caracteres" name="dide_caracteres" class="span8">
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

<script src="<?php echo base_url('public/assets/js/Docidentidad.js') ?>"></script>