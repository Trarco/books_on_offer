<?php

defined('MOODLE_INTERNAL') || die();
require_once(__DIR__ . '/lib.php');

class block_books_on_offer extends block_base
{
    public function init()
    {
        $this->title = get_string('pluginname', 'block_books_on_offer');
    }

    public function applicable_formats()
    {
        return ['course-view' => true];
    }

    public function instance_allow_multiple()
    {
        return false;
    }

    public function get_content()
    {
        global $OUTPUT, $PAGE;

        if ($this->content !== null) {
            return $this->content;
        }

        $PAGE->requires->css(new moodle_url('/blocks/books_on_offer/style.css'));
        $PAGE->requires->js_call_amd('block_books_on_offer/carousel', 'init');


        $this->content = new stdClass();

        $title = !empty($this->config->blocktitle) ? $this->config->blocktitle : get_string('pluginname', 'block_books_on_offer');
        $subtitle = !empty($this->config->subtitle) ? $this->config->subtitle : '';

        $books = [];

        if (!empty($this->config->booktitle) && is_array($this->config->booktitle)) {
            debugging('Avvio ciclo di lettura dei libri configurati', DEBUG_DEVELOPER);

            foreach ($this->config->booktitle as $i => $booktitle) {
                $author = $this->config->bookauthor[$i] ?? '[Nessun autore]';
                debugging("Libro $i: titolo='$booktitle', autore='$author'", DEBUG_DEVELOPER);

                // Recupera file immagine
                $fs = get_file_storage();
                $files = $fs->get_area_files($this->context->id, 'block_books_on_offer', 'bookimage', $i, 'itemid, filepath, filename', false);

                $imageurl = '';
                if (!empty($files)) {
                    $file = reset($files);
                    $imageurl = moodle_url::make_pluginfile_url(
                        $file->get_contextid(),
                        $file->get_component(),
                        $file->get_filearea(),
                        $file->get_itemid(),
                        $file->get_filepath(),
                        $file->get_filename()
                    );
                    debugging("Immagine trovata per libro $i: $imageurl", DEBUG_DEVELOPER);
                } else {
                    debugging("Nessuna immagine trovata per libro $i", DEBUG_DEVELOPER);
                }

                $books[] = [
                    'title' => $booktitle,
                    'author' => $author,
                    'image' => $imageurl
                ];
            }
        } else {
            debugging('Nessun libro configurato o campo `booktitle` non è un array', DEBUG_DEVELOPER);
        }

        $this->content->text = $OUTPUT->render_from_template('block_books_on_offer/content', [
            'blocktitle' => $title,
            'subtitle' => $subtitle,
            'books' => $books,
        ]);

        $this->content->footer = '';

        return $this->content;
    }

    public function has_config()
    {
        return false;
    }

    public function instance_config_save($data, $nolongerused = false)
    {
        global $USER;

        $filtered = [
            'booktitle' => [],
            'bookauthor' => [],
            'bookimage' => []
        ];

        if (!empty($data->booktitle) && is_array($data->booktitle)) {
            foreach ($data->booktitle as $i => $title) {
                $todelete = !empty($data->deletebook[$i]); // checkbox attiva?

                if (!$todelete) {
                    // Salvo solo righe valide
                    $filtered['booktitle'][] = $title;
                    $filtered['bookauthor'][] = $data->bookauthor[$i] ?? '';
                    $filtered['bookimage'][] = $data->bookimage[$i] ?? '';

                    // Sposto i file solo se la riga non è da eliminare
                    $draftitemid = $data->bookimage[$i] ?? null;
                    if ($draftitemid) {
                        file_save_draft_area_files(
                            $draftitemid,
                            $this->context->id,
                            'block_books_on_offer',
                            'bookimage',
                            count($filtered['booktitle']) - 1, // nuovo index
                            ['subdirs' => 0, 'maxfiles' => 1, 'accepted_types' => ['image']]
                        );
                    }
                }
            }
        }

        // Sovrascrivi i dati con quelli filtrati
        $data->booktitle = $filtered['booktitle'];
        $data->bookauthor = $filtered['bookauthor'];
        $data->bookimage = $filtered['bookimage'];

        parent::instance_config_save($data, $nolongerused);
    }
}
