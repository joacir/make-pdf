# Make PDF

A simple way to make pdf documents and reports in PHP.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

```
PHP 7.4.x.
```

### Installing

```
composer require aelian/make-pdf
```

## How it works

### Simple document

Instance a object PdfDocument:

```
use Pdf\MakePdf\PdfDocument;

$document = new PdfDocument();
```

Configure the document:

```
$settings = array(
    'fileName' => 'hello_world.pdf',
    'template' => array(
        'body' => array(
            array('cell' => array('text' => 'Hello World!')),
        )
    )
);
```

Create the document:

```
$document->create($settings);
```

### Simple report

Instance a object PdfReport:

```
use Pdf\MakePdf\PdfReport;

$report = new PdfReport();
```

Configure the report:

```
$settings = array(
    'fileName' => 'report.pdf',
    'template' => array(
        'body' => array(
            array('line' => array(
                array('cell' => array('fieldName' => 'Aliance.number')),
                array('cell' => array('fieldName' => 'Aliance.name'))
            ))
        )
    ),
    'records' => array(
        array('Aliance' => array('number' => 1, 'name' => 'Luke Skywalker')),
        array('Aliance' => array('number' => 2, 'name' => 'Leia Organa'))
    );
);
```

Create the report:

```
$report->create($settings);
```

See more examples in [tests](tests) folder.

## Pdf Settings

You can use these settings to customize your PDF document or report:

* *fileName* - A complete path and file name to the generated PDF. Whether not defined, Make PDF will return a PDF content stream to be render in web browser. (Default null)

* *outputType* - Destination where to send the document. It can be one of the following:
I: send the file inline to the browser. The PDF viewer is used if available.
D: send to the browser and force a file download with the name given by name.
S: return the document as a string.
(Default I)


* *orientation* - PDF page orientation. Use 'P' to portrait or 'L' to landscape. (Default 'P')

* *size* - PDF page size. Use A3, A4, A5, LETTER or LEGAL. (Default 'A4')

* *records* - The data structure used to generate a PDF content, normaly feed from a database entity. In a PdfDocument each record will generate a page, although in PdfReport a page will contain many records acording with template defined options.

* *skipFirstFooter* - Skips footer on first page. (Default false)

### Example of records:

```
$records = array(
    array('EntityName' => array('fieldName' => 'Some content one'),
    array('EntityName' => array('fieldName' => 'Some content two'),
    array('EntityName' => array('fieldName' => 'Some content two'),
    ...
);
```

* *header* - A data structure like *records*, but contain only one record used in page header content.

## Defining PDF templates

A Template is a structure of options to customize your PDF document or report. Its formed by sessions, nodes and your respective attributes. All the elements are structured in a associative array.

### Sessions

The sessions are the regions will form your document:

* *config* - The PDF defaults options will be used in entire document, like font size, alignment, border and margin.

* *header* - The PDF page header options.

* *columnTitles* - The PDF report column titles options. It will be printed just after *header*.

* *body* - The document body or report detail options. This session is required to create the PDF.

* *sumary* - The PDF sumary options, where reports will can be sumarized just after the *body* session.

* *footer* - The PDF page footer options. It´s the last session to be printed.

### Nodes

The sessions contain a set of "nodes" to form the PDF page layout desired. Theses nodes can contain diferents behaviors, like:

* *cell* - The simplest node. It behaves like a box where you can print some content.

* *line* - Use to put one or more nodes in the same row. The nodes can be the same type or diferents.

* *group* - Like *line* node a *group* is used to agroup many nodes, but it can contain many lines.

* *title* - Use to describe a title of a node, by default its appear on the top of the node and with a minor font size than node.

* *image* - Use to print a image. You can set the image size (width and height).

* *checkbox* - It print a blank box to be checked, or fullfilled with some content.

* *digit* - It print a sequence of boxes that can be used to set a field of a formulary.

* *barCode123ABC* - It print a bar code of kind 123ABC.

* *barCodeI25* - It print a bar code of kind I25.

* *verse* - Use to define the content of a page verse.

* *watermark* - It print a watermark in a PDF document or report.

* *qrCodeImage* - Use to print a QR Code image in a PDF document or report. You can set the image size (width and height).

### Attributes

The nodes can contain the follow attributes used to define its caracteristics:

* *text* - A static content to be printed in a node.

* *fieldName* - A dynamic content from *records* to be printed in a node. (example: Entity.field)

* *border* - The border of a node. (0 - none, 1 - all, R - right, L - left, T - top and B - bottom)

* *margin* - The left, right and top margin of the document or report page. Can be used only in *config* session. (Default 10 mm)

