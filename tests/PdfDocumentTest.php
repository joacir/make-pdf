<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Pdf\MakePdf\PdfDocument;
use PHPUnit\Framework\TestCase;

if (!defined("FIXTURES_PATH")) define("FIXTURES_PATH", dirname(__FILE__) . '/fixtures/');
if (!defined("RESULTS_PATH")) define("RESULTS_PATH", dirname(__FILE__) . '/results/');

class PdfDocumentTest extends TestCase {

    public $document = null;

    public $documentConfig = array(
        'border' => 1,
        'align' => 'C',
        'fontFamily' => 'Arial',
        'fontSizePt' => 10
    );

    public function setUp(): void
    {
        $this->document = new PdfDocument();
    }

    public function testInstance() {
        $this->assertTrue(is_a($this->document, 'Pdf\MakePdf\PdfDocument'));
    }

    public function testNoTemplateAndRecords() {
        $file = RESULTS_PATH . 'noTemplateAndRecords.pdf';
        $settings = array(
            'fileName' => $file,
            'FontFamily' => 'Arial',
            'FontSizePt' => 14
        );

        $created = $this->document->create($settings);

        $this->assertFalse($created);
    }

    public function testCell() {
        $file = RESULTS_PATH . 'cell.pdf';
        if (file_exists($file)) unlink($file);
        $settings = array(
            'fileName' => $file,
            'template' => array(
                'config' => $this->documentConfig,
                'body' => array(
                    array('cell' => array('text' => 'Some text cell 1')),
                    array('cell' => array('text' => 'Some text cell 2'))
                )
            )
        );

        $created = $this->document->create($settings);

        $this->assertTrue($created);
    }

    public function testLine() {
        $file = RESULTS_PATH . 'line.pdf';
        if (file_exists($file)) unlink($file);
        $settings = array(
            'fileName' => $file,
            'template' => array(
                'config' => $this->documentConfig,
                'body' => array(
                    array('line' => array(
                        array('cell' => array('text' => 'Some text line 1 cell 1')),
                        array('cell' => array('text' => 'Some text line 1 Cell 2'))
                    )),
                    array('line' => array(
                        'lineHeight' => 20,
                        array('cell' => array('text' => 'Some text line 2 cell 1')),
                        array('cell' => array('text' => 'Some text line 2 cell 2', 'lineHeight' => 10)),
                        array('cell' => array('text' => 'Some text line 2 cell 3'))
                    )),
                    array('line' => array(
                        array('cell' => array(
                            'text' => 'Some text line 3 cell 1',
                            'lineWidth' => 100,
                            'title' => array('text' => 'Title')
                        )),
                        array('cell' => array('text' => 'Some text line 3 cell 2')),
                        array('cell' => array('text' => 'Some text line 3 cell 3', 'fontStyle' => 'IB'))
                    )),
                    array('line' => array(
                        array('cell' => array('text' => 'Some text line 4 cell 1')),
                        array('cell' => array('text' => 'Some text line 4 cell 2'))
                    )),
                )
            )
        );

        $created = $this->document->create($settings);

        $this->assertTrue($created);
    }

    public function testTitle() {
        $file = RESULTS_PATH . 'title.pdf';
        if (file_exists($file)) unlink($file);
        $settings = array(
            'fileName' => $file,
            'template' => array(
                'config' => array(
                    'border' => 1,
                    'align' => 'C',
                    'fill' => false,
                    'fontFamily' => 'Arial',
                    'fontStyle' => null,
                    'fontSizePt' => 10,
                    'titleFontFamily' => 'Times',
                    'titleFontSizePt' => 8,
                    'titleFontStyle' => 'B'
                ),
                'body' => array(
                    array('cell' => array('text' => 'Some text cell 1', 'title' => array('text' => 'Title cell 1')))
                )
            )
        );

        $created = $this->document->create($settings);

        $this->assertTrue($created);
    }

