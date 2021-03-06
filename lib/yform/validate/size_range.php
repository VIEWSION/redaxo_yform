<?php

/**
 * yform
 * @author jan.kristinus[at]redaxo[dot]org Jan Kristinus
 * @author <a href="http://www.yakamara.de">www.yakamara.de</a>
 */

class rex_yform_validate_size_range extends rex_yform_validate_abstract
{

    function enterObject()
    {
        if ($this->params['send'] == '1') {

            $Object = $this->getValueObject();

            if ($Object->getValue() == '') {
                return;
            }

            $w = false;

            $minsize = -1;
            if ($this->getElement('min') != '') {
                $minsize = (int) $this->getElement('min');
            }

            $maxsize = -1;
            if ($this->getElement('max') != '') {
                $maxsize = (int) $this->getElement('max');
            }

            $size = strlen($Object->getValue());

            if ($minsize > -1 && $minsize > $size) {
                $w = true;
            }

            if ($maxsize > -1 && $maxsize < $size) {
                $w = true;
            }

            if ($w) {
                $this->params['warning'][$Object->getId()] = $this->params['error_class'];
                $this->params['warning_messages'][$Object->getId()] = $this->getElement('message');
            }

        }
    }

    function getDescription()
    {
        return 'size_range -> Laenge der Eingabe muss mindestens und/oder maximal sein, beispiel: validate|size_range|label|[minsize]|[maxsize]|Fehlermeldung';
    }

    function getDefinitions()
    {
        return array(
            'type' => 'validate',
            'name' => 'size_range',
            'values' => array(
                'name'    => array( 'type' => 'select_name', 'label' => 'Name' ),
                'min'     => array( 'type' => 'text', 'label' => 'Minimale Anzahl der Zeichen (opt)'),
                'max'     => array( 'type' => 'text', 'label' => 'Maximale Anzahl der Zeichen (opt)'),
                'message' => array( 'type' => 'text', 'label' => 'Fehlermeldung'),
            ),
            'description' => 'Hiermit wird ein Name überprüft ob er eine bestimmte minimale und maximale Anzahl von Zeichen hat',
        );

    }


}
