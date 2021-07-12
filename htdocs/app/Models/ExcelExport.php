<?php

namespace App\Models;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Files\LocalTemporaryFile;
use App\Models\Stock;

class ExcelExport implements FromCollection, WithEvents
{
  use Exportable;

  private $template_file = null;

  /**
   * @param string $template_file
   * @return $this
   */
  public function setTemplate(string $template_file)
  {
      if (file_exists($template_file)) {
          $this->template_file = $template_file;
      }
      return $this;
  }

  /**
   * @return Collection
   */
  public function collection()
  {
      return Stock::all();
  }

  /**
   * @return array
   */
  public function registerEvents(): array
  {
      return [
          BeforeExport::class => function (BeforeExport $event) {
              if (is_null($this->template_file)) {
                  return;
              }
              $event->writer->reopen(new LocalTemporaryFile($this->template_file), Excel::XLSX);
              $event->writer->getSheetByIndex(0);
              return $event->getWriter()->getSheetByIndex(0);
          },
      ];
  }
}
