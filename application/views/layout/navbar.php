 
<div class="header-bar">
  <div class="navbar navbar-fixed-top">
    <div class="navbar-inner" style="padding: 0;">
      <div class="container">
        <a class="brand custom-logoempresa" href="<?= base_url(); ?>">
        	<img src="<?= base_url(LOGOEMPRESA); ?>"/>
        </a>
        <div class="nav-collapse">
          <ul class="nav pull-right">
            <li class="dropdown">
            	<a href="#" class="dropdown-toggle" data-toggle="dropdown">
            		<i class="icon-cog"></i> <?= $this->session->userdata('usua_nombre').' '.$this->session->userdata('usua_apellido'); ?> <b class="caret"></b>
            	</a>
              <ul class="dropdown-menu">
                <li><a href="<?= base_url('perfil/usuario/editar/'.str_encrypt($this->session->userdata('usua_id') , KEY_ENCRYPT)); ?>"><i class="icon-user"></i> Perfil</a></li>
                <li><a href="<?= base_url('seguridad/login/accesslogout'); ?>"><i class="icon-power-off"></i> Salir</a></li>
              </ul>
            </li>
          </ul>
        </div>
        <!--/.nav-collapse -->
      </div>

  	<div class="container">
  		<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
  			<span class="icon-bar"></span>
  			<span class="icon-bar"></span>
  			<span class="icon-bar"></span>
  		</a>
          <?php
          $attributes = array(
            'id'        => 'form_navbar_search',
            'name'      => 'form_navbar_search',
            'class'     => 'navbar-search pull-left'
            );
          $ruta = 'seguridad/menu/accesodirecto/';
          echo form_open($ruta, $attributes);
          ?>
            <input type="text" class="search-query" placeholder="Buscar" name="menu_codigo">
          <?php
          echo form_close();
          ?>
  		<div class="nav-collapse">
  			<ul class="nav pull-right" style="display: none;">
  			  <li class="dropdown">
  			  	<a aria-expanded="false" href="#" class="dropdown-toggle" data-toggle="dropdown">
  			  		<i class="icon-cog"></i> Account <b class="caret"></b>
  			  	</a>
  			    <ul class="dropdown-menu">
  			      <li><a href="javascript:;">Settings</a></li>
  			      <li><a href="javascript:;">Help</a></li>
  			    </ul>
  			  </li>
  			</ul>
        <div class="pull-right">
          <select class="selectpicker span2" name="otip_central" id="otip_central" data-function="<?= base_url('panel/principal/cambiarofic/{UXOF_ID}/{SELECTED}'); ?>">
            <?php
              if(isset($OFICINAS)){
                foreach ($OFICINAS as $key => $oficina) {
            ?>
              <option data-uxof="<?= $oficina->uxof_id; ?>" value="<?= $oficina->ofic_id; ?>" <?= ($this->session->userdata('ofic_id') === $oficina->ofic_id)?'selected':''; ?>><?= $oficina->ofic_nombre; ?></option>
            <?php
                }
              }
            ?>
          </select>
        </div>
  		</div>
  		<!--/.nav-collapse --> 
      </div>
      <!-- /container --> 
    </div>
    <!-- /navbar-inner --> 
  </div>
  <!-- /navbar -->

  <div class="subnavbar">
    <div class="subnavbar-inner">
      <div class="container">
        <!--
        <ul class="mainnav" id="dropdown_menu" data-url="<?= base_url('seguridad/menu/listitem'); ?>" base-url="<?= base_url(); ?>">
        </ul>
        -->
        <ul class="mainnav" id="dropdown_menu" >
        <?php
              if(isset($PRIVILEGIOS)){
                foreach ($PRIVILEGIOS as $key => $privilegio) {
            ?>
              <li class="dropdown dropdown-accordion" data-accordion="#accordion" id="0<?=$key; ?>00">
              <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
                <span><?=$privilegio["menu_descripcion"]; ?></span>
                <b class="caret"></b></a>
                <ul class="dropdown-menu 0<?=$key; ?>00" role="menu" aria-labelledby="dLabel">
                <ul></ul>
                <div id="accordion<?=$key;?>" >
                
                <?php 
                      if(isset($privilegio["opcion"])){
                      $count=0;
                      $pos = 0;
                      $aux = array();
                      $script = false;
                      
                      foreach( $privilegio["opcion"] as $subkey => $item) {

                        //if($privilegio["opcion"][$count]['permisos']['mxro_ingresa'] === '1'){
                          $pos++;
                          $strkey = ''.$subkey;
                          if($subkey < 10 )
                            $strkey = '0'.$strkey;
                          
                          if($item["permisos"]['mxro_accesa'] === '1'){
                            if (strpos($item["menu_descripcion"],"**" ) !== false) {
                                  $pos=0;
                                  $count++;
                                  $aux[$count]["items"] = array();
                                  $aux[$count]["menu_descripcion"] = $item["menu_descripcion"];
                                  $aux[$count]["id"] = $key.$strkey;

                            }else{
                               $pos++;
                               $aux[$count]["items"][$pos]["menu_descripcion"] = $item["menu_descripcion"];
                               $aux[$count]["items"][$pos]["menu_formulario"]  = $item["menu_formulario"];
                               $aux[$count]["items"][$pos]["id"] = $key.$strkey;
                      
                            }//end else
                          }//end if
                        //}
                    }//enf foreach

                    if($count==0 && count($privilegio["opcion"]) > 0 ){
                        $script=false;
                        echo "<div>";
                        foreach($privilegio["opcion"] as $subkey => $item){
                              $url="javascript:void(0)";
                              if(isset($item["menu_formulario"]))
                                $url = base_url( $item["menu_formulario"] );
                              echo "<a href='".$url."' >".$item["menu_descripcion"]."</a>"; 
                        }
                        echo "</div>";

                    }else{
                     $script = true;
                     foreach($aux as $count => $opcion){
                        if(isset($opcion["id"])){
                          echo "  <h3>";
                          echo "    <a href='#collapse".$opcion["id"]."' index='".($count-1)."'>";
                          echo $opcion["menu_descripcion"];
                          echo "    </a>";
                          echo "  </h3>";
                          echo "<div id='collapse".$opcion["id"]."'>";
                          foreach($opcion["items"] as $pos => $item){
                                $url="javascript:void(0)";
                                if(isset($item["menu_formulario"]))
                                  $url = base_url( $item["menu_formulario"] );
                                echo "<a href='".$url."' >".$item["menu_descripcion"]."</a>"; 
                          }
                          echo "</div>";
                        }

                    }
                  }

                ?>
                
               </div>
               <? if($script) { ?>
               <script type="text/javascript">
               $(function() {
                $( "#accordion<?=$key;?>" ).accordion({
                  heightStyle: "content",
                  collapsible: true
                });
             
              // Prevent dropdown to be closed when we click on an accordion link
              $('#accordion<?=$key;?> h3').on('click', 'a', function (event) {
                event.preventDefault();
                event.stopPropagation();
                
                //console.log($( '#accordion<?=$key;?>').accordion( "option", "active" ));
                if(parseInt($(this).attr('index'))!==$( '#accordion<?=$key;?>').accordion( "option", "active" ))
                  $( '#accordion<?=$key;?>').accordion( "option", "active", parseInt($(this).attr('index') ) );
                else
                  $( '#accordion<?=$key;?>').accordion( "option", "active",  false );
                //console.log('active: '+active);
                
                });
                
              });
              </script>
              <? } ?>
                </ul>
                
              </li>
            <?php
                }
              }
            }
            ?>
        </ul>
      </div>
      <!-- /container --> 
    </div>
    <!-- /subnavbar-inner --> 
  </div>
  <!-- /subnavbar -->
</div>

<style>

.dropdown-accordion  a {
  display: block;
  /*padding: 10px 15px;*/
  text-decoration: none;
}
</style>


