Simple ORM for PHP projects using PDO
=====================================

Some  assumtions are made to keep things simple. Table names should be lower case versions of class names. Primary keys should be id.
Foreign keys should be the foriegn table name followed by _id. Field names should be the same as class properties.

Usage
-----

<?php
    
    include 'EntityManager.php';
    include 'QueryBuilder.php';
    include 'Classes.php';
   
    // PDO settings
    $settings = array(
        'database' => 'mysql:host=localhost;dbname=civorm',
        'user'     => 'root',
        'password' => 'password'
    );
    
    // Setup the entity manager
    $em = new Civrays\Orm\EntityManager($settings);
    $em->register('post', 'Post');          // Entity name, Class name
    $em->register('status', 'Status');
    $em->register('category', 'Category');
    
    // Query
    $builder = new Civrays\Orm\QueryBuilder($em);
    $entities = $builder->fetch("SELECT post, category, status");
    
    // Display results.
    echo ('<pre>');
    print_r($entities);
    echo ('</pre>');
    
?>