    public function testGroup() {
        $file = RESULTS_PATH . 'group.pdf';
        if (file_exists($file)) unlink($file);
        $settings = array(
            'fileName' => $file,
            'template' => array(
                'config' => array(
                    'border' => 1,
                    'align' => 'C',
                    'fontFamily' => 'Arial',
                    'fontSizePt' => 10,
                    'groupSpacing' => 2
                ),
                'body' => array(
                    array('group' => array(
                        'lineWidth' => 130,
                        'border' => 1,
                        array('line' => array(
                            array('cell' => array('text' => 'Some text line 1 cell 1')),
                            array('cell' => array('text' => 'Some text line 1 cell 2'))
                        )),
                        array('line' => array(
                            'lineHeight' => 20,
                            'border' => 1,
                            'title' => array('text' => 'Line 2 title'),
                            array('cell' => array('text' => 'Some text line 2 cell 1')),
                            array('cell' => array('text' => 'Some text line 2 cell 2', 'lineHeight' => 10)),
                            array('cell' => array('text' => 'Some text line 2 cell 3'))
                        ))
                    )),
                    array('group' => array(
                        'title' => array('text' => 'Group title'),
                        array('line' => array(
                            array('cell' => array(
                                'text' => 'Some text line 3 cell 1',
                                'lineWidth' => 100,
                                'title' => array('text' => 'Title')
                            )),
                            array('cell' => array('text' => 'Some text line 3 cell 2')),
                            array('cell' => array('text' => 'Some text line 3 cell 3', 'fontStyle' => 'IB'))
                        )),
                        array('line' => array(
                            array('cell' => array('text' => 'Some text line 4 cell 1')),
                            array('cell' => array('text' => 'Some text line 4 cell 2'))
                        )),
                    ))
                )
            ),
        );

        $created = $this->document->create($settings);

        $this->assertTrue($created);
    }

    public function testFieldName() {
        $file = RESULTS_PATH . 'fieldName.pdf';
        if (file_exists($file)) unlink($file);
        $settings = array(
            'fileName' => $file,
            'template' => array(
                'config' => array(
                    'border' => 0,
                    'align' => 'C',
                    'fontFamily' => 'Arial',
                    'fontSizePt' => 10
                ),
                'body' => array(
                    array('line' => array(
                        'border' => 1,
                        array('cell' => array('text' => 'Some text cell 1', 'title' => array('text' => 'Title'))),
                        array('cell' => array(
                            'fieldName' => 'Model.contentField',
                            'title' => array('fieldName' => 'Model.titleField')
                        ))
                    ))
                )
            ),
            'records' => array(
                array('Model' => array(
                    'contentField' => 'Some model field content',
                    'titleField' => 'Model field title'
                ))
            )
        );

        $created = $this->document->create($settings);

        $this->assertTrue($created);
    }

    public function testImage() {
        $image = FIXTURES_PATH . 'logo_aelian.png';
        $file = RESULTS_PATH . 'image.pdf';
        if (file_exists($file)) unlink($file);
        $settings = array(
            'fileName' => $file,
            'template' => array(
                'config' => array(
                    'border' => 0,
                    'align' => 'C',
                    'fontFamily' => 'Arial',
                    'fontSizePt' => 10
                ),
                'body' => array(
                    array('image' => array('text' => $image)),
                    array('line' => array(
                        array('image' => array('text' => $image, 'lineWidth' => 20)),
                        array('image' => array('fieldName' => 'Model.field', 'lineHeight' => 30, 'title' => array('text' => 'Title')))
                    )),
                    array('line' => array(
                        array('image' => array('text' => $image)),
                        array('image' => array('fieldName' => 'Model.field'))
                    ))
                )
            ),
            'records' => array(
                array('Model' => array('field' => $image))
            )
        );

        $created = $this->document->create($settings);

        $this->assertTrue($created);
    }

