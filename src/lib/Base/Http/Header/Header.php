<?php
namespace Base\Http\Header;

class Header
{

    public function __construct(array $headerFields)
    {
        $this->headerFields = $headerFields;
    }

    public function hasField($key)
    {
        return array_key_exists($key, $this->headerFields);
    }

    public function getField($key)
    {
        return $this->headerFields[$key];
    }

    public function getFields()
    {
        return $this->headerFields;
    }

    public function directiveExists($HeaderFieldName, $directiveName)
    {
        if ($this->hasField($HeaderFieldName)) {
            $field = $this->getField($HeaderFieldName);
            return (strpos($field, $directiveName) !== false);
        } else {
            return false;
        }
    }
}