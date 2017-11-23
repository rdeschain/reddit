### Supported Endpoints



`PUT http://charlesdewald.com/api/v1/users/register`

`POST http://charlesdewald.com/api/v1/users/login`

`GET http://charlesdewald.com/api/v1/reddit`

`PUT http://charlesdewald.com/api/v1/reddit/{id}/tags`

`GET http://charlesdewald.com/api/v1/reddit/favorites`

`GET http://charlesdewald.com/api/v1/reddit/favorites/tags?{query_string}`

#### Detailed Explanation

##### Registration

`PUT http://charlesdewald.com/api/v1/users/register` where `username` and `password` are required fields. If `username` or `password` are not supplied then  a status code of `422` will be returned. A successful call with return an `access_token`.

##### Login

`POST http://charlesdewald.com/api/v1/users/login` where `username` and `password` are required fields. If `username` or `password` are not supplied then  a status code of `422` will be returned. A successful call with return an `access_token`.

##### List of Reddit Posts

`GET http://charlesdewald.com/api/v1/reddit` returns a list of dictionaries from `https://www.reddit.com/hot.json`. Results are cached in Redis.

The access token is required to be passed in the header like so: `http header: "accessToken: xxxxxxxxxxxx"`

##### Tagging or Favoriting a Post
`PUT http://charlesdewald.com/api/v1/reddit/{id}/tags` will favorite the post on behalf of the user with any number of tags by passing a variable called `tags` of urlencoded values. If `tags` is not passed or empty then the post will be favorited with no tags.

The access token is required to be passed in the header like so: `http header: "accessToken: xxxxxxxxxxxx"`

##### Retrieving Favorite Posts
`GET http://charlesdewald.com/api/v1/reddit/favorites` will return all the users favorite posts or an empty set.

The access token is required to be passed in the header like so: `http header: "accessToken: xxxxxxxxxxxx"`

##### Retrieving Favorite Posts by Specified Tag(s)
`GET http://charlesdewald.com/api/v1/reddit/favorites/tags?{query_string}` returns only posts that have are favorited by the user and tagged with the specified tag(s) within the `query_string`. If no `query_string` is passed then all favorites are returned.

The access token is required to be passed in the header like so: `http header: "accessToken: xxxxxxxxxxxx"`