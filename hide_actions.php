<?php
$files = glob('resources/views/*/index.blade.php');
foreach($files as $file) {
    $content = file_get_contents($file);
    
    // Wrap Add button. They all have <a href="{{ route('*.create') }}" ...>
    $content = preg_replace('/(<a href="\{\{ route\(\'[^.]+\.create\'\) \}\}".*?<\/a>)/s', "@if(auth()->user()->isAdmin())\n$1\n@endif", $content);
    
    // Wrap the <th>الإجراءات</th> or <th>إلغاء</th>
    $content = preg_replace('/(<th[^>]*>(?:الإجراءات|إلغاء)<\/th>)/', "@if(auth()->user()->isAdmin())\n$1\n@endif", $content);
    
    // Wrap the <td class="... flex justify-center gap-2"> ... </td> which holds edit/delete forms
    $content = preg_replace('/(<td class="py-3 px-4 flex justify-center gap-2">.*?<\/td>)/s', "@if(auth()->user()->isAdmin())\n$1\n@endif", $content);
    
    file_put_contents($file, $content);
}
echo "Done\n";