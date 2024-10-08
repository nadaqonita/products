<?php

namespace App\DataTables\Interfaces;

interface DataTableConfig
{
    public function setQuery();
    public function setColumns();
    public function setButtons();
}
