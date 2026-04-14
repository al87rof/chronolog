<?php

namespace App\Interfaces\Service;


interface ReaderInterface
{
    /**
     * @param string $filePath
     * @return \Generator
     */
    public function read(string  $filePath): \Generator;

}
