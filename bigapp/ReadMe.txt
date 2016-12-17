
step 1: place the bigapp folder in htdocs
step 2: export the sql file 
step 3: install Advanced restClient app in chrome 
step 4: POST /orders ­ Create order in the system and persist the same in orders and
order_items table 
   insert in advanced rest Client  :: url --http://localhost/bigapp/rest/orders/ 
					data form parameter are: email, name, price, quantity
step 5: PUT /orders/{id} ­ Update order & order item attributes.
			insert in advanced rest Client  	::url -- http://localhost/bigapp/rest/orders/{id}
				data form parameter are: email, quantity
step 6: PUT /orders/{id}/cancel ­ Cancel the order.
insert in advanced rest Client  		:: url --  http://localhost/bigapp/rest/orders/{id}/cancel
		
step 7: GET /orders/{id} ­ Get order by id
   insert in advanced rest Client      ::http://localhost/bigapp/rest/orders/{id}