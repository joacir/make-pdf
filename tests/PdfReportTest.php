<?php
require_once('../lib/PdfReport.php');
if (!defined("FIXTURES_PATH")) define("FIXTURES_PATH", dirname(__FILE__) . DS . 'fixtures' . DS);
if (!defined("RESULTS_PATH")) define("RESULTS_PATH", dirname(__FILE__) . DS . 'results' . DS);

class PdfReportTest extends PHPUnit_Framework_TestCase {
       
    public $report = null;
    
    public $reportConfig = array(
        'border' => 1, 
        'align' => 'L', 
        'fontFamily' => 'Arial',
        'fontSizePt' => 10 
    );
    
    public $reportHeader = array(
        array('line' => array(                                
            'lineHeight' => 20,
            array('image' => array('fieldName' => 'Header.image', 'lineWidth' => 20)),
            array('cell' => array('fieldName' => 'Header.title'))
        ))
    );

    public $reportFooter = array(
        array('line' => array(                                
            array('cell' => array('text' => 'Some footer text cell 1')),
            array('cell' => array('text' => 'Some footer text cell 2')),
        )),
        array('line' => array(                                
            array('cell' => array('text' => 'SOME FOOTER TEXT')),
            array('cell' => array('text' => 'Printed: [DATE]')),
            array('cell' => array('text' => 'Pages: [PAGES]/[PAGE]')),
        ))
    );                

    public $reportColumnTitles = array(
        array('line' => array(                                
            'fontStyle' => 'B',
            array('cell' => array('text' => 'Number', 'lineWidth' => 20)),
            array('cell' => array('text' => 'Name'))
        ))
    );
    
    public $reportSumary = array(        
        array('cell' => array('text' => 'CUSTUMER COUNT ==> [RECORD_COUNT]', 'fontStyle' => 'I'))
    );
    

    public function setUp() {
        $this->report = new PdfReport();
        $this->reportHeaderRecord = array('image' => FIXTURES_PATH . 'logo_aelian.png', 'title' => 'REPORT TITLE');
    }

    public function testInstance() {
        $this->assertTrue(is_a($this->report, 'PdfReport'));
    }
    
    public function testSimple() {
        $file = RESULTS_PATH . 'simpleReport.pdf';
        if (file_exists($file)) unlink($file);
        $settings = array(
            'fileName' => $file,
            'template' => array(
                'config' => $this->reportConfig,
                'body' => array(
                    array('line' => array(
                        array('cell' => array('fieldName' => 'Custumer.number', 'lineWidth' => 20)),
                        array('cell' => array('fieldName' => 'Custumer.name'))
                    ))
                )
            ),
            'records' => $this->generateRecords(5)
        );
        
        $created = $this->report->create($settings);
        
        $this->assertTrue($created);
    }

    public function testHeaderAndFooter() {
        $file = RESULTS_PATH . 'headerAndFooterReport.pdf';
        if (file_exists($file)) unlink($file);
        $settings = array(
            'fileName' => $file,
            'template' => array(
                'config' => $this->reportConfig,
                'header' => $this->reportHeader,
                'body' => array(
                    array('line' => array(
                        array('cell' => array('fieldName' => 'Custumer.number', 'lineWidth' => 20)),
                        array('cell' => array('fieldName' => 'Custumer.name'))
                    ))
                ),
                'footer' => $this->reportFooter     
            ),
            'header' => $this->reportHeaderRecord,
            'records' => $this->generateRecords(5)
        );
        
        $created = $this->report->create($settings);
        
        $this->assertTrue($created);
    }
    
