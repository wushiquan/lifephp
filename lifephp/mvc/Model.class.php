<?php 
// -----------------------------------------------------------------------
// + LifePHP - Make coding life more efficient,Let the program run faster
// -----------------------------------------------------------------------
// + lifephp MVC model base class
// -----------------------------------------------------------------------
// + Copyright (c) 2016 http://www.lifephp.net  All rights reserved
// -----------------------------------------------------------------------
// + Author:  shiquanwu <2448154608@qq.com>
// -----------------------------------------------------------------------
// + Version: 1.0
// -----------------------------------------------------------------------

namespace lifephp\mvc;

use Life;
use ReflectionClass;
class Model
{
	/**
     * @var array validation errors (attribute name => array of errors)
     */
    private $errors;

	/**
	 * @uses returns the attribute labels.
     */
    public function attributeLabels()
    {
        return [];
    }

    /**
     * @uses   Returns the validation rules for attributes.
     * @return array validation rules
     */
    public function rules()
    {
        return [];
    }

    /**
     * @uses   Returns the form name that this model class should use.
     * @return string the form name of this model class.
     */
    public function getFormName()
    {
        $reflector = new ReflectionClass($this);
        return $reflector->getShortName();
    }

    /**
     * @uses  Populates the model with the input data.   
     *
     * @param array $data the data array to load, typically `$_POST`,`$_REQUEST` or `$_GET`.
     * @param string $formName the form name to use to load the data into the model.
     * If not set, [[getFormName()]] is used.
     * @return boolean whether `load()` found the expected form in `$data`.
     */
    public function load($data, $formName = null)
    {
        $scope = $formName === null ? $this->getFormName() : $formName;
        if(isset($data[$scope])) {
            $this->setAttributes($data[$scope]);

            return true;
        } else {
            return false;
        }
    }

    /**
     * @uses  Sets the attribute values in a massive way.
     * @param array $values attribute values (name => value) to be assigned to the model.
     * A safe attribute is one that is associated with a validation rule in the current [[scenario]].
     */
    public function setAttributes($values)
    {
        if (is_array($values)) {
            $attributes = array_flip($this->attributes());
            foreach ($values as $column_name => $value) {
                if (isset($attributes[$column_name])) {
                    $this->$column_name = $value;
                }
            }
        }
    }

    /**
     * @uses Returns the list of attribute names.
     * 		 By default, this method returns all public non-static properties of the class.
     * @return array list of attribute names.
     */
    public function attributes()
    {
        $class = new ReflectionClass($this);
        $names = [];
        foreach ($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            if (!$property->isStatic()) {
                $names[] = $property->getName();
            }
        }

        return $names;
    }

     /**
     * @uses  Returns attribute values.
     * @param array $names list of attributes whose value needs to be returned.
     * Defaults to null, meaning all attributes listed in [[attributes()]] will be returned.
     * If it is an array, only the attributes in the array will be returned.
     * @param array $except list of attributes whose value should NOT be returned.
     * @return array attribute values (name => value).
     */
    public function getAttributes($names = null, $except = [])
    {
        $values = [];
        if ($names === null) {
            $names = $this->attributes();
        }
        foreach ($names as $name) {
            $values[$name] = $this->$name;
        }
        foreach ($except as $name) {
            unset($values[$name]);
        }

        return $values;
    }

    /**
     * @uses  PHP setter magic method.
     * This method is overridden so that AR attributes can be accessed like properties.
     * @param string $name property name
     * @param mixed $value property value
     */
    public function setAttr($name, $value)
    {
        $this->$name = $value;
    }
}