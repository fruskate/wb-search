<?php

namespace Frukt\Searcher\Classes\Import;

use October\Rain\Support\Collection;

/**
 *
 */
trait TemporaryCollections
{
    /**
     * @var Collection
     */
    protected $itemCollection;

    /**
     *
     */
    public function initCollection(): void
    {
        $this->itemCollection = new Collection;
    }

    /**
     * @return Collection
     */
    public function getItemCollection(): Collection
    {
        if (!$this->itemCollection) {
            $this->initCollection();
        }

        return $this->itemCollection;
    }
}
