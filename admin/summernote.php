<?php
/**
 * Helper function to render a Summernote editor field.
 *
 * @param string $name      Field name/id for the textarea.
 * @param string $content   Initial HTML content for the editor.
 * @param string $attributes Extra HTML attributes for the textarea.
 */
function summernote_editor(string $name, string $content = '', string $attributes = ''): void {
    $attrs = $attributes ? ' ' . $attributes : '';
    echo '<textarea id="' . htmlspecialchars($name, ENT_QUOTES) . '" name="' . htmlspecialchars($name, ENT_QUOTES) . '"' . $attrs . '>'
        . htmlspecialchars($content) . '</textarea>';
}
?>