    public function testBarCode() {
        $file = RESULTS_PATH . 'barCode.pdf';
        if (file_exists($file)) unlink($file);
        $settings = array(
            'fileName' => $file,
            'template' => array(
                'config' => array(
                    'border' => 0,
                    'align' => 'C',
                    'fontFamily' => 'Arial',
                    'fontSizePt' => 10
                ),
                'body' => array(
                    array('line' => array(
                        'border' => 1,
                        'lineHeight' => 20,
                        array('barCode128ABC' => array('text' => 'JL731103389BR', 'lineWidth' => 50)),
                        array('barCode128ABC' => array('fieldName' => 'Model.fieldOne'))
                    )),
                    array('line' => array(
                        array('barCodeI25' => array('text' => '15193419300000191549900013500032300111515116', 'lineHeight' => 30)),
                    )),
                    array('line' => array(
                        'border' => 1,
                        array('barCodeI25' => array('fieldName' => 'Model.fieldTwo', 'lineHeight' => 25)),
                    ))
                )
            ),
            'records' => array(
                array('Model' => array(
                    'fieldOne' => 'JL731103389BR',
                    'fieldTwo' => '15193419300000191549900013500032300111515116'
                ))
            )
        );

        $created = $this->document->create($settings);

        $this->assertTrue($created);
    }

    public function testCheckbox() {
        $file = RESULTS_PATH . 'checkbox.pdf';
        if (file_exists($file)) unlink($file);
        $settings = array(
            'fileName' => $file,
            'template' => array(
                'config' => array(
                    'border' => 1,
                    'align' => 'C',
                    'fill' => false,
                    'fontFamily' => 'Arial',
                    'fontSizePt' => 10
                ),
                'body' => array(
                    array('line' => array(
                        array('checkbox' => array()),
                        array('checkbox' => array('fieldName' => 'Model.fieldOne', 'lineHeight' => 5)),
                        array('checkbox' => array('text' => '1', 'lineHeight' => 10)),
                        array('checkbox' => array('text' => 'X', 'lineHeight' => 15, 'fontSizePt' => 16)),
                    ))
                )
            ),
            'records' => array(
                array('Model' => array('fieldOne' => 'S'))
            )
        );

        $created = $this->document->create($settings);

        $this->assertTrue($created);
    }

    public function testHeaderAndFooter() {
        $file = RESULTS_PATH . 'headerAndFooter.pdf';
        if (file_exists($file)) unlink($file);
        $settings = array(
            'fileName' => $file,
            'template' => array(
                'config' => $this->documentConfig,
                'header' => array(
                    array('cell' => array('text' => 'SOME HEADER TEXT', 'fontSizePt' => 20, 'lineHeight' => 10))
                ),
                'body' => array(
                    array('line' => array(
                        array('cell' => array('text' => 'Some text cell 1', 'title' => array('text' => 'Cell 1 title'))),
                        array('cell' => array('fieldName' => 'Model.fieldOne', 'title' => array('fieldName' => 'Model.fieldTwo')))
                    ))
                ),
                'footer' => array(
                    array('cell' => array('text' => 'SOME FOOTER TEXT', 'fontSizePt' => 20, 'lineHeight' => 10))
                )
            ),
            'records' => array(
                array('Model' => array('fieldOne' => 'Some model field text cell 2', 'fieldTwo' => 'Cell 2 title'))
            )
        );

        $created = $this->document->create($settings);

        $this->assertTrue($created);
    }

