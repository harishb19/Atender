Welcome to Atender!
===================


Atender is an attendance bot made for **SIESGST Student council**. 

----------


Documents
-------------

Atender is a slack bot. with a web portal 



#### <i class="icon-plus"></i> Register a user

**list of comands**

 - /registerme  
 - /registerme help
 
 attendance_register.php contains the code.


#### <i class="icon-user"></i> Mark your attendance 
**list of comands**

- /markmein
- /markmein help
 
 attendance.php contains the code.


#### <i class="icon-list"></i> list your attendance

**list of comands**

- /atendre list
- /atendre help
 
list.php contains the code. 

----------


Requirements
-------------------

 - A slack team
 - server supporting curl 
 - php
 - knowlwdege about json

----------


Setup
-------------

 - Enter your DB credentials in attendance > admin > require.php
 - Create tables :
	 - attendance
		 - id (primary key int)
		 - uid , uname , team , reason  (varchar)
		 - created_at (datetime)
	 - register
		 - id (primary key int)
		 - uid , uname , team , prn , class  (varchar)
		 - created_at (datetime)
	 - attendance manager 
		 - id (primary key int)
		 - name , team , email ,password  (varchar)
 - Create a slack app
 https://api.slack.com/apps
	 - add above mentioned slash comands  
	 - create a webhook

	
 
	
----------


Upcoming
--------------------

Web portal

----------

Contributors
----------
- Harish Balasubramanian

 follow me on [github](https://github.com/harishb19)
