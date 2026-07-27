<?php

class HiddenItemGame
{
    private array $grid;
    private int $startX = -1;
    private int $startY = -1;

    public function __construct(array $grid)
    {
        $this->grid = $grid;
        $this->findStartingPosition();
    }

    /**
     * Mencari koordinat posisi awal pemain (X)
     */
    private function findStartingPosition(): void
    {
        foreach ($this->grid as $y => $row) {
            $x = strpos($row, 'X');
            if ($x !== false) {
                $this->startX = $x;
                $this->startY = $y;
                break;
            }
        }
        
        if ($this->startX === -1) {
            die("Posisi awal 'X' tidak ditemukan di dalam grid.\n");
        }
    }

    /**
     * Mengeksekusi pencarian rute: Atas -> Kanan -> Bawah
     */
    public function findProbableLocations(): array
    {
        $probableLocations = [];

        // 1. Bergerak ke Atas/Utara (A steps)
        $upPositions = [];
        for ($y = $this->startY - 1; $y >= 0; $y--) {
            // Jika bertemu rintangan '#', hentikan pergerakan ke arah ini
            if ($this->grid[$y][$this->startX] === '#') {
                break;
            }
            $upPositions[] = ['x' => $this->startX, 'y' => $y];
        }

        // 2. Bergerak ke Kanan/Timur (B steps) dari titik hasil langkah 1
        $rightPositions = [];
        foreach ($upPositions as $pos) {
            for ($x = $pos['x'] + 1; $x < strlen($this->grid[0]); $x++) {
                if ($this->grid[$pos['y']][$x] === '#') {
                    break;
                }
                $rightPositions[] = ['x' => $x, 'y' => $pos['y']];
            }
        }

        // 3. Bergerak ke Bawah/Selatan (C steps) dari titik hasil langkah 2
        foreach ($rightPositions as $pos) {
            for ($y = $pos['y'] + 1; $y < count($this->grid); $y++) {
                if ($this->grid[$y][$pos['x']] === '#') {
                    break;
                }
                // Semua titik yang berhasil dicapai di langkah ke-3 ini adalah kemungkinan lokasi item
                $probableLocations[] = ['x' => $pos['x'], 'y' => $y];
            }
        }

        return $probableLocations;
    }

    /**
     * Menampilkan daftar koordinat ke terminal
     */
    public function printCoordinates(array $locations): void
    {
        echo "Daftar kemungkinan koordinat lokasi item (X, Y):\n";
        echo "----------------------------------------------\n";
        if (empty($locations)) {
            echo "Tidak ada lokasi yang ditemukan.\n";
            return;
        }

        foreach ($locations as $loc) {
            echo "- (X: {$loc['x']}, Y: {$loc['y']})\n";
        }
        echo "\n";
    }

    /**
     * Menampilkan ulang grid dengan tanda '$' di lokasi item tersembunyi
     */
    public function printBonusGrid(array $locations): void
    {
        echo "Bonus: Peta Kemungkinan Lokasi (\$):\n";
        echo "---------------------------------\n";
        
        $bonusGrid = $this->grid;
        
        // Timpa karakter '.' dengan '$' pada setiap koordinat yang ditemukan
        foreach ($locations as $loc) {
            $bonusGrid[$loc['y']][$loc['x']] = '$';
        }

        // Cetak grid
        foreach ($bonusGrid as $row) {
            echo $row . "\n";
        }
        echo "\n";
    }
}

// ==========================================
// EKSEKUSI PROGRAM
// ==========================================

// Layout grid sesuai soal
$layout = [
    "########",
    "#......#",
    "#.###..#",
    "#...#.##",
    "#X#....#",
    "########"
];

// Inisiasi Game
$game = new HiddenItemGame($layout);

// Cari lokasi item
$locations = $game->findProbableLocations();

// Tampilkan output sesuai requirement[cite: 1]
$game->printCoordinates($locations);
$game->printBonusGrid($locations);