    public function testColumnTitleAndSumary() {
        $file = RESULTS_PATH . 'columnTitleAndSumaryReport.pdf';
        if (file_exists($file)) unlink($file);
        $settings = array(
            'fileName' => $file,
            'template' => array(
                'config' => $this->reportConfig,
                'header' => $this->reportHeader,
                'columnTitles' => $this->reportColumnTitles,
                'body' => array(
                    array('line' => array(
                        array('cell' => array('fieldName' => 'Custumer.number', 'lineWidth' => 20)),
                        array('cell' => array('fieldName' => 'Custumer.name'))
                    ))
                ),
                'sumary' => $this->reportSumary,
                'footer' => $this->reportFooter     
            ),
            'header' => $this->reportHeaderRecord,
            'records' => $this->generateRecords(5)
        );
        
        $created = $this->report->create($settings);
        
        $this->assertTrue($created);
    }
    
    public function testManyPages() {
        $file = RESULTS_PATH . 'manyPagesReport.pdf';
        if (file_exists($file)) unlink($file);
        $reportConfig = $this->reportConfig;
        $reportConfig['pageBreakTrigger'] = 15;
        $settings = array(
            'fileName' => $file,
            'template' => array(
                'config' => $reportConfig,
                'header' => $this->reportHeader,
                'columnTitles' => $this->reportColumnTitles,
                'body' => array(
                    array('line' => array(
                        'lineHeight' => 10,
                        'border' => '1',
                        array('cell' => array('fieldName' => 'Custumer.number', 'lineWidth' => 20)),
                        array('cell' => array('fieldName' => 'Custumer.name'))
                    ))
                ),
                'sumary' => $this->reportSumary,
                'footer' => $this->reportFooter     
            ),
            'header' => $this->reportHeaderRecord,
            'records' => $this->generateRecords(200)
        );
        
        $created = $this->report->create($settings);
        
        $this->assertTrue($created);
    }

    public function testGroup() {
        $file = RESULTS_PATH . 'groupReport.pdf';
        if (file_exists($file)) unlink($file);
        $reportConfig = $this->reportConfig;
        $reportConfig['pageBreakTrigger'] = 15;
        $settings = array(
            'fileName' => $file,
            'template' => array(
                'config' => $reportConfig,
                'header' => $this->reportHeader,
                'columnTitles' => $this->reportColumnTitles,
                'body' => array(
                    array('groupDetail' => array(
                        'fieldName' => 'Custumer.group',
                        array('line' => array(
                            'lineHeight' => 10,
                            'fontSizePt' => 12,
                            array('cell' => array('text' => 'Group:', 'lineWidth' => 40, 'border' => 0)),
                            array('cell' => array('fieldName' => 'Custumer.group', 'border' => 0)),
                        ))
                    )),
                    array('line' => array(
                        'lineHeight' => 10,
                        'border' => '1',
                        array('cell' => array('fieldName' => 'Custumer.number', 'lineWidth' => 20)),
                        array('cell' => array('fieldName' => 'Custumer.name'))
                    ))
                ),
                'sumary' => $this->reportSumary,
                'footer' => $this->reportFooter     
            ),
            'header' => $this->reportHeaderRecord,
            'records' => $this->generateRecords(200)            
        );
        
        $created = $this->report->create($settings);
        
        $this->assertTrue($created);
    }
    
    public function testPageGroup() {
        $file = RESULTS_PATH . 'pageGroupReport.pdf';
        if (file_exists($file)) unlink($file);
        $reportConfig = $this->reportConfig;
        $reportConfig['pageBreakTrigger'] = 10;
        $settings = array(
            'fileName' => $file,
            'template' => array(
                'config' => $reportConfig,
                'header' => $this->reportHeader,
                'columnTitles' => $this->reportColumnTitles,
                'body' => array(
                    array('groupDetail' => array(
                        'fieldName' => 'Custumer.group',
                        'newPage' => true,
                        array('line' => array(
                            'lineHeight' => 10,
                            'fontSizePt' => 12,
                            array('cell' => array('text' => 'Group:', 'lineWidth' => 40, 'border' => 0)),
                            array('cell' => array('fieldName' => 'Custumer.group', 'border' => 0)),
                        ))
                    )),
                    array('line' => array(
                        'lineHeight' => 10,
                        'border' => '1',
                        array('cell' => array('fieldName' => 'Custumer.number', 'lineWidth' => 20)),
                        array('cell' => array('fieldName' => 'Custumer.name'))
                    ))
                ),
                'sumary' => $this->reportSumary,
                'footer' => $this->reportFooter     
            ),
            'header' => $this->reportHeaderRecord,
            'records' => $this->generateRecords(200)            
        );
        
        $created = $this->report->create($settings);
        
        $this->assertTrue($created);
    }
    
