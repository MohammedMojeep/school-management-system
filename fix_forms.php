<?php
$files = glob('resources/views/*/index.blade.php');
foreach($files as $file) {
    $content = file_get_contents($file);
    // Remove the default onsubmit
    $content = preg_replace('/onsubmit="return confirm\([^)]+\)"/', '', $content);
    // For students and teachers
    if (strpos($file, 'students') !== false || strpos($file, 'teachers') !== false) {
        $msg = 'سيتم حذف حساب المستخدم أيضاً!';
    } else {
        $msg = 'لن تتمكن من التراجع عن هذه الخطوة! سيتم حذف السجل بالكامل.';
    }
    // Add id to form
    $content = preg_replace('/<form action="([^"]+)" method="POST"/', '<form action="$1" method="POST" id="delete-form-{{ $loop->iteration }}"', $content);
    $content = preg_replace('/<button type="submit"/', '<button type="button" onclick="confirmDelete(\'delete-form-{{ $loop->iteration }}\', \'' . $msg . '\')"', $content);
    file_put_contents($file, $content);
}
echo "Done\n";