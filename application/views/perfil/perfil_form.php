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

	                        

	                        <li class="active"><i class="<?= ICON_FORM; ?>"></i>&nbsp; Formulario</li>

	                    </ul>

	  				</div> <!-- /widget-header -->

					<div class="widget-content">

						<div id="formcontrols" class="">

							<?php

							$attributes = array(

							  'id'      	=> 'form_usuario',

							  'name'    	=> 'form_usuario',

							  'class'		=> 'form-horizontal',

							  'tipo_vista' 	=> $tipo_vista

							  );

							$ruta = 'perfil/usuario/guardar/'.((isset($data_usua))?str_encrypt($data_usua->usua_id, KEY_ENCRYPT):'');

							echo validation_errors();

							echo form_open($ruta, $attributes);

							?>

							<fieldset>

								<div class="control-group">

									<label for="usua_nombre" class="control-label">Nombre</label>

									<div class="controls">

										<input type="text" value="<?= (isset($data_usua))?$data_usua->usua_nombre:set_value('usua_nombre'); ?>" id="usua_nombre" name="usua_nombre" class="span8" maxlength="40" disabled>

									</div> <!-- /controls -->

								</div> <!-- /control-group -->

								<div class="control-group">

									<label for="dide_id" class="control-label">Tipo Documento</label>

									<div class="controls">

					                    <select class="selectpicker span8" name="dide_id" id="dide_id" disabled>

					                    <?php

					                    if(isset($data_doid)){

					                    	foreach ($data_doid as $item => $docidentidad) {

					                   	?>

											<option value="<?= $docidentidad->dide_id; ?>" data-length="<?= $docidentidad->dide_caracteres; ?>" <?= (isset($data_usua) && $data_usua->dide_id === $docidentidad->dide_id)?'selected': set_select('dide_id', $docidentidad->dide_id); ?>><?= $docidentidad->dide_descripcion; ?></option>

					                   	<?php

					                    	}

					                    }

					                    ?>

					                    </select>

									</div> <!-- /controls -->

								</div> <!-- /control-group -->

								<div class="control-group">

									<label for="usua_numero_documento" class="control-label">Doc. Identidad <span id="dideid_maxlength">(8 dig.)</span></label>

									<div class="controls">

										<input type="text" value="<?= (isset($data_usua))?$data_usua->usua_numero_documento:set_value('usua_numero_documento'); ?>" id="usua_numero_documento" name="usua_numero_documento" class="span8" maxlength="8" disabled>

									</div> <!-- /controls -->

								</div> <!-- /control-group -->

								<div class="control-group">

									<label for="usua_clave" class="control-label">Clave</label>

									<div class="controls checkbox input-group">

										<label class="input-group-addon" style="display: none;">

											<input type="checkbox" class="chkbx_x">

										</label>

										<input type="password" value="" id="usua_clave" name="usua_clave" class="span8 form-control" maxlength="50">

									</div> <!-- /controls -->

								</div> <!-- /control-group -->


							</fieldset>

					       	<?php

					        echo form_close();

					        ?>

							<hr>

							<br>




						</div>

					</div> <!-- /widget-content -->

				</div> <!-- /widget -->

		    </div> <!-- /span8 -->

	      </div> <!-- /row -->

	    </div> <!-- /container -->

	</div> <!-- /main-inner -->

</div> <!-- /main -->



<script src="<?php echo base_url('public/assets/js/Usuario.js') ?>"></script>