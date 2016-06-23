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
                            <li><a href="<?php echo base_url('registros/departamento') ?>"><i class="<?= $header_icon; ?>"></i>&nbsp; Departamentos</a></li>
                            <li>&nbsp; /</li>
                            <li><a href="<?= (isset($data_depa)?base_url('registros/departamento/ver/'.str_encrypt($data_depa->depa_id, KEY_ENCRYPT)):'javascript:;') ?>"><i class="<?= ICON_FORM; ?>"></i>&nbsp; Formulario</a></li>
                            <li>&nbsp; /</li>
                            <li><a href="<?= (isset($data_prov)?base_url('registros/provincia/ver/'.str_encrypt($data_depa->depa_id, KEY_ENCRYPT).'/'.str_encrypt($data_prov->prov_id, KEY_ENCRYPT)):'javascript:;') ?>"><i class="<?= $header_icon; ?>"></i>&nbsp; Provincias</a></li>
                            <li>&nbsp; /</li>
                            <li class="active"><i class="<?= $header_icon; ?>"></i>&nbsp; <?= $header_title; ?></li>
                        </ul>

                    </div> <!-- /widget-header -->
                    <div class="widget-content">
                        <div id="formcontrols" class="">
                            <?php
                            $attributes = array(
                              'id'          => 'form_distrito',
                              'name'        => 'form_distrito',
                              'class'       => 'form-horizontal',
                              'tipo_vista'  => $tipo_vista
                              );
                            $ruta = 'registros/distrito/guardar/'.(isset($data_prov)?str_encrypt($data_prov->prov_id, KEY_ENCRYPT):'').'/'.((isset($data_dist))?str_encrypt($data_dist->dist_id, KEY_ENCRYPT):'');
                            echo form_open($ruta, $attributes);
                            ?>
                            <fieldset>
                                <div class="control-group">
                                    <label for="depa_descripcion" class="control-label">Departamento</label>
                                    <div class="controls">
                                        <input type="text" value="<?= (isset($data_depa))?'('.$data_depa->depa_codigo.') '.$data_depa->depa_descripcion:set_value('depa_descripcion'); ?>" id="depa_descripcion" name="depa_descripcion" class="span8" maxlength="25" disabled>
                                    </div> <!-- /controls -->
                                </div> <!-- /control-group -->
                                <div class="control-group">
                                    <label for="prov_descripcion" class="control-label">Provincia</label>
                                    <div class="controls">
                                        <input type="text" value="<?= (isset($data_prov))?'('.$data_prov->prov_codigo.') '.$data_prov->prov_descripcion:set_value('prov_descripcion'); ?>" id="prov_descripcion" name="prov_descripcion" class="span8" maxlength="40" disabled>
                                    </div> <!-- /controls -->
                                </div> <!-- /control-group -->
                                <div class="control-group">
                                    <label for="dist_codigo" class="control-label">Código</label>
                                    <div class="controls">
                                        <input type="text" value="<?= (isset($data_dist))?$data_dist->dist_codigo:set_value('dist_codigo'); ?>" id="dist_codigo" name="dist_codigo" class="span8" maxlength="30">
                                    </div> <!-- /controls -->
                                </div> <!-- /control-group -->
                                <div class="control-group">
                                    <label for="prov_descripcion" class="control-label">Distrito</label>
                                    <div class="controls">

                                        <input type="text" value="<?= (isset($data_dist))?$data_dist->dist_descripcion:set_value('dist_descripcion'); ?>" id="dist_descripcion" name="dist_descripcion" class="span8" maxlength="50" >
                                    </div> <!-- /controls -->
                                </div> <!-- /control-group -->
                                <input type="hidden" name="prov_id" id="prov_id" value="<?=((isset($data_prov))?$data_prov->prov_id:'')?>" />
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

<script src="<?php echo base_url('public/assets/js/Distrito.js') ?>"></script>