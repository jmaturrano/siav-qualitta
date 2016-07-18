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
	                        <li><a href="<?php echo base_url('registros/requisitosxcarrera') ?>"><i class="<?= $header_icon; ?>"></i>&nbsp; <?= $header_title; ?></a></li>
	                        <li>&nbsp; /</li>
	                        <li class="active"><i class="<?= ICON_FORM; ?>"></i>&nbsp; Formulario</li>
	                    </ul>
	  				</div> <!-- /widget-header -->
					<div class="widget-content">
						<div id="formcontrols" class="">
							<?php
							$attributes = array(
							  'id'      	=> 'form_requisitosxcarrera',
							  'name'    	=> 'form_requisitosxcarrera',
							  'class'		=> 'form-horizontal',
							  'tipo_vista' 	=> $tipo_vista
							  );
							$ruta = 'registros/requisitosxcarrera/guardar/'.((isset($data_rxca))?str_encrypt($data_rxca->rxca_id, KEY_ENCRYPT):'');
							echo form_open($ruta, $attributes);
							?>
							<fieldset>

                                <div class="control-group">
                                    <label for="carr_id" class="control-label" style="">Carrera</label>
                                    <div class="controls">
                                        <select class="selectpicker span8" name="carr_id" id="carr_id" data-live-search="true" data-subruta="" data-container="body">
                                            <option value="">Seleccione</option>
                                        <?php
                                        if(isset($data_carr)){
                                            foreach ($data_carr as $item => $carrera) {
                                        ?>
                                            <option value="<?= $carrera->carr_id; ?>" <?= (isset($data_rxca) && $data_rxca->carr_id === $carrera->carr_id)?'selected':set_select('carr_id', $carrera->carr_id); ?>><?= $carrera->carr_codigo.' - '.substr($carrera->carr_descripcion, 0, LIMITSELECT); ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                        </select>
                                    </div> <!-- /controls -->
                                </div> <!-- /control-group -->

                                <div class="control-group">
                                    <label for="rcar_id" class="control-label" style="">Requisito</label>
                                    <div class="controls">
                                        <select class="selectpicker span8" name="rcar_id" id="rcar_id" data-live-search="true" data-subruta="" data-container="body">
                                            <option value="">Seleccione</option>
                                        <?php
                                        if(isset($data_rcar)){
                                            foreach ($data_rcar as $item => $requisito) {
                                        ?>
                                            <option value="<?= $requisito->rcar_id; ?>" <?= (isset($data_rxca) && $data_rxca->rcar_id === $requisito->rcar_id)?'selected':set_select('rcar_id', $requisito->rcar_id); ?>><?= substr($requisito->rcar_descripcion, 0, LIMITSELECT); ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                        </select>
                                    </div> <!-- /controls -->
                                </div> <!-- /control-group -->

								<div class="control-group">
									<label for="rxca_obligatorio" class="control-label">Obligatorio</label>
									<div class="controls">
										<?php
										$checked = 0;
										if(isset($data_rxca)){
											if($data_rxca->rxca_obligatorio == 'S'){
												$checked = 1;
											}
										}
										?>
										<input type="checkbox" id="rxca_obligatorio" name="rxca_obligatorio" checked="" value="<?= $checked; ?>" class="rxca_obligatorio" data-three-state="false" data-toggle="checkbox-x"> 
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







<script src="<?php echo base_url('public/assets/js/Requisitosxcarrera.js') ?>"></script>