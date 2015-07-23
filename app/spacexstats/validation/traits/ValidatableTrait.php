<?php
trait ValidatableTrait {
    public function isValid($input) {

        if (!$this->rules || !$this->messages) {
            throw new Exception('Please set the $rules & $messages properties on classes using ValidatableTrait.');
        }

        $validator = Validator::make($input, $this->rules, $this->messages);
        return $validator->passes() ? true : $validator->errors();
    }
}