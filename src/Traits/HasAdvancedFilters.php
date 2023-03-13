<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Traits;

trait HasAdvancedFilters
{

    public function setupTableAttributes()
    {
        dd($this->getTheme());
        $tableAttributes = $this->getTableAttributes()
        $tableAttributesArray = [];
        $flattenedTableAttributes = \Illuminate\Support\Arr::dot($tableAttributes);
        \Illuminate\Support\Arr::map($flattenedTableAttributes, function (string $value, string $key) use ($tableAttributesArray) {
            \Illuminate\Support\Arr::set($tableAttributesArray, $key, $value);
            return true;
        });
        $this->setTableAttributes($tableAttributesArray);

    }

}
