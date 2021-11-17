<?php
declare(strict_types=1);

namespace Frukt\Searcher\Classes\Import;


/**
 *
 */
trait CsvReaderHelper
{
    /**
     * @var CsvReader
     */
    protected $csvReader;

    /**
     * @var int
     */
    protected $rows = 0;

    /**
     *
     */
    public function getReader(): CsvReader
    {
        if (!$this->csvReader) {
            $this->csvReader = new CsvReader(storage_path($this->path));
        }

        return $this->csvReader;
    }
}
