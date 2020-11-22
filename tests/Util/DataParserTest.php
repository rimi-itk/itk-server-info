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

        return array_map(fn ($filename) => [
            file_get_contents($filename),
            Yaml::parse(file_get_contents(preg_replace('/\.data$/', '.expected', $filename))),
        ], $filenames);
    }

    public function _testXml()
    {
        $xml = <<<'XML'
<a>
<_info>A</_info>
<b>
<info>B

B


B</info>
</b>
</a>
XML;
        $sxe = new \SimpleXMLElement($xml);
        var_export(['json' => json_decode(json_encode($sxe), true)]);
    }
}
