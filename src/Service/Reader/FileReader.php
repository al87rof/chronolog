<?php

namespace App\Service\Reader;

use App\Interfaces\Service\ReaderInterface;

class FileReader implements ReaderInterface
{

    /**
     * @param string $filePath
     * @return \Generator
     */
    public function read(string $filePath): \Generator
    {
        $handle = fopen($filePath, "r");
        while (($line = fgets($handle)) !== false) {
            yield $line;
        }
        fclose($handle);
    }

}
