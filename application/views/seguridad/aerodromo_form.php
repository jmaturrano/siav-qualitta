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
	                        <li><a href="<?php echo base_url('seguridad/aerodromo') ?>"><i class="<?= $header_icon; ?>"></i>&nbsp; <?= $header_title; ?></a></li>
	                        <li>&nbsp; /</li>
	                        <li class="active"><i class="<?= ICON_FORM; ?>"></i>&nbsp; Formulario</li>
	                    </ul>

	  				</div> <!-- /widget-header -->

					<div class="widget-content">
						<div id="formcontrols" class="">
							<?php
							$attributes = array(
							  'id'      	=> 'form_aerodromo',
							  'name'    	=> 'form_aerodromo',
							  'class'		=> 'form-horizontal',
							  'tipo_vista' 	=> $tipo_vista
							  );
							$ruta = 'seguridad/aerodromo/guardar/'.((isset($data_aero))?str_encrypt($data_aero->aero_id,KEY_ENCRYPT):'');
							echo form_open($ruta, $attributes);
							?>
							<fieldset>

                                <div class="control-group">
                                    <label for="depa_id" class="control-label" style="">Departamento</label>
                                    <div class="controls">
                                        <select class="selectpicker span8" name="depa_id" id="depa_id" data-live-search="true" data-subruta="<?= base_url('registros/provincia/getProvincia_ajax/{SELECTED}'); ?>" data-container="body">
                                            <option value="">Seleccione</option>
                                        <?php
                                        if(isset($data_depa)){
                                            foreach ($data_depa as $item => $departamento) {
                                        ?>
                                            <option value="<?= $departamento->depa_id; ?>" <?= (isset($data_aero) && $data_aero->depa_id === $departamento->depa_id)?'selected':set_select('depa_id', $departamento->depa_id); ?>><?= substr($departamento->depa_descripcion, 0, LIMITSELECT); ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                        </select>
                                    </div> <!-- /controls -->
                                </div> <!-- /control-group -->

								<div class="control-group">
									<label for="aero_codigo" class="control-label">Código OACI</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_aero))?$data_aero->aero_codigo:set_value('aero_codigo'); ?>" id="aero_codigo" name="aero_codigo" class="span8" maxlength="5">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

								<div class="control-group">
									<label for="aero_nombre" class="control-label">Nombre Aeródromo</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_aero))?$data_aero->aero_nombre:set_value('aero_nombre'); ?>" id="aero_nombre" name="aero_nombre" class="span8" maxlength="80">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->
								<div class="control-group">
									<label for="aero_abreviatura" class="control-label">Abreviatura</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_aero))?$data_aero->aero_abreviatura:set_value('aero_abreviatura'); ?>" id="aero_abreviatura" name="aero_abreviatura" class="span8" maxlength="10">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->
								<div class="control-group">
									<label for="aero_direccion" class="control-label">Dirección</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_aero))?$data_aero->aero_direccion:set_value('aero_direccion'); ?>" id="aero_direccion" name="aero_direccion" class="span8" maxlength="120">
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

<script src="<?php echo base_url('public/assets/js/Aerodromo.js') ?>"></script>