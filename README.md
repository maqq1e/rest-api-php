# PHP REST API

## Install
Clone repository in your local machine ( **for example in OSPanel domains directory** ).
```
$ git clone https://github.com/maqq1e/rest-api-php.git
$ cd rest-api-php
```
Import `api_db.sql` file from _backup directory in your MySQl ( **for example PhpMyAdmin** ).
Change configuration fields in database.php file in config directory ( **if you need** ).

![Config fields](https://i.imgur.com/K0Bq454.png)

Start your server.
## Documentation

| /user/               | Type          | Arguments                              | Description       |
| -------------------- | :-----------: | :------------------------------------: | :---------------: |
| `/user/create.php`   | POST          | **name**\*, **surname**\*, **middle**  | Create new user   |
| `/user/read.php`     | GET           | **none**                               | List of all users |
| `/user/read_one.php` | GET           | **id***                                | One user by id    |
| `/user/update.php`   | POST          | **name**, **surname**, **middle**      | Update user   |
| `/user/delete.php`   | POST          | **id***                                | Delete user       |

| /user_tel/               | Type          | Arguments                                 | Description                     |
| ------------------------ | :-----------: | :---------------------------------------: | :-----------------------------: |
| `/user_tel/create.php`   | POST          | **user_id**\*, **number**\*, **is_main**, **type**  | Add new phone number for user   |
| `/user_tel/read.php`     | GET           | **id**\*                                  | List of all phone number of user by user id |
| `/user_tel/read_one.php` | GET           | **id**\*                                  | One phone number by id    |
| `/user_tel/update.php`   | POST          | **id**\*, **is_main**, **type**, **number** | Update phone number  |
| `/user_tel/delete.php`   | POST          | **id**\*                                | Delete user       |

| /user_email/               | Type          | Arguments                              | Description       |
| -------------------- | :-----------: | :------------------------------------: | :---------------: |
| `/user_email/create.php`   | POST          | **user_id**\*, **email**\*, **is_main**  | Add new email for user   |
| `/user_email/read.php`     | GET           | **id**\*                               | List of all email of user by user id |
| `/user_email/read_one.php` | GET           | **id**\*                                | One email by id    |
| `/user_email/update.php`   | POST          | **id**\*, **email**, **is_main**      | Update email   |
| `/user_email/delete.php`   | POST          | **id**\*                                | Delete email       |

> \* - is required

## Database structure
### Tables
![Tables](https://i.imgur.com/AFiutY5.png)
#### users
![users](https://i.imgur.com/cDZcxse.png)
#### tel
![tel](https://i.imgur.com/lsotBOE.png)
#### email
![email](https://i.imgur.com/UUyOiLQ.png)
