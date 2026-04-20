<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class SizesSeeder extends Seeder
{
    public function run(): void
    {
        $hoodies   = ['XS','S','M','L','XL','XXL'];
        $camisetas = ['XS','S','M','L','XL','XXL'];
        $pantalones= ['XS','S','M','L','XL','XXL'];
        $accesorios= ['Talla única'];

        Product::whereHas('category', fn($q) => $q->where('name','Hoodies'))
            ->update(['sizes' => json_encode($hoodies), 'colors' => json_encode(['Negro','Gris','Beige','Azul Marino','Blanco','Vino','Rosa','Mostaza'])]);

        Product::whereHas('category', fn($q) => $q->where('name','Camisetas'))
            ->update(['sizes' => json_encode($camisetas), 'colors' => json_encode(['Negro','Blanco','Verde Oliva','Gris'])]);

        Product::whereHas('category', fn($q) => $q->where('name','Pantalones'))
            ->update(['sizes' => json_encode($pantalones), 'colors' => json_encode(['Negro','Beige','Gris'])]);

        Product::whereHas('category', fn($q) => $q->where('name','Accesorios'))
            ->update(['sizes' => json_encode($accesorios), 'colors' => json_encode(['Negro','Beige'])]);

        $this->command->info('Tallas y colores asignados a todos los productos.');
    }
}
