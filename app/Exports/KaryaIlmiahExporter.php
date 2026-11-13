<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use ZipArchive;

class KaryaIlmiahExporter
{
    public function excel(Collection $records): string
    {
        $temporaryPath = tempnam(sys_get_temp_dir(), 'karya-ilmiah-');
        $path = $temporaryPath . '.xlsx';
        rename($temporaryPath, $path);

        $zip = new ZipArchive();
        if ($zip->open($path, ZipArchive::OVERWRITE) !== true) {
            @unlink($path);
            throw new \RuntimeException('Berkas Excel tidak dapat dibuat.');
        }

        $zip->addFromString('[Content_Types].xml', $this->contentTypesXml());
        $zip->addFromString('_rels/.rels', $this->rootRelationshipsXml());
        $zip->addFromString('xl/workbook.xml', $this->workbookXml());
        $zip->addFromString('xl/_rels/workbook.xml.rels', $this->workbookRelationshipsXml());
        $zip->addFromString('xl/worksheets/sheet1.xml', $this->worksheetXml($records));
        $zip->addFromString('xl/styles.xml', $this->stylesXml());
        $zip->close();

        return $path;
    }

    public function pdf(Collection $records): string
    {
        $columns = [
            ['No', 35],
            ['Judul Karya', 230],
            ['Penulis', 135],
            ['Jenis Publikasi', 145],
            ['Tahun', 50],
            ['Tanggal Ditambahkan (WIB)', 191],
        ];
        $rows = $records->values()->map(fn ($record, $index) => [
            $index + 1,
            $record->nama_karya,
            $record->nama_penulis,
            $record->jenis_publikasi,
            $record->tahun_publikasi ?? '-',
            $record->created_at?->copy()->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') ?? '-',
        ])->all();

        $preparedRows = [];
        foreach ($rows as $rowIndex => $row) {
            $cells = [];
            foreach ($row as $columnIndex => $value) {
                $text = $this->pdfSafeText((string) $value);
                $maxCharacters = max(5, (int) floor(($columns[$columnIndex][1] - 8) / 5));
                $cells[] = $this->wrapPdfText($text, $maxCharacters);
            }

            $lineCount = max(array_map('count', $cells));
            $preparedRows[] = [
                'index' => $rowIndex,
                'cells' => $cells,
                'height' => max(22, ($lineCount * 11) + 10),
            ];
        }

        $chunks = [];
        $pageRows = [];
        $pageHeight = 0;
        foreach ($preparedRows as $row) {
            if ($pageRows !== [] && $pageHeight + $row['height'] > 448) {
                $chunks[] = $pageRows;
                $pageRows = [];
                $pageHeight = 0;
            }
            $pageRows[] = $row;
            $pageHeight += $row['height'];
        }
        $chunks[] = $pageRows;

        $pageContents = [];
        $watermark = $this->pdfLogoWatermark();
        $exportedAtJakarta = new \DateTimeImmutable('now', new \DateTimeZone('Asia/Jakarta'));
        $exportTimestamp = 'Waktu ekspor: ' . $exportedAtJakarta->format('d/m/Y H:i') . ' WIB';

        foreach ($chunks as $pageIndex => $pageRows) {
            $headerY = 481;
            $rowBands = '';
            $rowSeparators = '';
            $rowTop = $headerY - 5;
            foreach ($pageRows as $row) {
                $rowTop -= $row['height'];
                if ($row['index'] % 2 === 1) {
                    $rowBands .= "0.976 0.957 0.973 rg 28 " . $rowTop . " 786 " . $row['height'] . " re f\n";
                }
                $rowSeparators .= "0.914 0.863 0.902 RG 0.5 w 28 " . $rowTop . " m 814 " . $rowTop . " l S\n";
            }

            $content = $rowBands . $watermark . $rowSeparators;
            $content .= "0.580 0.086 0.459 rg 28 535 786 32 re f\n";
            $content .= $this->pdfText(38, 547, 'PSGA - Karya Ilmiah', 15, true, '1 1 1');
            $content .= $this->pdfText(28, 521, $exportTimestamp, 8);
            $content .= $this->pdfText(740, 521, 'Halaman ' . ($pageIndex + 1) . '/' . count($chunks), 8);

            $content .= "0.580 0.086 0.459 rg 28 " . ($headerY - 5) . " 786 24 re f\n";
            $x = 28;
            foreach ($columns as [$label, $width]) {
                $content .= $this->pdfText($x + 4, $headerY + 3, $label, 9, true, '1 1 1');
                $x += $width;
            }

            $content .= "0.85 0.77 0.84 RG 0.4 w\n";
            $x = 28;
            $gridTop = $headerY + 19;
            $content .= $x . ' ' . $rowTop . ' m ' . $x . ' ' . $gridTop . " l S\n";
            foreach ($columns as [, $width]) {
                $x += $width;
                $content .= $x . ' ' . $rowTop . ' m ' . $x . ' ' . $gridTop . " l S\n";
            }

            $rowTop = $headerY - 5;
            $x = 28;
            foreach ($pageRows as $row) {
                foreach ($row['cells'] as $columnIndex => $lines) {
                    $width = $columns[$columnIndex][1];
                    $textBlockHeight = 9 + ((count($lines) - 1) * 11);
                    $firstBaseline = $rowTop - (($row['height'] - $textBlockHeight) / 2) - 7;
                    foreach ($lines as $lineIndex => $text) {
                        $baseline = (int) round($firstBaseline - ($lineIndex * 11));
                        $content .= $this->pdfText($x + 4, $baseline, $text, 9);
                    }
                    $x += $width;
                }
                $x = 28;
                $rowTop -= $row['height'];
            }

            $pageContents[] = $content;
        }

        return $this->buildPdf($pageContents);
    }