    public function testManyRecords() {
        $file = RESULTS_PATH . 'manyRecords.pdf';
        if (file_exists($file)) unlink($file);
        $settings = array(
            'fileName' => $file,
            'template' => array(
                'config' => $this->documentConfig,
                'header' => array(
                    array('cell' => array('text' => 'SOME HEADER TEXT', 'fontSizePt' => 20, 'lineHeight' => 10))
                ),
                'body' => array(
                    array('cell' => array('fieldName' => 'Model.field1', 'title' => array('fieldName' => 'Model.field2')))
                ),
                'footer' => array(
                    array('cell' => array('text' => 'SOME FOOTER TEXT', 'fontSizePt' => 20, 'lineHeight' => 10))
                )
            ),
            'records' => array(
                array('Model' => array('field1' => 'Record 1', 'field2' => 'Record 1 title')),
                array('Model' => array('field1' => 'Record 2', 'field2' => 'Record 2 title')),
                array('Model' => array('field1' => 'Record 3', 'field2' => 'Record 3 title'))
            )
        );

        $created = $this->document->create($settings);

        $this->assertTrue($created);
    }

    public function testFrontAndVerse() {
        $file = RESULTS_PATH . 'frontAndVerse.pdf';
        if (file_exists($file)) unlink($file);
        $settings = array(
            'fileName' => $file,
            'template' => array(
                'config' => $this->documentConfig,
                'header' => array(
                    array('cell' => array('text' => 'SOME HEADER TEXT', 'fontSizePt' => 20, 'lineHeight' => 10))
                ),
                'body' => array(
                    array('cell' => array('fieldName' => 'Model.field1', 'title' => array('fieldName' => 'Model.field2'))),
                    array('verse' => array('lineHeight' => 200, array('cell' => array('text' => 'Some text in verse page'))))
                ),
                'footer' => array(
                    array('cell' => array('text' => 'SOME FOOTER TEXT', 'fontSizePt' => 20, 'lineHeight' => 10))
                )
            ),
            'records' => array(
                array('Model' => array('field1' => 'Record 1', 'field2' => 'Record 1 title')),
                array('Model' => array('field1' => 'Record 2', 'field2' => 'Record 2 title')),
                array('Model' => array('field1' => 'Record 3', 'field2' => 'Record 3 title'))
            )
        );

        $created = $this->document->create($settings);

        $this->assertTrue($created);
    }

    public function testXmlTemplateFile() {
        $file = RESULTS_PATH . 'xmlTemplateFile.pdf';
        if (file_exists($file)) unlink($file);
        $settings = array(
            'orientation' => 'L',
            'templateFile' => FIXTURES_PATH . 'template_file_group_test.xml',
            'fileName' => $file,
            'records' => array(
                array('Model' => array('field1' => 'Record 1', 'field2' => 'Record 1 title')),
                array('Model' => array('field1' => 'Record 2', 'field2' => 'Record 2 title')),
                array('Model' => array('field1' => 'Record 3', 'field2' => 'Record 3 title'))
            )
        );

        $created = $this->document->create($settings);

        $this->assertTrue($created);
    }

