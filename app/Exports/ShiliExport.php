<?php

namespace App\Exports;

use App\Concerns\Instance;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ShiliExport implements FromArray, WithHeadings, ShouldAutoSize
{
    use Exportable, Instance;

    /**
     * @var array
     */
    private $headings;
    /**
     * @var array
     */
    private $data;
    /**
     * @return static
     */
    public function setHeadings(array $headings)
    {
        $this->headings = $headings;

        return $this;
    }
    /**
     * @return static
     */
    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return $this->headings;
    }
    /**
     * @return array
     */
    public function array(): array
    {
        return $this->data;
    }


}
