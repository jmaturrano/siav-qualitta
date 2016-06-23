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

	                        <li><a href="<?php echo base_url('registros/certificadoslegales') ?>"><i class="<?= $header_icon; ?>"></i>&nbsp;  <?= $header_title; ?></a></li>

	                        <li>&nbsp; /</li>

	                        <li class="active"><i class="<?= ICON_FORM; ?>"></i>&nbsp; Formulario</li>

	                    </ul>

	  				</div> <!-- /widget-header -->

					<div class="widget-content">

						<div id="formcontrols" class="">

							<?php

							$attributes = array(

							  'id'      	=> 'form_certificadoslegales',

							  'name'    	=> 'form_certificadoslegales',

							  'class'		=> 'form-horizontal',

							  'tipo_vista' 	=> $tipo_vista

							  );

							$ruta = 'registros/certificadoslegales/guardar/'.((isset($data_cele))?str_encrypt($data_cele->cele_id, KEY_ENCRYPT):'');

							echo form_open($ruta, $attributes);

							?>

							<fieldset>

								<div class="control-group">

									<label for="cele_descripcion" class="control-label">Descripción</label>

									<div class="controls">

										<input type="text" value="<?= (isset($data_cele))?$data_cele->cele_descripcion:set_value('cele_descripcion'); ?>" id="cele_descripcion" name="cele_descripcion" class="span8" maxlength="45">

									</div> <!-- /controls -->

								</div> <!-- /control-group -->

								<div class="control-group">

									<label for="cele_anios_vigencia" class="control-label">Año de Vigencia</label>

									<div class="controls">

										<input type="text" value="<?= (isset($data_cele))?$data_cele->cele_anios_vigencia:set_value('cele_anios_vigencia'); ?>" id="cele_anios_vigencia" name="cele_anios_vigencia" class="span8">

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



<script src="<?php echo base_url('public/assets/js/Certificadoslegales.js') ?>"></script>