* *align* - The content horizontal alignment. (L - left, C - center, R - right)

* *title* - The node title content. It´s can be static (by attribute *text*) or dynamic (by attribute *fieldName*).

* *fill* - A background color of a node (Use the RGB standard).

* *alternateFill* - Alternate background color thru the lines using white and the color setted by alternateFill (Use the RGB standard).

* *fontFamily* - The font family of a document or a node like Arial, Times, Courier, Symbol and ZapfDingbats. (Default Arial)

* *fontStyle* - The font style of a document or a node. (B - bold, I - italic, N - normal, U - underscored)

* *fontSizePt* - The font size of a document or a node. (Use points)

* *textColor* - The text color of a document or a node. Use RGB string separating with commas. (Ex.: '100,150,200')

* *titleFontFamily* - The font family of a node title like Arial, Times, Courier, Symbol and ZapfDingbats. (Default Arial)

* *titleFontStyle* - The font style of a node title. (B - bold, I - italic, N - normal, U - underscored)

* *titleFontSizePt* - The font size of a node title in points.

* *lineWidth* - The width size of a node in milimiters.

* *lineHeight* - The height size of a node in milimiters.

* *x* - The horizontal position of a node in milimiters.

* *y* - The vertical position of a node in milimiters.

* *relativeX* - A offset in horizontal position of a node in milimiters. It can be a negative value.

* *relativeY* - A offset in vertical position of a node in milimiters. It can be a negative value.

* *groupSpacing* - A blank space after a printed group.

* *decimal* - A double value format to print values. (Example: 1234.56 defined with "decimal = 2" results in "1.234,56"

* *date* - A date value format to print dates. (Example: 2018-05-01 defined with "date = d/m/Y" results in "01/05/2018"

* *autoPageBreak* - Set the bottom margin limit to page break automaticaly.

* *footerPage* - Set a page for show the group.

* *middlePage* - Set for show de group only in the middle of the page.

* *autoLineBreak* - Set to break a line automaticaly when a text have the "\n" caracter.

* *useTag* - Set a cell to ajust font style when detect a tag in the content. Allowed tags are: [B] - Bold, [I] - Italic and [U] - Underline

### Example of a complete template contained sessions, nodes and attributes:

```
$template = array(
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
```

See more examples in [tests](tests) folder.

### Defining XML template files

Another good and easy way to set PDF templates is creating a XML files. Through a XML file you can keep the template rules isolated from your code, possibiliting dynamic genaration of templates. Use the option *templateFile* to define the complete path and XML file name.

Example of a XML contained a template:

```
<!--?xml version="1.0" encoding="UTF-8"?-->
<template>
    <config>
        <border>1</border>
        <align>C</align>
        <fill>0</fill>
        <fontFamily>Arial</fontFamily>
        <fontSizePt>20</fontSizePt>
        <groupSpacing>2</groupSpacing>
    </config>
    <body>
        <group1>
            <group>
                <lineWidth>250</lineWidth>
                <border>1</border>
                <line1>
                    <line>
                        <cell1>
                            <cell fieldName="Model.field1">
                                <title fieldName="Model.field2"></title>
                            </cell>
                        </cell1>
                        <cell2>
                            <digit>
                                <text>6</text>
                            </digit>
                        </cell2>
                        <cell3>
                            <cell>
                                <text>Some text line 1 cell 3</text>
                            </cell>
                        </cell3>
                    </line>
                </line1>
                <line2>
                    <line>
                        <lineHeight>20</lineHeight>
                        <border>1</border>
                        <title><text>Line title</text></title>
                        <cell1><cell><text>Some text line 2 cell 1</text></cell></cell1>
                        <cell2><cell><text>Some text line 2 cell 2</text><lineHeight>10</lineHeight></cell></cell2>
                    </line>
                </line2>
            </group>
        </group1>
    </body>
</template>
```

It´s possible to set one or more XML files, using a array in "templateFile" option. Like:

```
$templateFile = array(
    '/templates/config.xml',
    '/templates/header.xml',
    '/templates/body.xml',
    '/templates/footer.xml'
);
```

See more examples of template XML files in [tests/fixtures](tests/fixtures) folder.


## Built With

* [FPDF](http://www.fpdf.org/) - PHP class which allows to generate PDF files with pure PHP.
* [PHP-QRCODE](https://github.com/chillerlan/php-qrcode) - A PHP QR Code library.

## Contributing

Please read [CONTRIBUTING.md](CONTRIBUTING.md) for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/aelian-repo/make-pdf/tags).

## License

This project is licensed under the Apache License - see the [LICENSE](LICENSE) file for details
