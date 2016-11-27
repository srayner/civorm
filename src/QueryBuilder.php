<?php

namespace Civrays\Orm;

use PDO;

class QueryBuilder
{
    protected $em;
    protected $parts = array('SELECT' => array(), 'JOIN' => array());
    protected $db;
    
    public function __construct($em)
    {
        $this->em = $em;
    }
    
    private function prepare($query)
    {
        // Remove the comma
        $words = explode(' ', str_replace(',', '', $query));
 
        $key = null;
        foreach($words as $word) {
            if (array_key_exists($word, $this->parts)) {
                $key = $word;
            }
            else if ($key) {
                array_push($this->parts[$key], $word);
            }
        }
    }
    
    public function fetch($query)
    {
        $this->prepare($query);
        $db = $this->em->getDb();
        $stm = $db->prepare($this->build());
        $stm->execute();
        $rows = $stm->fetchAll(PDO::FETCH_ASSOC);
        $this->hydrateFromRows($rows);
        return $this->em->getEntities()[$this->parts['SELECT'][0]];
    }
    
    private function hydrateFromRows($rows)
    {
        // Hydrate the entities.
        foreach ($rows as $row)
        {
            $relations = array();
            foreach($row as $key => $value)
            {            
                $parts = explode('.', $key);
                $entityName = $parts[0];
                $propertyName = $parts[1];

                if ($propertyName === 'id') {
                    $entity = $this->em->get($entityName, $value);
                }

                if ($entity && property_exists($entity, $propertyName)) {
                    $entity->$propertyName = $value;
                }

                if (isset($parts[2]) && $entity) {
                    array_push($relations, array(
                        'entity'    => $entity, 
                        'property'  => $propertyName,
                        'value'     => $value
                    ));
                }
            }
            foreach($relations as $relation) {
                $this->em->relate($relation);
            }
        }
    }
    
    public function build()
    {
        $s = "SELECT\n";
        $entities = $this->parts['SELECT'];
        $relations = array();
        foreach($entities as $entity) {
            $properties = array_keys(get_class_vars($this->em->getClass($entity)));
            foreach ($properties as $property) {
                $table = $entity;
                $field = $property;
                if (in_array($property, $entities)) {
                    array_push($relations, array(
                        'left_entity'  => $entity, 
                        'right_entity' => $property
                    ));
                    $field .= '_id';
                    $property .= '.id';
                } 
                $s .= "  `$table`.`$field` AS `$entity.$property`,\n";
            }
        }
        $s = rtrim($s, ",\n") . "\n";
        $s .= "FROM\n  `$entities[0]`\n";
        foreach($relations as $relation) {
            $left = $relation['left_entity'];
            $right = $relation['right_entity'];
            $s .= "INNER JOIN\n"
               . "  `$right` ON `$right`.`id` = `$left`.`{$right}_id`\n";
            
            
            // inner join
            
            
        }
        return $s;
    }
}