    private function worksheetXml(Collection $records): string
    {
        $rows = [[
            'No', 'Judul Karya', 'Penulis', 'Jenis Publikasi', 'Tahun Publikasi',
            'Deskripsi', 'Link', 'Tanggal Ditambahkan (WIB)',
        ]];

        foreach ($records->values() as $index => $record) {
            $rows[] = [
                $index + 1,
                $record->nama_karya,
                $record->nama_penulis,
                $record->jenis_publikasi,
                $record->tahun_publikasi ?? '',
                $record->deskripsi ?? '',
                $record->link ?? '',
                $record->created_at?->copy()->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s') ?? '',
            ];
        }

        $lastRow = count($rows);
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
        $xml .= '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">';
        $xml .= '<dimension ref="A1:H' . $lastRow . '"/>';
        $xml .= '<sheetViews><sheetView showGridLines="0" workbookViewId="0"><pane ySplit="1" topLeftCell="A2" activePane="bottomLeft" state="frozen"/>'
            . '<selection pane="bottomLeft" activeCell="A2" sqref="A2"/></sheetView></sheetViews>';
        $xml .= '<sheetFormatPr defaultRowHeight="22"/><cols>';
        foreach ([6, 30, 18, 21, 12, 30, 25, 25] as $index => $width) {
            $column = $index + 1;
            $xml .= '<col min="' . $column . '" max="' . $column . '" width="' . $width . '" customWidth="1"/>';
        }
        $xml .= '</cols><sheetData>';

        foreach ($rows as $rowIndex => $row) {
            $excelRow = $rowIndex + 1;
            $xml .= '<row r="' . $excelRow . '"' . ($rowIndex === 0 ? ' ht="36" customHeight="1"' : ' ht="28" customHeight="1"') . '>';
            foreach ($row as $columnIndex => $value) {
                $cell = $this->columnName($columnIndex + 1) . $excelRow;
                $isCentered = in_array($columnIndex, [0, 4, 7], true);
                $isAlternate = $rowIndex > 0 && $rowIndex % 2 === 0;
                $style = $rowIndex === 0
                    ? 1
                    : ($isAlternate ? ($isCentered ? 5 : 4) : ($isCentered ? 3 : 2));
                if ($rowIndex > 0 && in_array($columnIndex, [0, 4], true) && is_numeric($value)) {
                    $xml .= '<c r="' . $cell . '" s="' . $style . '" t="n"><v>' . $value . '</v></c>';
                    continue;
                }
                $xml .= '<c r="' . $cell . '" s="' . $style . '" t="inlineStr"><is><t xml:space="preserve">'
                    . $this->xmlEscape((string) $value)
                    . '</t></is></c>';
            }
            $xml .= '</row>';
        }

        return $xml . '</sheetData></worksheet>';
    }

