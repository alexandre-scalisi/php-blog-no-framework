<?php

namespace App\Validation;

use Exception;

class Validator
{
    private $db;

    private $defaultMessages = [
        'type' => 'The :field field should be of type :type',
        'required' => 'The :field field is required but was empty',
        'min' => 'The :field field is below min :min',
        'max' => 'The :field field is below min :max',
        'unique' => 'The :field field value was already in the table :table',
        'passwordConfirm' => 'The passwords didn\'t match' 
    ];

    private $fieldName;
    private $fieldValue;

    private $type;
    private $required;
    private $min;
    private $max;
    private $unique;
    private $passwordConfirm;

    private $errors = [];

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }


    /**
     * Method that validates a form field
     * 
     * @param string $fieldName              name of the form field
     * @param mixed|null $fieldValue         value of the field
     * @param array|null $params [           rules to validate for the field
     *  
     *      'type' => string|null,           type of the field
     *      'required' => bool,              is field required ?
     *      'min' => int|null,               min value of the field
     *      'max' => int|null,               max value of the field
     *      'passwordConfirm' => array(      
     *          'fieldName' => string,       name of passwordConfirm field
     *          'fieldValue' => string|null  value of passwordConfirm field
     *       ),
     *      'unique' => array(
     *          'fieldName' => string,       name of unique table
     *          'fieldValue' => string|null  name of unique column
     *       ),
     * ]        
     * 
     */
    public function validate(string $fieldName, $fieldValue = null, ?array $params = []): self
    {
        $this->reset();
        $this->fieldName = $fieldName;
        $this->fieldValue = $fieldValue;
        $this->parseParams($params);
        $this->handleExceptions();
        $this->handleType();
        $this->handleRequired();
        $this->handleMinMax();
        $this->handlePassword();
        $this->handleUnique();



        if (!empty($this->errors))
            $_SESSION['errors'][$this->fieldName] = $this->errors;

        return $this;
    }

    private function parseParams(?array $params)
    {
        $this->type = $params['type'] ?? 'any';
        $this->required = $params['required'] ?? false;
        $this->passwordConfirm = $params['passwordConfirm'] ?? null;
        $this->min = $params['min'] ?? null;
        $this->max = $params['max'] ?? null;
        $this->unique = $params['unique'] ?? null;
    }

    private function handleExceptions()
    {
        if (!in_array($this->type, ['int', 'string', 'password', 'email', 'any'])) throw new Exception('wrong type');
        if (!is_bool($this->required)) throw new Exception('required should be a boolean');
        if (!is_int($this->min) && !is_null($this->min)) throw new Exception('min must be an integer or null');
        if (!is_int($this->max) && !is_null($this->max)) throw new Exception('max must be an integer or null');
        if (is_int($this->max) && is_null($this->max) && $this->min < $this->max) throw new Exception('max must be an less than min');

        if($this->unique && (!is_array($this->unique) || count($this->unique) !== 2)) {
            throw new Exception('unique param should be an array with 2 values');
        }
        if ($this->unique && !in_array($this->unique[0], array_values($this->db->query("SHOW TABLES")->fetchAll(\PDO::FETCH_COLUMN))))
            throw new Exception('table ' . $this->unique[0] . ' was not found in the database');
        if ($this->unique && !in_array($this->unique[1], $this->db->query("DESCRIBE {$this->unique[0]}")->fetchAll(\PDO::FETCH_COLUMN)))
            throw new Exception("column {$this->unique[1]} was not found in the table {$this->unique[0]}");

        if ($this->passwordConfirm && (!is_array($this->passwordConfirm) || count($this->passwordConfirm) !== 2)) {
            throw new Exception('unique param should be an array with 2 values');
        }
    }

    private function handleType()
    {
        if (!$this->fieldValue) return;
        $error = false;
        switch ($this->type) {
            case 'int':
                if (!is_int($this->fieldValue)) $error = true;
                break;
            case 'string':
                if (!is_string($this->fieldValue)) $error = true;
                break;
            case 'email':
                if (!filter_var($this->fieldValue, FILTER_VALIDATE_EMAIL)) $error = true;
        }

        if ($error) {
            $this->errors['type'] = str_replace(':type', $this->type, $this->defaultMessages['type']);
            $this->errors['type'] = str_replace(':field', $this->fieldName, $this->errors['type']);
        }
    }

    private function handleRequired()
    {
        if ($this->required && $this->fieldValue === null)
            $this->errors['required'] = str_replace(':field', $this->fieldName, $this->defaultMessages['required']);
    }

    private function handleMinMax()
    {
        /** return if no field value or no min and max */
        if (!$this->fieldValue || (!$this->min || !$this->max)) return;

        /** handle int case */
        if (is_int($this->fieldValue)) {
            if ($this->min && $this->fieldValue < $this->min) {
                $this->errors['min'] = str_replace(':min', $this->min, $this->defaultMessages['min']);
                $this->errors['min'] = str_replace(':field', $this->fieldName, $this->errors['min']);
            } else if ($this->max && $this->fieldValue > $this->max) {
                $this->errors['max'] = str_replace(':max', $this->max, $this->defaultMessages['max']);
                $this->errors['max'] = str_replace(':field', $this->fieldName, $this->errors['max']);
            }
        } else if (is_string($this->fieldValue)) {
            /** handle string cases */
            if ($this->min && strlen($this->fieldValue) < $this->min) {
                $this->errors['min'] = str_replace(':min', $this->min, $this->defaultMessages['min']);
                $this->errors['min'] = str_replace(':field', $this->fieldName, $this->errors['min']);
            } else if ($this->max && strlen($this->fieldValue) > $this->max) {
                $this->errors['max'] = str_replace(':max', $this->max, $this->defaultMessages['max']);
                $this->errors['max'] = str_replace(':field', $this->fieldName, $this->errors['max']);
            }
        }
    }

    private function reset()
    {
        $this->fieldName = null;
        $this->fieldValue = null;
        $this->type = null;
        $this->required = null;
        $this->passwordConfirm = null;
        $this->min = null;
        $this->max = null;
        $this->unique = null;

        $this->errors = [];
    }

    public function handleUnique() {
        if(!$this->unique || !$this->fieldValue) return;
        [$table, $column] = $this->unique;

        $query = "SELECT * FROM $table WHERE $column = ?";

        $stmt = $this->db->prepare($query);
        $stmt->execute([$this->fieldValue]);

        if($stmt->fetch()) {
            $this->errors['unique'] = str_replace(':table', $table, $this->defaultMessages['unique']);
            $this->errors['unique'] = str_replace(':field', $this->fieldName, $this->errors['unique']);
        }
    }
    public function handlePassword() {
        if(!$this->required || !$this->fieldValue || $this->type !== 'password' ) return;

        if(!$this->passwordConfirm[1]) {
            return $this->errors[$this->passwordConfirm[0]]['required'] = str_replace(':field', $this->passwordConfirm[0], $this->defaultMessages['required']);
        }

        if($this->passwordConfirm[1] !== $this->fieldValue) {
            $this->errors['passwordConfirm'] = $this->defaultMessages['passwordConfirm'];
        }
        
        
    }
}