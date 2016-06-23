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



	                        <li><a href="<?php echo base_url('operaciones/tipoaeronave') ?>"><i class="<?= $header_icon; ?>"></i>&nbsp; <?= $header_title; ?></a></li>



	                        <li>&nbsp; /</li>



	                        <li class="active"><i class="<?= ICON_FORM; ?>"></i>&nbsp; Formulario</li>



	                    </ul>



	  				</div> <!-- /widget-header -->



					<div class="widget-content">

						<div id="formcontrols" class="">

							<?php

							$attributes = array(

							  'id'      	=> 'form_tipoaeronave',

							  'name'    	=> 'form_tipoaeronave',

							  'class'		=> 'form-horizontal',

							  'tipo_vista' 	=> $tipo_vista

							  );

							$ruta = 'operaciones/tipoaeronave/guardar/'.((isset($data_tiae))?str_encrypt($data_tiae->tiae_id, KEY_ENCRYPT):'');

							//echo validation_errors();

							echo form_open($ruta, $attributes);

							?>



							<fieldset>

								<div class="control-group">

									<label for="tiae_descripcion" class="control-label">Descripción</label>

									<div class="controls">

										<input type="text" value="<?= (isset($data_tiae))?$data_tiae->tiae_descripcion:set_value('tiae_descripcion'); ?>" id="tiae_descripcion" name="tiae_descripcion" class="span8" maxlength="40">

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







<script src="<?php echo base_url('public/assets/js/Tipoaeronave.js') ?>"></script>