php-mysqli-class
================

This php mysqli class can be used to do individual queries using predefined method of adding fields with data or for more advanced the raw query can be sent and executed as needed


Available Options:
================

1.Execution of prepared Statements for Insert etc..
2.Execution of Raw Query.
3.Error Handling done precisely
4.Getting first row of any query
5.Availability of query execute time.
6.Availability of getting total number of affected rows.
7.Availablility of obtaining of data by using resource id of any query


Updates :
================
I am constantly working on updates by adding more methods and security. If you find this interesting and helpful please like it :D 

Examples :
================

##Connection to Db : 
  
    The Syntax
    ----------------
    new Mysqli_Database(The Host, username, password, database name, database prefix);
    
    Example Usage
    ----------------
    $my_db = new Mysqli_Database('localhost', 'username', 'complexpass', 'database','pre_');
    

From the above the Connection can be accessed through the object $my_db


##Doing a Raw Query : 
  
    The Syntax
    -----------------
    $result = $my_db->raw_query($sql); 
    
    Example Usage
    ------------------
    $sql = 'SELECT * FROM table_name';
    $result = $my_db->raw_query($sql); 
    if(!empty($result))
    {
      while ($row = $this->fetch_assoc($result))
      {
        print_r($row);
      }
    }
    else
    {
      die("Could Not Execute Query");
    }
    

##Doing a prepared table insert : 
  
    The Syntax
    -----------------
    $result = $my_db->table_insert('table_name',{array of fields=>value}); 
    
    Example Usage
    ------------------
    $insert_data = array(
    
        'table_field1' => 'value1',
        'table_field2' => 'value2',
        'table_field3' => 'value3',
        'table_field4' => 'value4',
    
    );
    $result = $my_db->table_insert('table_name',$insert_data);
    if(!$result){echo "The Value Could Not be inserted";}
    else
    {echo "Value Inserted with the row id being {$result}";}
    

##Getting the first row of query : 
  
    The Syntax
    -----------------
    $result = $my_db->get_first_row($sql); 
    
    Example Usage
    ------------------
    $sql = 'SELECT * FROM table_name';
    $result = $my_db->get_first_row($sql); 



##Get the query executed Time : 
  
    The Syntax
    -----------------
     $time = $my_db->query_time;


    Example Usage
    ------------------
    $sql = 'SELECT * FROM table_name';
    $result = $my_db->raw_query($sql); 
    $time = $my_db->query_time;
    

##Get the total number of affected rows : 
  
    The Syntax
    -----------------
     $time = $my_db->affected_rows;


    Example Usage
    ------------------
    $sql = 'SELECT * FROM table_name';
    $result = $my_db->raw_query($sql); 
    $time = $my_db->affected_rows;
    