    public function testStrippedBodySumaryAndDecimal() {
        $file = RESULTS_PATH . 'strippedBodySumaryAndDecimalReport.pdf';
        if (file_exists($file)) unlink($file);
        $settings = array(
            'fileName' => $file,
            'template' => array(
                'config' => $this->reportConfig,
                'header' => $this->reportHeader,
                'columnTitles' => array(
                    array('line' => array(                                
                        'fontStyle' => 'B',
                        array('cell' => array('text' => 'Number', 'lineWidth' => 20)),
                        array('cell' => array('text' => 'Name')),
                        array('cell' => array('text' => 'Value', 'lineWidth' => 30))
                    ))
                ),
                'body' => array(
                    array('groupDetail' => array(
                        'fieldName' => 'Custumer.group',
                        array('line' => array(
                            'lineHeight' => 10,
                            'fontSizePt' => 12,
                            array('cell' => array('text' => 'Group:', 'lineWidth' => 40, 'border' => 0)),
                            array('cell' => array('fieldName' => 'Custumer.group', 'border' => 0)),
                        ))
                    )),
                    array('line' => array(
                        'lineHeight' => 10,
                        'alternateFill' => 210,
                        'border' => '1',
                        array('cell' => array('fieldName' => 'Custumer.number', 'lineWidth' => 20)),
                        array('cell' => array('fieldName' => 'Custumer.name')),
                        array('cell' => array('fieldName' => 'Custumer.value', 'lineWidth' => 30, 'decimal' => '2', 'align' => 'R'))
                    ))
                ),
                'sumary' => array(
                    'SUM_OF_CUSTUMER' => 'Custumer.value',
                    array('line' => array(
                        array('cell' => array('text' => 'CUSTUMER COUNT ==> [RECORD_COUNT]', 'fontStyle' => 'I')),
                        array('cell' => array('text' => '[SUM_OF_CUSTUMER]', 'fontStyle' => 'B', 'decimal' => '2', 'lineWidth' => 30, 'align' => 'R'))
                    ))
                ),
                'footer' => $this->reportFooter     
            ),
            'header' => $this->reportHeaderRecord,
            'records' => $this->generateRecords(200)            
        );
        
        $created = $this->report->create($settings);
        
        $this->assertTrue($created);
    }
    
