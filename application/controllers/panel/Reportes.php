<?php
 
defined('BASEPATH') OR exit('No direct script access allowed');
 
 
 
 
class Reportes extends CI_Controller {
 
    private static $PARAMETROS = array();
    private static $OFICINAS = array();
    private static $ROLES = array();
    private static $PRIVILEGIOS = array();
    private static $PERMISOS = array();
 
    private static $header_title  = 'Reporte';
    private static $header_icon  = ICON_DOWNLOAD;
 
    public function __construct() {
        parent :: __construct();
        $this->initData();
        
         $params = array('L', 'mm', 'A4');
         
         $this->load->library('excel');
         $this->load->library('factory');
 
         $this->load->model('poa/centropoblado_model');
         $this->load->model('poi/periodo_model');
         $this->load->model('poi/politica_model');
         $this->load->model('poi/estructura_model');
         $this->load->model('seguridad/oficinagrupo_model');
         $this->load->model('seguridad/oficina_model');
 
         $this->load->model('panel/reporte_model');
 
    }
 
    public function initData(){
        self::$OFICINAS     = revisar_oficinas($this);
        self::$ROLES        = revisar_roles($this, 0);
        self::$PRIVILEGIOS  = revisar_privilegios($this, 0);  
           
    }
 
    public function ver($repnum) {
        
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        self::$PERMISOS     = revisar_permisos(self::$PRIVILEGIOS, 'panel/reportes/ver/'.$repnum); 
        $data['PERMISOS']       = self::$PERMISOS;
 
        $this->load->library('layout');
        $this->load->library('pagination');
        
 
        $data['header_title']   = self::$header_title." Formato $repnum ";
        $data['header_icon']    = self::$header_icon;
 
        $data['btn_exportar']   = 'javascript:void(0)';
        if($repnum!=2 && $repnum!=3 && $repnum!=4  ){
            $data['btn_imprimir']   = 'javascript:void(0)';    
        }
        
        $data['tipo_vista']     = 'index';
        $data['ruta']      = 'panel/reportes/formato'.$repnum;
        $data['data_peri'] = $this->periodo_model->listar_periodos_combo();
        $data['data_ogru'] = $this->oficinagrupo_model->getOficinagrupoAll();
        $data['data_poli'] = $this->politica_model->listar_politicas_combo();
        $data['data_estr']  = $this->estructura_model->listar_estructuras_combo();

        
        
        $this->layout->view('panel/reportes_form', $data);
       
    }
    
    public function formato1() {

        $formats = array("#,###", "#,###.0", "#,###.00");
      
        $datapost = $this->security->xss_clean($this->input->post());
        $extension = '';
        if(isset($datapost['extension'])){
            $extension = $datapost['extension'];
        }
        
        $ogru_nombre = ''; 
        $ofic_nombre = '';        
        $peri_id  = $datapost['peri_id'];
        $data_peri = $this->periodo_model->obtener_periodo_por_id($peri_id);
        if(isset($datapost['fecha_reporte']) && $datapost['fecha_reporte']!=''){
            $fecha_reporte = str_replace('/', '-', $datapost['fecha_reporte']);
            $fecha_reporte = date('Y-m-d', strtotime($fecha_reporte));
        }else{
            $fecha_reporte = date('Y-m-d', time());
        }

        
 
        $ogru_id_array  = array();
        $ofic_id_array  = array();
        $poli_id_array  = array();
        $ppre_id_array  = array();
        $prod_id_array  = array();
        $acti_id_array  = array();
        $sact_id_array  = array();
        $estr_id_array  = array();


 
        if(isset($datapost['ogru_id_array'])){
            $ogru_id_array  = $datapost['ogru_id_array'];
            if(count($ogru_id_array)==1){                
                $data_ogru = $this->oficinagrupo_model->getOficinagrupoByID($ogru_id_array[0]);                
                $ogru_nombre = $data_ogru->ogru_nombre;
            }
        }else{
            $ogru_nombre = "Todas";
        }
 
        if(isset($datapost['ofic_id_array'])){
            $ofic_id_array  = $datapost['ofic_id_array'];
            if(count($ofic_id_array)==1){
                $data_ofic = $this->oficina_model->getOficinaByID($ofic_id_array[0]);
                $ofic_nombre = $data_ofic->ofic_nombre;
            }
        }else{
            $ofic_nombre = "Todas";
        }
        
        if(isset($datapost['poli_id_array'])){
            $poli_id_array  = $datapost['poli_id_array'];
        }
 
        if(isset($datapost['ppre_id_array'])){
            $ppre_id_array  = $datapost['ppre_id_array'];
        }
 
        if(isset($datapost['prod_id_array'])){
            $prod_id_array  = $datapost['prod_id_array'];
        }
 
        if(isset($datapost['acti_id_array'])){
            $acti_id_array  = $datapost['acti_id_array'];
        }
 
        if(isset($datapost['sact_id_array'])){
            $sact_id_array  = $datapost['sact_id_array'];
        }  

        if(isset($datapost['estr_id_array'])){
            $estr_id_array  = $datapost['estr_id_array'];
        }                           
 
        
        $result =  $this->reporte_model->getReporteProyectos(  $ogru_id_array, $ofic_id_array, $peri_id, $fecha_reporte, 
                                            $poli_id_array, $ppre_id_array, $prod_id_array, 
                                            $acti_id_array , $sact_id_array, $estr_id_array );
        
        $rows=array();
        
        
       
       /** Error reporting */
        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        //date_default_timezone_set('Europe/London');
 
        // Set document properties
        $this->excel->getProperties()->setCreator("iorganizacional.com.pe")
                                     ->setLastModifiedBy("iorganizacional.com.pe")
                                     ->setTitle("Reporte 1")
                                     ->setSubject("Formato 1A")
                                     ->setDescription("Programacion y Ejecucion de Metas.")
                                     ->setKeywords("programacion metas")
                                     ->setCategory("organizacional");
        

        $objDrawing = new PHPExcel_Worksheet_Drawing();
        $objDrawing->setName("name");
        $objDrawing->setDescription("Description");
        $objDrawing->setPath('public/assets/img/logo/agrorural-logo.png');
        $objDrawing->setCoordinates('P1');
        $objDrawing->setWorksheet($this->excel->setActiveSheetIndex(0));

        $this->excel->setActiveSheetIndex(0)
                        ->mergeCells('P1:Q1');
 
 
        // Add some data
 
        $this->excel->setActiveSheetIndex(0)
                    ->setCellValue('D3', "Reporte 1: Programación y Ejecución de Metas Físico Presupuestales \n Por Unidades Orgánicas");
        $this->excel->setActiveSheetIndex(0)
                        ->mergeCells('D3:K4');
        $this->excel->setActiveSheetIndex(0)
                                    ->getStyle('D3:I3')
                                    ->getAlignment()
                                    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Reporte N° 1')                    
                    ->setCellValue('G5', 'Periodo '.$data_peri->peri_nombre)
                    ->setCellValue('N7', 'A la Fecha : ')
                    ->setCellValue('N8', 'Hora : ');
                    //->setCellValue('A7', 'Unidad Orgánica : ')
                    //->setCellValue('A8', 'Agencia Zonal : ')
        $this->excel->setActiveSheetIndex(0)
                                    ->getStyle('A7:B8')
                                    ->getAlignment()
                                    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $this->excel->setActiveSheetIndex(0)
                                    ->getStyle('N7:O8')
                                    ->getAlignment()
                                    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $this->excel->setActiveSheetIndex(0)
                        ->mergeCells('G5:H5');

        $this->excel->setActiveSheetIndex(0)
                        ->mergeCells('N7:O7');
        
        $this->excel->setActiveSheetIndex(0)
                        ->mergeCells('N8:O8');
        
                   
        $fecha_reporte = date('d/m/Y', strtotime($fecha_reporte));
        $hora_reporte  = date('H:i:s', time());
        
 
        $this->excel->setActiveSheetIndex(0)                    
                    ->setCellValue('P7', $fecha_reporte)
                    ->setCellValue('P8', $hora_reporte);
                    //->setCellValue('C7', ' '.$ogru_nombre)
                    //->setCellValue('C8', ' '.$ofic_nombre)                    

        $this->excel->setActiveSheetIndex(0)
                    ->mergeCells('A1:B1');
 
         $this->excel->setActiveSheetIndex(0)
                    ->mergeCells('A7:B7');
        $this->excel->setActiveSheetIndex(0)
                    ->mergeCells('A8:B8');

        $this->excel->setActiveSheetIndex(0)
                        ->mergeCells('P7:Q7');
        
        $this->excel->setActiveSheetIndex(0)
                        ->mergeCells('P8:Q8');
        
 
        $this->excel->setActiveSheetIndex(0)
                                    ->getStyle('C5:C7')
                                    ->getAlignment()
                                    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    
 
        $this->excel->getActiveSheet()
                    ->getPageSetup()
                    ->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $this->excel->getActiveSheet()
                    ->getPageSetup()
                    ->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
       
        
        $BStyle = array(
          'borders' => array(
            'outline' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
            )
          )
        );
 
