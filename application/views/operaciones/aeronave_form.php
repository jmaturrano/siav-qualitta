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
	                        <li><a href="<?php echo base_url('operaciones/aeronave') ?>"><i class="<?= $header_icon; ?>"></i>&nbsp; <?= $header_title; ?></a></li>
	                        <li>&nbsp; /</li>
	                        <li class="active"><i class="<?= ICON_FORM; ?>"></i>&nbsp; Formulario</li>
	                    </ul>
	  				</div> <!-- /widget-header -->
					<div class="widget-content">
						<div id="formcontrols" class="">
							<?php
							$attributes = array(
							  'id'      	=> 'form_aeronave',
							  'name'    	=> 'form_aeronave',
							  'class'		=> 'form-horizontal',
							  'tipo_vista' 	=> $tipo_vista
							  );
							$ruta = 'operaciones/aeronave/guardar/'.((isset($data_aero))?str_encrypt($data_aero->aero_id, KEY_ENCRYPT):'');
							echo form_open($ruta, $attributes);
							?>
							<fieldset>
								<div class="control-group" >
									<label for="tiae_id" class="control-label">Marca</label>
									<div class="controls">
					                    <select class="selectpicker span4" name="tiae_id" id="tiae_id" data-container="body">
					                    <option value="">Seleccione</option>
					                    <?php
					                    if(isset($data_tiae)){
					                    	foreach ($data_tiae as $item => $tiae) {
					                   	?>
											<option value="<?= $tiae->tiae_id; ?>" <?= (isset($data_aero) && $data_aero->tiae_id === $tiae->tiae_id)?'selected': set_select('tiae_id', $tiae->tiae_id); ?>><?= $tiae->tiae_descripcion; ?></option>
					                   	<?php
					                    	}
					                    }
					                    ?>
					                    </select>
									</div> <!-- /controls -->
								</div> <!-- /control-group -->
								<div class="control-group" >
									<label for="moae_id" class="control-label">Modelo</label>
									<div class="controls">
					                    <select class="selectpicker span4" name="moae_id" id="moae_id" data-container="body">
					                    <option value="">Seleccione</option>
					                    <?php
					                    if(isset($data_moae)){
					                    	foreach ($data_moae as $item => $moae) {
					                   	?>
											<option value="<?= $moae->moae_id; ?>" <?= (isset($data_aero) && $data_aero->moae_id === $moae->moae_id)?'selected': set_select('moae_id', $moae->moae_id); ?>><?= $moae->moae_descripcion; ?></option>
					                   	<?php
					                    	}
					                    }
					                    ?>
					                    </select>
									</div> <!-- /controls -->
								</div> <!-- /control-group -->
								<div class="control-group">
									<label for="aero_matricula" class="control-label">Matrícula</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_aero))?$data_aero->aero_matricula:set_value('aero_matricula'); ?>" id="aero_matricula" name="aero_matricula" class="span8" maxlength="45">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->
								<div class="control-group">
									<label for="aero_fecha_fabricacion" class="control-label">Fecha de fabricación</label>
									<div class="controls">
									    <div class="datepickerx" data-date="<?= (isset($data_aero))?fecha_latino($data_aero->aero_fecha_fabricacion):date('d/m/Y'); ?>" data-date-format="dd/mm/yyyy">
									      <input class="span2" size="16" type="text" value="<?= (isset($data_aero))?fecha_latino($data_aero->aero_fecha_fabricacion):set_value('aero_fecha_fabricacion'); ?>" id="aero_fecha_fabricacion" name="aero_fecha_fabricacion">
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

<script src="<?php echo base_url('public/assets/js/Aeronave.js') ?>"></script>