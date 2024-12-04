
# Order Stock Demo

## Description:
A simple dockerized laravel app, that has three main models, Product, Ingredient, and Order.
A Burger (Product) may have several ingredients:
- 150g Beef
- 30g Cheese
- 20g Onion

The system keeps the stock of each of these ingredients stored in the database. the following levels for seeding the database:
- 20kg Beef
- 5kg Cheese
- 1kg Onion

When a customer makes an order that includes a Burger, The system needs to update the stock of each of the ingredients so it reflects the amounts consumed.
Also when any of the ingredients stock level reaches 50%, the system should send an email message to alert the merchant they need to buy more of this ingredient.

Requirements:
First, Write a controller action that:
- Accepts the order details from the request payload.
- Persists the Order in the database.
- Updates the stock of the ingredients.
Second, ensure that en email is sent once the level of any of the ingredients reach below 50%. Only a single email should be sent, further consumption of the same ingredient below 50% shouldn't trigger an email.

Finally, write several test cases that assert the order was correctly stored and the stock was correctly updated.
The incoming payload may look like this:
```
{
    "products": [
        {
            "product_id": 1,
            "quantity": 2,
        }
    ]
}
```

## Installation Steps:
### 1. Clone the repository
```bash
git clone git@github.com:Abdulaziz-Aladwi/order-stock-demo.git
cd order-stock-demo
```

### 2. Set up docker environment and run containers
```bash
make up
```

### 3. Set Up Environment variables
```bash
cp .env.example .env
```

### 4. Generate the application key
```bash
make key
```

### 5. Clear cache
```bash
make config-cache
```

### 6. Install dependencies
```bash
make composer-install
```

### 7. Run Migrations & Seeders
Migrate database and seed initial data:
```bash
make migrate && make seed
```

## Create order endpoint
```bash
[post] api/order/create

payload:

{
    "products": [
        {
            "product_id": 1,
            "quantity": 2,
        }
    ]
}
```

## Notes:
- Whenever new order is created, background job is dispatched to decrement product ingredient stock, and if the stock is less than 50% another background job is dispatched to notify inventory admin by email about the stock.
- used redis for locking and for queuing background jobs.
- to run background jobs:
```bash
make queue-work
```
