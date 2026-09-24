<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class AddPlaceholderImagesSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('→ صور placeholder...');

        $gradients = [
            ['#005a96', '#14b8a6'], ['#7c3aed', '#ec4899'], ['#f59e0b', '#ef4444'],
            ['#10b981', '#3b82f6'], ['#6366f1', '#8b5cf6'], ['#06b6d4', '#0ea5e9'],
            ['#dc2626', '#f97316'], ['#a855f7', '#d946ef'], ['#84cc16', '#22c55e'],
            ['#0891b2', '#14b8a6'], ['#e11d48', '#be123c'], ['#7e22ce', '#4c1d95'],
            ['#1e40af', '#3b82f6'], ['#ca8a04', '#facc15'], ['#059669', '#34d399'],
            ['#9f1239', '#fb7185'], ['#1e293b', '#334155'], ['#d97706', '#fbbf24'],
            ['#065f46', '#10b981'], ['#312e81', '#6366f1'],
        ];

        foreach ($gradients as $i => $c) {
            $n = $i + 1;
            $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 1000">
  <defs><linearGradient id="g{$n}" x1="0%" y1="0%" x2="100%" y2="100%">
    <stop offset="0%" stop-color="{$c[0]}"/><stop offset="100%" stop-color="{$c[1]}"/>
  </linearGradient></defs>
  <rect width="800" height="1000" fill="url(#g{$n})"/>
</svg>
SVG;
            Storage::disk('public')->put("products/placeholder-{$n}.svg", $svg);
        }

        $this->command->line('   ✓ 20 صورة placeholder جاهزة');
    }
}