
# Api Laravel Magang

Tugas magang di PT Tebar Digital membuat api dengan laravel 9

## Api Authentikasi

#### register

```http
  post /api/register
```

#### login

```http
  post /api/login
```

# Lupa Password
```http
  POST /api/forgot-password/
```

| column | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `email`      | `email` |  masukan email yang lupa sandi |


```http
  POST /api/reset-password/
```

| column | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `token`      | `token` |  masukan token yang di kirim dari mail trap |
| `email`      | `email` |  masukan email yang lupa sandi |
| `password`      | `password` |  masukan password baru |
| `password_confirmation`      | `password` |  confirmasi password |



## API User Crud

#### mendapatkan semua user

```http
  GET /api/dashboard/admin/user
```

#### membuat user

```http
  post /api/dashboard/admin/user
```



#### Update user

```http
   put /api/dashboard/admin/user/{id}
```
 
| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `int` | **Required**. Id of item to put |

#### Delete user

```http
   delete /api/dashboard/admin/user/delete/{id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `int` | **Required**. Id of item to delete |






## API Post Crud

#### mendapatkan semua post

```http
  GET /api/dashboard/admin/post
```

#### membuat post

```http
  post /api/dashboard/admin/post
```

#### show post

```http
   get /api/dashboard/admin/post/show/{id}
```
 
| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `int` | **Required**. Id of item to get |




#### Update post

```http
   put /api/dashboard/admin/post/{id}
```
 
| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `int` | **Required**. Id of item to put |

#### Delete post

```http
   delete /api/dashboard/admin/post/delete/{id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `int` | **Required**. Id of item to delete |





## API Comentar Crud

#### mendapatkan semua user

```http
  GET /api/comentar
```

#### membuat user

```http
  post /api/comentar
```



#### Update user

```http
   put /api/comentar/{id}
```
 
| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `int` | **Required**. Id of item to put |

#### Delete user

```http
   delete /api/comentar/delete/{id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `int` | **Required**. Id of item to delete |


## Authors

- [@AvinFajarF](https://www.github.com/AvinFajarF)

