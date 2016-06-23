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
	                        <li><a href="<?php echo base_url('seguridad/menu') ?>"><i class="<?= $header_icon; ?>"></i>&nbsp; <?= $header_title; ?></a></li>
	                        <li>&nbsp; /</li>
	                        <li class="active"><i class="<?= ICON_FORM; ?>"></i>&nbsp; Formulario</li>
	                    </ul>
	  				</div> <!-- /widget-header -->
					<div class="widget-content">
						<div id="formcontrols" class="">
							<?php
							$attributes = array(
							  'id'      	=> 'form_menu',
							  'name'    	=> 'form_menu',
							  'class'		=> 'form-horizontal',
							  'tipo_vista' 	=> $tipo_vista
							  );
							$ruta = 'seguridad/menu/guardar/'.((isset($data_menu))?str_encrypt($data_menu->menu_id, KEY_ENCRYPT):'');
							echo form_open($ruta, $attributes);
							?>
							<fieldset>

	                            <div class="control-group">
	                                <label for="menu_nivel" class="control-label" style="">Nivel</label>
	                                <div class="controls">
	                                    <select class="selectpicker span8" name="menu_nivel" id="menu_nivel" data-container="body">
	                                    	<option value="">Seleccione</option>
	                                        <option value="1" <?= (isset($data_menu)&&$data_menu->menu_nivel==='1')?'selected':set_select('menu_nivel', '1'); ?>>Menú</option>
	                                        <option value="2" <?= (isset($data_menu)&&$data_menu->menu_nivel==='2')?'selected':set_select('menu_nivel', '2'); ?>>Opción</option>
	                                    </select>
	                                </div> <!-- /controls -->
	                            </div> <!-- /control-group -->
	                            <div class="control-group">
	                                <label for="menu_idpadre" class="control-label" style="">Superior</label>
	                                <div class="controls">
	                                    <select class="selectpicker span8" name="menu_idpadre" id="menu_idpadre" data-container="body">
	                                        <option value="">Ninguno</option>
	                                        <?php
	                                        if(isset($data_menupadre)){
	                                        	foreach ($data_menupadre as $item => $mpadre) {
	                                        		$selected = (isset($data_menu) && $data_menu->menu_idpadre === $mpadre->menu_id)?'selected':'';
	                                       	?>
	                                       		<option value="<?= $mpadre->menu_id; ?>" <?= (isset($data_menu)?$selected:set_select('menu_idpadre', $mpadre->menu_id)); ?>><?= $mpadre->menu_descripcion; ?></option>
	                                       	<?php
	                                        	}
	                                        }
	                                        ?>
	                                    </select>
	                                </div> <!-- /controls -->
	                            </div> <!-- /control-group -->
								<div class="control-group">
									<label for="menu_codigo" class="control-label">Código (04 dig.)</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_menu))?$data_menu->menu_codigo:set_value('menu_codigo'); ?>" id="menu_codigo" name="menu_codigo" class="span8" maxlength="4">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->
								<div class="control-group">
									<label for="menu_orden" class="control-label">Orden grupo</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_menu))?str_pad($data_menu->menu_orden, 5, '0', STR_PAD_LEFT):set_value('menu_orden'); ?>" id="menu_orden" name="menu_orden" class="span8" maxlength="5">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->
								<div class="control-group">
									<label for="menu_formulario" class="control-label">Formulario</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_menu))?$data_menu->menu_formulario:set_value('menu_formulario'); ?>" id="menu_formulario" name="menu_formulario" class="span8" maxlength="100">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->
								<div class="control-group">
									<label for="menu_descripcion" class="control-label">Descripción menú</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_menu))?$data_menu->menu_descripcion:set_value('menu_descripcion'); ?>" id="menu_descripcion" name="menu_descripcion" class="span8" maxlength="100">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->
	                            <div class="control-group">
	                                <label for="menu_control_agencia" class="control-label" style="">Control Agencia</label>
	                                <div class="controls">
	                                    <select class="selectpicker span8" name="menu_control_agencia" id="menu_control_agencia" data-container="body">
	                                        <option value="S" <?= (isset($data_menu)&&$data_menu->menu_control_agencia==='S')?'selected':set_select('menu_control_agencia', 'S'); ?>>Si</option>
	                                        <option value="N" <?= (isset($data_menu)&&$data_menu->menu_control_agencia==='N')?'selected':set_select('menu_control_agencia', 'N'); ?>>No</option>
	                                    </select>
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

<script src="<?php echo base_url('public/assets/js/Menu.js') ?>"></script>