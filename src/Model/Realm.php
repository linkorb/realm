<?php

namespace Realm\Model;

use RuntimeException;

class Realm
{
    use PropertyTrait;
    protected $id;
    protected $concepts = [];
    protected $codelists = [];
    protected $forms = [];
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    
    public function addConcept(Concept $concept)
    {
        $this->concepts[$concept->getId()] = $concept;
        return $this;
    }
    
    public function hasConcept($id)
    {
        return isset($this->concepts[$id]);
    }
    
    public function getConcepts()
    {
        return $this->concepts;
    }
    
    public function getConcept($id)
    {
        $id = (string)$id;
        if (!$this->hasConcept($id)) {
            throw new RuntimeException("No such concept: " . $id);
        }
        return $this->concepts[$id];
    }
    
    public function addForm(Form $form)
    {
        $this->forms[$form->getId()] = $form;
        return $this;
    }
    
    public function getForms()
    {
        return $this->forms;
    }
    
    public function addCodelist(Codelist $codelist)
    {
        $this->codelists[$codelist->getId()] = $codelist;
        return $this;
    }
    
    public function getCodelists()
    {
        return $this->codelists;
    }
    
    public function hasCodelist($id)
    {
        return isset($this->codelists[$id]);
    }
    
    public function getCodelist($id)
    {
        if (!$this->hasCodelist($id)) {
            throw new RuntimeException("Undefined codelist: " . $id);
        }
        return $this->codelists[$id];
    }
}
