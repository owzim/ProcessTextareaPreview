<?php

/**
 * Module Config Helper for ProcessWire
 *
 * @author Christian Raunitschka (owzim)
 * @copyright Christian Raunitschka
 * @version 0.0.1
 *
 * See README.md for usage
 *
 */

class TextareaPreviewConfigHelper {

    const DEFAULT_FIELDTYPE = 'InputfieldText';
    const DEFAULT_OPTIONS_FIELDTYPE = 'InputfieldRadios';

    public static function apply($module, $config) {
        foreach ($config as $key => $value) {
            $module->set($key, $value['value']); // set default value in construct
        }
    }

    public static function renderForm($data, $config) {

        $modules = wire('modules');

        $data = (object) $data; // cast to object, because it's prettier to acces keys
        $config = (object) $config; // cast to object, because it's prettier to acces keys

        foreach ($config as $key => $valueData) {
            if(!isset($data->$key)) {
                $data->$key = $valueData->value;
            }
        }

        $form = new InputfieldWrapper();

        foreach ($config as $key => $valueData) {

            $valueData = (object) $valueData; // cast to object, because it's prettier to acces keys
            $value = $valueData->value;
            $label = isset($valueData->label) ? $valueData->label : $key;

            if (isset($valueData->options)) {
                $inputfieldType = isset($valueData->inputFieldtype) ? $valueData->inputFieldtype : self::DEFAULT_OPTIONS_FIELDTYPE;
            } else {
                $inputfieldType = isset($valueData->inputFieldtype) ? $valueData->inputFieldtype : self::DEFAULT_FIELDTYPE;
            }

            $f = $modules->get($inputfieldType);
            $f->name = $key;
            $f->label = $label;

            if (isset($valueData->options)) {
                foreach ($valueData->options as $optionLabel => $optionValue) {
                    $f->addOption($optionValue, $optionLabel);
                }
            }

            if (isset($valueData->attributes)) {
                foreach ($valueData->attributes as $attributeKey => $attributeValue) {
                    $f->set($attributeKey, $attributeValue);
                }
            }

            if(isset($data->$key)) {
                $f->value = $data->$key;
            } else {
                $f->value = $value;
            }

            $form->add($f);
        }

        return $form;
    }
}