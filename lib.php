<?php

defined('MOODLE_INTERNAL') || die();

/**
 * Serve i file del blocco books_on_offer.
 */
function block_books_on_offer_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = [])
{
    global $USER, $DB;

    if ($context->contextlevel != CONTEXT_BLOCK) {
        return false;
    }

    // Verifica che l'utente abbia accesso alla pagina dove è visibile il blocco
    if (!isloggedin()) {
        return false;
    }

    if ($filearea !== 'bookimage') {
        return false;
    }

    $itemid = array_shift($args);
    $filename = array_pop($args);
    $filepath = $args ? '/' . implode('/', $args) . '/' : '/';

    $fs = get_file_storage();
    $file = $fs->get_file($context->id, 'block_books_on_offer', $filearea, $itemid, $filepath, $filename);

    if (!$file || $file->is_directory()) {
        return false;
    }

    // Serve il file
    send_stored_file($file, 0, 0, $forcedownload, $options);
}