    public function testManyXmlTemplateFiles() {
        $settings = array(
            'templateFile' => array(
                'config' => FIXTURES_PATH . 'template_config_test.xml',
                'header' => FIXTURES_PATH . 'template_header_test.xml',
                'columnTitles' => FIXTURES_PATH . 'template_column_title_test.xml',
                'body' => FIXTURES_PATH . 'template_body_test.xml',
                'sumary' => FIXTURES_PATH . 'template_sumary_test.xml',
                'footer' => FIXTURES_PATH . 'template_footer_test.xml'
            )
        );

        $this->document->setup($settings);
        $this->document->setTemplate();

        $expected = array(
            'config' => array(
                'border' => 1,
                'align' => 'L',
                'fill' => 0,
                'fontFamily' => 'Arial',
                'fontSizePt' => 10
            ),
            'header' => array(
                'line1' => array('line' => array(
                    'lineHeight' => 20,
                    'image1' => array('image' => array('fieldName' => 'Header.image', 'lineWidth' => 20)),
                    'cell1' => array('cell' => array('fieldName' => 'Header.title'))
                ))
            ),
            'columnTitles' => array(
                'line1' => array('line' => array(
                    'fontStyle' => 'B',
                    'cell1' => array('cell' => array('text' => 'Number', 'lineWidth' => 20)),
                    'cell2' => array('cell' => 'Name'),
                    'cell3' => array('cell' => array('text' => 'Value', 'lineWidth' => 30))
                ))
            ),
            'body' => array(
                'groupDetail1' => array('groupDetail' => array(
                    'fieldName' => 'Custumer.group',
                    'line1' => array('line' => array(
                        'lineHeight' => 10,
                        'fontSizePt' => 12,
                        'cell1' => array('cell' => array('text' => 'Group:', 'lineWidth' => 40, 'border' => 0)),
                        'cell2' => array('cell' => array('fieldName' => 'Custumer.group', 'border' => 0)),
                    ))
                )),
                'line1' => array('line' => array(
                    'lineHeight' => 10,
                    'alternateFill' => 210,
                    'border' => '1',
                    'cell1' => array('cell' => array('fieldName' => 'Custumer.number', 'lineWidth' => 20)),
                    'cell2' => array('cell' => array('fieldName' => 'Custumer.name')),
                    'cell3' => array('cell' => array('fieldName' => 'Custumer.value', 'lineWidth' => 30))
                ))
            ),
            'sumary' => array(
                'SUM_OF_CUSTUMER' => 'Custumer.value',
                'line1' => array('line' => array(
                    'cell1' => array('cell' => array('text' => 'CUSTUMER COUNT ==> [RECORD_COUNT]', 'fontStyle' => 'I')),
                    'cell2' => array('cell' => array('text' => '[SUM_OF_CUSTUMER]', 'fontStyle' => 'B', 'decimal' => '2'))
                ))
            ),
            'footer' => array(
                'line1' => array('line' => array(
                    'cell1' => array('cell' => 'Some footer text'),
                    'cell2' => array('cell' => 'In [DATE]'),
                    'cell3' => array('cell' => 'Pages: [PAGE]/[PAGES]'),
                ))
            )
        );

        $this->assertEquals($expected, $this->document->template);
    }

    public function testRecordXmlTemplateFiles() {
        $file = RESULTS_PATH . 'recordXmlTemplateFiles.pdf';
        if (file_exists($file)) unlink($file);
        $templateFileRecordOne = FIXTURES_PATH . 'template_record_one.xml';
        $templateFileRecordTwo = FIXTURES_PATH . 'template_record_two.xml';
        $templateFileRecordThree = FIXTURES_PATH . 'template_record_three.xml';
        $settings = array(
            'fileName' => $file,
            'records' => array(
                array(
                    'templateFile' => array('body' => $templateFileRecordOne, 'config' => FIXTURES_PATH . 'template_config.xml'),
                    'Model' => array('number' => '001', 'name' => 'Maria Jose')
                ),
                array(
                    'templateFile' => array('body' => $templateFileRecordTwo, 'config' => FIXTURES_PATH . 'template_config.xml'),
                    'Model' => array('number' => '002', 'name' => 'Antonio Silva')
                ),
                array(
                    'templateFile' => array('body' => $templateFileRecordThree, 'config' => FIXTURES_PATH . 'template_config.xml'),
                    'Model' => array('number' => '003', 'name' => 'Ana Paula')
                )
            )
        );

        $created = $this->document->create($settings);

        $this->assertTrue($created);
    }

    public function testQRCode() {
        $qrcode = 'https://www.youtube.com/watch?v=DLzxrzFCyOs&t=43s';
        $file = RESULTS_PATH . 'qrCode.pdf';
        if (file_exists($file)) unlink($file);
        $settings = array(
            'fileName' => $file,
            'template' => array(
                'config' => array(
                    'border' => 0,
                    'align' => 'C',
                    'fontFamily' => 'Arial',
                    'fontSizePt' => 10
                ),
                'body' => array(
                    array('qrCodeImage' => array('text' => $qrcode)),
                    array('line' => array(
                        array('qrCodeImage' => array('text' => $qrcode, 'lineWidth' => 20)),
                        array('qrCodeImage' => array('fieldName' => 'Model.field', 'lineHeight' => 30, 'title' => array('text' => 'QR Code Example')))
                    )),
                )
            ),
            'records' => array(
                array('Model' => array(
                    'field' => $qrcode,
                ))
            )
        );

        $created = $this->document->create($settings);

        $this->assertTrue($created);
    }

