<?php

namespace App\Tests\Util;

use App\Util\DataParser;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

class DataParserTest extends TestCase
{
    /** @var DataParser */
    private $dataHelper;

    protected function setUp()
    {
        $this->dataHelper = new DataParser();
    }

    /**
     * @dataProvider provider
     */
    public function testLoadData(string $data, array $expected)
    {
        $actual = $this->dataHelper->parseData($data);

        $this->assertEquals($expected, $actual);
    }

    public function provider()
    {
        $filenames = glob(__DIR__.'/DataParserTest/tests/*.data');

        return array_map(static fn ($filename) => [
            file_get_contents($filename),
            Yaml::parse(file_get_contents(preg_replace('/\.data$/', '.expected', $filename))),
        ], $filenames);
    }

    /**
     * @dataProvider xml2ArrayProvider
     */
    public function testXml2Array($data, $expected)
    {
        $actual = $this->dataHelper->parseData($data);

        $this->assertEquals($expected, $actual);
    }

    public function xml2ArrayProvider()
    {
        yield [
            <<<'XML'
<server>
<vhost file="/etc/apache2/sites-enabled/000-default.conf"><![CDATA[DocumentRoot /var/www/html
]]></vhost>
</server>
XML
            ,
            [
                'vhost' => [
                    '@attributes' => [
                        'file' => '/etc/apache2/sites-enabled/000-default.conf',
                    ],
                    '@text' => 'DocumentRoot /var/www/html'."\n",
                ],
            ],
        ];

        yield [
            <<<'XML'
<server>
<vhost file="/etc/apache2/sites-enabled/000-default.conf"><![CDATA[DocumentRoot /var/www/html
]]></vhost>
<vhost file="/etc/apache2/sites-enabled/000-example.conf"><![CDATA[DocumentRoot /var/www/example
]]></vhost>
</server>
XML
            ,
            [
                'vhost' => [
                    [
                        '@attributes' => [
                            'file' => '/etc/apache2/sites-enabled/000-default.conf',
                        ],
                        '@text' => 'DocumentRoot /var/www/html'."\n",
                    ],
                    [
                        '@attributes' => [
                            'file' => '/etc/apache2/sites-enabled/000-example.conf',
                        ],
                        '@text' => 'DocumentRoot /var/www/example'."\n",
                    ],
                ],
            ],
        ];

        yield [
            <<<'XML'
<server>
<php>
<version><![CDATA[PHP
]]></version></php>
</server>
XML
            ,
            [
                'php' => [
                    'version' => 'PHP'."\n",
                ],
            ],
        ];
    }
}
