# NewsApi

========================

Steps to install:

1. composer.phar install
2. app/console doctrine:schema:create
3. app/console doctrine:fixtures:load
4. app/console oauth:client:create

=======================

### Register User

[POST] account/register
```curl
curl -X POST -H "Content-Type: application/json" -H "Cache-Control: no-cache" -H "" -d 
'{"fos_user_registration_form":{
        "email":"xxxxxx@xxxxxxx.com",
        "username":"xxxxxx",
        "plainPassword":{
            "first":"xxxxx",
            "second":"xxxxx"
        }
 }}' "http://localhost/NewsApi/web/app_dev.php/account/register"
```

### Login User
[POST] /oauth/v2/token
```curl
curl --request POST \
  --url http://localhost/NewsApi/web/app_dev.php/oauth/v2/token \
  --header 'cache-control: no-cache' \
  --header 'content-type: multipart/form-data; boundary=---011000010111000001101001' \
  --form grant_type=password \
  --form client_id=1_17inwtyci7vkcswoggccogswkwwss8cg4kc40sg488owskgows \
  --form client_secret=48jl1dzs57qcw0woo84w8k80oswgg84cc4wsw8gowc08sok48s \
  --form username=test \
  --form password=test
  ```
### Change User Password
[POST] /user/passwords
```curl
curl -X POST -H "Authorization: Bearer NWNmZTJjNDcwNDZlNzRmNGFiODFiNjAyZDNiMDkwZDg4NTRkN2RkYThkOTgyZmYwMmQzMTQzMTM1Yzg2YzEwNw" -H "Content-Type: application/json" -H "Cache-Control: no-cache" -d '
{"fos_user_change_password_form" : {
        "current_password":"test",
        "new":{
            "first":"test2",
            "second":"test2"
        }
    }
}' "http://localhost/NewsApi/web/app_dev.php/user/passwords"
```

### Get News
[GET] /news/{newsId}
```curl
curl -X GET -H "Authorization: Bearer NWNiNGE5ODBiMTA0NGFkMzJlOWNhMTA4OGU2NGIwNDY3MjNiZjk5ZDAzM2ZmNTFkZTkxMTkwYWYzNzA1NzU0Mg" -H "Cache-Control: no-cache" "http://localhost/NewsApi/web/app_dev.php/news/1"
```
### Create News
[POST] /news/
```curl
curl -X POST -H "Authorization: Bearer MmQwNGQwNjY4YmVlYjcxOGZhMTQ5MmZhOTNmM2M4NTRlNzU3MTMzZDlhZTA0MDUwNjc4ZWIyOTEwZGFhNmViMg" -H "Content-Type: application/json" -H "Cache-Control: no-cache" -d 
'{
    "news" : {
        "title" : "Test Title",
        "body" : "Test Body"
    }
}' "http://localhost/NewsApi/web/app_dev.php/news/"
```
### Edit News
[PUT] /news/{newsId}
```curl
curl -X PUT -H "Authorization: Bearer ZThiMWY0ODYwNmYyYTNiZDg1ZDU0Mjk1YWUyMTE4YTZhNDhiOWE1MDYyZmI0M2VkZTlkZGFlNzAxYzRkNDM5Ng" -H "Content-Type: application/json" -H "Cache-Control: no-cache"  -d 
'{
    "news" : {
        "title" : "Test Title Edited",
        "body" : "Test Body Edited"
    }
}' "http://localhost/NewsApi/web/app_dev.php/news/1"
```

### Remove News
[DELETE] /news/{newsId}
```curl
curl -X DELETE -H "Authorization: Bearer ZThiMWY0ODYwNmYyYTNiZDg1ZDU0Mjk1YWUyMTE4YTZhNDhiOWE1MDYyZmI0M2VkZTlkZGFlNzAxYzRkNDM5Ng" -H "Cache-Control: no-cache" "http://localhost/NewsApi/web/app_dev.php/news/3"
```

### Get News By Status
[GET] /news/statuses/{status}
```curl
curl -X GET -H "Authorization: Bearer NWNiNGE5ODBiMTA0NGFkMzJlOWNhMTA4OGU2NGIwNDY3MjNiZjk5ZDAzM2ZmNTFkZTkxMTkwYWYzNzA1NzU0Mg" -H "Cache-Control: no-cache" "http://localhost/BlogApi/web/app_dev.php/news/statuses/1"
```

### Get News By User
[GET] /user/users/{userId}/news
```curl
curl -X GET -H "Authorization: Bearer YTIxODY3OTVlYzdiZWM2M2MxNzBiMzEwNGMyZWNmNDMxNWJiZmMxMjhmNzI2NWUyNTcwYjA1Mjk5OGU0OWI0YQ" -H "Content-Type: application/json" -H "Cache-Control: no-cache" "http://localhost/BlogApi/web/app_dev.php/user/users/3/news"
```
