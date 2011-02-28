<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Dorm Demo</title>
</head>

<body>

<h1>Dorm Demo</h1>

<div style="float:left;width:45%;">

<ol>
    <li>Set up your database. Database schema can be found in <a href="schema.sql">schema.sql</a>.</li>
    <li>Rename config.xml.sample to config.xml. You also need to edit your connection info (database="mysql://user:pass@host/dbname").</li>
    <li>Go to <a href="insert.php" target="iframe">insert.php</a> to save a sample book.</li>
    <li>Go to <a href="load.php" target="iframe">load.php</a> to load the book you just saved.</li>
    <li>Go to <a href="update.php" target="iframe">update.php</a> to update the sample book.</li>
    <li>Go to <a href="load_collection.php" target="iframe">load_collection.php</a> to see how getCollection() works.</li>
    <li>Go to <a href="delete.php" target="iframe">delete.php</a> to delete the sample book.</li>
    <li>Go to <a href="flush_cache.php" target="iframe">flush_cache.php</a> to flush cache.</li>
    <li><strong>Notice: </strong> The book ID (1) is hardcoded in all pages, so if you want to start from scratch, you need to <a href="reset_data.php" target="iframe">reset your database data</a>.</li>

</ol>

<p>Play around with the code and you'll notice how easy it is to use Dorm. However, don't forget to set up <a href="http://dev.mysql.com/doc/refman/5.1/en/innodb-foreign-key-constraints.html" target="_blank">foreign keys</a> / primary keys in your databases whenever there is a relation, otherwise, Dorm won't <a href="http://www.getdorm.com/#limitations">understand it</a>.</p>

<p>Disclaimer: we chose the Bible as an example simply because it is the bestseller of all times (the data is pretty random anyways).</p>

<p><a href="http://www.getdorm.com">www.getdorm.com</a></p>

</div>
<div>

</div>

<iframe style="float:right;width:50%;height:500px;" src="" name="iframe"></iframe>
</body>
</html>
