# How it works

Into the ***app/bin*** directory you'll find a ***benchmark.php*** file.

Before run it run the docker-compose using the command ***docker-compose up --build***.

All the docker variables can be edited into the ***.env***.
Before run the test make sure to create a database named "uuid_test" accessible with username: ***root*** and password: ***root***

Then enter into the php container using the command ***docker exec -it uuid_app bash***
and execute the command ***php benchmark.php*** from the ***/var/www/app/bin*** directory.


# Results

Results for insert N row into the db (the number can be configured at the top of benchmark.php file).
    
    Inserted data into autoincrement_id table in 119.900014
    
    Inserted data into unoptimized_uuid table in 111.529051
    
    Inserted data into optimized_uuid table in 108.552015
    
    
Results for execute the select N row by id from the db (the number can be configured at the top of benchmark.php file).
    
    Selected data from autoincrement_id table in 0.003072
    
    Selected data from unoptimized_uuid table in 0.007546
    
    Selected data from optimized_uuid table in 0.003082
    
    
Results for execute the select in reverse id order from the db.

    Selected data from autoincrement_id table in 1.597336
    
    Selected data from unoptimized_uuid table in 2.541748
    
    Selected data from optimized_uuid table in 1.534559