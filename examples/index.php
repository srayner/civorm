<pre>
<?php
    
    include 'EntityManager.php';
    include 'QueryBuilder.php';
    include 'Classes.php';
   
    // PDO settings
    $settings = array(
        'database' => 'mysql:host=localhost;dbname=civorm',
        'user'     => 'root',
        'password' => 'sr.32-'
    );
    
    // Setup the entity manager
    $em = new Civrays\Orm\EntityManager($settings);
    $em->register('post', 'Post');
    $em->register('status', 'Status');
    $em->register('category', 'Category');
    
    // Query
    $builder = new Civrays\Orm\QueryBuilder($em);
    $entities = $builder->fetch("SELECT post, category, status");
    
    // Display results.
    echo ('<pre>');
    print_r($entities);
    
?>
</pre>