    public function testLabels() {
        $file = RESULTS_PATH . 'labels.pdf';
        if (file_exists($file)) unlink($file);
        $settings = array(
            'fileName' => $file,
            'size' => 'letter',
            'template' => array(
                'config' => array(
                    'border' => 0, 
                    'align' => 'L', 
                    'fill' => false, 
                    'fontFamily' => 'Arial',
                    'fontStyle' => null,
                    'fontSizePt' => 8,
                    'margin' => 15
                ),
                'body' => array(                    
                    array('cell' => array('fieldName' => 'Label.left', 'lineWidth' => 85)),
                    array('cell' => array('fieldName' => 'Label.right', 'lineWidth' => 85, 'relativeX' => 101, 'relativeY' => -19)),
                    array('line' => array(array('cell' => array('lineHeight' => 16))))
                )
            ),
            'records' => array(
                array('Label' => array(
                    'left' => "Custumer name 1\nCustumer address\nState\n13510000 City\nRegister Number 1", 
                    'right' => "Custumer name 2\nCustumer address\nState\n13510000 City\nRegister Number 2"
                )),
                array('Label' => array(
                    'left' => "Custumer name 3\nCustumer address\nState\n13510000 City\nRegister Number 3", 
                    'right' => "Custumer name 4\nCustumer address\nState\n13510000 City\nRegister Number 4"
                )),
                array('Label' => array(
                    'left' => "Custumer name 5\nCustumer address\nState\n13510000 City\nRegister Number 5", 
                    'right' => "Custumer name 6\nCustumer address\nState\n13510000 City\nRegister Number 6"
                )),
                array('Label' => array(
                    'left' => "Custumer name 7\nCustumer address\nState\n13510000 City\nRegister Number 7", 
                    'right' => "Custumer name 8\nCustumer address\nState\n13510000 City\nRegister Number 8"
                )),
                array('Label' => array(
                    'left' => "Custumer name 9\nCustumer address\nState\n13510000 City\nRegister Number 9", 
                    'right' => "Custumer name 10\nCustumer address\nState\n13510000 City\nRegister Number 10"
                )),
                array('Label' => array(
                    'left' => "Custumer name 11\nCustumer address\nState\n13510000 City\nRegister Number 11", 
                    'right' => "Custumer name 12\nCustumer address\nState\n13510000 City\nRegister Number 12"
                )),
                array('Label' => array(
                    'left' => "Custumer name 13\nCustumer address\nState\n13510000 City\nRegister Number 13", 
                    'right' => "Custumer name 14\nCustumer address\nState\n13510000 City\nRegister Number 14"
                )),
                array('Label' => array(
                    'left' => "Custumer name 15\nCustumer address\nState\n13510000 City\nRegister Number 15", 
                    'right' => "Custumer name 16\nCustumer address\nState\n13510000 City\nRegister Number 16"
                )),
                array('Label' => array(
                    'left' => "Custumer name 17\nCustumer address\nState\n13510000 City\nRegister Number 17", 
                    'right' => "Custumer name 18\nCustumer address\nState\n13510000 City\nRegister Number 18"
                )),
                array('Label' => array(
                    'left' => "Custumer name 19\nCustumer address\nState\n13510000 City\nRegister Number 19", 
                    'right' => "Custumer name 20\nCustumer address\nState\n13510000 City\nRegister Number 20"
                ))
            )            
        );
        
        $created = $this->report->create($settings);
        
        $this->assertTrue($created);        
    }
    
    public function testRecordXmlTemplateFiles() {
        $file = RESULTS_PATH . 'reportRecordXmlTemplateFiles.pdf';
        $records = $this->generateRecords(10);
        $templateFileRecord = FIXTURES_PATH . 'template_record_four.xml';
        $templateFileRecordOdd = FIXTURES_PATH . 'template_record_five.xml';
        foreach ($records as $key => $record) {
            $records[$key]['templateFile']['body'] = ($key % 2 == 0) ? $templateFileRecord : $templateFileRecordOdd;           
        }
        if (file_exists($file)) unlink($file);
        $settings = array(
            'fileName' => $file,
            'template' => array(
                'config' => $this->reportConfig,
                'header' => $this->reportHeader,
                'columnTitles' => $this->reportColumnTitles,
                'sumary' => $this->reportSumary,
                'footer' => $this->reportFooter     
            ),
            'header' => $this->reportHeaderRecord,
            'records' => $records
        );
        
        $created = $this->report->create($settings);
        
        $this->assertTrue($created);
    }

    public function generateRecords($count) {
        $records = array();
        $group = 1;
        for ($i = 1; $i < $count; $i++) {
            if ($i % 10 == 0) {
                $group++;
            }
            $value = rand(0, 999999);
            $records[] = array('Custumer' => array('number' => $i, 'name' => 'Custumer name ' . $i, 'group' => $group, 'value' => ($value / $i)));
        }
        
        return $records;
    }
    
}
?>
