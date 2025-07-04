<?php

defined('MOODLE_INTERNAL') || die();

class block_books_on_offer_edit_form extends block_edit_form
{
    protected function specific_definition($mform)
    {
        // Sezione titolo
        $mform->addElement('header', 'configheader', get_string('configtitle', 'block_books_on_offer'));

        // Titolo blocco
        $mform->addElement('text', 'config_blocktitle', get_string('title', 'block_books_on_offer'));
        $mform->setDefault('config_blocktitle', 'Books on Offer -30%');
        $mform->setType('config_blocktitle', PARAM_TEXT);

        // Sottotitolo
        $mform->addElement('text', 'config_subtitle', get_string('subtitle', 'block_books_on_offer'));
        $mform->setDefault('config_subtitle', 'Lorem ipsum dolor sit amet...');
        $mform->setType('config_subtitle', PARAM_TEXT);

        // Elementi ripetibili per i libri
        $repeatarray = [];

        $repeatarray[] = $mform->createElement('filemanager', 'config_bookimage', get_string('bookimage', 'block_books_on_offer'), null, [
            'subdirs' => 0,
            'maxfiles' => 1,
            'accepted_types' => ['image'],
        ]);
        $repeatarray[] = $mform->createElement('text', 'config_booktitle', get_string('booktitle', 'block_books_on_offer'), ['size' => '60']);
        $repeatarray[] = $mform->createElement('text', 'config_bookauthor', get_string('bookauthor', 'block_books_on_offer'), ['size' => '60']);

        $repeateloptions = [];
        $repeateloptions['config_bookimage']['type'] = PARAM_RAW;
        $repeateloptions['config_booktitle']['type'] = PARAM_TEXT;
        $repeateloptions['config_bookauthor']['type'] = PARAM_TEXT;

        // Ultimo parametro 'config_' permette il salvataggio corretto in $this->config
        // Determina il numero di elementi già presenti
        $repeats = 3;
        if (!empty($this->block->config->booktitle) && is_array($this->block->config->booktitle)) {
            $repeats = count($this->block->config->booktitle);
        }

        $this->repeat_elements($repeatarray, $repeats, $repeateloptions, 'numbooks', 'addbooks', 1, get_string('addmorebooks', 'block_books_on_offer'), true, 'config');
    }

    public function set_data($defaults)
    {
        global $USER;

        // Prepara i file manager per ogni campo immagine ripetuto
        if (!empty($defaults->config_bookimage)) {
            foreach ($defaults->config_bookimage as $i => $unused) {
                $draftitemid = file_get_submitted_draft_itemid("config_bookimage[$i]");
                file_prepare_draft_area($draftitemid, $this->block->context->id, 'block_books_on_offer', 'bookimage', $i, [
                    'subdirs' => 0,
                    'maxfiles' => 1,
                    'accepted_types' => ['image']
                ]);
                $defaults->{"config_bookimage[$i]"} = $draftitemid;
            }
        }

        parent::set_data($defaults);
    }
}
