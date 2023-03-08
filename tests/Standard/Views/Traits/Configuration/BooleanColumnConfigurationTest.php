<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Standard\Views\Traits\Configuration;

use Closure;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Standard\TestCase;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;

class BooleanColumnConfigurationTest extends TestCase
{
    /** @test */
    public function boolean_column_can_set_callback(): void
    {
        $column = BooleanColumn::make('Name');

        $this->assertFalse($column->hasCallback());

        $column->setCallback(fn ($value) => (bool) $value === true);

        $this->assertTrue($column->hasCallback());

        $this->assertTrue($column->getCallback() instanceof Closure);
    }

    /** @test */
    public function boolean_column_can_set_success_value(): void
    {
        $column = BooleanColumn::make('Name');

        $this->assertTrue($column->getSuccessValue());

        $column->setSuccessValue(false);

        $this->assertFalse($column->getSuccessValue());
    }

    /** @test */
    public function boolean_column_can_set_view(): void
    {
        $column = BooleanColumn::make('Name');

        $this->assertSame('livewire-tables::includes.columns.boolean', $column->getView());

        $column->setView('livewire-tables::includes.columns.boolean2');

        $this->assertSame('livewire-tables::includes.columns.boolean2', $column->getView());
    }
}