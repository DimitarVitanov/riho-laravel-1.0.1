<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

// Fix misspelled "new yourk" to "New York"
use Illuminate\Support\Facades\DB;
use App\Models\LocalSeoTarget;

echo "Fixing misspelled keywords...\n";

// Find and fix "new yourk" variations
$misspelledVariations = [
    'new yourk',
    'New yourk',
    'new Yourk',
    'New Yourk'
];

$correctName = 'New York';

foreach ($misspelledVariations as $misspelled) {
    $count = LocalSeoTarget::where('target_value', 'like', '%' . $misspelled . '%')->count();
    
    if ($count > 0) {
        echo "Found $count records with '$misspelled'\n";
        
        LocalSeoTarget::where('target_value', 'like', '%' . $misspelled . '%')
            ->update(['target_value' => $correctName]);
            
        echo "Fixed '$misspelled' to '$correctName'\n";
    }
}

// Also check for any other common misspellings
$otherFixes = [
    'new york city' => 'New York City',
    'newyork' => 'New York',
    'NYC' => 'New York City',
];

foreach ($otherFixes as $incorrect => $correct) {
    $count = LocalSeoTarget::where('target_value', $incorrect)->count();
    
    if ($count > 0) {
        echo "Found $count records with '$incorrect'\n";
        
        LocalSeoTarget::where('target_value', $incorrect)
            ->update(['target_value' => $correct]);
            
        echo "Fixed '$incorrect' to '$correct'\n";
    }
}

echo "Keyword cleanup completed!\n";
