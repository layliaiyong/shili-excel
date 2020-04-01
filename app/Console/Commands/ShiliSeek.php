<?php

namespace App\Console\Commands;

use App\Concerns\Instance;
use App\Exports\ShiliExport;
use App\Imports\ShiliImport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Excel;

class ShiliSeek extends Command
{
    use Instance;
    /**
     * 命令行执行命令
     * @var string
     */
    protected $signature = 'shili:seek {--column=*} {path} {item*}';
    // php .\artisan shili:seek --column="姓名" "E:\projects\github\shili-excel\storage\copy" "血型(正反定型)" "RH血型(D)"
    // php .\artisan shili:seek --column="姓名" "E:\projects\github\shili-excel\storage\shili\2016.12" "血型(正反定型)" "RH血型(D)"

    /**
     * 命令描述
     *
     * @var string
     */
    protected $description = '寻找检测结果';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $columns = $this->option('column');
        $path = $this->argument('path');
        $items = $this->argument('item');
        // dd($items, $columns, $path);

        $files = scandir($path);
        foreach ($files as $file) {
            if(in_array($file, ['.', '..'])) {
                continue;
            }
            $this->importOne(realpath("$path/$file"), $columns, $items);
        }
        // 导出
        $this->export($columns, $items);
    }

    public function packHeadings($columns, $items)
    {
        $headings = ['表名'];
        foreach ($columns as $column) {
            $headings[] = $column;
        }
        foreach ($items as $item) {
            $headings[] = $item;
        }
        return $headings;
    }

    public function importOne($path, $columns, $items)
    {
        echo "File: $path ";
        $filename = trim(substr($path, strlen(dirname($path))), " \\/");
        // dd(file_get_contents($path));
        $encoding = mb_detect_encoding(file_get_contents($path));
        // dd($filename);
        // $receiver = [];
        $import = ShiliImport::instance()->setFilename($filename)->setItems($items)->setColumnTitles($columns)->setItemTitle("检测项目")->setResultTitle("检测结果");
        $array = $import->toArray($path, null, Excel::XLS);
        $data = $import->seek($array[0]);
        $count = count($data);
        echo "DataCount: $count\n";
        $this->push($data);
    }

    public function push($rows)
    {
        $this->data = isset($this->data) ?: [];
        foreach ($rows as $row) {
            $this->date[] = $row;
        }
    }

    public function export($columns, $items)
    {
        $headings = $this->packHeadings($columns, $items);
        $outfile = "export.xlsx";
        ShiliExport::instance()->setHeadings($headings)->setData($this->date)->store($outfile);
        // dd($this->data);
    }
}
