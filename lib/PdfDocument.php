<?php
if (!defined("DS")) define("DS", "/");
if (!defined("NEWFPDF_FONTPATH")) define("NEWFPDF_FONTPATH", dirname(__FILE__) . DS . 'font' . DS);
if (!defined("PARAGRAPH_STRING")) define("PARAGRAPH_STRING", "~~~");

require_once(dirname(__FILE__) . DS . 'fpdf' . DS . 'fpdf.php');
require_once(dirname(__FILE__) . DS . 'Cell.php');
require_once(dirname(__FILE__) . DS . 'Line.php');
require_once(dirname(__FILE__) . DS . 'Group.php');
require_once(dirname(__FILE__) . DS . 'GroupDetail.php');
require_once(dirname(__FILE__) . DS . 'Image.php');
require_once(dirname(__FILE__) . DS . 'Title.php');
require_once(dirname(__FILE__) . DS . 'Verse.php');
require_once(dirname(__FILE__) . DS . 'WaterMark.php');
require_once(dirname(__FILE__) . DS . 'BarCode128ABC.php');
require_once(dirname(__FILE__) . DS . 'BarCodeI25.php');
require_once(dirname(__FILE__) . DS . 'Digit.php');
require_once(dirname(__FILE__) . DS . 'Checkbox.php');

class PdfDocument extends FPDF {

    public $CurUnit = null;
    
    /**
    * Arquivo xml que contém as características do documento
    * Informar caminho e nome completo
    */
    public $templateFile = null;
    
    /**
    * Matriz que contém as características (config, sessões e nodes) do documento
    * Informado diretamente ou importado do templateFile via setTemplate() 
    */
    public $template = array();
    
    /**
    * Cada node pode ser uma tag do xml informado em templateFile
    * Os nodes são divididos por sessões: header, body e footer
    * Tipos de node: cell, image, digit, checkbox, BarCode128ABC, BarCodeI25, title, line, group e verse
    */
    public $nodes = null;

    /**
    * Cada chave do config pode ser um atributo do node
    * Chaves que não forem especificadas herdam valores de seus nodes pais, ou que forem definidas em configure()
    * 
    * Tipos de config:
    * 
    * border -> Borda (0, 1, R, L, T, B)
    * align -> Alinhamento (L, C, R) 
    * fill -> Cor de preenchimento do node (número que equivale a cor)
    * fontFamily -> Fonte (Arial, Times, Courier, Symbol, ZapfDingbats) 
    * fontStyle -> Estilo da fonte (B, I, N, U)
    * fontSizePt -> Tamanho da fonte em pontos
    * titleFontFamily -> Fonte do título (Arial, Times, Courier, Symbol, ZapfDingbats) 
    * titleFontStyle -> Estilo da fonte do título (B, I, N, U)
    * titleFontSizePt -> Tamanho da fonte do título em pontos
    * lineWidth -> Largura do node
    * lineHeight -> Altura do node
    * x -> Posição horizontal do node
    * y -> Posição vertical do node
    * relativeX -> Adicionado a posição horizontal do node
    * relativeY -> Adicionado a posição vertical no node
    * text -> Conteúdo do node (conteúdo fixo)
    * fieldName -> Campo origem do conteúdo do node (formato: Model.fieldname)
    * title -> Título do node (cria objeto title)
    * groupSpacing -> Espaço adicionado depois de impresso o group (somente group)
    * margin -> Margem esquerda, direita e superior do documento (somente config do document),
    * decimal -> Formata o conteúdo com o número de casas decimais informado Ex: 9 com "decimal = 2" imprime "9,00"
    */
    public $config = array('border' => null, 'align' => 'L', 'fill' => false);
    
    /**
    * Registros de conteúdo do detalhe do documento
    */
    public $records = array();
    
    /**
    * Registro do detalhe selecionado
    */
    public $record = array();
    
    /**
    * Registro do cabeçalho do documento
    * Referenciado em config['fieldName'] = 'Header.field'
    */
    public $header = array();
    public $fillOn = true;    
    public $angle = 0;
    
    public function __construct($orientation = 'P', $unit = 'mm', $size = 'A4') {
        parent::__construct($orientation, $unit, $size);
        $this->CurUnit = $unit;
    }
    
