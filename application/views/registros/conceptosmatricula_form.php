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
	                        <li><a href="<?php echo base_url('registros/conceptosmatricula') ?>"><i class="<?= $header_icon; ?>"></i>&nbsp; <?= $header_title; ?></a></li>
	                        <li>&nbsp; /</li>
	                        <li class="active"><i class="<?= ICON_FORM; ?>"></i>&nbsp; Formulario</li>
	                    </ul>
	  				</div> <!-- /widget-header -->
					<div class="widget-content">
						<div id="formcontrols" class="">
							<?php
							$attributes = array(
							  'id'      	=> 'form_conceptosmatricula',
							  'name'    	=> 'form_conceptosmatricula',
							  'class'		=> 'form-horizontal',
							  'tipo_vista' 	=> $tipo_vista
							  );
							$ruta = 'registros/conceptosmatricula/guardar/'.((isset($data_cmat))?str_encrypt($data_cmat->cmat_id, KEY_ENCRYPT):'');
							echo form_open($ruta, $attributes);
							?>
							<fieldset>
                                <div class="control-group">
                                    <label for="ctip_id" class="control-label" style="">Tipo concepto</label>
                                    <div class="controls">
                                        <select class="selectpicker span8" name="ctip_id" id="ctip_id" data-live-search="true" data-subruta="" data-container="body">
                                            <option value="">Seleccione</option>
	                                        <?php
	                                        if(isset($data_ctip)){
	                                            foreach ($data_ctip as $item => $ctip) {
	                                        ?>
	                                            <option value="<?= $ctip->ctip_id; ?>" <?= (isset($data_cmat) && $data_cmat->ctip_id === $ctip->ctip_id)?'selected':''; ?> ><?= $ctip->ctip_descripcion; ?></option>
	                                        <?php
	                                            }//end foreach
	                                        }
	                                        ?>
                                        </select>
                                    </div> <!-- /controls -->
                                </div> <!-- /control-group -->

                                <div class="control-group">
                                    <label for="lipe_id" class="control-label" style="">Lista Precio</label>
                                    <div class="controls">
                                        <select class="selectpicker span8 selectpicker-carrera" name="lipe_id" id="lipe_id" data-live-search="true" data-subruta="" data-container="body">
                                            <option value="">Seleccione</option>
	                                        <?php
	                                        if(isset($data_lipe)){
	                                            foreach ($data_lipe as $item => $lipe) {
	                                            	$checked = ($lipe->lipe_indvigente === 'S' ? 'selected' : '');
	                                        ?>
	                                            <option <?= $checked; ?> value="<?= $lipe->lipe_id; ?>"><?= $lipe->lipe_descripcion.($checked==='selected'?' - Principal' : ''); ?></option>
	                                        <?php
	                                            }//end foreach
	                                        }
	                                        ?>
                                        </select>
                                    </div> <!-- /controls -->
                                </div> <!-- /control-group -->

								<div class="control-group">
									<label for="cmat_orden" class="control-label">Orden</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_cmat))?$data_cmat->cmat_orden:set_value('cmat_orden'); ?>" id="cmat_orden" name="cmat_orden" class="span8">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

								<div class="control-group">
									<label for="cmat_descripcion" class="control-label">Descripción</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_cmat))?$data_cmat->cmat_descripcion:set_value('cmat_descripcion'); ?>" id="cmat_descripcion" name="cmat_descripcion" class="span8" maxlength="50">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

								<div class="control-group">
									<label for="cmat_costo" class="control-label">Costo</label>
									<div class="controls">
										<?php
										$costo = (isset($data_cmat))?$data_cmat->cmat_costo:set_value('cmat_costo');
										?>
										<input type="text" value="<?= ($costo != '') ? number_format($costo, 2) : ''; ?>" id="cmat_costo" name="cmat_costo" class="span8" >
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

								<div class="control-group">
									<label for="cmat_obligatorio" class="control-label">Hacer obligatorio</label>
									<div class="controls">
										<?php
										$checked = 0;
										if(isset($data_cmat)){
											if($data_cmat->cmat_obligatorio == 'S'){
												$checked = 1;
											}
										}
										?>
										<input type="checkbox" id="cmat_obligatorio" name="cmat_obligatorio" checked="" value="<?= $checked; ?>" class="cmat_obligatorio" data-three-state="false" data-toggle="checkbox-x"> 
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







<script src="<?php echo base_url('public/assets/js/Conceptomatricula.js') ?>"></script>