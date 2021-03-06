### Supported Endpoints



`PUT http://charlesdewald.com/api/v1/users/register`

`POST http://charlesdewald.com/api/v1/users/login`

`GET http://charlesdewald.com/api/v1/reddit`

`PUT http://charlesdewald.com/api/v1/reddit/{id}/tags`

`GET http://charlesdewald.com/api/v1/reddit/favorites`

`GET http://charlesdewald.com/api/v1/reddit/favorites/tags?{query_string}`

#### Detailed Explanation

##### Registration

`PUT http://charlesdewald.com/api/v1/users/register` where `username` and `password` are required fields. Pass variables as x-www-form-urlencoded or raw like so `username=charles1123b&password=pass`. A successful call will return an `access_token`.

##### Login

`POST http://charlesdewald.com/api/v1/users/login` where `username` and `password` are required fields. A successful call will return an `access_token`.

##### List of Reddit Posts

`GET http://charlesdewald.com/api/v1/reddit` returns a list of dictionaries from `https://www.reddit.com/hot.json`. Results are cached in Redis for 5 minutes.

The access token is required to be passed in the header like so: `http header: "accessToken: xxxxxxxxxxxx"`

##### Favoring and Tagging a Post
`PUT http://charlesdewald.com/api/v1/reddit/{id}/tags` will favor the post on behalf of the user with any number of tags by passing `tags` as x-www-form-urlencoded. If the `tags` variable is not passed or empty then the post will be favored with no tags. The `id` is the reddit_id to tag.

The access token is required to be passed in the header like so: `http header: "accessToken: xxxxxxxxxxxx"`

An example of passing `tags`

![Tagging a post](public/images/put_tags.png)


##### Retrieving Favorite Posts
`GET http://charlesdewald.com/api/v1/reddit/favorites` will return all the user's favorite posts or an empty set.

The access token is required to be passed in the header like so: `http header: "accessToken: xxxxxxxxxxxx"`

##### Retrieving Favorite Posts by Specified Tag(s)
`GET http://charlesdewald.com/api/v1/reddit/favorites/tags?{query_string}` returns only posts that are favored by the user and tagged with the specified tag(s) within the `query_string`. If no `query_string` is passed then all favored posts are returned.

The access token is required to be passed in the header like so: `http header: "accessToken: xxxxxxxxxxxx"`

An example:

![Getting tags](public/images/get_tags.png)