    public function create($settings) {
        $documentPdf = false;
        $this->setup($settings);
        if (!empty($this->templateFile)) {
            $this->setTemplate();
        }
        if (!empty($this->template) || !empty($this->records)) {
            if (empty($this->records)) {
                $this->records[] = null;
            }
            foreach ($this->records as $record) {
                $this->record = $record;
                $this->setRecordTemplate();
                $this->configure();
                $this->AddPage();
                $this->Body();                
            }
            if (empty($this->fileName)) {
                $documentPdf = $this->Output();            
            } else {
                $documentPdf = $this->saveFile($this->fileName);
            }                        
        }
        
        return $documentPdf;
    }
    
    public function setup($settings) {
        $orientation = $this->CurOrientation;
        if (!empty($settings['orientation'])) {
            $orientation = $settings['orientation'];
        }
        $unit = $this->CurUnit;
        if (!empty($settings['unit'])) {
            $unit = $settings['unit'];
        }        
        $size = $this->CurPageSize;
        if (!empty($settings['size'])) {
            $size = $settings['size'];
        }        
        $this->__construct($orientation, $unit, $size); 
        foreach ($settings as $attribute => $value) {
            $this->{$attribute} = $value;
        } 
        $this->AliasNbPages();         
    }    
    
    public function setRecordTemplate() {
        if (!empty($this->record['templateFile'])) {
            $this->templateFile = $this->record['templateFile'];
            $this->setTemplate();
        }    
    }
    
    public function setTemplate() {
        require_once('Xml.php');
        if (!is_array($this->templateFile)) {
            $template = Xml::toArray(Xml::build($this->templateFile));             
        } else {
            $template = array('template' => array());
            foreach ($this->templateFile as $session => $templateFile) {
                if (is_array($templateFile)) {
                    foreach ($templateFile as $subTemplateFile) {
                        $template['template'] = array_merge_recursive($template['template'], Xml::toArray(Xml::build($subTemplateFile)));                                                                         
                    }
                } else {
                    $template['template'] = array_merge($template['template'], Xml::toArray(Xml::build($templateFile)));                                                 
                }
            }
        }
        if (!empty($template['template'])) {
            $this->template = $this->normalizeTemplate($template['template']);               
        }
    }
    
    public function normalizeTemplate($configs) {
        foreach ($configs as $configKey => $configValue) {
            $oldKey = $configKey;
            if ($configKey == '@') {
                $configKey = 'text';
            } else {
                if (strlen($configKey) > 1 && substr($configKey, 0, 1) == '@') {
                    $configKey = substr($configKey, 1, strlen($configKey) - 1);
                }
            }
            unset($configs[$oldKey]);
            if (is_array($configValue)) {
                $configs[$configKey] = $this->normalizeTemplate($configValue);                                        
            } else {
                $configs[$configKey] = $configValue;
            }
        }
        
        return $configs;
    }

    public function configure() {
        if (!empty($this->template['config'])) {
            $this->config = array_merge($this->config, $this->template['config']);            
        }
        if (!empty($this->FontFamily)) {
            $fontFamily = $this->FontFamily;            
        } else {
            $fontFamily = 'Arial';
        }
        $fontStyle = $this->FontStyle;
        $fontSizePt = $this->FontSizePt;
        if (isset($this->config['fontSizePt'])) {
            $fontSizePt = $this->config['fontSizePt'];
        } else {
            $this->config['fontSizePt'] = $fontSizePt;
        }
        if (isset($this->config['fontStyle'])) {
            $fontStyle = $this->config['fontStyle'];
        } else {
            $this->config['fontStyle'] = $fontStyle;
        }
        if (isset($this->config['fontFamily'])) {
            $fontFamily = $this->config['fontFamily'];
        } else {
            $this->config['fontFamily'] = $fontFamily;
        }
        $this->SetFont($fontFamily, $fontStyle, $fontSizePt);
        if (isset($this->config['fill'])) {
            $this->SetFillColor($this->config['fill'], $this->config['fill'], $this->config['fill']);                                    
        }
        if (!isset($this->config['lineHeight'])) {
            $this->config['lineHeight'] = $this->FontSize + 1;            
        }
        if (isset($this->config['margin'])) {
            $this->SetMargins($this->config['margin'], $this->config['margin']);
        }
        if (!isset($this->config['lineWidth'])) {
            $this->config['lineWidth'] = $this->w - $this->lMargin - $this->rMargin;
        }
        $this->nodes = array('header' => array(), 'body' => array(), 'footer' => array());
        $this->SetAutoPageBreak(false);
    }
    