        $CStyle = array(
          'borders' => array(
            'left' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
            ),
            'right' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
            )
          )
        );
 
        $DStyle = array(
          'borders' => array(
            'top' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
            ),
            'left' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
            ),
            'right' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
            )
          )
        );
 
        $EStyle = array(
          'borders' => array(
            'top' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
            )
          )
        );
 
        $FStyle = array(
          'borders' => array(
            'left' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
            )
          )
        );
 
        $header = array();

        $colIndex = PHPExcel_Cell::columnIndexFromString('A');
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
 
        $header[$columnLetter] = array();
        $header[$columnLetter]['label'] = 'Programa Presupuestal';
        $header[$columnLetter]['start'] = 10;
        $header[$columnLetter]['end'] = 12;

        $colIndex++;
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
 
        $header[$columnLetter] = array();
        $header[$columnLetter]['label'] = 'Producto';
        $header[$columnLetter]['start'] = 10;
        $header[$columnLetter]['end'] = 12;

        $colIndex++;
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
 
        $header[$columnLetter] = array();
        $header[$columnLetter]['label'] = 'Actividad';
        $header[$columnLetter]['start'] = 10;
        $header[$columnLetter]['end'] = 12;

        $colIndex++;
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
 
        $header[$columnLetter] = array();
        $header[$columnLetter]['label'] = 'Subactividad';
        $header[$columnLetter]['start'] = 10;
        $header[$columnLetter]['end'] = 12;

        $colIndex++;
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
 
        $header[$columnLetter] = array();
        $header[$columnLetter]['label'] = 'Unidad Medida';
        $header[$columnLetter]['start'] = 10;
        $header[$columnLetter]['end'] = 12;

        $colIndex++;
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
 
        $header[$columnLetter] = array();
        $header[$columnLetter]['label'] = 'Unidad Orgánica';
        $header[$columnLetter]['start'] = 10;
        $header[$columnLetter]['end'] = 12;

        $colIndex++;
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
 
        $header[$columnLetter] = array();
        $header[$columnLetter]['label'] = 'Agencia Zonal';
        $header[$columnLetter]['start'] = 10;
        $header[$columnLetter]['end'] = 12;

        $colIndex++;
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
        $endLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex+8);
        $startLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
 
        $header[$columnLetter] = array();
        $header[$columnLetter]['label'] = 'Total Oficina';
        $header[$columnLetter]['items'] = array();
        $header[$columnLetter]['start'] = 10;
        $header[$columnLetter]['end'] = $endLetter;

        $endLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex);

        $numberFormat =array();
 
        $header[$columnLetter]['items'][$startLetter] = array();
        $header[$columnLetter]['items'][$startLetter]['label'] = 'Programación PIM';
        $header[$columnLetter]['items'][$startLetter]['start'] = 11;
        $header[$columnLetter]['items'][$startLetter]['end'] = $endLetter;
 
        $header[$columnLetter]['items'][$startLetter]['items'] = array();
        $header[$columnLetter]['items'][$startLetter]['items'][$startLetter]['label'] = 'Fisico';
        $header[$columnLetter]['items'][$startLetter]['items'][$startLetter]['start'] = 12;

        $numberFormat[$startLetter] = $formats[2];
 
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['label'] = "Presupuestal \n S/.";
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['start'] = 12;

        $numberFormat[$endLetter] = $formats[0];

        $colIndex+=2;
        $startLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
        $endLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex+1);
 
        $header[$columnLetter]['items'][$startLetter] = array();
        $header[$columnLetter]['items'][$startLetter]['label'] = 'Ejecución';
        $header[$columnLetter]['items'][$startLetter]['start'] = 11;
        $header[$columnLetter]['items'][$startLetter]['end'] = $endLetter;


        $header[$columnLetter]['items'][$startLetter]['items'] = array();
        $header[$columnLetter]['items'][$startLetter]['items'][$startLetter]['label'] = 'Fisico';
        $header[$columnLetter]['items'][$startLetter]['items'][$startLetter]['start'] = 12;        

        $numberFormat[$startLetter] = $formats[2];

        $endLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex);
 
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['label'] = "Presupuestal \n S/.";
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['start'] = 12;

        $numberFormat[$endLetter] = $formats[0];

        $colIndex++;        
        $endLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex);

        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['label'] = "Financiera \n S/.";
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['start'] = 12;

        $numberFormat[$endLetter] = $formats[0];

        $colIndex+=2;
        $startLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
        $endLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex+1);
        
 
        $header[$columnLetter]['items'][$startLetter] = array();
        $header[$columnLetter]['items'][$startLetter]['label'] = 'Saldo';
        $header[$columnLetter]['items'][$startLetter]['start'] = 11;
        $header[$columnLetter]['items'][$startLetter]['end'] = $endLetter;
 
        $header[$columnLetter]['items'][$startLetter]['items'] = array();
        $header[$columnLetter]['items'][$startLetter]['items'][$startLetter]['label'] = 'Fisico';
        $header[$columnLetter]['items'][$startLetter]['items'][$startLetter]['start'] = 12;

        $numberFormat[$startLetter] = $formats[2];

        $endLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex);
 
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['label'] = "Presupuestal \n S/.";
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['start'] = 12;

        $numberFormat[$endLetter] = $formats[0];

        $colIndex++;        
        $endLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex);

        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['label'] = "Financiera \n S/.";
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['start'] = 12;

        $numberFormat[$endLetter] = $formats[0];

        $colIndex+=2;
        $startLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
        $endLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex);
 
        $header[$columnLetter]['items'][$startLetter] = array();
        $header[$columnLetter]['items'][$startLetter]['label'] = '% Avance';
        $header[$columnLetter]['items'][$startLetter]['start'] = 11;
        $header[$columnLetter]['items'][$startLetter]['end'] = $endLetter;
 
        $header[$columnLetter]['items'][$startLetter]['items'] = array();
        $header[$columnLetter]['items'][$startLetter]['items'][$startLetter]['label'] = 'Fisico';
        $header[$columnLetter]['items'][$startLetter]['items'][$startLetter]['start'] = 12;

        $numberFormat[$startLetter] = $formats[1];
 
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['label'] = "Presupuestal";
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['start'] = 12;

        $numberFormat[$endLetter] = $formats[1];
        
 
        foreach($header as $column => $data){
 
           
 
            if(isset($data['items'])){
 
                $start = $column.$data['start'];
                $end   = $data['end'].$data['start'];
 
                foreach ($data['items'] as $key => $value) {
                    //print_r($value);die();
                    $this->excel->setActiveSheetIndex(0)                        
                         ->setCellValue($key.$value['start'], $value['label']); 
 
                    $this->excel->setActiveSheetIndex(0)
                        ->getStyle($key.$value['start'].':'.$value['end'].$value['start'])
                        ->applyFromArray($BStyle); 
 
                    $this->excel->setActiveSheetIndex(0)
                        ->mergeCells($key.$value['start'].':'.$value['end'].$value['start']);  
 
                    $this->excel->setActiveSheetIndex(0)
                        ->getStyle($key.$value['start'].':'.$value['end'].$value['start'])                        
                        ->getFont()->setSize(8);                        
                    $this->excel->setActiveSheetIndex(0)
                        ->getStyle($key.$value['start'].':'.$value['end'].$value['start'])                        
                        ->getAlignment()
                        ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                        ->setWrapText(true);
                        if(isset($value['items'])){
                            foreach ($value['items'] as $subkey => $subvalue) {
                                $this->excel->getActiveSheet()
                                    ->getColumnDimension($subkey)
                                    ->setWidth(8);
                                $this->excel->setActiveSheetIndex(0)                        
                                     ->setCellValue($subkey.$subvalue['start'], $subvalue['label']); 
                                $this->excel->setActiveSheetIndex(0)
                                     ->getStyle($subkey.$subvalue['start'])                        
                                     ->getFont()->setSize(8);
                                $this->excel->setActiveSheetIndex(0)
                                    ->getStyle($subkey.$subvalue['start'])
                                    ->getAlignment()
                                    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                    ->setWrapText(true);
                                $this->excel->setActiveSheetIndex(0)
                                    ->getStyle($subkey.$subvalue['start'])
                                    ->applyFromArray($BStyle);
                            }
 
                        }
                }
 
            }else {
 
                $start = $column.$data['start'];
                $end   = $column.$data['end'];
 
            }
            
            
              // Add some data
            $this->excel->setActiveSheetIndex(0)                        
                        ->setCellValue( $start, $data['label']);
 
            $this->excel->getActiveSheet()
                        ->getColumnDimension($column)
                        ->setWidth(8);

            $this->excel->setActiveSheetIndex(0)
                        ->mergeCells($start.':'.$end);
 
            $this->excel->setActiveSheetIndex(0)
                        ->getStyle($start.':'.$end)
                        ->applyFromArray($BStyle);
 
            $this->excel->setActiveSheetIndex(0)
                        ->getStyle($start.':'.$end)                        
                        ->getFont()->setSize(8);
 
            $this->excel->setActiveSheetIndex(0)
                        ->getStyle($start.':'.$end)
                        ->getAlignment()
                        ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                        ->setWrapText(true);
 
            
            
    
        }   
         $count=0;
         $anterior=array();
         $total= array();
          
        $colIndex = PHPExcel_Cell::columnIndexFromString('G');
        
        for($i=0;$i<15;$i++){
            $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
            $total[$columnLetter]=0;            
            $colIndex++;
        }

         if(isset($result)){
            foreach ($result as $key => $row) {
                # code...
                
                $ubigeo[$row->depa_id]['depa_descripcion']=$row->depa_descripcion;
                $ubigeo[$row->depa_id]['provincias'][$row->prov_id]['prov_descripcion']=$row->prov_descripcion;
                $ubigeo[$row->depa_id]['provincias'][$row->prov_id]['distritos'][$row->dist_id]['dist_descripcion']=$row->dist_descripcion;
                $ubigeo[$row->depa_id]['provincias'][$row->prov_id]['distritos'][$row->dist_id]['column']='';
 
                $datos['programa_presupuestal'][$row->ppre_id]['ppre_codref'] = $row->ppre_codref;
                $datos['programa_presupuestal'][$row->ppre_id]['ppre_nombre'] = ucwords(mb_strtolower($row->ppre_nombre, 'UTF-8'));                
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['prod_codref'] = $row->prod_codref;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['prod_nombre'] = ucwords(mb_strtolower($row->prod_nombre, 'UTF-8'));
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['acti_codref'] = $row->acti_codref;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['acti_nombre'] = ucwords(mb_strtolower($row->acti_nombre, 'UTF-8'));
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['sact_nombre'] = ucwords(mb_strtolower($row->sact_nombre, 'UTF-8'));
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['ogru_nombre'] = ucwords(mb_strtolower($row->ogru_nombre, 'UTF-8'));
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['ofic_nombre'] = ucwords(mb_strtolower($row->ofic_nombre, 'UTF-8'));
                //Almacenando datos presupuestales
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['poa_metaprepim'] = 0;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['poa_metaprepim'] = 0;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id]['poa_metaprepim'] = 0;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id]['distritos'][$row->dist_id]['poa_metaprepim'] = 0;
                if($row->poa_sact_id == $row->sact_id)
                    $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id]['distritos'][$row->dist_id]['poa'][$row->poa_id]['poa_metaprepim'] = $row->poa_metaprepim;
                else
                    $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id]['distritos'][$row->dist_id]['poa'][$row->poa_id]['poa_metaprepim'] = 0;

                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['poa_metaprepid'] = 0;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['poa_metaprepid'] = 0;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id]['poa_metaprepid'] = 0;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id]['distritos'][$row->dist_id]['poa_metaprepid'] = 0;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id]['distritos'][$row->dist_id]['poa'][$row->poa_id]['poa_metaprepid'] = $row->poa_metaprepid;
                //Almacenando datos Financieros
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['poa_metaprepig'] = 0;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['poa_metaprepig'] = 0;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id]['poa_metaprepig'] = 0;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id]['distritos'][$row->dist_id]['poa_metaprepig'] = 0;

                if($row->poa_sact_id == $row->sact_id)
                    $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id]['distritos'][$row->dist_id]['poa'][$row->poa_id]['poa_metaprepig'] = $row->poa_metaprepig;
                else
                    $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id]['distritos'][$row->dist_id]['poa'][$row->poa_id]['poa_metaprepig'] = 0;
                
                //Almacenando datos físicos
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['unidad_medida'][$row->umed_id]['umed_nombre'] = ucwords(mb_strtolower($row->umed_nombre, 'UTF-8'));                
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['unidad_medida'][$row->umed_id][$row->pmfi_tipo]['pmfi_meta'] = 0;                
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['unidad_medida'][$row->umed_id]['departamento'][$row->depa_id][$row->pmfi_tipo]['pmfi_meta'] = 0;                
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['unidad_medida'][$row->umed_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id][$row->pmfi_tipo]['pmfi_meta'] = 0;                
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['unidad_medida'][$row->umed_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id]['distritos'][$row->dist_id][$row->pmfi_tipo]['pmfi_meta'] = 0;                
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['unidad_medida'][$row->umed_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id]['distritos'][$row->dist_id]['poa'][$row->poa_id]['meta_fisica'][$row->pmfi_tipo][$row->pmfi_orden]['pmfi_meta'] = $row->pmfi_meta;
                
                
            }
            
 
            
            
            //Calculando totales de cantidades físicas
 
            foreach($datos['programa_presupuestal']  as $ppre_id => $programa){
                
                foreach($programa['producto'] as $prod_id => $producto){
 
                    foreach($producto['actividad'] as $acti_id => $actividad){
 
                        foreach($actividad['subactividad'] as $sact_id => $subactividad){
 
                            foreach($subactividad['unidad_medida'] as $umed_id => $unidad){
                                if(!isset($datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['unidad_medida'][$umed_id]['M']['pmfi_meta']))
                                    $datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['unidad_medida'][$umed_id]['M']['pmfi_meta']=0;
                                if(!isset($datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['unidad_medida'][$umed_id]['E']['pmfi_meta']))
                                    $datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['unidad_medida'][$umed_id]['E']['pmfi_meta']=0;
 
                                foreach($unidad['departamento'] as $depa_id => $departamento) {
                                    if(!isset($departamento['M']['pmfi_meta']))
                                        $departamento['M']['pmfi_meta']=0;
                                    if(!isset($departamento['E']['pmfi_meta']))
                                        $departamento['E']['pmfi_meta']=0;
                                    
                                    foreach($departamento['provincias'] as $prov_id => $provincia){
                                        
                                        if(!isset($provincia['M']['pmfi_meta']))
                                            $provincia['M']['pmfi_meta']=0;
                                        if(!isset($provincia['E']['pmfi_meta']))
                                            $provincia['E']['pmfi_meta']=0;
 
                                        foreach ($provincia['distritos'] as $dist_id => $distrito) {
                                    
                                            if(!isset($distrito['M']['pmfi_meta']))
                                                $distrito['M']['pmfi_meta']=0;
                                            if(!isset($distrito['E']['pmfi_meta']))
                                                $distrito['E']['pmfi_meta']=0;
 
                                            foreach ($distrito['poa'] as $poa_id => $poa) {
                                    
                                                 
                                                if(isset($poa['meta_fisica']['M'])){
                                                    foreach ($poa['meta_fisica']['M'] as $pmfi_orden => $meta) {
                                                        
                                                        $distrito['M']['pmfi_meta']=$distrito['M']['pmfi_meta']+$meta['pmfi_meta'];
                                                    }
                                                    
 
                                                }
                                                if(isset($poa['meta_fisica']['E'])){
                                                    foreach ($poa['meta_fisica']['E'] as $pmfi_orden => $meta) {
 
                                                        $distrito['E']['pmfi_meta']=$distrito['E']['pmfi_meta']+$meta['pmfi_meta'];
                                                    }
                                                    
                                                }
                                                
                                            }
                                            //echo "<pre>";print_r($distrito['M']['pmfi_meta']);
                                            $provincia['M']['pmfi_meta']=$provincia['M']['pmfi_meta']+$distrito['M']['pmfi_meta'];
                                            $provincia['E']['pmfi_meta']=$provincia['E']['pmfi_meta']+$distrito['E']['pmfi_meta'];
                                            
                                        }
                                        //echo "<pre>";print_r($provincia['M']['pmfi_meta']);
                                        $departamento['M']['pmfi_meta']=$departamento['M']['pmfi_meta']+$provincia['M']['pmfi_meta'];
                                        $departamento['E']['pmfi_meta']=$departamento['E']['pmfi_meta']+$provincia['E']['pmfi_meta'];
                                        
                                    }
                                    $datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['unidad_medida'][$umed_id]['M']['pmfi_meta']+=$departamento['M']['pmfi_meta'];
                                    $datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['unidad_medida'][$umed_id]['E']['pmfi_meta']+=$departamento['E']['pmfi_meta'];
                                        
                                }
                                
                            }
 
                            
 
                        }
                        
 
                    }                    
 
                }            
 
            }
 
            //Calculando totales de cantidades presupuestales y financieras
            foreach($datos['programa_presupuestal']  as $ppre_id => $programa){
                
                foreach($programa['producto'] as $prod_id => $producto){
 
                    foreach($producto['actividad'] as $acti_id => $actividad){
 
                        foreach($actividad['subactividad'] as $sact_id => $subactividad){
                                if(!isset($datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['M']['ppre_importe']))
                                    $datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['M']['ppre_importe']=0;
                                if(!isset($datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['E']['ppre_importe']))
                                    $datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['E']['ppre_importe']=0;
                            
                                foreach($subactividad['departamento'] as $depa_id => $departamento) {
                                    
                                    
                                    foreach($departamento['provincias'] as $prov_id => $provincia){                                                                               
 
                                        foreach ($provincia['distritos'] as $dist_id => $distrito) {
                                                                               
 
                                            foreach ($distrito['poa'] as $poa_id => $poa) {                                                                                                                                    
                                                $distrito['poa_metaprepim']+=$poa['poa_metaprepim'];                                                       
                                                $distrito['poa_metaprepid']+=$poa['poa_metaprepid'];                                               
                                                $distrito['poa_metaprepig']+=$poa['poa_metaprepig'];
                                            }
                                            
                                            $provincia['poa_metaprepim']+=$distrito['poa_metaprepim'];
                                            $provincia['poa_metaprepid']+=$distrito['poa_metaprepid'];
                                            $provincia['poa_metaprepig']+=$distrito['poa_metaprepig'];
                                            
                                        }
                                        $departamento['poa_metaprepim']+=$provincia['poa_metaprepim'];
                                        $departamento['poa_metaprepid']+=$provincia['poa_metaprepid'];
                                        $departamento['poa_metaprepig']+=$provincia['poa_metaprepig'];
                                        
                                    }
                                    $datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['poa_metaprepim']+=$departamento['poa_metaprepim'];
                                    $datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['poa_metaprepid']+=$departamento['poa_metaprepid'];
                                    $datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['poa_metaprepig']+=$departamento['poa_metaprepig'];
                                        
                                }
                                
                            
 
                            
 
                        }
                        
 
                    }                    
 
                }            
 
            }
           //echo "<pre>";
           //print_r($datos); 
           //die("test");
            
            foreach($datos['programa_presupuestal']  as $ppre_id => $programa){
                
                foreach($programa['producto'] as $prod_id => $producto){
 
                    foreach($producto['actividad'] as $acti_id => $actividad){
 
                        foreach($actividad['subactividad'] as $sact_id => $subactividad){
 
                            foreach($subactividad['unidad_medida'] as $umed_id => $unidad){
                               
                                $colIndex = PHPExcel_Cell::columnIndexFromString('A');
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);                                
                                $rows[$count][$columnLetter]=$programa['ppre_codref'];//A
                                if($extension==EXT_EXCEL){
                                    $rows[$count][$columnLetter].=': '.$programa['ppre_nombre'];
                                }
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                $rows[$count][$columnLetter]=$producto['prod_codref'];//B
                                if($extension==EXT_EXCEL){
                                    $rows[$count][$columnLetter].=': '.$producto['prod_nombre'];
                                }
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                $rows[$count][$columnLetter]=$actividad['acti_codref'];//C
                                if($extension==EXT_EXCEL){
                                    $rows[$count][$columnLetter].=': '.$actividad['acti_nombre'];
                                }
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                $rows[$count][$columnLetter]=$subactividad['sact_nombre'];//D
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                $rows[$count][$columnLetter]=$unidad['umed_nombre'];//E
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                $rows[$count][$columnLetter]=$subactividad['ogru_nombre'];//D
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                $rows[$count][$columnLetter]=$subactividad['ofic_nombre'];//D
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);                                
                                $rows[$count][$columnLetter]=$unidad['M']['pmfi_meta'];//F
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                $rows[$count][$columnLetter]=$subactividad['poa_metaprepim'];//G
                                $total[$columnLetter]+=$rows[$count][$columnLetter];
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                $rows[$count][$columnLetter]=$unidad['E']['pmfi_meta'];//H
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                $rows[$count][$columnLetter]=$subactividad['poa_metaprepid'];//I
                                $total[$columnLetter]+=$rows[$count][$columnLetter];
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                $rows[$count][$columnLetter]=$subactividad['poa_metaprepig'];//J
                                $total[$columnLetter]+=$rows[$count][$columnLetter];
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                $rows[$count][$columnLetter]=($unidad['M']['pmfi_meta'] - $unidad['E']['pmfi_meta'] );//K
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                $rows[$count][$columnLetter]=0;//L
                                $total[$columnLetter]+=$rows[$count][$columnLetter];
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                $rows[$count][$columnLetter]=($subactividad['poa_metaprepim'] - $subactividad['poa_metaprepig']);//M
                                $total[$columnLetter]+=$rows[$count][$columnLetter];
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                if($unidad['M']['pmfi_meta']>0)
                                    $rows[$count][$columnLetter]=($unidad['E']['pmfi_meta']/$unidad['M']['pmfi_meta'])*100;//N
                                else
                                    $rows[$count][$columnLetter]=0;//N
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                if($subactividad['poa_metaprepim']>0)
                                    $rows[$count][$columnLetter]=($subactividad['poa_metaprepig']/$subactividad['poa_metaprepim'])*100;//O
                                else
                                    $rows[$count][$columnLetter]=0;//O
 
                                $total[$columnLetter]+=$rows[$count][$columnLetter];
                                
                                $count++;
                            }
 
                            $count++;
 
                        }
 
                        $count++;
 
                    }
 
                    $count++;
 
                }
 
                $count++;
 
            }
        }
       
       
 
        $start = 13;
        if(isset($rows)){
            foreach($rows as $row){
                $col=0;
                foreach($row as $column => $value){
                    
                    $this->excel->setActiveSheetIndex(0)                        
                                ->setCellValue($column.$start, $value);
                    
                    
                    
                    $this->excel->setActiveSheetIndex(0)
                                ->getStyle($column.$start)                        
                                ->getFont()->setSize(8);
 
 
                    if($col==0){
                        $this->excel->getActiveSheet(0)
                                ->getStyle($column.$start)
                                ->getNumberFormat()
                                ->setFormatCode('0000');
                    }
 
                    if( is_numeric($value) && $col>4 ){ 
                        $this->excel->setActiveSheetIndex(0)
                                ->getStyle($column.$start)
                                ->applyFromArray($BStyle);
 
                        $this->excel->getActiveSheet(0)
                                ->getStyle($column.$start)
                                ->getNumberFormat()
                                ->setFormatCode($numberFormat[$column]);
 
                        $this->excel->setActiveSheetIndex(0)
                                    ->getStyle($column.$start)
                                    ->getAlignment()
                                    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
                                    ->setWrapText(true);
                        $this->excel->getActiveSheet(0)
                                    ->getStyle($column.$start)
                                    ->getAlignment()
                                    ->setIndent(1);
                    }else { 
 
                        if($value==''){
                            $this->excel->setActiveSheetIndex(0)
                                        ->getStyle($column.$start)
                                        ->applyFromArray($CStyle);
                        }else{
                            
                            $this->excel->setActiveSheetIndex(0)
                                        ->getStyle($column.$start)
                                        ->applyFromArray($DStyle);
                        }
 
                        
                        $this->excel->setActiveSheetIndex(0)
                                    ->getStyle($column.$start)
                                    ->getAlignment()
                                    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                    ->setWrapText(true);
                        $this->excel->getActiveSheet(0)
                                    ->getStyle($column.$start)
                                    ->getAlignment()
                                    ->setIndent(1);
                    }
 
                    //echo " $column.$start $value" ;
                    $col++;
                }
 
                $start++;
                /*
                if($start>35)
                    break;
                */
                
            }
        }
 
        $this->excel->setActiveSheetIndex(0)
                    ->getStyle("A$start:G$start")
                    ->applyFromArray($EStyle);
        
        $this->excel->setActiveSheetIndex(0)
                    ->getStyle("R10:R12")
                    ->applyFromArray($FStyle);
                                     
 
 
        $this->excel->setActiveSheetIndex(0)                        
                    ->setCellValue('A'.($start+1), 'Totales');
 
        $this->excel->setActiveSheetIndex(0)
                    ->getStyle('A'.($start+1).':'.'G'.($start+1))
                    ->getAlignment()
                    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                    ->setWrapText(true);

        $this->excel->setActiveSheetIndex(0)
                        ->getStyle('A'.($start+1).':'.'G'.($start+1))
                        ->applyFromArray($BStyle); 

        $this->excel->setActiveSheetIndex(0)
             ->getStyle('A'.($start+1).':'.'G'.($start+1))                        
             ->getFont()->setSize(8);  
 
        $this->excel->setActiveSheetIndex(0)
             ->mergeCells('A'.($start+1).':'.'G'.($start+1));  
 

        $this->excel->setActiveSheetIndex(0)
                        ->getStyle('H'.($start+1))
                        ->applyFromArray($FStyle); 
        
        $colIndex = PHPExcel_Cell::columnIndexFromString('I');
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);    
 
        $this->excel->setActiveSheetIndex(0)                        
                    ->setCellValue($columnLetter.($start+1), $total[$columnLetter]);

        $this->excel->setActiveSheetIndex(0)
                        ->getStyle($columnLetter.($start+1))
                        ->applyFromArray($BStyle); 

        $colIndex+=2;
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);    
     
        $this->excel->setActiveSheetIndex(0)                        
                    ->setCellValue($columnLetter.($start+1), $total[$columnLetter]);


        $this->excel->setActiveSheetIndex(0)
                        ->getStyle($columnLetter.($start+1))
                        ->applyFromArray($BStyle); 

        $colIndex++;
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
      

        $this->excel->setActiveSheetIndex(0)                        
                    ->setCellValue($columnLetter.($start+1), $total[$columnLetter]);


        $this->excel->setActiveSheetIndex(0)
                        ->getStyle($columnLetter.($start+1))
                        ->applyFromArray($BStyle); 

        $colIndex+=2;
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);    

 
        $this->excel->setActiveSheetIndex(0)                        
                    ->setCellValue($columnLetter.($start+1), $total[$columnLetter]);


        $this->excel->setActiveSheetIndex(0)
                        ->getStyle($columnLetter.($start+1))
                        ->applyFromArray($BStyle); 

        $colIndex++;
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 


        $this->excel->setActiveSheetIndex(0)                        
                    ->setCellValue($columnLetter.($start+1), $total[$columnLetter]);


        $this->excel->setActiveSheetIndex(0)
                        ->getStyle($columnLetter.($start+1))
                        ->applyFromArray($BStyle); 

       $colIndex+=2;
       $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);


        if($total['I']>0)
            $total[$columnLetter]=($total['K']/$total['I'])*100;//M
        else
            $total[$columnLetter]=0;//M                   
 
        $this->excel->setActiveSheetIndex(0)                        
                    ->setCellValue($columnLetter.($start+1), $total[$columnLetter]);


        $this->excel->setActiveSheetIndex(0)
                        ->getStyle($columnLetter.($start+1))
                        ->applyFromArray($BStyle); 

        $this->excel->setActiveSheetIndex(0)
             ->getStyle('H'.($start+1))                        
             ->getFont()->setSize(8);

 
         $this->excel->getActiveSheet(0)
                     ->getStyle('H'.($start+1).':'.$columnLetter.($start+1))
                     ->getNumberFormat()
                     ->setFormatCode('#.00');
        
        $this->excel->setActiveSheetIndex(0)
                    ->getStyle('H'.($start+1).':'.$columnLetter.($start+1))
                    ->getAlignment()
                    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
                    ->setWrapText(true);
        $this->excel->getActiveSheet(0)
                    ->getStyle('H'.($start+1).':'.$columnLetter.($start+1))
                    ->getAlignment()
                    ->setIndent(1);
         $this->excel->setActiveSheetIndex(0)
                     ->getStyle('F'.($start+1).':'.$columnLetter.($start+1))                        
                     ->getFont()->setSize(8);
 
 
        /*                    
        $this->excel->setActiveSheetIndex(0)
                    ->mergeCells('A14:A24');
        */
        // Rename worksheet
        //die('test');
 
 
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $this->excel->setActiveSheetIndex(0);
        
        if($extension==EXT_EXCEL){
            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Reporte_1.xlsx"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');
 
            // If you're serving to IE over SSL, then the following may be needed
            header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
            header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header ('Pragma: public'); // HTTP/1.0
 
            
 
            $objWriter = $this->factory->createWriter($this->excel, 'Excel2007');
            //print_r($objWriter);
            $objWriter->save('php://output');
        }else{
        
            /*    
            $rendererName = PHPExcel_Settings::PDF_RENDERER_TCPDF;
            $rendererLibrary = 'tcpdf';
            $rendererLibraryPath = APPPATH.'third_party/'. $rendererLibrary;
            $filename='_Reporte_Usuarios_front'.'.pdf'; 
            PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath);
            */
            $rendererName = PHPExcel_Settings::PDF_RENDERER_DOMPDF;
            $rendererLibrary = 'dompdf';
            $rendererLibraryPath = APPPATH.'third_party/'. $rendererLibrary.'/src';
            $filename='Reporte_1'.'.pdf'; 
            PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath);
            /*
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
            header('Cache-Control: max-age=0'); //no cache
            */
            /*
            ini_set('max_execution_time', 3000);
            ini_set('memory_limit','16M');
            */
            // $objWriter = new PHPExcel_Writer_PDF($objPHPExcel);
            //print_r($this->excel);die();
            $objWriter = $this->factory->createWriter($this->excel, 'PDF'); 
            $objWriter->setSheetIndex(0);
            //print_r($objWriter);die();
            $objWriter->save('php://output');
        }
        exit;
       
    }

 
    public function formato2() {

        $formats = array("#,###", "#,###.0", "#,###.00");
        
        $datapost = $this->security->xss_clean($this->input->post());
        $extension = '';
        if(isset($datapost['extension'])){
            $extension = $datapost['extension'];
        }
        
        $ogru_nombre = ''; 
        $ofic_nombre = '';        
        $peri_id  = $datapost['peri_id'];
        $data_peri = $this->periodo_model->obtener_periodo_por_id($peri_id);
        if(isset($datapost['fecha_reporte']) && $datapost['fecha_reporte']!=''){
            $fecha_reporte = str_replace('/', '-', $datapost['fecha_reporte']);
            $fecha_reporte = date('Y-m-d', strtotime($fecha_reporte));
        }else{
            $fecha_reporte = date('Y-m-d', time());
        }


 
        $ogru_id_array  = array();
        $ofic_id_array  = array();
        $poli_id_array  = array();
        $ppre_id_array  = array();
        $prod_id_array  = array();
        $acti_id_array  = array();
        $sact_id_array  = array();
        $estr_id_array  = array();
 
        if(isset($datapost['ogru_id_array'])){
            $ogru_id_array  = $datapost['ogru_id_array'];
            if(count($ogru_id_array)==1){                
                $data_ogru = $this->oficinagrupo_model->getOficinagrupoByID($ogru_id_array[0]);                
                $ogru_nombre = $data_ogru->ogru_nombre;
            }
        }else{
            $ogru_nombre = "Todas";
        }
 
        if(isset($datapost['ofic_id_array'])){
            $ofic_id_array  = $datapost['ofic_id_array'];
            if(count($ofic_id_array)==1){
                $data_ofic = $this->oficina_model->getOficinaByID($ofic_id_array[0]);
                $ofic_nombre = $data_ofic->ofic_nombre;
            }
        }else{
            $ofic_nombre = "Todas";
        }
        
        if(isset($datapost['poli_id_array'])){
            $poli_id_array  = $datapost['poli_id_array'];
        }
 
        if(isset($datapost['ppre_id_array'])){
            $ppre_id_array  = $datapost['ppre_id_array'];
        }
 
        if(isset($datapost['prod_id_array'])){
            $prod_id_array  = $datapost['prod_id_array'];
        }
 
        if(isset($datapost['acti_id_array'])){
            $acti_id_array  = $datapost['acti_id_array'];
        }
 
        if(isset($datapost['sact_id_array'])){
            $sact_id_array  = $datapost['sact_id_array'];
        }                            

        if(isset($datapost['estr_id_array'])){
            $estr_id_array  = $datapost['estr_id_array'];
        }   
 
        
       
        
        $result =  $this->reporte_model->getReporteProyectos(  $ogru_id_array, $ofic_id_array, $peri_id, $fecha_reporte, 
                                            $poli_id_array, $ppre_id_array, $prod_id_array, 
                                            $acti_id_array , $sact_id_array, $estr_id_array );
        
        
        
        
       
       /** Error reporting */
        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        //date_default_timezone_set('Europe/London');
 
        // Set document properties
        $this->excel->getProperties()->setCreator("iorganizacional.com.pe")
                                     ->setLastModifiedBy("iorganizacional.com.pe")
                                     ->setTitle("Reporte 2")
                                     ->setSubject("Formato 2")
                                     ->setDescription("Programacion y Ejecucion de Metas.")
                                     ->setKeywords("programacion metas")
                                     ->setCategory("organizacional");
        
        $objDrawing = new PHPExcel_Worksheet_Drawing();
        $objDrawing->setName("name");
        $objDrawing->setDescription("Description");
        $objDrawing->setPath('public/assets/img/logo/agrorural-logo.png');
        $objDrawing->setCoordinates('P1');
        $objDrawing->setWorksheet($this->excel->setActiveSheetIndex(0));

        $this->excel->setActiveSheetIndex(0)
                        ->mergeCells('P1:Q1');
 
        // Add some data
        $fecha_reporte = date('d/m/Y', strtotime($fecha_reporte));
 
        $this->excel->setActiveSheetIndex(0)
                    ->setCellValue('D2', "Formato 2: Programación y Ejecución de Metas Físico Presupuestales \n Por Ubicación Geográfica");
        $this->excel->setActiveSheetIndex(0)
                        ->mergeCells('D2:L3');
        $this->excel->setActiveSheetIndex(0)
                                    ->getStyle('D3:L3')
                                    ->getAlignment()
                                    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Reporte N° 2')                                                 
                    ->setCellValue('P6', 'A la Fecha');
        $this->excel->setActiveSheetIndex(0)
                    ->setCellValue('G4', 'Periodo '.$data_peri->peri_nombre)
                    ->setCellValue('Q6', $fecha_reporte);

        $this->excel->setActiveSheetIndex(0)
                        ->mergeCells('G4:H4');
 
        $this->excel->setActiveSheetIndex(0)
                    ->getStyle('C4:C6')
                    ->getAlignment()
                    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    
 
        $this->excel->getActiveSheet()
                    ->getPageSetup()
                    ->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $this->excel->getActiveSheet()
                    ->getPageSetup()
                    ->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
       
        
        $BStyle = array(
          'borders' => array(
            'outline' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
            )
          )
        );
 
        $CStyle = array(
          'borders' => array(
            'left' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
            ),
            'right' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
            )
          )
        );
 
        $DStyle = array(
          'borders' => array(
            'top' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
            ),
            'left' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
            ),
            'right' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
            )
          )
        );
 
        $EStyle = array(
          'borders' => array(
            'top' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
            )
          )
        );
 
        $FStyle = array(
          'borders' => array(
            'left' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
            )
          )
        );
 
        $header = array();

        $colIndex = PHPExcel_Cell::columnIndexFromString('A');
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
 
 
        $header[$columnLetter] = array();
        $header[$columnLetter]['label'] = 'Programa Presupuestal';
        $header[$columnLetter]['start'] = 8;
        $header[$columnLetter]['end'] = 12;

        $colIndex++;
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
 
        $header[$columnLetter] = array();
        $header[$columnLetter]['label'] = 'Producto';
        $header[$columnLetter]['start'] = 8;
        $header[$columnLetter]['end'] = 12;

        $colIndex++;
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
        
 
        $header[$columnLetter] = array();
        $header[$columnLetter]['label'] = 'Actividad';
        $header[$columnLetter]['start'] = 8;
        $header[$columnLetter]['end'] = 12;

        $colIndex++;
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
        
 
        $header[$columnLetter] = array();
        $header[$columnLetter]['label'] = 'Subactividad';
        $header[$columnLetter]['start'] = 8;
        $header[$columnLetter]['end'] = 12;

        $colIndex++;
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
        
 
        $header[$columnLetter] = array();
        $header[$columnLetter]['label'] = 'Unidad Medida';
        $header[$columnLetter]['start'] = 8;
        $header[$columnLetter]['end'] = 12;

       
        $colIndex++;
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
        $endLetter    = PHPExcel_Cell::stringFromColumnIndex($colIndex+17);
        $startLetter  = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
        
 
        $header[$columnLetter] = array();
        $header[$columnLetter]['label'] = 'Total Departamento';
        $header[$columnLetter]['items'] = array();
        $header[$columnLetter]['start'] = 8;
        $header[$columnLetter]['end'] = 10;
        $header[$columnLetter]['col'] = $endLetter;

        $endLetter    = PHPExcel_Cell::stringFromColumnIndex($colIndex);

        $numberFormat =array();

        $header[$columnLetter]['items'][$startLetter] = array();
        $header[$columnLetter]['items'][$startLetter]['label'] = 'Programación PIA';
        $header[$columnLetter]['items'][$startLetter]['start'] = 11;
        $header[$columnLetter]['items'][$startLetter]['end'] = $endLetter;
 
        $header[$columnLetter]['items'][$startLetter]['items'] = array();
        $header[$columnLetter]['items'][$startLetter]['items'][$startLetter]['label'] = 'Fisico';
        $header[$columnLetter]['items'][$startLetter]['items'][$startLetter]['start'] = 12;

        $numberFormat[$startLetter] = $formats[2];
 
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['label'] = 'Presupuestal S/.';
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['start'] = 12;

        $numberFormat[$endLetter]=$formats[0];

        $colIndex+=2;        
        $startLetter  = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
        $endLetter    = PHPExcel_Cell::stringFromColumnIndex($colIndex);
 
        $header[$columnLetter]['items'][$startLetter] = array();
        $header[$columnLetter]['items'][$startLetter]['label'] = 'Programación PIM';
        $header[$columnLetter]['items'][$startLetter]['start'] = 11;
        $header[$columnLetter]['items'][$startLetter]['end'] = $endLetter;
 
        $header[$columnLetter]['items'][$startLetter]['items'] = array();
        $header[$columnLetter]['items'][$startLetter]['items'][$startLetter]['label'] = 'Fisico';
        $header[$columnLetter]['items'][$startLetter]['items'][$startLetter]['start'] = 12;

        $numberFormat[$startLetter]=$formats[2];
 
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['label'] = 'Presupuestal S/.';
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['start'] = 12;

        $numberFormat[$endLetter]=$formats[0];
        
        $colIndex+=2;
        $startLetter  = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
        $endLetter    = PHPExcel_Cell::stringFromColumnIndex($colIndex+1);
 
        $header[$columnLetter]['items'][$startLetter] = array();
        $header[$columnLetter]['items'][$startLetter]['label'] = 'Ejecución';
        $header[$columnLetter]['items'][$startLetter]['start'] = 11;
        $header[$columnLetter]['items'][$startLetter]['end'] = $endLetter;
 
        $header[$columnLetter]['items'][$startLetter]['items'] = array();
        $header[$columnLetter]['items'][$startLetter]['items'][$startLetter]['label'] = 'Fisico';
        $header[$columnLetter]['items'][$startLetter]['items'][$startLetter]['start'] = 12;
        $numberFormat[$startLetter]=$formats[2];

        $endLetter    = PHPExcel_Cell::stringFromColumnIndex($colIndex);
 
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['label'] = 'Presupuestal S/.';
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['start'] = 12;
        $numberFormat[$endLetter]=$formats[0];

        $colIndex++;        
        $endLetter    = PHPExcel_Cell::stringFromColumnIndex($colIndex);
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['label'] = 'Financiera S/.';
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['start'] = 12;
        $numberFormat[$endLetter]=$formats[0];

        
        $colIndex+=2;
        $startLetter  = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
        $endLetter    = PHPExcel_Cell::stringFromColumnIndex($colIndex+4);


 
        $header[$columnLetter]['items'][$startLetter] = array();
        $header[$columnLetter]['items'][$startLetter]['label'] = 'Saldo';
        $header[$columnLetter]['items'][$startLetter]['start'] = 11;
        $header[$columnLetter]['items'][$startLetter]['end'] = $endLetter;
 
        $header[$columnLetter]['items'][$startLetter]['items'] = array();
        $header[$columnLetter]['items'][$startLetter]['items'][$startLetter]['label'] = 'Fisico PIA';
        $header[$columnLetter]['items'][$startLetter]['items'][$startLetter]['start'] = 12;
        $numberFormat[$startLetter]=$formats[2];

        $endLetter    = PHPExcel_Cell::stringFromColumnIndex($colIndex);
 
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['label'] = 'Fisico PIM';
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['start'] = 12;

        $numberFormat[$endLetter]=$formats[0];

        $colIndex++;        
        $endLetter    = PHPExcel_Cell::stringFromColumnIndex($colIndex);
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['label'] = 'Presupuestal PIA';
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['start'] = 12;
        $numberFormat[$endLetter]=$formats[0];

        $colIndex++;        
        $endLetter    = PHPExcel_Cell::stringFromColumnIndex($colIndex);
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['label'] = 'Presupuestal PIM';
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['start'] = 12;
        $numberFormat[$endLetter]=$formats[0];

        $colIndex++;        
        $endLetter    = PHPExcel_Cell::stringFromColumnIndex($colIndex);
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['label'] = 'Financiera PIA S/.';
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['start'] = 12;
        $numberFormat[$endLetter]=$formats[0];

        $colIndex++;        
        $endLetter    = PHPExcel_Cell::stringFromColumnIndex($colIndex);
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['label'] = 'Financiera PIM S/.';
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['start'] = 12;
        $numberFormat[$endLetter]=$formats[0];


        $colIndex+=2;
        $startLetter  = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
        $endLetter    = PHPExcel_Cell::stringFromColumnIndex($colIndex+4);


 
        $header[$columnLetter]['items'][$startLetter] = array();
        $header[$columnLetter]['items'][$startLetter]['label'] = '% Avance';
        $header[$columnLetter]['items'][$startLetter]['start'] = 11;
        $header[$columnLetter]['items'][$startLetter]['end'] = $endLetter;
 
        $header[$columnLetter]['items'][$startLetter]['items'] = array();
        $header[$columnLetter]['items'][$startLetter]['items'][$startLetter]['label'] = 'Fisico PIA';
        $header[$columnLetter]['items'][$startLetter]['items'][$startLetter]['start'] = 12;
        $numberFormat[$startLetter]=$formats[1];

        $endLetter    = PHPExcel_Cell::stringFromColumnIndex($colIndex);
 
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['label'] = 'Fisico PIM';
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['start'] = 12;

        $numberFormat[$endLetter]=$formats[1];

        $colIndex++;        
        $endLetter    = PHPExcel_Cell::stringFromColumnIndex($colIndex);
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['label'] = 'Presupuestal PIA';
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['start'] = 12;
        $numberFormat[$endLetter]=$formats[0];

        $colIndex++;        
        $endLetter    = PHPExcel_Cell::stringFromColumnIndex($colIndex);
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['label'] = 'Presupuestal PIM';
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['start'] = 12;
        $numberFormat[$endLetter]=$formats[0];

        $colIndex++;        
        $endLetter    = PHPExcel_Cell::stringFromColumnIndex($colIndex);
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['label'] = 'Financiera PIA S/.';
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['start'] = 12;
        $numberFormat[$endLetter]=$formats[0];

        $colIndex++;        
        $endLetter    = PHPExcel_Cell::stringFromColumnIndex($colIndex);
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['label'] = 'Financiera PIM S/.';
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['start'] = 12;
        $numberFormat[$endLetter]=$formats[0];


        //echo "<pre>";print_r($header);die();
        
 
        foreach($header as $column => $data){
 
           
 
            if(isset($data['items'])){
 
                $start = $column.$data['start'];
                $end   = $data['col'].$data['end'];
 
                foreach ($data['items'] as $key => $value) {
                    //print_r($value);die();
                    $this->excel->setActiveSheetIndex(0)                        
                         ->setCellValue($key.$value['start'], $value['label']); 
 
                    $this->excel->setActiveSheetIndex(0)
                        ->getStyle($key.$value['start'].':'.$value['end'].$value['start'])
                        ->applyFromArray($BStyle); 
 
                    $this->excel->setActiveSheetIndex(0)
                        ->mergeCells($key.$value['start'].':'.$value['end'].$value['start']);  
 
                    $this->excel->setActiveSheetIndex(0)
                        ->getStyle($key.$value['start'].':'.$value['end'].$value['start'])                        
                        ->getFont()->setSize(8);                        
                    $this->excel->setActiveSheetIndex(0)
                        ->getStyle($key.$value['start'].':'.$value['end'].$value['start'])                        
                        ->getAlignment()
                        ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                        ->setWrapText(true);
                        if(isset($value['items'])){
                            foreach ($value['items'] as $subkey => $subvalue) {
                                $this->excel->getActiveSheet()
                                    ->getColumnDimension($subkey)
                                    ->setWidth(10);
                                $this->excel->setActiveSheetIndex(0)                        
                                     ->setCellValue($subkey.$subvalue['start'], $subvalue['label']); 
                                $this->excel->setActiveSheetIndex(0)
                                     ->getStyle($subkey.$subvalue['start'])                        
                                     ->getFont()->setSize(8);
                                $this->excel->setActiveSheetIndex(0)
                                    ->getStyle($subkey.$subvalue['start'])
                                    ->getAlignment()
                                    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                    ->setWrapText(true);
                                $this->excel->setActiveSheetIndex(0)
                                    ->getStyle($subkey.$subvalue['start'])
                                    ->applyFromArray($BStyle);
                            }
 
                        }
                }
 
            }else {
 
                $start = $column.$data['start'];                
                $end   = $column.$data['end'];
            }
            
            
              // Add some data
            $this->excel->setActiveSheetIndex(0)                        
                        ->setCellValue( $start, $data['label']);
 
            $this->excel->getActiveSheet()
                        ->getColumnDimension($column)
                        ->setWidth(10);
            
            $this->excel->setActiveSheetIndex(0)
                        ->getStyle($start.':'.$end)
                        ->applyFromArray($BStyle);
 
            $this->excel->setActiveSheetIndex(0)
                        ->getStyle($start.':'.$end)                        
                        ->getFont()->setSize(8);
 
            $this->excel->setActiveSheetIndex(0)
                        ->getStyle($start.':'.$end)
                        ->getAlignment()
                        ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                        ->setWrapText(true);
 
            $this->excel->setActiveSheetIndex(0)
                        ->mergeCells($start.':'.$end);
            
    
        }   
         $count=0;
         $anterior=array();
         $rows=array();
         $ubigeo=array();
         $datos=array();
         if(isset($result)){
 
             foreach ($result as $key => $row) {
                # code...
                
                $ubigeo[$row->depa_id]['depa_descripcion']=$row->depa_descripcion;
                $ubigeo[$row->depa_id]['provincias'][$row->prov_id]['prov_descripcion']=$row->prov_descripcion;
                $ubigeo[$row->depa_id]['provincias'][$row->prov_id]['distritos'][$row->dist_id]['dist_descripcion']=$row->dist_descripcion;
                
                $datos['programa_presupuestal'][$row->ppre_id]['ppre_codref'] = $row->ppre_codref;
                $datos['programa_presupuestal'][$row->ppre_id]['ppre_nombre'] = ucwords(mb_strtolower($row->ppre_nombre, 'UTF-8'));                
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['prod_codref'] = $row->prod_codref;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['prod_nombre'] = ucwords(mb_strtolower($row->prod_nombre, 'UTF-8'));
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['acti_codref'] = $row->acti_codref;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['acti_nombre'] = ucwords(mb_strtolower($row->acti_nombre, 'UTF-8'));
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['sact_nombre'] = ucwords(mb_strtolower($row->sact_nombre, 'UTF-8'));
                //Almacenando datos presupuestales
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['poa_metafispia'] = 0;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['poa_metafispia'] = 0;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id]['poa_metafispia'] = 0;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id]['distritos'][$row->dist_id]['poa_metafispia'] = 0;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id]['distritos'][$row->dist_id]['poa'][$row->poa_id]['poa_metafispia'] = $row->poa_metafispia;

                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['poa_metaprepia'] = 0;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['poa_metaprepia'] = 0;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id]['poa_metaprepia'] = 0;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id]['distritos'][$row->dist_id]['poa_metaprepia'] = 0;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id]['distritos'][$row->dist_id]['poa'][$row->poa_id]['poa_metaprepia'] = $row->poa_metaprepia;

                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['poa_metaprepim'] = 0;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['poa_metaprepim'] = 0;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id]['poa_metaprepim'] = 0;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id]['distritos'][$row->dist_id]['poa_metaprepim'] = 0;
                if($row->poa_sact_id == $row->sact_id)
                    $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id]['distritos'][$row->dist_id]['poa'][$row->poa_id]['poa_metaprepim'] = $row->poa_metaprepim;
                else
                    $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id]['distritos'][$row->dist_id]['poa'][$row->poa_id]['poa_metaprepim'] = 0;    

                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['poa_metaprepid'] = 0;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['poa_metaprepid'] = 0;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id]['poa_metaprepid'] = 0;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id]['distritos'][$row->dist_id]['poa_metaprepid'] = 0;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id]['distritos'][$row->dist_id]['poa'][$row->poa_id]['poa_metaprepid'] = $row->poa_metaprepid;
                //Almacenando datos Financieros
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['poa_metaprepig'] = 0;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['poa_metaprepig'] = 0;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id]['poa_metaprepig'] = 0;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id]['distritos'][$row->dist_id]['poa_metaprepig'] = 0;
                if($row->poa_sact_id == $row->sact_id)
                    $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id]['distritos'][$row->dist_id]['poa'][$row->poa_id]['poa_metaprepig'] = $row->poa_metaprepig;
                else
                    $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id]['distritos'][$row->dist_id]['poa'][$row->poa_id]['poa_metaprepig'] = 0;    
                //Almacenando datos físicos
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['unidad_medida'][$row->umed_id]['umed_nombre'] = ucwords(mb_strtolower($row->umed_nombre, 'UTF-8'));                
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['unidad_medida'][$row->umed_id][$row->pmfi_tipo]['pmfi_meta'] = 0;                
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['unidad_medida'][$row->umed_id]['departamento'][$row->depa_id][$row->pmfi_tipo]['pmfi_meta'] = 0;                
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['unidad_medida'][$row->umed_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id][$row->pmfi_tipo]['pmfi_meta'] = 0;                
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['unidad_medida'][$row->umed_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id]['distritos'][$row->dist_id][$row->pmfi_tipo]['pmfi_meta'] = 0;                
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['unidad_medida'][$row->umed_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id]['distritos'][$row->dist_id]['poa'][$row->poa_id]['meta_fisica'][$row->pmfi_tipo][$row->pmfi_orden]['pmfi_meta'] = $row->pmfi_meta;
                
                
            }
            
 
        //print_r($ubigeo);die();
        $colIndexDep = PHPExcel_Cell::columnIndexFromString('X');
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndexDep);
 
        $columnas=array();
        
        foreach($ubigeo as $depa_id => $departamento){
            
            $count_dist=0;
               // Add some data            
            $colIndexProv = $colIndexDep;
            foreach($departamento['provincias'] as $prov_id => $provincia){
                
                $count_dist_prov=0;
                //echo $columnLetter.$numrow.' '.$provincia['prov_descripcion'];
                $colIndexDist = $colIndexProv;
                foreach ($provincia['distritos'] as $dist_id => $distrito) {
                    $numrow=10;
                    $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndexDist);
                    $this->excel->setActiveSheetIndex(0)                        
                         ->setCellValue( $columnLetter.$numrow, $distrito['dist_descripcion']);
                    
                    $colIndexTit=$colIndexDist;
                    $colIndexDist=$colIndexDist+13;
                    $columnLetterEnd = PHPExcel_Cell::stringFromColumnIndex($colIndexDist-1);
                    $this->excel->setActiveSheetIndex(0)
                        ->getStyle($columnLetter.$numrow.':'.$columnLetterEnd.$numrow)
                        ->applyFromArray($BStyle); 
                    
                    $this->excel->setActiveSheetIndex(0)
                        ->getStyle($columnLetter.$numrow.':'.$columnLetterEnd.$numrow)                        
                        ->getFont()->setSize(8);                        
                    $this->excel->setActiveSheetIndex(0)
                        ->getStyle($columnLetter.$numrow.':'.$columnLetterEnd.$numrow)                        
                        ->getAlignment()
                        ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                        ->setWrapText(true);
                    $this->excel->setActiveSheetIndex(0)
                    ->mergeCells($columnLetter.$numrow.':'.$columnLetterEnd.$numrow);
                    $count_dist++;
                    $count_dist_prov++;
                    $orden=0;
                    for($i=0;$i<5;$i++){
                        $descripcion="";
                        $max = 2;
                        if ($i == 0) {
                            $descripcion="Programación PIA";
                        } elseif ($i == 1) {
                            $descripcion="Programacion PIM";
                        } elseif ($i == 2) {
                            $descripcion="Ejecución";
                            $max = 3;
                        } elseif ($i == 3) {
                            $descripcion="Saldo";
                            $max = 3;
                        } elseif ($i == 4) {
                            $descripcion=" % Avance";
                            $max = 3;
                        }
                        $colIndexSubtit=$colIndexTit;
                        for($j=0;$j<$max;$j++){
                            $subdesc="";
                            if ($j == 0) {
                                $subdesc="Fisico";
                            } elseif ($j == 1) {
                                $subdesc="Presupuestal \n S/.";
                            }
                             elseif ($j == 2) {
                                $subdesc="Financiera \n S/.";
                            }
 
                            $numrow=12;
                            $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndexSubtit);
                            $columnas[$colIndexSubtit]['depa_id']=$depa_id;
                            $columnas[$colIndexSubtit]['prov_id']=$prov_id;
                            $columnas[$colIndexSubtit]['dist_id']=$dist_id;
                            $columnas[$colIndexSubtit]['orden']=$orden;
                            $orden++;
                            $this->excel->setActiveSheetIndex(0)                        
                                 ->setCellValue( $columnLetter.$numrow, $subdesc);
                            $this->excel->getActiveSheet()
                                    ->getColumnDimension($columnLetter)
                                    ->setWidth(10);                                                        
                            $this->excel->setActiveSheetIndex(0)
                                ->getStyle($columnLetter.$numrow)
                                ->applyFromArray($BStyle); 
                        
                            $this->excel->setActiveSheetIndex(0)
                                ->getStyle($columnLetter.$numrow)                        
                                ->getFont()->setSize(8);                        
                            $this->excel->setActiveSheetIndex(0)
                                ->getStyle($columnLetter.$numrow)                        
                                ->getAlignment()
                                ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                ->setWrapText(true);
                            
                            $colIndexSubtit++;
                        }
                        $numrow=11;
                        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndexTit);
                        $this->excel->setActiveSheetIndex(0)                        
                             ->setCellValue( $columnLetter.$numrow, $descripcion);
                        $colIndexTit=$colIndexTit+$max;
                        $columnLetterEnd = PHPExcel_Cell::stringFromColumnIndex($colIndexTit-1);
                        $this->excel->setActiveSheetIndex(0)
                            ->getStyle($columnLetter.$numrow.':'.$columnLetterEnd.$numrow)
                            ->applyFromArray($BStyle); 
                    
                        $this->excel->setActiveSheetIndex(0)
                            ->getStyle($columnLetter.$numrow.':'.$columnLetterEnd.$numrow)                        
                            ->getFont()->setSize(8);                        
                        $this->excel->setActiveSheetIndex(0)
                            ->getStyle($columnLetter.$numrow.':'.$columnLetterEnd.$numrow)                        
                            ->getAlignment()
                            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                            ->setWrapText(true);
                        $this->excel->setActiveSheetIndex(0)
                        ->mergeCells($columnLetter.$numrow.':'.$columnLetterEnd.$numrow);
                    }
 
                }
                $numrow=9;
                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndexProv);
                $this->excel->setActiveSheetIndex(0)                        
                        ->setCellValue( $columnLetter.$numrow, $provincia['prov_descripcion']);
                $colIndexProv=$colIndexProv+($count_dist_prov*13);
                $columnLetterEnd = PHPExcel_Cell::stringFromColumnIndex($colIndexProv-1);
                $this->excel->setActiveSheetIndex(0)
                        ->getStyle($columnLetter.$numrow.':'.$columnLetterEnd.$numrow)
                        ->applyFromArray($BStyle); 
                    
                    $this->excel->setActiveSheetIndex(0)
                        ->getStyle($columnLetter.$numrow.':'.$columnLetterEnd.$numrow)                        
                        ->getFont()->setSize(8);                        
                    $this->excel->setActiveSheetIndex(0)
                        ->getStyle($columnLetter.$numrow.':'.$columnLetterEnd.$numrow)                        
                        ->getAlignment()
                        ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                        ->setWrapText(true);
                    $this->excel->setActiveSheetIndex(0)
                    ->mergeCells($columnLetter.$numrow.':'.$columnLetterEnd.$numrow); 
            }
            $numrow=8;
            $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndexDep);
            
            $this->excel->setActiveSheetIndex(0)                        
                        ->setCellValue( $columnLetter.$numrow, $departamento['depa_descripcion']);
            $colIndexDep=$colIndexDep+($count_dist*13);
            $columnLetterEnd = PHPExcel_Cell::stringFromColumnIndex($colIndexDep-1);
            $this->excel->setActiveSheetIndex(0)
                        ->getStyle($columnLetter.$numrow.':'.$columnLetterEnd.$numrow)
                        ->applyFromArray($BStyle); 
                    
                    $this->excel->setActiveSheetIndex(0)
                        ->getStyle($columnLetter.$numrow.':'.$columnLetterEnd.$numrow)                        
                        ->getFont()->setSize(8);                        
                    $this->excel->setActiveSheetIndex(0)
                        ->getStyle($columnLetter.$numrow.':'.$columnLetterEnd.$numrow)                        
                        ->getAlignment()
                        ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                        ->setWrapText(true);
                    $this->excel->setActiveSheetIndex(0)
                    ->mergeCells($columnLetter.$numrow.':'.$columnLetterEnd.$numrow); 
            
            
 
        }
            
            //Calculando totales de cantidades físicas
 
            foreach($datos['programa_presupuestal']  as $ppre_id => $programa){
                
                foreach($programa['producto'] as $prod_id => $producto){
 
                    foreach($producto['actividad'] as $acti_id => $actividad){
 
                        foreach($actividad['subactividad'] as $sact_id => $subactividad){
 
                            foreach($subactividad['unidad_medida'] as $umed_id => $unidad){
                                if(!isset($datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['unidad_medida'][$umed_id]['M']['pmfi_meta']))
                                    $datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['unidad_medida'][$umed_id]['M']['pmfi_meta']=0;
                                if(!isset($datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['unidad_medida'][$umed_id]['E']['pmfi_meta']))
                                    $datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['unidad_medida'][$umed_id]['E']['pmfi_meta']=0;
 
                                foreach($unidad['departamento'] as $depa_id => $departamento) {
                                    if(!isset($departamento['M']['pmfi_meta']))
                                        $departamento['M']['pmfi_meta']=0;
                                    if(!isset($departamento['E']['pmfi_meta']))
                                        $departamento['E']['pmfi_meta']=0;
                                    
                                    foreach($departamento['provincias'] as $prov_id => $provincia){
                                        
                                        if(!isset($provincia['M']['pmfi_meta']))
                                            $provincia['M']['pmfi_meta']=0;
                                        if(!isset($provincia['E']['pmfi_meta']))
                                            $provincia['E']['pmfi_meta']=0;
 
                                        foreach ($provincia['distritos'] as $dist_id => $distrito) {
                                    
                                            if(!isset($datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['unidad_medida'][$umed_id]['departamento'][$depa_id]['provincias'][$prov_id]['distritos'][$dist_id]['M']['pmfi_meta']))
                                                $datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['unidad_medida'][$umed_id]['departamento'][$depa_id]['provincias'][$prov_id]['distritos'][$dist_id]['M']['pmfi_meta']=0;
                                            if(!isset($datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['unidad_medida'][$umed_id]['departamento'][$depa_id]['provincias'][$prov_id]['distritos'][$dist_id]['E']['pmfi_meta']))
                                                $datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['unidad_medida'][$umed_id]['departamento'][$depa_id]['provincias'][$prov_id]['distritos'][$dist_id]['E']['pmfi_meta']=0;
 
                                            foreach ($distrito['poa'] as $poa_id => $poa) {
                                    
                                                 
                                                if(isset($poa['meta_fisica']['M'])){
                                                    foreach ($poa['meta_fisica']['M'] as $pmfi_orden => $meta) {
                                                        
                                                        $datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['unidad_medida'][$umed_id]['departamento'][$depa_id]['provincias'][$prov_id]['distritos'][$dist_id]['M']['pmfi_meta']+=$meta['pmfi_meta'];
                                                    }
                                                    
 
                                                }
                                                if(isset($poa['meta_fisica']['E'])){
                                                    foreach ($poa['meta_fisica']['E'] as $pmfi_orden => $meta) {
 
                                                        $datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['unidad_medida'][$umed_id]['departamento'][$depa_id]['provincias'][$prov_id]['distritos'][$dist_id]['E']['pmfi_meta']+=$meta['pmfi_meta'];
                                                    }
                                                    
                                                }
                                                
                                            }
                                            //echo "<pre>";print_r($distrito['M']['pmfi_meta']);
                                            $provincia['M']['pmfi_meta']+=$datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['unidad_medida'][$umed_id]['departamento'][$depa_id]['provincias'][$prov_id]['distritos'][$dist_id]['M']['pmfi_meta'];
                                            $provincia['E']['pmfi_meta']+=$datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['unidad_medida'][$umed_id]['departamento'][$depa_id]['provincias'][$prov_id]['distritos'][$dist_id]['E']['pmfi_meta'];
                                            
                                        }
                                        //echo "<pre>";print_r($provincia['M']['pmfi_meta']);
                                        $departamento['M']['pmfi_meta']=$departamento['M']['pmfi_meta']+$provincia['M']['pmfi_meta'];
                                        $departamento['E']['pmfi_meta']=$departamento['E']['pmfi_meta']+$provincia['E']['pmfi_meta'];
                                        
                                    }
                                    $datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['unidad_medida'][$umed_id]['M']['pmfi_meta']+=$departamento['M']['pmfi_meta'];
                                    $datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['unidad_medida'][$umed_id]['E']['pmfi_meta']+=$departamento['E']['pmfi_meta'];
                                        
                                }
                                
                            }
 
                            
 
                        }
                        
 
                    }                    
 
                }            
 
            }
 
           //Calculando totales de cantidades presupuestales y financieras
            foreach($datos['programa_presupuestal']  as $ppre_id => $programa){
                
                foreach($programa['producto'] as $prod_id => $producto){
 
                    foreach($producto['actividad'] as $acti_id => $actividad){
 
                        foreach($actividad['subactividad'] as $sact_id => $subactividad){
                                if(!isset($datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['M']['ppre_importe']))
                                    $datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['M']['ppre_importe']=0;
                                if(!isset($datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['E']['ppre_importe']))
                                    $datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['E']['ppre_importe']=0;
                            
                                foreach($subactividad['departamento'] as $depa_id => $departamento) {
                                    
                                    
                                    foreach($departamento['provincias'] as $prov_id => $provincia){                                                                               
 
                                        foreach ($provincia['distritos'] as $dist_id => $distrito) {
                                                                               
 
                                            foreach ($distrito['poa'] as $poa_id => $poa) {  
                                                $distrito['poa_metafispia']+=$poa['poa_metafispia'];
                                                $distrito['poa_metaprepia']+=$poa['poa_metaprepia'];
                                                $distrito['poa_metaprepim']+=$poa['poa_metaprepim'];                                                       
                                                $distrito['poa_metaprepid']+=$poa['poa_metaprepid'];                                               
                                                $distrito['poa_metaprepig']+=$poa['poa_metaprepig'];
                                            }
                                            $provincia['poa_metaprepia']+=$distrito['poa_metaprepia'];
                                            $provincia['poa_metafispia']+=$distrito['poa_metafispia'];
                                            $provincia['poa_metaprepim']+=$distrito['poa_metaprepim'];
                                            $provincia['poa_metaprepid']+=$distrito['poa_metaprepid'];
                                            $provincia['poa_metaprepig']+=$distrito['poa_metaprepig'];
                                            
                                        }
                                        $departamento['poa_metaprepia']+=$provincia['poa_metaprepia'];
                                        $departamento['poa_metafispia']+=$provincia['poa_metafispia'];
                                        $departamento['poa_metaprepim']+=$provincia['poa_metaprepim'];
                                        $departamento['poa_metaprepid']+=$provincia['poa_metaprepid'];
                                        $departamento['poa_metaprepig']+=$provincia['poa_metaprepig'];
                                        
                                    }
                                    $datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['poa_metaprepia']+=$departamento['poa_metaprepia'];
                                    $datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['poa_metafispia']+=$departamento['poa_metafispia'];
                                    $datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['poa_metaprepim']+=$departamento['poa_metaprepim'];
                                    $datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['poa_metaprepid']+=$departamento['poa_metaprepid'];
                                    $datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['poa_metaprepig']+=$departamento['poa_metaprepig'];
                                        
                                }
                                
                            
 
                            
 
                        }
                        
 
                    }                    
 
                }            
 
            }
           //echo "<pre>";
           //print_r($datos); 
           //die("test");
            
            foreach($datos['programa_presupuestal']  as $ppre_id => $programa){
                
                foreach($programa['producto'] as $prod_id => $producto){
 
                    foreach($producto['actividad'] as $acti_id => $actividad){
 
                        foreach($actividad['subactividad'] as $sact_id => $subactividad){
 
                            foreach($subactividad['unidad_medida'] as $umed_id => $unidad){
                                $colIndex = PHPExcel_Cell::columnIndexFromString('A');
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);                                
                                $rows[$count][$columnLetter]=$programa['ppre_codref'].': '.$programa['ppre_nombre'];//A
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                $rows[$count][$columnLetter]=$producto['prod_codref'].': '.$producto['prod_nombre'];//B
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                $rows[$count][$columnLetter]=$actividad['acti_codref'].': '.$actividad['acti_nombre'];//C
                                $colIndex++;                                
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                $rows[$count][$columnLetter]=$subactividad['sact_nombre'];//D
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                $rows[$count][$columnLetter]=$unidad['umed_nombre'];//E
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                $a = $subactividad['poa_metafispia'] ;                                
                                $rows[$count][$columnLetter]= $a;//F (a)
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                $b = $subactividad['poa_metaprepia'];
                                $rows[$count][$columnLetter]= $b;//G (b)
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
                                $c = $subactividad['poa_metafispim'];                               
                                $rows[$count][$columnLetter]=$c;//H (c)
                                //$rows[$count][$columnLetter]=$unidad['M']['pmfi_meta'];//H
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                $d = $subactividad['poa_metaprepim'];
                                $rows[$count][$columnLetter]=$d;//I (d)
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                $e = $unidad['E']['pmfi_meta'];
                                $rows[$count][$columnLetter]=$e;//J (e)
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                $f = $subactividad['poa_metaprepid'];
                                $rows[$count][$columnLetter]= $f;//K (f)
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                $g = $subactividad['poa_metaprepig'];
                                $rows[$count][$columnLetter]=  $g;//L (g)
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                $rows[$count][$columnLetter]=($a - $e );//M (a-e)
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                $rows[$count][$columnLetter]=($c - $e);//N (c-e)
                                $colIndex++;
                                $rows[$count][$columnLetter]=($b - $f );//O (b-f)
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                $rows[$count][$columnLetter]=($d - $f);//P (d-f)
                                $colIndex++;
                                $rows[$count][$columnLetter]=($b - $g );//Q (b-g)
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                $rows[$count][$columnLetter]=($d - $g);//R (d-g)
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                if($unidad['M']['pmfi_meta']>0)
                                    $rows[$count][$columnLetter]=($e/$a)*100;//S (e/a)
                                else
                                    $rows[$count][$columnLetter]=0;//S 
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                if($c>0)
                                    $rows[$count][$columnLetter]=($e/$c)*100;//T (e/c)
                                else
                                    $rows[$count][$columnLetter]=0;//T
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                if($b>0)
                                    $rows[$count][$columnLetter]=($f/$b)*100;//U (f/b)
                                else
                                    $rows[$count][$columnLetter]=0;//U
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                if($d>0)
                                    $rows[$count][$columnLetter]=($f/$d)*100;//V (f/d)
                                else
                                    $rows[$count][$columnLetter]=0;//V
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                if($b>0)
                                    $rows[$count][$columnLetter]=($g/$b)*100;//W (g/b)
                                else
                                    $rows[$count][$columnLetter]=0;//W
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                if($d>0)
                                    $rows[$count][$columnLetter]=($g/$d)*100;//X (g/d)
                                else
                                    $rows[$count][$columnLetter]=0;//P
 
                                foreach ($columnas as $index => $columna) {
                                    $columnLetter = PHPExcel_Cell::stringFromColumnIndex($index);
                                    if($columna['orden']==0 && isset($datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['unidad_medida'][$umed_id]['departamento'][$columna['depa_id']]['provincias'][$columna['prov_id']]['distritos'][$columna['dist_id']]['M']['pmfi_meta'])){
                                        $rows[$count][$columnLetter]=$datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['unidad_medida'][$umed_id]['departamento'][$columna['depa_id']]['provincias'][$columna['prov_id']]['distritos'][$columna['dist_id']]['M']['pmfi_meta'];
                                        $numberFormat[$columnLetter]=$formats[2];
                                    }else if($columna['orden']==1 && isset($datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['departamento'][$columna['depa_id']]['provincias'][$columna['prov_id']]['distritos'][$columna['dist_id']]['poa_metaprepim'])){
                                        $rows[$count][$columnLetter]=$datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['departamento'][$columna['depa_id']]['provincias'][$columna['prov_id']]['distritos'][$columna['dist_id']]['poa_metaprepim'];
                                        $numberFormat[$columnLetter]=$formats[0];
                                    }else if($columna['orden']==2 && isset($datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['unidad_medida'][$umed_id]['departamento'][$columna['depa_id']]['provincias'][$columna['prov_id']]['distritos'][$columna['dist_id']]['E']['pmfi_meta'])){
                                        $rows[$count][$columnLetter]=$datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['unidad_medida'][$umed_id]['departamento'][$columna['depa_id']]['provincias'][$columna['prov_id']]['distritos'][$columna['dist_id']]['E']['pmfi_meta'];
                                        $numberFormat[$columnLetter]=$formats[2];
                                    }else if($columna['orden']==3 && isset($datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['departamento'][$columna['depa_id']]['provincias'][$columna['prov_id']]['distritos'][$columna['dist_id']]['poa_metaprepig'])){
                                        $rows[$count][$columnLetter]=$datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['departamento'][$columna['depa_id']]['provincias'][$columna['prov_id']]['distritos'][$columna['dist_id']]['poa_metaprepig'];
                                        $numberFormat[$columnLetter]=$formats[0];
                                    }else if($columna['orden']==4){
                                        $rows[$count][$columnLetter]=($rows[$count][PHPExcel_Cell::stringFromColumnIndex($index-4)]- $rows[$count][PHPExcel_Cell::stringFromColumnIndex($index-2)] );//J
                                        $numberFormat[$columnLetter]=$formats[2];
                                    }else if($columna['orden']==5){
                                        $rows[$count][$columnLetter]=($rows[$count][PHPExcel_Cell::stringFromColumnIndex($index-4)]- $rows[$count][PHPExcel_Cell::stringFromColumnIndex($index-2)] );//J
                                        $numberFormat[$columnLetter]=$formats[0];
                                    }else if($columna['orden']==6 && $rows[$count][PHPExcel_Cell::stringFromColumnIndex($index-6)] > 0 ){
                                        $rows[$count][$columnLetter]=($rows[$count][PHPExcel_Cell::stringFromColumnIndex($index-4)] / $rows[$count][PHPExcel_Cell::stringFromColumnIndex($index-6)] )*100;//J
                                        $numberFormat[$columnLetter]=$formats[1];
                                    }else if($columna['orden']==7 && $rows[$count][PHPExcel_Cell::stringFromColumnIndex($index-6)] > 0 ){
                                        $rows[$count][$columnLetter]=($rows[$count][PHPExcel_Cell::stringFromColumnIndex($index-4)] / $rows[$count][PHPExcel_Cell::stringFromColumnIndex($index-6)] )*100;//J
                                        $numberFormat[$columnLetter]=$formats[1];
                                    }else{
                                        $rows[$count][$columnLetter]=0;
                                        $numberFormat[$columnLetter]=$formats[2];
                                    }
                                    # code...
                                }
                                
                                $count++;
                            }
 
                            $count++;
 
                        }
 
                        $count++;
 
                    }
 
                    $count++;
 
                }
 
                $count++;
 
            }
        }
        //die("test");
         
        $start = 13;
        if(isset($rows)){
            foreach($rows as $row){
                $col=0;
                foreach($row as $column => $value){
                    
                    $this->excel->setActiveSheetIndex(0)                        
                                ->setCellValue($column.$start, $value);
                    
                    
                    
                    $this->excel->setActiveSheetIndex(0)
                                ->getStyle($column.$start)                        
                                ->getFont()->setSize(8);
 
 
                    if($col==0){
                        $this->excel->getActiveSheet(0)
                                ->getStyle($column.$start)
                                ->getNumberFormat()
                                ->setFormatCode('0000');
                    }
 
                    if( is_numeric($value) && $col>4 ){ 
                        $this->excel->setActiveSheetIndex(0)
                                ->getStyle($column.$start)
                                ->applyFromArray($BStyle);
 
                        $this->excel->getActiveSheet(0)
                                ->getStyle($column.$start)
                                ->getNumberFormat()
                                ->setFormatCode($numberFormat[$column]);
 
                        $this->excel->setActiveSheetIndex(0)
                                    ->getStyle($column.$start)
                                    ->getAlignment()
                                    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
                                    ->setWrapText(true);
                        $this->excel->getActiveSheet(0)
                                    ->getStyle($column.$start)
                                    ->getAlignment()
                                    ->setIndent(1);
                    }else { 
 
                        if($value==''){
                            $this->excel->setActiveSheetIndex(0)
                                ->getStyle($column.$start)
                                ->applyFromArray($BStyle);
                        }else{
                            
                            $this->excel->setActiveSheetIndex(0)
                                    ->getStyle($column.$start)
                                    ->applyFromArray($BStyle);
                        }
 
                        
                        $this->excel->setActiveSheetIndex(0)
                                    ->getStyle($column.$start)
                                    ->getAlignment()
                                    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                    ->setWrapText(true);
                        $this->excel->getActiveSheet(0)
                                    ->getStyle($column.$start)
                                    ->getAlignment()
                                    ->setIndent(1);
                    }
 
                    //echo " $column.$start $value" ;
                    $col++;
                }
 
                $start++;
                /*
                if($start>35)
                    break;
                */
                
            }
        }
 
        $this->excel->setActiveSheetIndex(0)
                                ->getStyle("A$start:Q$start")
                                ->applyFromArray($EStyle);
        
        $this->excel->setActiveSheetIndex(0)
                                ->getStyle("N10:N12")
                                ->applyFromArray($FStyle);
             
        
        
        $this->excel->setActiveSheetIndex(0)
                    ->mergeCells('A1:B1');
        /*                    
        $this->excel->setActiveSheetIndex(0)
                    ->mergeCells('A14:A24');
        */
        // Rename worksheet
        //die("test");
 
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $this->excel->setActiveSheetIndex(0);
 
        if($extension==EXT_EXCEL){
            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Reporte_2.xlsx"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');
 
            // If you're serving to IE over SSL, then the following may be needed
            header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
            header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header ('Pragma: public'); // HTTP/1.0        
 
            $objWriter = $this->factory->createWriter($this->excel, 'Excel2007');
            //print_r($objWriter);
            $objWriter->save('php://output');
        }else { 
                   
            $rendererName = PHPExcel_Settings::PDF_RENDERER_DOMPDF;
            $rendererLibrary = 'dompdf';
            $rendererLibraryPath = APPPATH.'third_party/'. $rendererLibrary.'/src';
            $filename='Reporte_2'.'.pdf'; 
            PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath);
            /*
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
            header('Cache-Control: max-age=0'); //no cache
            */
            /*
            ini_set('max_execution_time', 3000);
            ini_set('memory_limit','16M');
            */
            // $objWriter = new PHPExcel_Writer_PDF($objPHPExcel);
            //print_r($this->excel);die();
            $objWriter = $this->factory->createWriter($this->excel, 'PDF'); 
            $objWriter->setSheetIndex(0);
            //print_r($objWriter);die();
            $objWriter->save('php://output');
         }   
        exit;
       
    }
 
 
    public function formato3() {

        $formats = array("#,###", "#,###.0", "#,###.00");
      
        $datapost = $this->security->xss_clean($this->input->post());
        $extension = '';
        if(isset($datapost['extension'])){
            $extension = $datapost['extension'];
        }
        
        $ogru_nombre = ''; 
        $ofic_nombre = '';        
        $peri_id  = $datapost['peri_id'];
        $data_peri = $this->periodo_model->obtener_periodo_por_id($peri_id);
        if(isset($datapost['fecha_reporte']) && $datapost['fecha_reporte']!=''){
            $fecha_reporte = str_replace('/', '-', $datapost['fecha_reporte']);
            $fecha_reporte = date('Y-m-d', strtotime($fecha_reporte));
        }else{
            $fecha_reporte = date('Y-m-d', time());
        }

        
 
        $ogru_id_array  = array();
        $ofic_id_array  = array();
        $poli_id_array  = array();
        $ppre_id_array  = array();
        $prod_id_array  = array();
        $acti_id_array  = array();
        $sact_id_array  = array();
        $estr_id_array  = array();


 
        if(isset($datapost['ogru_id_array'])){
            $ogru_id_array  = $datapost['ogru_id_array'];
            if(count($ogru_id_array)==1){                
                $data_ogru = $this->oficinagrupo_model->getOficinagrupoByID($ogru_id_array[0]);                
                $ogru_nombre = $data_ogru->ogru_nombre;
            }
        }else{
            $ogru_nombre = "Todas";
        }
 
        if(isset($datapost['ofic_id_array'])){
            $ofic_id_array  = $datapost['ofic_id_array'];
            if(count($ofic_id_array)==1){
                $data_ofic = $this->oficina_model->getOficinaByID($ofic_id_array[0]);
                $ofic_nombre = $data_ofic->ofic_nombre;
            }
        }else{
            $ofic_nombre = "Todas";
        }
        
        if(isset($datapost['poli_id_array'])){
            $poli_id_array  = $datapost['poli_id_array'];
        }
 
        if(isset($datapost['ppre_id_array'])){
            $ppre_id_array  = $datapost['ppre_id_array'];
        }
 
        if(isset($datapost['prod_id_array'])){
            $prod_id_array  = $datapost['prod_id_array'];
        }
 
        if(isset($datapost['acti_id_array'])){
            $acti_id_array  = $datapost['acti_id_array'];
        }
 
        if(isset($datapost['sact_id_array'])){
            $sact_id_array  = $datapost['sact_id_array'];
        }  

        if(isset($datapost['estr_id_array'])){
            $estr_id_array  = $datapost['estr_id_array'];
        }                           
 
        
        $result =  $this->reporte_model->getReporteProyectos(  $ogru_id_array, $ofic_id_array, $peri_id, $fecha_reporte, 
                                            $poli_id_array, $ppre_id_array, $prod_id_array, 
                                            $acti_id_array , $sact_id_array, $estr_id_array );
        
        $rows=array();
        
        
       
       /** Error reporting */
        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        //date_default_timezone_set('Europe/London');
 
        // Set document properties
        $this->excel->getProperties()->setCreator("iorganizacional.com.pe")
                                     ->setLastModifiedBy("iorganizacional.com.pe")
                                     ->setTitle("Reporte 3")
                                     ->setSubject("Formato 3")
                                     ->setDescription("Programacion y Ejecucion de Metas.")
                                     ->setKeywords("programacion metas")
                                     ->setCategory("organizacional");
        

        $objDrawing = new PHPExcel_Worksheet_Drawing();
        $objDrawing->setName("name");
        $objDrawing->setDescription("Description");
        $objDrawing->setPath('public/assets/img/logo/agrorural-logo.png');
        $objDrawing->setCoordinates('P1');
        $objDrawing->setWorksheet($this->excel->setActiveSheetIndex(0));

        $this->excel->setActiveSheetIndex(0)
                        ->mergeCells('P1:Q1');
 
 
        // Add some data
 
        $this->excel->setActiveSheetIndex(0)
                    ->setCellValue('D3', "Reporte 3: Programación y Ejecución de Metas Físico Presupuestales \n Por Gestores");
        $this->excel->setActiveSheetIndex(0)
                        ->mergeCells('D3:K4');
        $this->excel->setActiveSheetIndex(0)
                                    ->getStyle('D3:I3')
                                    ->getAlignment()
                                    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Reporte N° 3')                    
                    ->setCellValue('G5', 'Periodo '.$data_peri->peri_nombre)
                    ->setCellValue('N7', 'A la Fecha : ')
                    ->setCellValue('N8', 'Hora : ');
                    //->setCellValue('A7', 'Unidad Orgánica : ')
                    //->setCellValue('A8', 'Agencia Zonal : ')
        $this->excel->setActiveSheetIndex(0)
                                    ->getStyle('A7:B8')
                                    ->getAlignment()
                                    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $this->excel->setActiveSheetIndex(0)
                                    ->getStyle('N7:O8')
                                    ->getAlignment()
                                    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $this->excel->setActiveSheetIndex(0)
                        ->mergeCells('G5:H5');

        $this->excel->setActiveSheetIndex(0)
                        ->mergeCells('N7:O7');
        
        $this->excel->setActiveSheetIndex(0)
                        ->mergeCells('N8:O8');
        
                   
        $fecha_reporte = date('d/m/Y', strtotime($fecha_reporte));
        $hora_reporte  = date('H:i:s', time());
        
 
        $this->excel->setActiveSheetIndex(0)                    
                    ->setCellValue('P7', $fecha_reporte)
                    ->setCellValue('P8', $hora_reporte);
                    //->setCellValue('C7', ' '.$ogru_nombre)
                    //->setCellValue('C8', ' '.$ofic_nombre)                    

        $this->excel->setActiveSheetIndex(0)
                    ->mergeCells('A1:B1');
 
         $this->excel->setActiveSheetIndex(0)
                    ->mergeCells('A7:B7');
        $this->excel->setActiveSheetIndex(0)
                    ->mergeCells('A8:B8');

        $this->excel->setActiveSheetIndex(0)
                        ->mergeCells('P7:Q7');
        
        $this->excel->setActiveSheetIndex(0)
                        ->mergeCells('P8:Q8');
        
 
        $this->excel->setActiveSheetIndex(0)
                                    ->getStyle('C5:C7')
                                    ->getAlignment()
                                    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    
 
        $this->excel->getActiveSheet()
                    ->getPageSetup()
                    ->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $this->excel->getActiveSheet()
                    ->getPageSetup()
                    ->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
       
        
        $BStyle = array(
          'borders' => array(
            'outline' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
            )
          )
        );
 
        $CStyle = array(
          'borders' => array(
            'left' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
            ),
            'right' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
            )
          )
        );
 
        $DStyle = array(
          'borders' => array(
            'top' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
            ),
            'left' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
            ),
            'right' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
            )
          )
        );
 
        $EStyle = array(
          'borders' => array(
            'top' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
            )
          )
        );
 
        $FStyle = array(
          'borders' => array(
            'left' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
            )
          )
        );
 
        $header = array();

        $colIndex = PHPExcel_Cell::columnIndexFromString('A');
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
 
        $header[$columnLetter] = array();
        $header[$columnLetter]['label'] = 'Programa Presupuestal';
        $header[$columnLetter]['start'] = 10;
        $header[$columnLetter]['end'] = 12;

        $colIndex++;
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
 
        $header[$columnLetter] = array();
        $header[$columnLetter]['label'] = 'Producto';
        $header[$columnLetter]['start'] = 10;
        $header[$columnLetter]['end'] = 12;

        $colIndex++;
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
 
        $header[$columnLetter] = array();
        $header[$columnLetter]['label'] = 'Actividad';
        $header[$columnLetter]['start'] = 10;
        $header[$columnLetter]['end'] = 12;

        $colIndex++;
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
 
        $header[$columnLetter] = array();
        $header[$columnLetter]['label'] = 'Subactividad';
        $header[$columnLetter]['start'] = 10;
        $header[$columnLetter]['end'] = 12;

        $colIndex++;
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
 
        $header[$columnLetter] = array();
        $header[$columnLetter]['label'] = 'Unidad Medida';
        $header[$columnLetter]['start'] = 10;
        $header[$columnLetter]['end'] = 12;

        $colIndex++;
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
 
        $header[$columnLetter] = array();
        $header[$columnLetter]['label'] = 'Unidad Orgánica';
        $header[$columnLetter]['start'] = 10;
        $header[$columnLetter]['end'] = 12;

        $colIndex++;
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
 
        $header[$columnLetter] = array();
        $header[$columnLetter]['label'] = 'Agencia Zonal';
        $header[$columnLetter]['start'] = 10;
        $header[$columnLetter]['end'] = 12;

        $colIndex++;
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
 
        $header[$columnLetter] = array();
        $header[$columnLetter]['label'] = 'Gestores de Cartera';
        $header[$columnLetter]['start'] = 10;
        $header[$columnLetter]['end'] = 12;

        $colIndex++;
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
 
        $header[$columnLetter] = array();
        $header[$columnLetter]['label'] = 'Tipo';
        $header[$columnLetter]['start'] = 10;
        $header[$columnLetter]['end'] = 12;

        $colIndex++;
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
        $endLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex+8);
        $startLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
 
        $header[$columnLetter] = array();
        $header[$columnLetter]['label'] = 'Total Oficina';
        $header[$columnLetter]['items'] = array();
        $header[$columnLetter]['start'] = 10;
        $header[$columnLetter]['end'] = $endLetter;

        $endLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex);

        $numberFormat =array();
 
        $header[$columnLetter]['items'][$startLetter] = array();
        $header[$columnLetter]['items'][$startLetter]['label'] = 'Programación PIM';
        $header[$columnLetter]['items'][$startLetter]['start'] = 11;
        $header[$columnLetter]['items'][$startLetter]['end'] = $endLetter;
 
        $header[$columnLetter]['items'][$startLetter]['items'] = array();
        $header[$columnLetter]['items'][$startLetter]['items'][$startLetter]['label'] = 'Fisico';
        $header[$columnLetter]['items'][$startLetter]['items'][$startLetter]['start'] = 12;

        $numberFormat[$startLetter] = $formats[2];
 
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['label'] = "Presupuestal \n S/.";
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['start'] = 12;

        $numberFormat[$endLetter] = $formats[0];

        $colIndex+=2;
        $startLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
        $endLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex+1);
 
        $header[$columnLetter]['items'][$startLetter] = array();
        $header[$columnLetter]['items'][$startLetter]['label'] = 'Ejecución';
        $header[$columnLetter]['items'][$startLetter]['start'] = 11;
        $header[$columnLetter]['items'][$startLetter]['end'] = $endLetter;


        $header[$columnLetter]['items'][$startLetter]['items'] = array();
        $header[$columnLetter]['items'][$startLetter]['items'][$startLetter]['label'] = 'Fisico';
        $header[$columnLetter]['items'][$startLetter]['items'][$startLetter]['start'] = 12;        

        $numberFormat[$startLetter] = $formats[2];

        $endLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex);
 
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['label'] = "Presupuestal \n S/.";
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['start'] = 12;

        $numberFormat[$endLetter] = $formats[0];

        $colIndex++;        
        $endLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex);

        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['label'] = "Financiera \n S/.";
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['start'] = 12;

        $numberFormat[$endLetter] = $formats[0];

        $colIndex+=2;
        $startLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
        $endLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex+1);
        
 
        $header[$columnLetter]['items'][$startLetter] = array();
        $header[$columnLetter]['items'][$startLetter]['label'] = 'Saldo';
        $header[$columnLetter]['items'][$startLetter]['start'] = 11;
        $header[$columnLetter]['items'][$startLetter]['end'] = $endLetter;
 
        $header[$columnLetter]['items'][$startLetter]['items'] = array();
        $header[$columnLetter]['items'][$startLetter]['items'][$startLetter]['label'] = 'Fisico';
        $header[$columnLetter]['items'][$startLetter]['items'][$startLetter]['start'] = 12;

        $numberFormat[$startLetter] = $formats[2];

        $endLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex);
 
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['label'] = "Presupuestal \n S/.";
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['start'] = 12;

        $numberFormat[$endLetter] = $formats[0];

        $colIndex++;        
        $endLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex);

        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['label'] = "Financiera \n S/.";
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['start'] = 12;

        $numberFormat[$endLetter] = $formats[0];

        $colIndex+=2;
        $startLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
        $endLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex);
 
        $header[$columnLetter]['items'][$startLetter] = array();
        $header[$columnLetter]['items'][$startLetter]['label'] = '% Avance';
        $header[$columnLetter]['items'][$startLetter]['start'] = 11;
        $header[$columnLetter]['items'][$startLetter]['end'] = $endLetter;
 
        $header[$columnLetter]['items'][$startLetter]['items'] = array();
        $header[$columnLetter]['items'][$startLetter]['items'][$startLetter]['label'] = 'Fisico';
        $header[$columnLetter]['items'][$startLetter]['items'][$startLetter]['start'] = 12;

        $numberFormat[$startLetter] = $formats[1];
 
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['label'] = "Presupuestal";
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['start'] = 12;

        $numberFormat[$endLetter] = $formats[1];
        
 
        foreach($header as $column => $data){
 
           
 
            if(isset($data['items'])){
 
                $start = $column.$data['start'];
                $end   = $data['end'].$data['start'];
 
                foreach ($data['items'] as $key => $value) {
                    //print_r($value);die();
                    $this->excel->setActiveSheetIndex(0)                        
                         ->setCellValue($key.$value['start'], $value['label']); 
 
                    $this->excel->setActiveSheetIndex(0)
                        ->getStyle($key.$value['start'].':'.$value['end'].$value['start'])
                        ->applyFromArray($BStyle); 
 
                    $this->excel->setActiveSheetIndex(0)
                        ->mergeCells($key.$value['start'].':'.$value['end'].$value['start']);  
 
                    $this->excel->setActiveSheetIndex(0)
                        ->getStyle($key.$value['start'].':'.$value['end'].$value['start'])                        
                        ->getFont()->setSize(8);                        
                    $this->excel->setActiveSheetIndex(0)
                        ->getStyle($key.$value['start'].':'.$value['end'].$value['start'])                        
                        ->getAlignment()
                        ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                        ->setWrapText(true);
                        if(isset($value['items'])){
                            foreach ($value['items'] as $subkey => $subvalue) {
                                $this->excel->getActiveSheet()
                                    ->getColumnDimension($subkey)
                                    ->setWidth(8);
                                $this->excel->setActiveSheetIndex(0)                        
                                     ->setCellValue($subkey.$subvalue['start'], $subvalue['label']); 
                                $this->excel->setActiveSheetIndex(0)
                                     ->getStyle($subkey.$subvalue['start'])                        
                                     ->getFont()->setSize(8);
                                $this->excel->setActiveSheetIndex(0)
                                    ->getStyle($subkey.$subvalue['start'])
                                    ->getAlignment()
                                    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                    ->setWrapText(true);
                                $this->excel->setActiveSheetIndex(0)
                                    ->getStyle($subkey.$subvalue['start'])
                                    ->applyFromArray($BStyle);
                            }
 
                        }
                }
 
            }else {
 
                $start = $column.$data['start'];
                $end   = $column.$data['end'];
 
            }
            
            
              // Add some data
            $this->excel->setActiveSheetIndex(0)                        
                        ->setCellValue( $start, $data['label']);
 
            $this->excel->getActiveSheet()
                        ->getColumnDimension($column)
                        ->setWidth(8);

            $this->excel->setActiveSheetIndex(0)
                        ->mergeCells($start.':'.$end);
 
            $this->excel->setActiveSheetIndex(0)
                        ->getStyle($start.':'.$end)
                        ->applyFromArray($BStyle);
 
            $this->excel->setActiveSheetIndex(0)
                        ->getStyle($start.':'.$end)                        
                        ->getFont()->setSize(8);
 
            $this->excel->setActiveSheetIndex(0)
                        ->getStyle($start.':'.$end)
                        ->getAlignment()
                        ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                        ->setWrapText(true);
 
            
            
    
        }   
         $count=0;
         $anterior=array();
         $total= array();
          
        $colIndex = PHPExcel_Cell::columnIndexFromString('G');
        
        for($i=0;$i<15;$i++){
            $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
            $total[$columnLetter]=0;            
            $colIndex++;
        }

         if(isset($result)){
            foreach ($result as $key => $row) {
                # code...
                
                $ubigeo[$row->depa_id]['depa_descripcion']=$row->depa_descripcion;
                $ubigeo[$row->depa_id]['provincias'][$row->prov_id]['prov_descripcion']=$row->prov_descripcion;
                $ubigeo[$row->depa_id]['provincias'][$row->prov_id]['distritos'][$row->dist_id]['dist_descripcion']=$row->dist_descripcion;
                $ubigeo[$row->depa_id]['provincias'][$row->prov_id]['distritos'][$row->dist_id]['column']='';
 
                $datos['programa_presupuestal'][$row->ppre_id]['ppre_codref'] = $row->ppre_codref;
                $datos['programa_presupuestal'][$row->ppre_id]['ppre_nombre'] = ucwords(mb_strtolower($row->ppre_nombre, 'UTF-8'));                
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['prod_codref'] = $row->prod_codref;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['prod_nombre'] = ucwords(mb_strtolower($row->prod_nombre, 'UTF-8'));
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['acti_codref'] = $row->acti_codref;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['acti_nombre'] = ucwords(mb_strtolower($row->acti_nombre, 'UTF-8'));
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['sact_nombre'] = ucwords(mb_strtolower($row->sact_nombre, 'UTF-8'));
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['ogru_nombre'] = ucwords(mb_strtolower($row->ogru_nombre, 'UTF-8'));
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['ofic_nombre'] = ucwords(mb_strtolower($row->ofic_nombre, 'UTF-8'));
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['usua_nombre'] = ucwords(mb_strtolower($row->usua_nombre, 'UTF-8'));
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['rol_nombre'] = ucwords(mb_strtolower($row->rol_nombre, 'UTF-8'));
                //Almacenando datos presupuestales
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['poa_metaprepim'] = 0;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['poa_metaprepim'] = 0;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id]['poa_metaprepim'] = 0;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id]['distritos'][$row->dist_id]['poa_metaprepim'] = 0;
                if($row->poa_sact_id == $row->sact_id)
                    $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id]['distritos'][$row->dist_id]['poa'][$row->poa_id]['poa_metaprepim'] = $row->poa_metaprepim;
                else
                    $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id]['distritos'][$row->dist_id]['poa'][$row->poa_id]['poa_metaprepim'] = 0;

                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['poa_metaprepid'] = 0;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['poa_metaprepid'] = 0;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id]['poa_metaprepid'] = 0;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id]['distritos'][$row->dist_id]['poa_metaprepid'] = 0;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id]['distritos'][$row->dist_id]['poa'][$row->poa_id]['poa_metaprepid'] = $row->poa_metaprepid;
                //Almacenando datos Financieros
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['poa_metaprepig'] = 0;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['poa_metaprepig'] = 0;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id]['poa_metaprepig'] = 0;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id]['distritos'][$row->dist_id]['poa_metaprepig'] = 0;

                if($row->poa_sact_id == $row->sact_id)
                    $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id]['distritos'][$row->dist_id]['poa'][$row->poa_id]['poa_metaprepig'] = $row->poa_metaprepig;
                else
                    $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id]['distritos'][$row->dist_id]['poa'][$row->poa_id]['poa_metaprepig'] = 0;
                
                //Almacenando datos físicos
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['unidad_medida'][$row->umed_id]['umed_nombre'] = ucwords(mb_strtolower($row->umed_nombre, 'UTF-8'));                
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['unidad_medida'][$row->umed_id][$row->pmfi_tipo]['pmfi_meta'] = 0;                
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['unidad_medida'][$row->umed_id]['departamento'][$row->depa_id][$row->pmfi_tipo]['pmfi_meta'] = 0;                
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['unidad_medida'][$row->umed_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id][$row->pmfi_tipo]['pmfi_meta'] = 0;                
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['unidad_medida'][$row->umed_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id]['distritos'][$row->dist_id][$row->pmfi_tipo]['pmfi_meta'] = 0;                
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['subactividad'][$row->sact_id]['unidad_medida'][$row->umed_id]['departamento'][$row->depa_id]['provincias'][$row->prov_id]['distritos'][$row->dist_id]['poa'][$row->poa_id]['meta_fisica'][$row->pmfi_tipo][$row->pmfi_orden]['pmfi_meta'] = $row->pmfi_meta;
                
                
            }
            
 
            
            
            //Calculando totales de cantidades físicas
 
            foreach($datos['programa_presupuestal']  as $ppre_id => $programa){
                
                foreach($programa['producto'] as $prod_id => $producto){
 
                    foreach($producto['actividad'] as $acti_id => $actividad){
 
                        foreach($actividad['subactividad'] as $sact_id => $subactividad){
 
                            foreach($subactividad['unidad_medida'] as $umed_id => $unidad){
                                if(!isset($datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['unidad_medida'][$umed_id]['M']['pmfi_meta']))
                                    $datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['unidad_medida'][$umed_id]['M']['pmfi_meta']=0;
                                if(!isset($datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['unidad_medida'][$umed_id]['E']['pmfi_meta']))
                                    $datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['unidad_medida'][$umed_id]['E']['pmfi_meta']=0;
 
                                foreach($unidad['departamento'] as $depa_id => $departamento) {
                                    if(!isset($departamento['M']['pmfi_meta']))
                                        $departamento['M']['pmfi_meta']=0;
                                    if(!isset($departamento['E']['pmfi_meta']))
                                        $departamento['E']['pmfi_meta']=0;
                                    
                                    foreach($departamento['provincias'] as $prov_id => $provincia){
                                        
                                        if(!isset($provincia['M']['pmfi_meta']))
                                            $provincia['M']['pmfi_meta']=0;
                                        if(!isset($provincia['E']['pmfi_meta']))
                                            $provincia['E']['pmfi_meta']=0;
 
                                        foreach ($provincia['distritos'] as $dist_id => $distrito) {
                                    
                                            if(!isset($distrito['M']['pmfi_meta']))
                                                $distrito['M']['pmfi_meta']=0;
                                            if(!isset($distrito['E']['pmfi_meta']))
                                                $distrito['E']['pmfi_meta']=0;
 
                                            foreach ($distrito['poa'] as $poa_id => $poa) {
                                    
                                                 
                                                if(isset($poa['meta_fisica']['M'])){
                                                    foreach ($poa['meta_fisica']['M'] as $pmfi_orden => $meta) {
                                                        
                                                        $distrito['M']['pmfi_meta']=$distrito['M']['pmfi_meta']+$meta['pmfi_meta'];
                                                    }
                                                    
 
                                                }
                                                if(isset($poa['meta_fisica']['E'])){
                                                    foreach ($poa['meta_fisica']['E'] as $pmfi_orden => $meta) {
 
                                                        $distrito['E']['pmfi_meta']=$distrito['E']['pmfi_meta']+$meta['pmfi_meta'];
                                                    }
                                                    
                                                }
                                                
                                            }
                                            //echo "<pre>";print_r($distrito['M']['pmfi_meta']);
                                            $provincia['M']['pmfi_meta']=$provincia['M']['pmfi_meta']+$distrito['M']['pmfi_meta'];
                                            $provincia['E']['pmfi_meta']=$provincia['E']['pmfi_meta']+$distrito['E']['pmfi_meta'];
                                            
                                        }
                                        //echo "<pre>";print_r($provincia['M']['pmfi_meta']);
                                        $departamento['M']['pmfi_meta']=$departamento['M']['pmfi_meta']+$provincia['M']['pmfi_meta'];
                                        $departamento['E']['pmfi_meta']=$departamento['E']['pmfi_meta']+$provincia['E']['pmfi_meta'];
                                        
                                    }
                                    $datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['unidad_medida'][$umed_id]['M']['pmfi_meta']+=$departamento['M']['pmfi_meta'];
                                    $datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['unidad_medida'][$umed_id]['E']['pmfi_meta']+=$departamento['E']['pmfi_meta'];
                                        
                                }
                                
                            }
 
                            
 
                        }
                        
 
                    }                    
 
                }            
 
            }
 
            //Calculando totales de cantidades presupuestales y financieras
            foreach($datos['programa_presupuestal']  as $ppre_id => $programa){
                
                foreach($programa['producto'] as $prod_id => $producto){
 
                    foreach($producto['actividad'] as $acti_id => $actividad){
 
                        foreach($actividad['subactividad'] as $sact_id => $subactividad){
                                if(!isset($datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['M']['ppre_importe']))
                                    $datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['M']['ppre_importe']=0;
                                if(!isset($datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['E']['ppre_importe']))
                                    $datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['E']['ppre_importe']=0;
                            
                                foreach($subactividad['departamento'] as $depa_id => $departamento) {
                                    
                                    
                                    foreach($departamento['provincias'] as $prov_id => $provincia){                                                                               
 
                                        foreach ($provincia['distritos'] as $dist_id => $distrito) {
                                                                               
 
                                            foreach ($distrito['poa'] as $poa_id => $poa) {                                                                                                                                    
                                                $distrito['poa_metaprepim']+=$poa['poa_metaprepim'];                                                       
                                                $distrito['poa_metaprepid']+=$poa['poa_metaprepid'];                                               
                                                $distrito['poa_metaprepig']+=$poa['poa_metaprepig'];
                                            }
                                            
                                            $provincia['poa_metaprepim']+=$distrito['poa_metaprepim'];
                                            $provincia['poa_metaprepid']+=$distrito['poa_metaprepid'];
                                            $provincia['poa_metaprepig']+=$distrito['poa_metaprepig'];
                                            
                                        }
                                        $departamento['poa_metaprepim']+=$provincia['poa_metaprepim'];
                                        $departamento['poa_metaprepid']+=$provincia['poa_metaprepid'];
                                        $departamento['poa_metaprepig']+=$provincia['poa_metaprepig'];
                                        
                                    }
                                    $datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['poa_metaprepim']+=$departamento['poa_metaprepim'];
                                    $datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['poa_metaprepid']+=$departamento['poa_metaprepid'];
                                    $datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['subactividad'][$sact_id]['poa_metaprepig']+=$departamento['poa_metaprepig'];
                                        
                                }
                                
                            
 
                            
 
                        }
                        
 
                    }                    
 
                }            
 
            }
           //echo "<pre>";
           //print_r($datos); 
           //die("test");
            
            foreach($datos['programa_presupuestal']  as $ppre_id => $programa){
                
                foreach($programa['producto'] as $prod_id => $producto){
 
                    foreach($producto['actividad'] as $acti_id => $actividad){
 
                        foreach($actividad['subactividad'] as $sact_id => $subactividad){
 
                            foreach($subactividad['unidad_medida'] as $umed_id => $unidad){
                               
                                $colIndex = PHPExcel_Cell::columnIndexFromString('A');
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);  
                                $rows[$count][$columnLetter]=$programa['ppre_codref'];//A
                                if($extension==EXT_EXCEL){
                                    $rows[$count][$columnLetter].=': '.$programa['ppre_nombre'];
                                }
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                $rows[$count][$columnLetter]=$producto['prod_codref'];//B
                                if($extension==EXT_EXCEL){
                                    $rows[$count][$columnLetter].=': '.$producto['prod_nombre'];
                                }
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                $rows[$count][$columnLetter]=$actividad['acti_codref'];//C
                                if($extension==EXT_EXCEL){
                                    $rows[$count][$columnLetter].=': '.$actividad['acti_nombre'];
                                }
                                $colIndex++;                              
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                $rows[$count][$columnLetter]=$subactividad['sact_nombre'];//D
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                $rows[$count][$columnLetter]=$unidad['umed_nombre'];//E
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                $rows[$count][$columnLetter]=$subactividad['ogru_nombre'];//F
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                $rows[$count][$columnLetter]=$subactividad['ofic_nombre'];//G
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                $rows[$count][$columnLetter]=$subactividad['usua_nombre'];//H
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                $rows[$count][$columnLetter]=$subactividad['rol_nombre'];//I
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);                                
                                $rows[$count][$columnLetter]=$unidad['M']['pmfi_meta'];//F
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                $rows[$count][$columnLetter]=$subactividad['poa_metaprepim'];//G
                                $total[$columnLetter]+=$rows[$count][$columnLetter];
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                $rows[$count][$columnLetter]=$unidad['E']['pmfi_meta'];//H
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                $rows[$count][$columnLetter]=$subactividad['poa_metaprepid'];//I
                                $total[$columnLetter]+=$rows[$count][$columnLetter];
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                $rows[$count][$columnLetter]=$subactividad['poa_metaprepig'];//J
                                $total[$columnLetter]+=$rows[$count][$columnLetter];
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                $rows[$count][$columnLetter]=($unidad['M']['pmfi_meta'] - $unidad['E']['pmfi_meta'] );//K
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                $rows[$count][$columnLetter]=0;//L
                                $total[$columnLetter]+=$rows[$count][$columnLetter];
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                $rows[$count][$columnLetter]=($subactividad['poa_metaprepim'] - $subactividad['poa_metaprepig']);//M
                                $total[$columnLetter]+=$rows[$count][$columnLetter];
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                if($unidad['M']['pmfi_meta']>0)
                                    $rows[$count][$columnLetter]=($unidad['E']['pmfi_meta']/$unidad['M']['pmfi_meta'])*100;//N
                                else
                                    $rows[$count][$columnLetter]=0;//N
                                $colIndex++;
                                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                if($subactividad['poa_metaprepim']>0)
                                    $rows[$count][$columnLetter]=($subactividad['poa_metaprepig']/$subactividad['poa_metaprepim'])*100;//O
                                else
                                    $rows[$count][$columnLetter]=0;//O
 
                                $total[$columnLetter]+=$rows[$count][$columnLetter];
                                
                                $count++;
                            }
 
                            $count++;
 
                        }
 
                        $count++;
 
                    }
 
                    $count++;
 
                }
 
                $count++;
 
            }
        }
       
       
 
        $start = 13;
        if(isset($rows)){
            foreach($rows as $row){
                $col=0;
                foreach($row as $column => $value){
                    
                    $this->excel->setActiveSheetIndex(0)                        
                                ->setCellValue($column.$start, $value);
                    
                    
                    
                    $this->excel->setActiveSheetIndex(0)
                                ->getStyle($column.$start)                        
                                ->getFont()->setSize(8);
 
 
                    if($col==0){
                        $this->excel->getActiveSheet(0)
                                ->getStyle($column.$start)
                                ->getNumberFormat()
                                ->setFormatCode('0000');
                    }
 
                    if( is_numeric($value) && $col>4 ){ 
                        $this->excel->setActiveSheetIndex(0)
                                ->getStyle($column.$start)
                                ->applyFromArray($BStyle);
 
                        $this->excel->getActiveSheet(0)
                                ->getStyle($column.$start)
                                ->getNumberFormat()
                                ->setFormatCode($numberFormat[$column]);
 
                        $this->excel->setActiveSheetIndex(0)
                                    ->getStyle($column.$start)
                                    ->getAlignment()
                                    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
                                    ->setWrapText(true);
                        $this->excel->getActiveSheet(0)
                                    ->getStyle($column.$start)
                                    ->getAlignment()
                                    ->setIndent(1);
                    }else { 
 
                        if($value==''){
                            $this->excel->setActiveSheetIndex(0)
                                        ->getStyle($column.$start)
                                        ->applyFromArray($CStyle);
                        }else{
                            
                            $this->excel->setActiveSheetIndex(0)
                                        ->getStyle($column.$start)
                                        ->applyFromArray($DStyle);
                        }
 
                        
                        $this->excel->setActiveSheetIndex(0)
                                    ->getStyle($column.$start)
                                    ->getAlignment()
                                    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                    ->setWrapText(true);
                        $this->excel->getActiveSheet(0)
                                    ->getStyle($column.$start)
                                    ->getAlignment()
                                    ->setIndent(1);
                    }
 
                    //echo " $column.$start $value" ;
                    $col++;
                }
 
                $start++;
                /*
                if($start>35)
                    break;
                */
                
            }
        }
 
        $this->excel->setActiveSheetIndex(0)
                    ->getStyle("A$start:I$start")
                    ->applyFromArray($EStyle);
        
        $this->excel->setActiveSheetIndex(0)
                    ->getStyle("R10:R12")
                    ->applyFromArray($FStyle);
                                     
 
 
        $this->excel->setActiveSheetIndex(0)                        
                    ->setCellValue('A'.($start+1), 'Totales');
 
        $this->excel->setActiveSheetIndex(0)
                    ->getStyle('A'.($start+1).':'.'I'.($start+1))
                    ->getAlignment()
                    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                    ->setWrapText(true);

        $this->excel->setActiveSheetIndex(0)
                        ->getStyle('A'.($start+1).':'.'I'.($start+1))
                        ->applyFromArray($BStyle); 

        $this->excel->setActiveSheetIndex(0)
             ->getStyle('A'.($start+1).':'.'I'.($start+1))                        
             ->getFont()->setSize(8);  
 
        $this->excel->setActiveSheetIndex(0)
             ->mergeCells('A'.($start+1).':'.'I'.($start+1));  
 

        $this->excel->setActiveSheetIndex(0)
                        ->getStyle('J'.($start+1))
                        ->applyFromArray($FStyle); 
        
        $colIndex = PHPExcel_Cell::columnIndexFromString('K');
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);    
 
        $this->excel->setActiveSheetIndex(0)                        
                    ->setCellValue($columnLetter.($start+1), $total[$columnLetter]);

        $this->excel->setActiveSheetIndex(0)
                        ->getStyle($columnLetter.($start+1))
                        ->applyFromArray($BStyle); 

        $colIndex+=2;
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);    
     
        $this->excel->setActiveSheetIndex(0)                        
                    ->setCellValue($columnLetter.($start+1), $total[$columnLetter]);


        $this->excel->setActiveSheetIndex(0)
                        ->getStyle($columnLetter.($start+1))
                        ->applyFromArray($BStyle); 

        $colIndex++;
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
      

        $this->excel->setActiveSheetIndex(0)                        
                    ->setCellValue($columnLetter.($start+1), $total[$columnLetter]);


        $this->excel->setActiveSheetIndex(0)
                        ->getStyle($columnLetter.($start+1))
                        ->applyFromArray($BStyle); 

        $colIndex+=2;
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);    

 
        $this->excel->setActiveSheetIndex(0)                        
                    ->setCellValue($columnLetter.($start+1), $total[$columnLetter]);


        $this->excel->setActiveSheetIndex(0)
                        ->getStyle($columnLetter.($start+1))
                        ->applyFromArray($BStyle); 

        $colIndex++;
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 


        $this->excel->setActiveSheetIndex(0)                        
                    ->setCellValue($columnLetter.($start+1), $total[$columnLetter]);


        $this->excel->setActiveSheetIndex(0)
                        ->getStyle($columnLetter.($start+1))
                        ->applyFromArray($BStyle); 

       $colIndex+=2;
       $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);


        if($total['K']>0)
            $total[$columnLetter]=($total['M']/$total['K'])*100;//M
        else
            $total[$columnLetter]=0;//M                   
 
        $this->excel->setActiveSheetIndex(0)                        
                    ->setCellValue($columnLetter.($start+1), $total[$columnLetter]);


        $this->excel->setActiveSheetIndex(0)
                        ->getStyle($columnLetter.($start+1))
                        ->applyFromArray($BStyle); 

        $this->excel->setActiveSheetIndex(0)
             ->getStyle('J'.($start+1))                        
             ->getFont()->setSize(8);

 
         $this->excel->getActiveSheet(0)
                     ->getStyle('J'.($start+1).':'.$columnLetter.($start+1))
                     ->getNumberFormat()
                     ->setFormatCode('#.00');
        
        $this->excel->setActiveSheetIndex(0)
                    ->getStyle('J'.($start+1).':'.$columnLetter.($start+1))
                    ->getAlignment()
                    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
                    ->setWrapText(true);
        $this->excel->getActiveSheet(0)
                    ->getStyle('J'.($start+1).':'.$columnLetter.($start+1))
                    ->getAlignment()
                    ->setIndent(1);
         $this->excel->setActiveSheetIndex(0)
                     ->getStyle('H'.($start+1).':'.$columnLetter.($start+1))                        
                     ->getFont()->setSize(8);
 
 
        /*                    
        $this->excel->setActiveSheetIndex(0)
                    ->mergeCells('A14:A24');
        */
        // Rename worksheet
        //die('test');
 
 
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $this->excel->setActiveSheetIndex(0);
        
        if($extension==EXT_EXCEL){
            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Reporte_3.xlsx"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');
 
            // If you're serving to IE over SSL, then the following may be needed
            header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
            header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header ('Pragma: public'); // HTTP/1.0
 
            
 
            $objWriter = $this->factory->createWriter($this->excel, 'Excel2007');
            //print_r($objWriter);
            $objWriter->save('php://output');
        }else{
        
            /*    
            $rendererName = PHPExcel_Settings::PDF_RENDERER_TCPDF;
            $rendererLibrary = 'tcpdf';
            $rendererLibraryPath = APPPATH.'third_party/'. $rendererLibrary;
            $filename='_Reporte_Usuarios_front'.'.pdf'; 
            PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath);
            */
            $rendererName = PHPExcel_Settings::PDF_RENDERER_DOMPDF;
            $rendererLibrary = 'dompdf';
            $rendererLibraryPath = APPPATH.'third_party/'. $rendererLibrary.'/src';
            $filename='Reporte_3'.'.pdf'; 
            PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath);
            /*
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
            header('Cache-Control: max-age=0'); //no cache
            */
            /*
            ini_set('max_execution_time', 3000);
            ini_set('memory_limit','16M');
            */
            // $objWriter = new PHPExcel_Writer_PDF($objPHPExcel);
            //print_r($this->excel);die();
            $objWriter = $this->factory->createWriter($this->excel, 'PDF'); 
            $objWriter->setSheetIndex(0);
            //print_r($objWriter);die();
            $objWriter->save('php://output');
        }
        exit;
       
    }

 
 

    public function formato4() {
        $formats = array("#,###", "#,###.0", "#,###.00");
      
        $datapost = $this->security->xss_clean($this->input->post());
        $extension = '';
        if(isset($datapost['extension'])){
            $extension = $datapost['extension'];
        }
        
        $ogru_nombre = ''; 
        $ofic_nombre = '';        
        $peri_id  = $datapost['peri_id'];
        $data_peri = $this->periodo_model->obtener_periodo_por_id($peri_id);
        if(isset($datapost['fecha_reporte']) && $datapost['fecha_reporte']!=''){
            $fecha_reporte = str_replace('/', '-', $datapost['fecha_reporte']);
            $fecha_reporte = date('Y-m-d', strtotime($fecha_reporte));
        }else{
            $fecha_reporte = date('Y-m-d', time());
        }
 
        $ogru_id_array  = array();
        $ofic_id_array  = array();
        $poli_id_array  = array();
        $ppre_id_array  = array();
        $prod_id_array  = array();
        $acti_id_array  = array();
        $sact_id_array  = array();
        $estr_id_array  = array();
 
        if(isset($datapost['ogru_id_array'])){
            $ogru_id_array  = $datapost['ogru_id_array'];
            if(count($ogru_id_array)==1){                
                $data_ogru = $this->oficinagrupo_model->getOficinagrupoByID($ogru_id_array[0]);                
                $ogru_nombre = $data_ogru->ogru_nombre;
            }
        }else{
            $ogru_nombre = "Todas";
        }
 
        if(isset($datapost['ofic_id_array'])){
            $ofic_id_array  = $datapost['ofic_id_array'];
            if(count($ofic_id_array)==1){
                $data_ofic = $this->oficina_model->getOficinaByID($ofic_id_array[0]);
                $ofic_nombre = $data_ofic->ofic_nombre;
            }
        }else{
            $ofic_nombre = "Todas";
        }
        
        if(isset($datapost['poli_id_array'])){
            $poli_id_array  = $datapost['poli_id_array'];
        }
 
        if(isset($datapost['ppre_id_array'])){
            $ppre_id_array  = $datapost['ppre_id_array'];
        }
 
        if(isset($datapost['prod_id_array'])){
            $prod_id_array  = $datapost['prod_id_array'];
        }
 
        if(isset($datapost['acti_id_array'])){
            $acti_id_array  = $datapost['acti_id_array'];
        }
 
        if(isset($datapost['sact_id_array'])){
            $sact_id_array  = $datapost['sact_id_array'];
        }

        if(isset($datapost['estr_id_array'])){
            $estr_id_array  = $datapost['estr_id_array'];
        }         


 
        
        $result =  $this->reporte_model->getReporteProyectos(  $ogru_id_array, $ofic_id_array, $peri_id, $fecha_reporte, 
                                            $poli_id_array, $ppre_id_array, $prod_id_array, 
                                            $acti_id_array , $sact_id_array , $estr_id_array );
        
        $rows=array();
        
        
       
       /** Error reporting */
        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        //date_default_timezone_set('Europe/London');
 
        // Set document properties
        $this->excel->getProperties()->setCreator("iorganizacional.com.pe")
                                     ->setLastModifiedBy("iorganizacional.com.pe")
                                     ->setTitle("Reporte 4")
                                     ->setSubject("Formato 4")
                                     ->setDescription("Programacion y Ejecucion de Metas.")
                                     ->setKeywords("programacion metas")
                                     ->setCategory("organizacional");

        $objDrawing = new PHPExcel_Worksheet_Drawing();
        $objDrawing->setName("name");
        $objDrawing->setDescription("Description");
        $objDrawing->setPath('public/assets/img/logo/agrorural-logo.png');
        $objDrawing->setCoordinates('P1');
        $objDrawing->setWorksheet($this->excel->setActiveSheetIndex(0));

        $this->excel->setActiveSheetIndex(0)
                        ->mergeCells('P1:Q1');
 
 
        // Add some data
 
        $this->excel->setActiveSheetIndex(0)
                    ->setCellValue('D3', "Reporte 4: Relación de Proyectos de Inversión \n Por Departamentos");
        $this->excel->setActiveSheetIndex(0)
                        ->mergeCells('D3:K4');
        $this->excel->setActiveSheetIndex(0)
                                    ->getStyle('D3:K4')
                                    ->getAlignment()
                                    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


        $this->excel->setActiveSheetIndex(0)
                    ->mergeCells('A1:B1');
 
        

        $this->excel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Reporte N° 4')
                    ->setCellValue('A6', 'Unidad Orgánica : ')
                    ->setCellValue('A7', 'Agencia Zonal : ')                    
                    ->setCellValue('P7', 'A la Fecha');
        $this->excel->setActiveSheetIndex(0)
                                    ->getStyle('A5:B7')
                                    ->getAlignment()
                                    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $this->excel->setActiveSheetIndex(0)
                    ->mergeCells('A6:B6');
        $this->excel->setActiveSheetIndex(0)
                    ->mergeCells('A7:B7');
        
        $fecha_reporte = date('d/m/Y', strtotime($fecha_reporte));                     
 
        $this->excel->setActiveSheetIndex(0)
                    ->setCellValue('C6', ' '.$ogru_nombre)
                    ->setCellValue('C7', ' '.$ofic_nombre)
                    ->setCellValue('G5', 'Periodo '.$data_peri->peri_nombre)
                    ->setCellValue('Q7', $fecha_reporte);

        $this->excel->setActiveSheetIndex(0)
                        ->mergeCells('G5:H5');

        $this->excel->setActiveSheetIndex(0)
                                    ->getStyle('G5:H5')
                                    ->getAlignment()
                                    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
 
        $this->excel->setActiveSheetIndex(0)
                                    ->getStyle('C5:C7')
                                    ->getAlignment()
                                    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    
 
        $this->excel->getActiveSheet()
                    ->getPageSetup()
                    ->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $this->excel->getActiveSheet()
                    ->getPageSetup()
                    ->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
       
        
        $BStyle = array(
          'borders' => array(
            'outline' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
            )
          )
        );
 
        $CStyle = array(
          'borders' => array(
            'left' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
            ),
            'right' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
            )
          )
        );
 
        $DStyle = array(
          'borders' => array(
            'top' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
            ),
            'left' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
            ),
            'right' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
            )
          )
        );
 
        $EStyle = array(
          'borders' => array(
            'top' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
            )
          )
        );
 
        $FStyle = array(
          'borders' => array(
            'left' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
            )
          )
        );
 
        $header = array();

        $colIndex = PHPExcel_Cell::columnIndexFromString('A');
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
 
        $header[$columnLetter] = array();
        $header[$columnLetter]['label'] = 'Programa Presupuestal';
        $header[$columnLetter]['start'] = 10;
        $header[$columnLetter]['end'] = 11;

        $colIndex++;
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
 
        $header[$columnLetter] = array();
        $header[$columnLetter]['label'] = 'Producto';
        $header[$columnLetter]['start'] = 10;
        $header[$columnLetter]['end'] = 11;

        $colIndex++;
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
 
        $header[$columnLetter] = array();
        $header[$columnLetter]['label'] = 'Actividad';
        $header[$columnLetter]['start'] = 10;
        $header[$columnLetter]['end'] = 11;

        $colIndex++;
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 

        $header[$columnLetter] = array();
        $header[$columnLetter]['label'] = 'SIAF';
        $header[$columnLetter]['start'] = 10;
        $header[$columnLetter]['end'] = 11;

        $colIndex++;
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 

        $header[$columnLetter] = array();
        $header[$columnLetter]['label'] = 'SNIP';
        $header[$columnLetter]['start'] = 10;
        $header[$columnLetter]['end'] = 11;

        $colIndex++;
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 

        $header[$columnLetter] = array();
        $header[$columnLetter]['label'] = 'Proyecto';
        $header[$columnLetter]['start'] = 10;
        $header[$columnLetter]['end'] = 11;

        $colIndex++;
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
 
        $header[$columnLetter] = array();
        $header[$columnLetter]['label'] = 'Subactividad';
        $header[$columnLetter]['start'] = 10;
        $header[$columnLetter]['end'] = 11;

        $colIndex++;
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
 
        $header[$columnLetter] = array();
        $header[$columnLetter]['label'] = 'Unidad Medida';
        $header[$columnLetter]['start'] = 10;
        $header[$columnLetter]['end'] = 11;

        $colIndex++;
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
        $endLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex+6);
        $startLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
 
       

        $endLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex);

        $numberFormat=array();
 
        $header[$columnLetter]['items'][$startLetter] = array();
        $header[$columnLetter]['items'][$startLetter]['label'] = 'Programación PIM';
        $header[$columnLetter]['items'][$startLetter]['start'] = 10;
        $header[$columnLetter]['items'][$startLetter]['end'] = $endLetter;
 
        $header[$columnLetter]['items'][$startLetter]['items'] = array();
        $header[$columnLetter]['items'][$startLetter]['items'][$startLetter]['label'] = 'Fisico';
        $header[$columnLetter]['items'][$startLetter]['items'][$startLetter]['start'] = 11;

        $numberFormat[$startLetter] = $formats[2];
 
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['label'] = "Presupuestal \n S/.";
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['start'] = 11;

        $numberFormat[$endLetter] = $formats[0];

        $colIndex+=2;
        $startLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
        $endLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex+1);
 
        $header[$columnLetter]['items'][$startLetter] = array();
        $header[$columnLetter]['items'][$startLetter]['label'] = 'Ejecución';
        $header[$columnLetter]['items'][$startLetter]['start'] = 10;
        $header[$columnLetter]['items'][$startLetter]['end'] = $endLetter;
 
        $header[$columnLetter]['items'][$startLetter]['items'] = array();
        $header[$columnLetter]['items'][$startLetter]['items'][$startLetter]['label'] = 'Fisico';
        $header[$columnLetter]['items'][$startLetter]['items'][$startLetter]['start'] = 11;

        $numberFormat[$startLetter] = $formats[2];

        $endLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex);
 
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['label'] = "Presupuestal \n S/.";
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['start'] = 11;

        $numberFormat[$endLetter] = $formats[0];

        $colIndex++;        
        $endLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex);

        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['label'] = "Financiera \n S/.";
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['start'] = 11;

        $numberFormat[$endLetter] = $formats[0];

        $colIndex+=2;
        $startLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
        $endLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex+1);
        
 
        $header[$columnLetter]['items'][$startLetter] = array();
        $header[$columnLetter]['items'][$startLetter]['label'] = '% Avance';
        $header[$columnLetter]['items'][$startLetter]['start'] = 10;
        $header[$columnLetter]['items'][$startLetter]['end'] = $endLetter;
 
        $header[$columnLetter]['items'][$startLetter]['items'] = array();
        $header[$columnLetter]['items'][$startLetter]['items'][$startLetter]['label'] = 'Fisico';
        $header[$columnLetter]['items'][$startLetter]['items'][$startLetter]['start'] = 11;

        $numberFormat[$startLetter] = $formats[2];

        $endLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex);
 
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['label'] = "Presupuestal \n S/.";
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['start'] = 11;

        $numberFormat[$endLetter] = $formats[0];

        $colIndex++;        
        $endLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex);

        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['label'] = "Financiera \n S/.";
        $header[$columnLetter]['items'][$startLetter]['items'][$endLetter]['start'] = 11;

        $numberFormat[$endLetter] = $formats[0];

        
        $colIndex+=2;
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
 
        $header[$columnLetter] = array();
        $header[$columnLetter]['label'] = 'Estado del Proyecto';
        $header[$columnLetter]['start'] = 10;
        $header[$columnLetter]['end'] = 11;
 
        
 
        foreach($header as $column => $data){
 
           
 
            if(isset($data['items'])){
 
                //$start = $column.$data['start'];
                //$end   = $data['end'].$data['start'];
 
                foreach ($data['items'] as $key => $value) {
                    //print_r($value);die();
                    $this->excel->setActiveSheetIndex(0)                        
                         ->setCellValue($key.$value['start'], $value['label']); 
 
                    $this->excel->setActiveSheetIndex(0)
                        ->getStyle($key.$value['start'].':'.$value['end'].$value['start'])
                        ->applyFromArray($BStyle); 
 
                    $this->excel->setActiveSheetIndex(0)
                        ->mergeCells($key.$value['start'].':'.$value['end'].$value['start']);  
 
                    $this->excel->setActiveSheetIndex(0)
                        ->getStyle($key.$value['start'].':'.$value['end'].$value['start'])                        
                        ->getFont()->setSize(8);                        
                    $this->excel->setActiveSheetIndex(0)
                        ->getStyle($key.$value['start'].':'.$value['end'].$value['start'])                        
                        ->getAlignment()
                        ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                        ->setWrapText(true);
                        if(isset($value['items'])){
                            foreach ($value['items'] as $subkey => $subvalue) {
                                $this->excel->getActiveSheet()
                                    ->getColumnDimension($subkey)
                                    ->setWidth(10);
                                $this->excel->setActiveSheetIndex(0)                        
                                     ->setCellValue($subkey.$subvalue['start'], $subvalue['label']); 
                                $this->excel->setActiveSheetIndex(0)
                                     ->getStyle($subkey.$subvalue['start'])                        
                                     ->getFont()->setSize(8);
                                $this->excel->setActiveSheetIndex(0)
                                    ->getStyle($subkey.$subvalue['start'])
                                    ->getAlignment()
                                    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                    ->setWrapText(true);
                                $this->excel->setActiveSheetIndex(0)
                                    ->getStyle($subkey.$subvalue['start'])
                                    ->applyFromArray($BStyle);
                            }
 
                        }
                }
 
            }else {
 
                $start = $column.$data['start'];
                $end   = $column.$data['end'];
                  // Add some data
                $this->excel->setActiveSheetIndex(0)                        
                            ->setCellValue( $start, $data['label']);
     
                $this->excel->getActiveSheet()
                            ->getColumnDimension($column)
                            ->setWidth(10);
     
                $this->excel->setActiveSheetIndex(0)
                            ->getStyle($start.':'.$end)
                            ->applyFromArray($BStyle);
     
                $this->excel->setActiveSheetIndex(0)
                            ->getStyle($start.':'.$end)                        
                            ->getFont()->setSize(8);
     
                $this->excel->setActiveSheetIndex(0)
                            ->getStyle($start.':'.$end)
                            ->getAlignment()
                            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                            ->setWrapText(true);
     
                $this->excel->setActiveSheetIndex(0)
                            ->mergeCells($start.':'.$end);
 
            }
            
            
            
            
    
        }   
         $count=0;
         $anterior=array();
         $total= array();
 
        $colIndex = PHPExcel_Cell::columnIndexFromString('I');
        
        for($i=0;$i<15;$i++){
            $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
            $total[$columnLetter]=0;            
            $colIndex++;
        }
         if(isset($result)){
            foreach ($result as $key => $row) {
                # code...
                
                $ubigeo[$row->depa_id]['depa_descripcion']=$row->depa_descripcion;
                $ubigeo[$row->depa_id]['provincias'][$row->prov_id]['prov_descripcion']=$row->prov_descripcion;
                $ubigeo[$row->depa_id]['provincias'][$row->prov_id]['distritos'][$row->dist_id]['dist_descripcion']=$row->dist_descripcion;
                $ubigeo[$row->depa_id]['provincias'][$row->prov_id]['distritos'][$row->dist_id]['column']='';
 
                $datos['programa_presupuestal'][$row->ppre_id]['ppre_codref'] = $row->ppre_codref;
                $datos['programa_presupuestal'][$row->ppre_id]['ppre_nombre'] = ucwords(mb_strtolower($row->ppre_nombre, 'UTF-8'));                
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['prod_codref'] = $row->prod_codref;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['prod_nombre'] = ucwords(mb_strtolower($row->prod_nombre, 'UTF-8'));
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['acti_codref'] = $row->acti_codref;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['acti_nombre'] = ucwords(mb_strtolower($row->acti_nombre, 'UTF-8'));
                
                //Almacenando datos del poa
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['poa'][$row->poa_id]['poa_siaf']=$row->poa_siaf;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['poa'][$row->poa_id]['poa_snip']=$row->poa_snip;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['poa'][$row->poa_id]['poa_nomproyecto']=$row->poa_nomproyecto;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['poa'][$row->poa_id]['epro_nombre']=$row->epro_nombre;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['poa'][$row->poa_id]['poa_metafispia']=$row->poa_metafispia;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['poa'][$row->poa_id]['poa_metaprepia']=$row->poa_metaprepia;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['poa'][$row->poa_id]['poa_metafispim']=$row->poa_metafispim;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['poa'][$row->poa_id]['poa_metaprepim']=$row->poa_metaprepim;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['poa'][$row->poa_id]['poa_metaprepid']=$row->poa_metaprepid;
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['poa'][$row->poa_id]['poa_metaprepig']=$row->poa_metaprepig;
                               
                
                //Almacenando datos físicos
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['poa'][$row->poa_id]['subactividad'][$row->sact_id]['sact_nombre'] = ucwords(mb_strtolower($row->sact_nombre, 'UTF-8'));
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['poa'][$row->poa_id]['subactividad'][$row->sact_id]['unidad_medida'][$row->umed_id]['umed_nombre'] = ucwords(mb_strtolower($row->umed_nombre, 'UTF-8'));                
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['poa'][$row->poa_id]['subactividad'][$row->sact_id]['unidad_medida'][$row->umed_id]['meta_fisica'][$row->pmfi_tipo]['pmfi_meta'] = 0;//Nivel de línea
                $datos['programa_presupuestal'][$row->ppre_id]['producto'][$row->prod_id]['actividad'][$row->acti_id]['poa'][$row->poa_id]['subactividad'][$row->sact_id]['unidad_medida'][$row->umed_id]['meta_fisica'][$row->pmfi_tipo]['items'][$row->pmfi_orden] = $row->pmfi_meta;//Nivel de acumulacion
                                
            }
                                           

            //die("test");
 
            //Calculando totales de cantidades físicas
             foreach($datos['programa_presupuestal']  as $ppre_id => $programa){
                
                foreach($programa['producto'] as $prod_id => $producto){
 
                    foreach($producto['actividad'] as $acti_id => $actividad){
 
                        foreach($actividad['poa'] as $poa_id => $poa){

                            foreach ($poa['subactividad'] as $sact_id => $subactividad) {
 
                                foreach($subactividad['unidad_medida'] as $umed_id => $unidad){
                                    if(!isset($datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['poa'][$poa_id]['subactividad'][$sact_id]['unidad_medida'][$umed_id]['meta_fisica']['M']['pmfi_meta']))
                                        $datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['poa'][$poa_id]['subactividad'][$sact_id]['unidad_medida'][$umed_id]['meta_fisica']['M']['pmfi_meta']=0;
                                    
                                    if(!isset($unidad['meta_fisica']['M']['items']))
                                        $unidad['meta_fisica']['M']['items']=array();

                                    foreach($unidad['meta_fisica']['M']['items'] as $orden => $item) {                                    

                                        $datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['poa'][$poa_id]['subactividad'][$sact_id]['unidad_medida'][$umed_id]['meta_fisica']['M']['pmfi_meta']+=$item;
                                        
                                            
                                    }

                                    if(!isset($datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['poa'][$poa_id]['subactividad'][$sact_id]['unidad_medida'][$umed_id]['meta_fisica']['E']['pmfi_meta']))
                                        $datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['poa'][$poa_id]['subactividad'][$sact_id]['unidad_medida'][$umed_id]['meta_fisica']['E']['pmfi_meta']=0;

                                    if(!isset($unidad['meta_fisica']['E']['items']))
                                        $unidad['meta_fisica']['E']['items']=array();

                                    foreach($unidad['meta_fisica']['E']['items'] as $orden => $item) {                                   
                                                                                                                                                   
                                        $datos['programa_presupuestal'][$ppre_id]['producto'][$prod_id]['actividad'][$acti_id]['poa'][$poa_id]['subactividad'][$sact_id]['unidad_medida'][$umed_id]['meta_fisica']['E']['pmfi_meta']+=$item;
                                            
                                    }
                                    
                                }

                            }                            
 
                        }
                        
 
                    }                    
 
                }            
 
            }
           //echo "<pre>";
           //print_r($datos); 
           //die("test");
            
            foreach($datos['programa_presupuestal']  as $ppre_id => $programa){
                
                foreach($programa['producto'] as $prod_id => $producto){
 
                    foreach($producto['actividad'] as $acti_id => $actividad){
 
                        foreach($actividad['poa'] as $poa_id => $poa){

                            foreach ($poa['subactividad'] as $sact_id => $subactividad) {

                                $asigno_poa_metaprepim = 0;
                                $asigno_poa_metaprepid = 0;
                                $asigno_poa_metaprepig = 0;
 
                                foreach($subactividad['unidad_medida'] as $umed_id => $unidad){
                                   
                                    $colIndex = PHPExcel_Cell::columnIndexFromString('A');
                                    $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);                                
                                    $rows[$count][$columnLetter]=$programa['ppre_codref'].': '.$programa['ppre_nombre'];//A
                                    $colIndex++;
                                    $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                    $rows[$count][$columnLetter]=$producto['prod_codref'].': '.$producto['prod_nombre'];//B
                                    $colIndex++;
                                    $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                    $rows[$count][$columnLetter]=$actividad['acti_codref'].': '.$actividad['acti_nombre'];//C
                                    $colIndex++;
                                    $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                    $rows[$count][$columnLetter]=$poa['poa_siaf'];//D
                                    $colIndex++;
                                    $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                    $rows[$count][$columnLetter]=$poa['poa_snip'];//E
                                    $colIndex++;
                                    $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                    $rows[$count][$columnLetter]=$poa['poa_nomproyecto'];//F
                                    $colIndex++;
                                    $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                    $rows[$count][$columnLetter]=$subactividad['sact_nombre'];//G
                                    $colIndex++;
                                    $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                    $rows[$count][$columnLetter]=$unidad['umed_nombre'];//H
                                    $colIndex++;
                                    $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
                                    $rows[$count][$columnLetter]=$unidad['meta_fisica']['M']['pmfi_meta'];//I
                                    $colIndex++;
                                    $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                    $rows[$count][$columnLetter]=0;//J
                                    if($asigno_poa_metaprepim==0){
                                        $rows[$count][$columnLetter]=$poa['poa_metaprepim'];//J
                                        $asigno_poa_metaprepim++;
                                    }
                                    $total[$columnLetter]+=$rows[$count][$columnLetter];
                                    $colIndex++;
                                    $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                    $rows[$count][$columnLetter]=$unidad['meta_fisica']['E']['pmfi_meta'];//K
                                    $colIndex++;
                                    $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                    $rows[$count][$columnLetter]=0;//L
                                    if($asigno_poa_metaprepid==0){
                                        $rows[$count][$columnLetter]=$poa['poa_metaprepid'];//L
                                        $asigno_poa_metaprepid++;
                                    }
                                    $total[$columnLetter]+=$rows[$count][$columnLetter];
                                    $colIndex++;
                                    $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                    $rows[$count][$columnLetter]=0;//M
                                    if($asigno_poa_metaprepig==0){
                                        $rows[$count][$columnLetter]=$poa['poa_metaprepig'];//M
                                        $asigno_poa_metaprepig++;
                                    }
                                    $total[$columnLetter]+=$rows[$count][$columnLetter];
                                    $colIndex++;
                                    $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
                                    $rows[$count][$columnLetter]=0;//N
                                    if($rows[$count][PHPExcel_Cell::stringFromColumnIndex($colIndex-6)]>0){
                                        $rows[$count][$columnLetter]=100*$rows[$count][PHPExcel_Cell::stringFromColumnIndex($colIndex-4)]/$rows[$count][PHPExcel_Cell::stringFromColumnIndex($colIndex-6)];//N
                                    }
                                    $colIndex++;
                                    $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                    $rows[$count][$columnLetter]=0;//O
                                    if($rows[$count][PHPExcel_Cell::stringFromColumnIndex($colIndex-6)]>0){
                                        $rows[$count][$columnLetter]=100*$rows[$count][PHPExcel_Cell::stringFromColumnIndex($colIndex-4)]/$rows[$count][PHPExcel_Cell::stringFromColumnIndex($colIndex-6)];//N
                                    }
                                    $colIndex++;
                                    $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);
                                    $rows[$count][$columnLetter]=0;//P
                                    $colIndex++;
                                    $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
                                    $rows[$count][$columnLetter]=$poa['epro_nombre'];//Q
                                   
                                    
                                    
                                    $count++;
                                }

                            $count++;

                        }
 
                            $count++;
 
                        }
 
                        $count++;
 
                    }
 
                    $count++;
 
                }
 
                $count++;
 
            }
        }
       
       //die("test");
 
        $start = 12;
        if(isset($rows)){
            foreach($rows as $row){
                $col=0;
                foreach($row as $column => $value){
                    
                    $this->excel->setActiveSheetIndex(0)                        
                                ->setCellValue($column.$start, $value);
                    
                    
                    
                    $this->excel->setActiveSheetIndex(0)
                                ->getStyle($column.$start)                        
                                ->getFont()->setSize(8);
 
 
                    if($col==0){
                        $this->excel->getActiveSheet(0)
                                ->getStyle($column.$start)
                                ->getNumberFormat()
                                ->setFormatCode('0000');
                    }
 
                    if( is_numeric($value) && $col>4 ){ 
                        $this->excel->setActiveSheetIndex(0)
                                ->getStyle($column.$start)
                                ->applyFromArray($BStyle);
 
                        $this->excel->getActiveSheet(0)
                                ->getStyle($column.$start)
                                ->getNumberFormat()
                                ->setFormatCode($numberFormat[$column]);
 
                        $this->excel->setActiveSheetIndex(0)
                                    ->getStyle($column.$start)
                                    ->getAlignment()
                                    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
                                    ->setWrapText(true);
                        $this->excel->getActiveSheet(0)
                                    ->getStyle($column.$start)
                                    ->getAlignment()
                                    ->setIndent(1);
                    }else { 
 
                       
                            
                            $this->excel->setActiveSheetIndex(0)
                                        ->getStyle($column.$start)
                                        ->applyFromArray($BStyle);
                        
 
                        
                        $this->excel->setActiveSheetIndex(0)
                                    ->getStyle($column.$start)
                                    ->getAlignment()
                                    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                    ->setWrapText(true);
                        $this->excel->getActiveSheet(0)
                                    ->getStyle($column.$start)
                                    ->getAlignment()
                                    ->setIndent(1);
                    }
 
                    //echo " $column.$start $value" ;
                    $col++;
                }
 
                $start++;
                /*
                if($start>35)
                    break;
                */
                
            }
        }
 
        $this->excel->setActiveSheetIndex(0)
                    ->getStyle("A$start:E$start")
                    ->applyFromArray($EStyle);
        
        $this->excel->setActiveSheetIndex(0)
                    ->getStyle("N10:N12")
                    ->applyFromArray($FStyle);

        
        $this->excel->setActiveSheetIndex(0)
                    ->getStyle("R10:R12")
                    ->applyFromArray($FStyle);
                                     
 
 
        $this->excel->setActiveSheetIndex(0)                        
                    ->setCellValue('A'.($start+1), 'Totales');
 
        $this->excel->setActiveSheetIndex(0)
                    ->getStyle('A'.($start+1).':'.'H'.($start+1))
                    ->getAlignment()
                    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                    ->setWrapText(true);

        $this->excel->setActiveSheetIndex(0)
                        ->getStyle('A'.($start+1).':'.'H'.($start+1))
                        ->applyFromArray($BStyle); 

        $this->excel->setActiveSheetIndex(0)
             ->getStyle('A'.($start+1).':'.'H'.($start+1))                        
             ->getFont()->setSize(8);  
 
        $this->excel->setActiveSheetIndex(0)
             ->mergeCells('A'.($start+1).':'.'H'.($start+1));  

        $colIndex = PHPExcel_Cell::columnIndexFromString('J');
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);    
 
        $this->excel->setActiveSheetIndex(0)                        
                    ->setCellValue($columnLetter.($start+1), $total[$columnLetter]);

        $this->excel->setActiveSheetIndex(0)
                        ->getStyle($columnLetter.($start+1))
                        ->applyFromArray($BStyle); 

        $colIndex+=2;
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);    
     
        $this->excel->setActiveSheetIndex(0)                        
                    ->setCellValue($columnLetter.($start+1), $total[$columnLetter]);


        $this->excel->setActiveSheetIndex(0)
                        ->getStyle($columnLetter.($start+1))
                        ->applyFromArray($BStyle); 

        $colIndex++;
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 
      

        $this->excel->setActiveSheetIndex(0)                        
                    ->setCellValue($columnLetter.($start+1), $total[$columnLetter]);

        $this->excel->setActiveSheetIndex(0)
                        ->getStyle($columnLetter.($start+1))
                        ->applyFromArray($BStyle); 

        $colIndex+=2;
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);  

        $this->excel->setActiveSheetIndex(0)
                        ->getStyle($columnLetter.($start+1))
                        ->applyFromArray($BStyle); 

        if($total['J']>0)
            $total[$columnLetter]=($total['L']/$total['J'])*100;//M
        else
            $total[$columnLetter]=0;//M 

        $this->excel->setActiveSheetIndex(0)                        
                    ->setCellValue($columnLetter.($start+1), $total[$columnLetter]);

        $colIndex++;
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1);  
        
        
        $total[$columnLetter]=0;//M                   
 
        $this->excel->setActiveSheetIndex(0)                        
                    ->setCellValue($columnLetter.($start+1), $total[$columnLetter]);


        $this->excel->setActiveSheetIndex(0)
                        ->getStyle($columnLetter.($start+1))
                        ->applyFromArray($BStyle); 
   
        
       
        /*
        $this->excel->setActiveSheetIndex(0)                        
                    ->setCellValue('F'.($start+1), 'Totales');
 
        $this->excel->setActiveSheetIndex(0)
                    ->getStyle('F'.($start+1))
                    ->getAlignment()
                    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                    ->setWrapText(true);
         
        $this->excel->setActiveSheetIndex(0)                        
                    ->setCellValue('G'.($start+1), $total['G']);
 
        $this->excel->setActiveSheetIndex(0)                        
                    ->setCellValue('I'.($start+1), $total['I']);
 
        $this->excel->setActiveSheetIndex(0)                        
                    ->setCellValue('K'.($start+1), $total['K']);
 
        $this->excel->setActiveSheetIndex(0)                        
                    ->setCellValue('M'.($start+1), $total['M']);
        */
         $this->excel->getActiveSheet(0)
                     ->getStyle('G'.($start+1).':'.'M'.($start+1))
                     ->getNumberFormat()
                     ->setFormatCode('#.00');
        
        $this->excel->setActiveSheetIndex(0)
                    ->getStyle('G'.($start+1).':'.'M'.($start+1))
                    ->getAlignment()
                    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
                    ->setWrapText(true);
        $this->excel->getActiveSheet(0)
                    ->getStyle('G'.($start+1).':'.'M'.($start+1))
                    ->getAlignment()
                    ->setIndent(1);
         $this->excel->setActiveSheetIndex(0)
                     ->getStyle('F'.($start+1).':'.'M'.($start+1))                        
                     ->getFont()->setSize(8);
 
 
        /*                    
        $this->excel->setActiveSheetIndex(0)
                    ->mergeCells('A14:A24');
        */
        // Rename worksheet
        //die('test');
 
 
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $this->excel->setActiveSheetIndex(0);
        
        if($extension==EXT_EXCEL){
            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Reporte_4.xlsx"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');
 
            // If you're serving to IE over SSL, then the following may be needed
            header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
            header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header ('Pragma: public'); // HTTP/1.0
 
            
 
            $objWriter = $this->factory->createWriter($this->excel, 'Excel2007');
            //print_r($objWriter);
            $objWriter->save('php://output');
        }else{
        
            /*    
            $rendererName = PHPExcel_Settings::PDF_RENDERER_TCPDF;
            $rendererLibrary = 'tcpdf';
            $rendererLibraryPath = APPPATH.'third_party/'. $rendererLibrary;
            $filename='_Reporte_Usuarios_front'.'.pdf'; 
            PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath);
            */
            $rendererName = PHPExcel_Settings::PDF_RENDERER_DOMPDF;
            $rendererLibrary = 'dompdf';
            $rendererLibraryPath = APPPATH.'third_party/'. $rendererLibrary.'/src';
            $filename='Reporte_4'.'.pdf'; 
            PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath);
            /*
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
            header('Cache-Control: max-age=0'); //no cache
            */
            /*
            ini_set('max_execution_time', 3000);
            ini_set('memory_limit','16M');
            */
            // $objWriter = new PHPExcel_Writer_PDF($objPHPExcel);
            //print_r($this->excel);die();
            $objWriter = $this->factory->createWriter($this->excel, 'PDF'); 
            $objWriter->setSheetIndex(0);
            //print_r($objWriter);die();
            $objWriter->save('php://output');
        }
        exit;
       
    }
 
 
    function validateData(){
        //$this->centropoblado_model->validateProvince();
        //$this->centropoblado_model->validateDistrict();
        //$this->centropoblado_model->fillCenpo();
        exit;
    }
 
 
 
    
 
 
}