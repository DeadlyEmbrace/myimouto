<?php
namespace MyImouto\ExternalStorage;

class SearchManager
{
    public function getModelRelation($className)
    {
        if (CONFIG()->external_storage) {
            
        } else {
            return $className::none();
        }
    }
}
