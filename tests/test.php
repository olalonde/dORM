<?php
// Query Object

// Placeholder extends the query object but also implements autoloading to allow lazy loading

// internally $dorm->getBook('id')
// => Dorm_Placeholder_Object()->select('book')->id('id');

// Placeholder for an array property
// => Dorm_Placeholder_Array()->select()->parent('ParentClassName', 'parent_id');

// todo: where => whereColumn, whereProperty => where, etc.

/////////////////////////////////////
// LAZY LOADING
/////////////////////////////////////

// get and getCollection return a placeholder that holds information on how to load the object
$book = $dorm->getBook('book_id');
$books = $dorm->getBookCollection();

// Whenever a user property is accessed (__get(), __set() or __call()), dynamically load the object from the db
// and replace the placeholder by the loaded object. i.e:
echo $book->title;
foreach ($books as $book) {echo $book->title;}

// skip lazy loading: execute the query now. helps avoiding autoloading conflicts with the query object
$book->load(); // book should now be an instance of Book instead of Dorm_Placeholder


/////////////////////////////////////
// QUERY REFINING
/////////////////////////////////////

// Note: it is also possible to refine queries on lazy loaded properties.
// Note: in most cases, it is possible to replace an id by the object itself or an associative array
// Note: unless specified, the order of methods has no importance
// get the book where id = book_id
$book = $dorm->getBook()->id('book_id');
$book = $dorm->getBook('book_id'); // equivalent
// get book where title = 'The Bible'.
// Notice: if book titles aren't unique, you should use getBookCollection since it might return many rows.
$book = $dorm->getBook()->where("book_title = 'The Bible'");
// you can also use the following syntax to prevent SQL injection
$book = $dorm->getBook()->where('book_title = ?', 'The Bible');
// if you can't remember what the column name is, you can use the property name instead
$book = $dorm->getBook()->whereProperty('title = ?', 'The Bible');
// You can add the "and" and "or" prefix to where and whereProperty
$book = $dorm->getBook()
    ->where('book_title = ?', 'The Bible')
    ->andWhere('book_id = ?', 'id');
$book = $dorm->getBook()->where('book_title = ? AND book_id = ?', array('The Bible', 'book_id')); // equivalent of above

$book = $dorm->getBook()->orWhere('book_title = ?', 'The Bible');
$book = $dorm->getBook()->andWhereProperty('title = ?', 'The Bible');

// if you know your query will return more than one row, replace getBook() by getBookCollection() (the same methods are available)
$books = $dorm->getBookCollection(); // the placeholder should implement an SPL iterator to behave like an array
// limit the resultset
$books = $dorm->getBookCollection()->limit(10, 100);
$books = $dorm->getBookCollection()->limit(100)->offset(10); // equivalent of the above
// order the result (the order in which you call orderBy tells the priority)
// desc() is optional. if not specified, the order will be ascendant
$books = $dorm->getBookCollection()->orderBy('book_title')->desc();
$books = $dorm->getBookCollection()->orderBy('book_title')->desc()->orderBy('book_id');
$books = $dorm->getBookCollection()->orderByProperty('title'); // if you can't remember the column name
// get all publishers of a book. useful for relationships
$publishers = $dorm->getPublisherCollection()->parentBook('book_id');
// get the author of a book
$author = $dorm->getAuthor()->parentBook('book_id');

// custom SQL query. Notice: this will override any previous query refinement
$books = $dorm->getBookCollection()->sql('SELECT * FROM books');

// Once an object is accessed/comitted, it is no longer possible to refine the query on it.
$book->title; // or
$book->exec();
// this is how to do if you want to "reload" an object
$dorm->load($book)->where('...')->otherMethod('...')->exec(); //etc.

/////////////////////////////////////
// EAGER LOADING
/////////////////////////////////////

/*
 * Eager  load consists of loading object/array properties in the same query
 * as the parent object. Prevents lazy loading which would mean more queries.
 * You can specify which property to eager load by default in the map file
 * <property loadType="eager" />. This is useful when an object's property is
 * accessed very often.
 */

// eager load the publishers property of Book. this is the property name, not the table name
$book = $dorm->getBook('id')->join('publishers');
// eager load publishers and their address property
$book = $dorm->getBook('id')->join('publishers', 'publishers.address');
$book = $dorm->getBook('id')->join('publishers.address'); // equivalent as above (no need to join publishers, it is implicitly joined by publishers.address)
// eager load ALL properties (not recursive)
$book = $dorm->getBook('id')->join('*');
// disable eager load on a given property (useful if a property is set to default eager load in the map file)
$book = $dorm->getBook('id')->unjoin('publishers');
// disable eager load on all properties
$book = $dorm->getBook('id')->unjoin('*');


/////////////////////////////////////
// CONCLUSION
/////////////////////////////////////

// I have pretty much covered lazy loading, query refinement and eager loading
// Here is how a complex query could look like
$books = $dorm->getBookCollection()
    ->join('publishers')
    ->join('publishers.address')
    ->whereProperty('title = ? OR publishers.address.city = ?', array('The Bible', 'Vatican'))
    ->orderByProperty('title')->asc()
    ->offset(2)
    ->limit(4);