    public function testFontStyleTag() {
        $file = RESULTS_PATH . 'fontStyleTag.pdf';
        if (file_exists($file)) unlink($file);
        $settings = array(
            'fileName' => $file,
            'template' => array(
                'config' => $this->documentConfig,
                'header' => array(
                    array('cell' => array('useTag' => true, 'text' => 'SOME <b>HEADER</b> TEXT', 'fontSizePt' => 20, 'lineHeight' => 10))
                ),
                'body' => array(
                    array('cell' => array('useTag' => true, 'fieldName' => 'Model.field1', 'title' => array('fieldName' => 'Model.field2')))
                ),
                'footer' => array(
                    array('cell' => array('useTag' => true, 'text' => 'SOME <B>OLD</B> FOOTER <B>TEXT</B> TOO', 'fontSizePt' => 20, 'lineHeight' => 10))
                )
            ),
            'records' => array(
                array('Model' => array('field1' => 'Record 1 <B>with bold</B> and <I>italic font</I> and <U>underline</U> too. <B><I><U>But all of them too!</U></I></B>', 'field2' => '<b>Record</b> 1 title')),
                array('Model' => array('field1' => 'Record 2', 'field2' => 'Record 2 <b>bold</b> title')),
                array('Model' => array('field1' => 'Record 3', 'field2' => 'Record 3 <b>title</b>'))
            )
        );

        $created = $this->document->create($settings);

        $this->assertTrue($created);
    }

    public function testPostNetCode() {
        $zipcode = '17526430';
        $file = RESULTS_PATH . 'postNetCode.pdf';
        if (file_exists($file)) unlink($file);
        $settings = array(
            'fileName' => $file,
            'template' => array(
                'config' => array(
                    'border' => 0,
                    'align' => 'C',
                    'fontFamily' => 'Arial',
                    'fontSizePt' => 10
                ),
                'body' => array(
                    array('postNetCode' => array('text' => $zipcode)),
                    array('line' => array(
                        array('postNetCode' => array('text' => $zipcode, 'lineWidth' => 60.33, 'lineHeight' => 1)),
                        array('postNetCode' => array('fieldName' => 'Model.field', 'lineHeight' => 1.5))
                    )),
                    array('line' => array(
                        array('postNetCode' => array('text' => $zipcode, 'title' => ['text' => 'Titulo', 'align' => 'L'])),
                    )),
                )
            ),
            'records' => array(
                array('Model' => array(
                    'field' => $zipcode,
                ))
            )
        );

        $created = $this->document->create($settings);

        $this->assertTrue($created);
    }

    public function testConfigFields() {
        $file = RESULTS_PATH . 'configFields.pdf';
        if (file_exists($file)) unlink($file);
        $settings = array(
            'orientation' => 'L',
            'templateFile' => FIXTURES_PATH . 'template_file_config_fields_test.xml',
            'fileName' => $file,
            'records' => array(
                array('Model' => array('field1' => 'Record 1', 'field2' => 'Record 1 title')),
                array('Model' => array('field1' => 'Record 2', 'field2' => 'Record 2 title')),
                array('Model' => array('field1' => 'Record 3', 'field2' => 'Record 3 title'))
            ),
            'configFields' => [
                'cell1' => ['text' => 'Some text line 2 cell 1'],
                'cell2' => ['text' => 'Some text line 2 cell 2', 'lineHeight' => 10],
                'cell3' => ['text' => 'Some text line 2 cell 3'],
            ],
        );

        $created = $this->document->create($settings);

        $this->assertTrue($created);
    }

}
