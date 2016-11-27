Simple ORM for PHP projects using PDO
=====================================

Some  assumtions are made to keep things simple. Table names should be lower case versions of class names. Primary keys should be id.
Foreign keys should be the foriegn table name followed by _id. Field names should be the same as class properties.

Usage
-----

$settings = array(
    'database', 'localhost'
    'user' => 'root',
    'password' => 'password'
);

$em = new \Civrays\Orm\EntityManager($settings);
$em->registerClass($yourclass);

$query = 'SELECT Class1, Class2, Class3';
$queryBuilder = new \Civrays\Orm\QueryBuilder($query);
$results = $queryBuilder->fetch();






