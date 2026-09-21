<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateViews extends Command
{
    protected $signature = 'make:views';
    protected $description = 'Generate placeholder blade views for WellCare project';

    public function handle()
    {
        $structure = [
            "" => ["dashboard", "analytics", "ecommerce"],
            "auth" => ["login", "register", "reset", "lock", "verify", "email_verification", "logout"],
            "pages" => ["starter", "profile", "pricing", "timeline", "invoice", "faqs", "gallery", "maintenance", "comingsoon"],
            "apps" => ["todo", "contacts", "calendar"],
            "forms" => ["elements", "validation", "editor", "pickers"],
            "tables" => ["basic", "datatables"],
            "charts" => ["line", "area", "column", "bar", "mixed", "timeline", "rangearea", "funnel", "candlestick",
                        "boxplot", "bubble", "scatter", "heatmap", "treemap", "pie", "radialbar", "radar", "polar"],
            "maps" => ["google", "vector"],
            "errors" => ["404", "500", "503", "429", "offline"],
        ];

        foreach ($structure as $folder => $files) {
            $folderPath = resource_path("views/" . $folder);
            File::ensureDirectoryExists($folderPath);

            foreach ($files as $file) {
                $filePath = $folderPath . "/" . $file . ".blade.php";
                if (!File::exists($filePath)) {
                    File::put($filePath, $this->bladeContent(ucwords(str_replace("_", " ", $file))));
                    $this->info("Created: " . $filePath);
                }
            }
        }

        $this->info("✅ All placeholder views generated successfully!");
    }

    private function bladeContent($title)
    {
        return <<<BLADE
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{$title} Page</h1>
</div>
@endsection
BLADE;
    }
}
