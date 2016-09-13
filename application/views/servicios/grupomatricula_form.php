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
	                        <li><a href="<?php echo base_url('servicios/grupomatricula') ?>"><i class="<?= $header_icon; ?>"></i>&nbsp; <?= $header_title; ?></a></li>
	                        <li>&nbsp; /</li>
	                        <li class="active"><i class="<?= ICON_FORM; ?>"></i>&nbsp; Formulario</li>
	                    </ul>
	  				</div> <!-- /widget-header -->
					<div class="widget-content">
						<div id="formcontrols" class="">
							<?php
							$attributes = array(
							  'id'      	=> 'form_grupomatricula',
							  'name'    	=> 'form_grupomatricula',
							  'class'		=> 'form-horizontal',
							  'tipo_vista' 	=> $tipo_vista
							  );
							$ruta = 'servicios/grupomatricula/guardar/'.((isset($data_gmat))?str_encrypt($data_gmat->gmat_id, KEY_ENCRYPT):'');
							echo form_open($ruta, $attributes);
							?>
							<fieldset>
                                <div class="control-group">
                                    <label for="moda_id" class="control-label" style="">Modalidad</label>
                                    <div class="controls">
                                        <select class="selectpicker span8" name="moda_id" id="moda_id" data-live-search="true" data-subruta="" data-container="body">
                                            <option value="">Seleccione</option>
                                        <?php
                                        if(isset($data_moda)){
                                            foreach ($data_moda as $item => $modalidad) {
                                        ?>
                                            <option value="<?= $modalidad->moda_id; ?>" <?= (isset($data_gmat) && $data_gmat->moda_id === $modalidad->moda_id)?'selected':set_select('moda_id', $modalidad->moda_id); ?>><?= substr($modalidad->moda_descripcion, 0, LIMITSELECT); ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                        </select>
                                    </div> <!-- /controls -->
                                </div> <!-- /control-group -->

                                <div class="control-group">
                                    <label for="carr_id" class="control-label" style="">Programa de instrucción</label>
                                    <div class="controls">
                                        <select class="selectpicker span8" name="carr_id" id="carr_id" data-live-search="true" data-subruta="" data-container="body">
                                            <option value="">Seleccione</option>
                                        <?php
                                        if(isset($data_carr)){
                                            foreach ($data_carr as $item => $carrera) {
                                        ?>
                                            <option value="<?= $carrera->carr_id; ?>" <?= (isset($data_gmat) && $data_gmat->carr_id === $carrera->carr_id)?'selected':set_select('carr_id', $carrera->carr_id); ?>><?= $carrera->carr_codigo.' - '.substr($carrera->carr_descripcion, 0, LIMITSELECT); ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                        </select>
                                    </div> <!-- /controls -->
                                </div> <!-- /control-group -->

								<div class="control-group">
									<label for="gmat_fecha_inicio" class="control-label">Fecha inicio</label>
									<div class="controls">
									    <div class="datepickerx" data-date="<?= (isset($data_gmat))?fecha_latino($data_gmat->gmat_fecha_inicio):date('d/m/Y'); ?>" data-date-format="dd/mm/yyyy">
									      <input class="span2" size="16" type="text" value="<?= (isset($data_gmat))?fecha_latino($data_gmat->gmat_fecha_inicio):date('d/m/Y'); ?>" id="gmat_fecha_inicio" name="gmat_fecha_inicio">
									    </div>
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

								<div class="control-group">
									<label for="gmat_observacion" class="control-label">Observaciones</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_gmat))?$data_gmat->gmat_observacion:set_value('gmat_observacion'); ?>" id="gmat_observacion" name="gmat_observacion" class="span8" maxlength="200">
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


<script src="<?php echo base_url('public/assets/js/Grupomatricula.js') ?>"></script>