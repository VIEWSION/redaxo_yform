<?php

/**
 * yform
 * @author jan.kristinus[at]redaxo[dot]org Jan Kristinus
 * @author <a href="http://www.yakamara.de">www.yakamara.de</a>
 */

class rex_yform_value_be_media extends rex_yform_value_abstract
{

    function enterObject()
    {
        static $counter = 0;
        $counter++;

        if ($this->getValue() == '' && !$this->params['send']) {
            $this->setValue($this->getElement(3));
        }

        $this->params['form_output'][$this->getId()] = $this->parse('value.be_media.tpl.php', compact('counter'));

        $this->params['value_pool']['email'][$this->getElement(1)] = stripslashes($this->getValue());
        if ($this->getElement(4) != 'no_db') {
            $this->params['value_pool']['sql'][$this->getElement(1)] = $this->getValue();
        }
    }

    function getDescription()
    {
        return 'be_media -> Beispiel: be_media|name|label|defaultwert|no_db';
    }

    function getDefinitions()
    {
        return array(
            'type' => 'value',
            'name' => 'be_media',
            'values' => array(
                'name' => array( 'type' => 'name',   'label' => 'Name' ),
                'label' => array( 'type' => 'text',    'label' => 'Bezeichnung'),
                'default' => array( 'type' => 'text',     'label' => 'Defaultwert'),
            ),
            'description' => 'Mediafeld, welches eine Datei aus dem Medienpool holt',
            'dbtype' => 'text'
        );
    }


    static function getListValue($params)
    {
        $return = $params['subject'];
        if (strlen($return) > 16) {
            $return = '<span style="white-space:nowrap;" title="' . htmlspecialchars($return) . '">' . substr($return, 0, 6) . ' ... ' . substr($return, -6) . '</span>';
        }
        return $return;

    }

    public static function getSearchField($params)
    {
        $params['searchForm']->setValueField('text', array('name' => $params['field']->getName(), 'label' => $params['field']->getLabel()));
    }

    public static function getSearchFilter($params)
    {
        $value = $params['value'];
        $field =  $params['field']->getName();

        if ($value == '(empty)') {
            return ' (`' . mysql_real_escape_string($field) . '` = "" or `' . mysql_real_escape_string($field) . '` IS NULL) ';

        } elseif ($value == '!(empty)') {
            return ' (`' . mysql_real_escape_string($field) . '` <> "" and `' . mysql_real_escape_string($field) . '` IS NOT NULL) ';

        }

        $pos = strpos($value, '*');
        if ($pos !== false) {
            $value = str_replace('%', '\%', $value);
            $value = str_replace('*', '%', $value);
            return ' `' . mysql_real_escape_string($field) . "` LIKE  '" . mysql_real_escape_string($value) . "'";
        } else {
            return ' `' . mysql_real_escape_string($field) . "` =  '" . mysql_real_escape_string($value) . "'";
        }

    }

}
