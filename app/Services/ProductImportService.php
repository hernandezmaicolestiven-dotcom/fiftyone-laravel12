<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

/**
 * ProductImportService
 *
 * Encapsula la lógica de importación de productos desde CSV y XLSX.
 * Separa el parsing del archivo de la lógica del controlador.
 */
class ProductImportService
{
    /** Tipos MIME reales permitidos (verificados con finfo, no solo extensión) */
    private const ALLOWED_MIMES = [
        'text/plain',
        'text/csv',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/zip',
        'application/octet-stream',
    ];

    /**
     * Importa productos desde un archivo CSV o XLSX.
     *
     * @return int Número de productos importados
     *
     * @throws \InvalidArgumentException Si el tipo de archivo no es válido
     */
    public function import(UploadedFile $file): int
    {
        $this->validateMimeType($file);

        $ext = strtolower($file->getClientOriginalExtension());

        $rows = in_array($ext, ['xlsx', 'xls'])
            ? $this->parseXlsx($file->getRealPath())
            : $this->parseCsv($file->getRealPath());

        // Omitir fila de encabezado
        array_shift($rows);

        $imported = 0;
        foreach ($rows as $row) {
            $row = array_pad($row, 6, '');
            [, $name, $description, $price, $stock, $categoryName] = $row;

            if (empty(trim((string) $name))) {
                continue;
            }

            $category = ! empty(trim((string) $categoryName))
                ? Category::firstOrCreate(['name' => trim((string) $categoryName)])
                : null;

            Product::create([
                'name' => trim((string) $name),
                'description' => trim((string) $description),
                'price' => (float) $price,
                'stock' => (int) $stock,
                'category_id' => $category?->id,
            ]);

            $imported++;
        }

        Log::info('Importación de productos completada', ['imported' => $imported]);

        return $imported;
    }

    /**
     * Verifica el tipo MIME real del archivo usando magic bytes.
     *
     * @throws \InvalidArgumentException
     */
    private function validateMimeType(UploadedFile $file): void
    {
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $realMime = $finfo->file($file->getRealPath());

        if (! in_array($realMime, self::ALLOWED_MIMES)) {
            throw new \InvalidArgumentException(
                "Tipo de archivo no permitido ({$realMime}). Solo CSV o Excel."
            );
        }
    }

    /** Parsea un archivo CSV y retorna array de filas */
    private function parseCsv(string $path): array
    {
        $rows = [];
        $handle = fopen($path, 'r');
        while (($row = fgetcsv($handle)) !== false) {
            $rows[] = $row;
        }
        fclose($handle);

        return $rows;
    }

    /** Parsea un archivo XLSX usando ZipArchive + SimpleXML */
    private function parseXlsx(string $path): array
    {
        $zip = new \ZipArchive;
        if ($zip->open($path) !== true) {
            return [];
        }

        $ssXml = $zip->getFromName('xl/sharedStrings.xml');
        $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
        $zip->close();

        // Cargar strings compartidos
        $strings = [];
        if ($ssXml) {
            $ss = new \SimpleXMLElement($ssXml);
            foreach ($ss->si as $si) {
                $strings[] = (string) $si->t;
            }
        }

        // Parsear celdas
        $rows = [];
        if ($sheetXml) {
            $ws = new \SimpleXMLElement($sheetXml);
            foreach ($ws->sheetData->row as $row) {
                $rowData = [];
                foreach ($row->c as $cell) {
                    $type = (string) $cell['t'];
                    $val = (string) $cell->v;
                    $rowData[] = ($type === 's') ? ($strings[(int) $val] ?? '') : $val;
                }
                $rows[] = $rowData;
            }
        }

        return $rows;
    }
}
