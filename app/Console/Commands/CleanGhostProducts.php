<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Carbon\Carbon;

class CleanGhostProducts extends Command
{
  
    protected $signature = 'products:clean-drafts';
    
    protected $description = 'Remove produtos em rascunho (draft) abandonados há mais de 24 horas para limpar o banco.';

    public function handle()
    {
        
        $ghosts = Product::where('status', 'draft')
            ->where(function($query) {
                $query->where('name', 'new product')
                      ->orWhereNull('name');
            })
            ->where('created_at', '<', Carbon::now()->subHours(24))
            ->get();

        $count = $ghosts->count();

        foreach ($ghosts as $ghost) {
            $ghost->clearMediaCollection('cover'); 
            $ghost->clearMediaCollection('gallery');
            $ghost->delete();
        }

        $this->info("Limpeza concluída: {$count} produtos fantasmas foram removidos do banco de dados.");
        return 0;
    }
}