<?php

namespace App\Tests\Service\Reader;

use App\Service\Reader\FileReader;
use PHPUnit\Framework\TestCase;



class FileReaderTest extends TestCase
{
    private FileReader $reader;

    protected function setUp(): void
    {
        $this->reader = new FileReader();
    }


    /**
     * @throws \ReflectionException
     */
    public function testRead(){
        $this->assertInstanceOf(\Generator::class,$this->reader->read('file.csv'));
    }


}
