<?php
declare(strict_types=1);
namespace Frukt\Searcher\Classes\Import;

use \array_combine;

/**
 *
 */
class CsvReader
{
    /**
     * @var string|null
     */
    private ?string $path = null;

    /**
     * @var int|null
     */
    private ?int $length = null;

    /**
     * @var \SplFileObject|null
     */
    private ?\SplFileObject $splFileObject = null;

    /**
     * @var string
     */
    private string $separator;

    /**
     * @param string $path
     */
    public function __construct(string $path, string $separator = "\t")
    {
        $this->separator = $separator;
        $this->setPath($path);
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return (string) $this->path;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return $this
     */
    public function read(): \SplFileObject
    {
        if (null === $this->splFileObject) {
            $this->splFileObject = new \SplFileObject($this->getPath());
            $this->splFileObject->setFlags(\SplFileObject::READ_CSV | \SplFileObject::SKIP_EMPTY | \SplFileObject::DROP_NEW_LINE);
            $this->splFileObject->setCsvControl($this->separator);
        }

        return $this->splFileObject;
    }

    /**
     *
     */
    public function length(): int
    {
        if (null == $this->length) {
            $this->read()->seek(PHP_INT_MAX);
            $this->length = $this->read()->key();
            $this->read()->rewind();
        }

        return (int) $this->length;
    }

    /**
     * @return \Generator
     */
    public function iterate(): \Generator
    {
        $head = null;
        foreach($this->read() as $row) {
            if (null === $head) {
                $head = $row;
                continue;
            }

            if ($row) {
                yield array_combine($head, (array) $row);
            }
        }
    }
}
