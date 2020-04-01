<?php

namespace App\Imports;

use App\Concerns\Instance;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToArray;

class ShiliImport
{
    use Importable, Instance;

    private $filename;
    private $item_title;
    private $result_title;
    private $column_titles;
    private $items;
    private $receiver;

    /**
     * @return static
     */
    public function setFilename($name)
    {
        $this->filename = $name;

        return $this;
    }
    /**
     * @return static
     */
    public function setItemTitle($title)
    {
        $this->item_title = $title;

        return $this;
    }
    /**
     * @return static
     */
    public function setResultTitle($title)
    {
        $this->result_title = $title;

        return $this;
    }

    public function setItems($items)
    {
        $this->items = $items;

        return $this;
    }

    public function setColumnTitles($columns)
    {
        $this->column_titles = $columns;

        return $this;
    }

    public function setReceiver(array &$receiver)
    {
        $data = &$receiver;

        return $this;
    }

    /**
     * @param array $array
     */
    public function seek(array $array)
    {
        $headings = array_shift($array);
        $item_position = array_search($this->item_title, $headings);
        $result_position = array_search($this->result_title, $headings);
        $columns = [];
        foreach ($this->column_titles as $column_title) {
            $columns[] = array_search($column_title, $headings);
        }
        $data = [];
        // 第一行，取出其他列的值
        $first = reset($array);
        $matches = [];
        // 查找项目
        foreach ($this->items as $item) {
            $matches[$item] = [];
            // 遍历所有行
            foreach ($array as $index => $row) {
                // 查找匹配项目
                if($row[$item_position] == $item) {
                    // $match_count++;
                    $matches[$item][] = $row[$result_position];
                }
            }
        }
        // 找到最大匹配数量
        $max = 0;
        foreach ($this->items as $item) {
            $count = count($matches[$item]);
            $count > $max && $max = $count;//
        }
        echo "Max: $max ";
        // 没有匹配到也生成一行
        $max = $max == 0 ? 1 : $max;// 如果不生成请注释
        // 生成多行数据
        for($i = 0; $i < $max; $i++) {
            $one = [$this->filename];
            foreach ($columns as $position) {
                $one[] = $first[$position];
            }
            foreach ($this->items as $item) {
                $one[] = empty($matches[$item][$i]) ? '' : $matches[$item][$i];
            }
            $data[] = $one;
        }

        return $data;
    }
}
