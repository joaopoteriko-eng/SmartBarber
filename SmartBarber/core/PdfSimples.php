<?php
class PdfSimples
{
    private array $linhas = [];

    public function titulo(string $texto): void { $this->linhas[] = [$texto, 16, true]; }
    public function linha(string $texto, int $tam = 11, bool $negrito = false): void { $this->linhas[] = [$texto, $tam, $negrito]; }
    public function espaco(): void { $this->linhas[] = ['', 8, false]; }

    private function escapar(string $t): string
    {
        $t = mb_convert_encoding($t, 'Windows-1252', 'UTF-8');  
        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $t);
    }

    public function gerar(): string
    {
        $paginas = [];
        $atual = [];
        $y = 800;
        foreach ($this->linhas as [$texto, $tam, $neg]) {
            if ($y < 60) {
                $paginas[] = $atual;
                $atual = [];
                $y = 800;
            }
            $atual[] = [$texto, $tam, $neg, $y];
            $y -= $tam + 6;
        }
        $paginas[] = $atual;

        $kids = [];
        foreach (array_keys($paginas) as $i) {
            $kids[] = (5 + 2 * $i) . ' 0 R';
        }
        $objs = [
            '<< /Type /Catalog /Pages 2 0 R >>',
            '<< /Type /Pages /Kids [' . implode(' ', $kids) . '] /Count ' . count($paginas) . ' >>',
            '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica /Encoding /WinAnsiEncoding >>',
            '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold /Encoding /WinAnsiEncoding >>',
        ];
        foreach ($paginas as $i => $linhas) {
            $stream = '';
            foreach ($linhas as [$texto, $tam, $neg, $y]) {
                if ($texto === '') continue;
                $stream .= 'BT /' . ($neg ? 'F2' : 'F1') . " $tam Tf 50 $y Td (" . $this->escapar($texto) . ") Tj ET\n";
            }
            $objs[] = '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] '
                    . '/Resources << /Font << /F1 3 0 R /F2 4 0 R >> >> /Contents ' . (6 + 2 * $i) . ' 0 R >>';
            $objs[] = '<< /Length ' . strlen($stream) . " >>\nstream\n" . $stream . 'endstream';
        }
       
        $pdf = "%PDF-1.4\n";
        $pos = [];
        foreach ($objs as $i => $obj) {
            $pos[] = strlen($pdf);
            $pdf .= ($i + 1) . " 0 obj\n" . $obj . "\nendobj\n";
        }
        $xref = strlen($pdf);
        $pdf .= "xref\n0 " . (count($objs) + 1) . "\n0000000000 65535 f \n";
        foreach ($pos as $p) {
            $pdf .= sprintf("%010d 00000 n \n", $p);
        }
        $pdf .= "trailer\n<< /Size " . (count($objs) + 1) . " /Root 1 0 R >>\nstartxref\n$xref\n%%EOF";
        return $pdf;
    }
}