    public function Body() {
        $this->addNodes('body');
    }
    
    public function Header() {
        $this->addNodes('header');
    }

    public function Footer() {
        $this->addNodes('footer');
    } 

    public function addNodes($session) {
        if (!empty($this->template[$session])) {
            foreach ($this->template[$session] as $nodes) {
                $this->setRecordTemplate();
                if (is_array($nodes)) {
                    foreach ($nodes as $type => $config) {                
                        $type = ucfirst($type);
                        if (class_exists($type)) {
                            $this->nodes[$session][] = new $type($this, $config);                        
                        }
                    }                    
                }
            }                        
        }
    } 

    public function getPdf() {
        return $this;
    }

    public function getLineWidth() {
        return $this->config['lineWidth'];
    }

    public function getLineHeight() {
        return $this->config['lineHeight'];
    }
    
    public function setLasth($lasth) {
        $this->lasth = $lasth;
    }

    public function getLasth() {
        return $this->lasth;
    }

    public function getLeftMargin() {
        return $this->lMargin;
    }

    public function getFontSize() {
        return $this->FontSize;
    }

    public function getBorder() {
        return $this->config['border'];
    }

    public function getAlign() {
        return $this->config['align'];
    }

    public function getFill() {
        return $this->config['fill'];
    }

    public function getTitleFontFamily() {
        $titleFontFamily = $this->getFontFamily();
        if (isset($this->config['titleFontFamily'])) {
            $titleFontFamily = $this->config['titleFontFamily'];
        }
        
        return $titleFontFamily;
    }

    public function getTitleFontSizePt() {
        $titleFontSizePt = $this->getFontSizePt();
        if (isset($this->config['titleFontSizePt'])) {
            $titleFontSizePt = $this->config['titleFontSizePt'];
        }
        
        return $titleFontSizePt;
    }

    public function getTitleFontStyle() {
        $titleFontStyle = $this->getFontStyle();
        if (isset($this->config['titleFontStyle'])) {
            $titleFontStyle = $this->config['titleFontStyle'];
        }
        
        return $titleFontStyle;
    }

    public function getFontFamily() {
        return $this->config['fontFamily'];
    }

    public function getFontSizePt() {
        return $this->config['fontSizePt'];
    }

    public function getFontStyle() {
        return $this->config['fontStyle'];
    }
    
    public function getGroupSpacing() {
        return isset($this->config['groupSpacing']) ? $this->config['groupSpacing'] : 0;
    }

    public function Cell($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false, $link = '') {
        parent::Cell($w, $h, $this->convertToISO($txt), $border, $ln, $align, $fill, $link);
    }

    public function MultiCell($w, $h, $txt, $border = 0, $align = 'J', $fill = false) {
        parent::MultiCell($w, $h, $this->convertToISO($txt), $border, $align, $fill);
    }

    public function convertToISO($text) {
        if (mb_detect_encoding($text, 'auto', true) == "UTF-8") {
            $text = @iconv('UTF-8', 'ISO-8859-1//IGNORE', $text);
        }
        
        return $text;
    }
    
    public function saveFile($pathFileName) {
        $this->Output($pathFileName, 'F');
        
        return file_exists($this->fileName);
    }

    public function Output($pathFileName = null, $destination = "I", $isUTF8 = false) {
        if (empty($pathFileName)) {
            $pathFileName = 'document_' . date("Ymd_His") . '.pdf';
        }
        
        return parent::Output($pathFileName, $destination, $isUTF8);
    }
    
    public function Rotate($angle, $x = -1, $y = -1) {
        if ($x == -1) $x = $this->x;
        if ($y == -1) $y = $this->y;
        if ($this->angle != 0) $this->_out('Q');
        $this->angle = $angle;
        if ($angle != 0) {
            $angle *= M_PI / 180;
            $c = cos($angle);
            $s = sin($angle);
            $cx = $x * $this->k;
            $cy = ($this->h - $y) * $this->k;
            $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm', $c, $s, -$s, $c, $cx, $cy, -$cx, -$cy));
        }
    }
    
    public function _endpage() {
        if ($this->angle != 0) {
            $this->angle = 0;
            $this->_out('Q');
        }
        parent::_endpage();
    }
        
}

?>