    private function stylesXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<fonts count="2"><font><color rgb="FF382638"/><sz val="11"/><name val="Calibri"/></font>'
            . '<font><b/><color rgb="FFFFFFFF"/><sz val="11"/><name val="Calibri"/></font></fonts>'
            . '<fills count="4"><fill><patternFill patternType="none"/></fill>'
            . '<fill><patternFill patternType="gray125"/></fill>'
            . '<fill><patternFill patternType="solid"><fgColor rgb="FF941675"/><bgColor indexed="64"/></patternFill></fill>'
            . '<fill><patternFill patternType="solid"><fgColor rgb="FFFAF4F9"/><bgColor indexed="64"/></patternFill></fill></fills>'
            . '<borders count="2"><border><left/><right/><top/><bottom/><diagonal/></border>'
            . '<border><left/><right/><top/><bottom style="thin"><color rgb="FFE9DCE6"/></bottom><diagonal/></border></borders>'
            . '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
            . '<cellXfs count="6"><xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>'
            . '<xf numFmtId="0" fontId="1" fillId="2" borderId="0" xfId="0" applyFont="1" applyFill="1" applyAlignment="1"><alignment horizontal="center" vertical="center" wrapText="1"/></xf>'
            . '<xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0" applyBorder="1" applyAlignment="1"><alignment vertical="center" wrapText="1"/></xf>'
            . '<xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center" wrapText="1"/></xf>'
            . '<xf numFmtId="0" fontId="0" fillId="3" borderId="1" xfId="0" applyFill="1" applyBorder="1" applyAlignment="1"><alignment vertical="center" wrapText="1"/></xf>'
            . '<xf numFmtId="0" fontId="0" fillId="3" borderId="1" xfId="0" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center" wrapText="1"/></xf></cellXfs>'
            . '<cellStyles count="1"><cellStyle name="Normal" xfId="0" builtinId="0"/></cellStyles>'
            . '</styleSheet>';
    }

    private function contentTypesXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            . '<Default Extension="xml" ContentType="application/xml"/>'
            . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            . '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            . '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
            . '</Types>';
    }

    private function rootRelationshipsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            . '</Relationships>';
    }

    private function workbookXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . '<sheets><sheet name="Karya Ilmiah" sheetId="1" r:id="rId1"/></sheets>'
            . '</workbook>';
    }

    private function workbookRelationshipsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
            . '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
            . '</Relationships>';
    }

    private function columnName(int $number): string
    {
        $name = '';
        while ($number > 0) {
            $number--;
            $name = chr(65 + ($number % 26)) . $name;
            $number = intdiv($number, 26);
        }

        return $name;
    }

    private function xmlEscape(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }

    private function pdfText(int $x, int $y, string $text, int $size, bool $bold = false, string $color = '0.22 0.15 0.22'): string
    {
        $font = $bold ? 'F2' : 'F1';
        return $color . " rg BT /{$font} {$size} Tf {$x} {$y} Td ("
            . $this->pdfEscape($text)
            . ") Tj ET\n";
    }

    private function pdfSafeText(string $value): string
    {
        $converted = iconv('UTF-8', 'Windows-1252//TRANSLIT//IGNORE', $value);
        return $converted === false ? '' : preg_replace('/[\x00-\x1F\x7F]/', ' ', $converted);
    }

    private function wrapPdfText(string $text, int $maxCharacters): array
    {
        $words = preg_split('/\s+/', trim($text), -1, PREG_SPLIT_NO_EMPTY) ?: ['-'];
        $lines = [];
        $line = '';

        foreach ($words as $word) {
            if (strlen($word) > $maxCharacters) {
                if ($line !== '') {
                    $lines[] = $line;
                    $line = '';
                }
                $parts = str_split($word, $maxCharacters);
                while (count($parts) > 1) {
                    $lines[] = array_shift($parts);
                }
                $line = $parts[0];
                continue;
            }

            $candidate = $line === '' ? $word : $line . ' ' . $word;
            if (strlen($candidate) > $maxCharacters) {
                $lines[] = $line;
                $line = $word;
            } else {
                $line = $candidate;
            }
        }

        if ($line !== '' || $lines === []) {
            $lines[] = $line !== '' ? $line : '-';
        }

        return $lines;
    }

    private function pdfEscape(string $value): string
    {
        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $value);
    }

    private function buildPdf(array $pageContents): string
    {
        $objects = [];
        $pageCount = count($pageContents);
        $objects[1] = '<< /Type /Catalog /Pages 2 0 R >>';
        $objects[3] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica /Encoding /WinAnsiEncoding >>';
        $objects[4] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold /Encoding /WinAnsiEncoding >>';
        $pageReferences = [];

        foreach ($pageContents as $index => $content) {
            $pageId = 5 + ($index * 2);
            $contentId = $pageId + 1;
            $pageReferences[] = $pageId . ' 0 R';
            $objects[$pageId] = '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 842 595] '
                . '/Resources << /Font << /F1 3 0 R /F2 4 0 R >> >> '
                . '/Contents ' . $contentId . ' 0 R >>';
            $objects[$contentId] = '<< /Length ' . strlen($content) . ">>\nstream\n" . $content . "\nendstream";
        }
        $objects[2] = '<< /Type /Pages /Kids [' . implode(' ', $pageReferences) . '] /Count ' . $pageCount . ' >>';
        $lastObject = 4 + ($pageCount * 2);

        ksort($objects);
        $pdf = "%PDF-1.4\n%\xE2\xE3\xCF\xD3\n";
        $offsets = [0];
        for ($id = 1; $id <= $lastObject; $id++) {
            $offsets[$id] = strlen($pdf);
            $pdf .= $id . " 0 obj\n" . $objects[$id] . "\nendobj\n";
        }

        $xrefOffset = strlen($pdf);
        $pdf .= "xref\n0 " . ($lastObject + 1) . "\n0000000000 65535 f \n";
        for ($id = 1; $id <= $lastObject; $id++) {
            $pdf .= sprintf("%010d 00000 n \n", $offsets[$id]);
        }

        return $pdf . 'trailer << /Size ' . ($lastObject + 1) . " /Root 1 0 R >>\nstartxref\n"
            . $xrefOffset . "\n%%EOF";
    }

    private function pdfLogoWatermark(): string
    {
        // The grayscale stencil is derived from PSGA.jpg. Draw it as small PDF paths
        // with coverage levels to keep the logo edges smooth in every PDF viewer.
        $mask = file_get_contents(public_path('images/PSGA-mask-gray.bin'));
        if ($mask === false) {
            throw new \RuntimeException('Logo PSGA tidak dapat dimuat untuk ekspor PDF.');
        }

        $sourceWidth = 1080;
        $sourceHeight = 1080;
        $targetSize = 720;
        $logoSize = 440;
        $pixelSize = $logoSize / $targetSize;
        $originX = (842 - $logoSize) / 2;
        $originY = (595 - $logoSize) / 2;
        $opacity = 0.08;
        $content = '';
        $paths = [];

        for ($targetY = 0; $targetY < $targetSize; $targetY++) {
            $runStart = null;
            $runCoverage = 0;

            for ($targetX = 0; $targetX <= $targetSize; $targetX++) {
                $coverage = 0;
                if ($targetX < $targetSize) {
                    for ($sampleY = 0; $sampleY < 2; $sampleY++) {
                        $sourceY = (int) floor(($targetY + (($sampleY + 0.5) / 2)) * $sourceHeight / $targetSize);
                        for ($sampleX = 0; $sampleX < 2; $sampleX++) {
                            $sourceX = (int) floor(($targetX + (($sampleX + 0.5) / 2)) * $sourceWidth / $targetSize);
                            $coverage += ord($mask[($sourceY * $sourceWidth) + $sourceX]);
                        }
                    }
                    $coverage = (int) (round(($coverage / 4) / 17) * 17);
                }

                if ($coverage > 0 && $runStart === null) {
                    $runStart = $targetX;
                    $runCoverage = $coverage;
                } elseif ($coverage !== $runCoverage && $runStart !== null) {
                    $x = $originX + ($runStart * $pixelSize);
                    $y = $originY + (($targetSize - 1 - $targetY) * $pixelSize);
                    $width = ($targetX - $runStart) * $pixelSize;
                    $paths[$runCoverage] = ($paths[$runCoverage] ?? '')
                        . $x . ' ' . $y . ' ' . $width . ' ' . $pixelSize . " re\n";
                    $runStart = null;
                    if ($coverage > 0) {
                        $runStart = $targetX;
                        $runCoverage = $coverage;
                    }
                }
            }
        }

        ksort($paths);
        foreach ($paths as $coverage => $path) {
            $gray = 1 - ($opacity * $coverage / 255);
            $content .= $gray . " g\n" . $path . "f\n";
        }

        return $content;
    }
}
