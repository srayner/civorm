<?php

namespace Civrays\Orm;

use PDO;

class EntityManager
{
    /** @var array */
    protected $classes = array();
    
    /** @var array  */
    protected $entities = array();
    
    protected $db;
    
    public function __construct($settings)
    {
        $this->db = new PDO($settings['database'], $settings['user'], $settings['password']);
    }
    
    public function getDb()
    {
        return $this->db;
    }
    
    /**
     * Registers a class name against an entity name.
     * 
     * @param type $name The entity name to be registered against.
     * @param type $class The class name to be registered.
     */
    public function register($name, $class)
    {
        $this->classes[$name] = $class;
    }
    
    /**
     * Returns an entity if it exists. If it doesn't get exist and the entity
     * name provided has a classname registed agianst it, then the entity is
     * created, added to the repository and returned.
     * 
     * Returns null if the entityname does not have a class registered against it.
     * 
     * @param type $name The entity name
     * @param type $id The id of the entity to be returned.
     * 
     * @return Object | null
     */
    public function get($name, $id)
    {
        $entity = isset($this->entities[$name][$id]) ? $this->entities[$name][$id] : null;
        if (!$entity) {
            if (isset($this->classes[$name])) {
                $entity = new $this->classes[$name];
                $entity->id = $id;
                $this->entities[$name][$id] = $entity;
            }
        }
        return $entity;
    }
    
    /**
     * Creates a relationship between two entities.
     * 
     * @param type $relation
     */
    public function relate($relation)
    {
        if (isset($this->entities[$relation['property']][$relation['value']])) {
            $relation['entity']->{$relation['property']} = $this->entities[$relation['property']][$relation['value']];
        }
    }
     
    /**
     * Returns the repository containing all the entities.
     * @return array
     */
    public function getEntities()
    {
        return $this->entities;
    }
    
    public function getClass($entity)
    {
        return $this->classes[$entity];
    }
}