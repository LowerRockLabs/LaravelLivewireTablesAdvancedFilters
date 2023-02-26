<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Views;

use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Models\Pet;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\TestCase;
use Rappasoft\LaravelLivewireTables\Exceptions\DataTableConfigurationException;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;

class BooleanColumnTest extends TestCase
{
    /** @test */
    public function boolean_column_can_not_be_a_label(): void
    {
        $this->expectException(DataTableConfigurationException::class);

        BooleanColumn::make('Name')->label(fn () => 'My Label')->getContents(Pet::find(1));
    }

    /** @test */
    public function boolean_column_can_be_yes_no(): void
    {
        $column = BooleanColumn::make('Name');

        $this->assertEquals('icons', $column->getType());

        $column->yesNo();

        $this->assertEquals('yes-no', $column->getType());
    }
}
