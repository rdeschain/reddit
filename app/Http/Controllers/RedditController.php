<?php
/**
 * Created by PhpStorm.
 * User: nbkn9dy
 * Date: 11/19/17
 * Time: 7:55 PM
 */

namespace App\Http\Controllers;

use App\Favorite;
use App\Utility\FavoriteUtil;
use App\Post;
use App\Tag;
use Carbon\Carbon;
use DB;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;


/**
 * Class RedditController
 * @package App\Http\Controllers
 */
class RedditController extends Controller
{
    /**
     * @param Request $request
     * @return static
     */
    public function redditList(Request $request)
    {

        $res = Cache::get('list');
        $expiresAt = Carbon::now()->addMinutes(5);

        //use cache
        if ($res === null) {

            try {

                $client = new Client();

                $response = $client->request('GET', 'https://www.reddit.com/hot.json', ['headers' => [
                    'User-Agent' => 'testing/1.0',
                ]]);

                $res = [];
                $redditContent = \GuzzleHttp\json_decode($response->getBody()->getContents());

                foreach ($redditContent->data->children as $content) {

                    $article = ['reddit_id' => $content->data->id,
                                'permalink' => 'https://reddit.com' . $content->data->permalink,
                                'url' => $content->data->url,
                                'author' => $content->data->author];

                    $res[] = $article;

                    /**
                     * listener dispatches DB article inserts after writing to cache
                     **/
                    Cache::add('article:' . $content->data->id, $article, $expiresAt);
                }

                Cache::add('list', $res, $expiresAt);

            } catch (\Exception $e) {
                return Response::create(['error' => 'Oops...Something bad happened during calling hot.json or redis storing. Try again'], 500);
            }
        }

        return Response::create($res);
    }


    /**
     * @param Request $request
     * @return static
     */
    public function redditAddTags(Request $request)
    {

        parse_str($request->input('tags'), $tags);
        $reddit_id = strval($request->route('id'));
        $userid = $request->get('userid');

        //make sure reddit_id exists in cache or in db
        $article = (object)Cache::get('article:' . $reddit_id);

        if (empty((array)($article))) {

            $article = Post::where('reddit_id', $reddit_id)->first();
        }

        //if still null then item does not exist in system
        if (empty((array)$article)) {
            return Response::create(['error' => 'reddit_id not found'], 422);
        }

        /**
         * Design consideration on treating collection Favorite records by user_id and reddit_id as one logical unit.
         * Requests will 'refresh' the favorite tags.
         */

        DB::transaction(function () use ($tags, $reddit_id, $userid) {

            // drop any user tags for this post
            Favorite::where(['user_id' => $userid,
                             'reddit_id' => $reddit_id])->delete();


            // consider favoriting a post w/o any tags
            if (empty($tags)) {

                Favorite::insert(['reddit_id' => $reddit_id,
                                  'user_id' => $userid]);

            } else {

                foreach ($tags as $v) {

                    //create new or update existing tag
                    $tag = Tag::firstOrCreate(['name' => $v], [
                        'name' => $v]);

                    Favorite::firstOrCreate(['tag_id' => $tag->id,
                                             'reddit_id' => $reddit_id,
                                             'user_id' => $userid]);
                }
            }

        }, 5);

        $tag_message = sizeof($tags) > 0 ? ' with tags {' . implode(',', $tags) . '}' : ' without any tags';

        //drop user favorite list since new favorite has been added
        Cache::tags('user:' . $userid)->flush();

        return Response::create(['message' => 'Added ' . $reddit_id . ' as a favorite' . $tag_message]);
    }

    /**
     * @param Request $request
     * @return static
     */
    public function redditFavorites(Request $request)
    {

        $expiresAt = Carbon::now()->addMinutes(5);
        $userid = $request->get('userid');

        /*$res = Cache::get('user:' . $userid . ':fav');
        if ($res == null) {
            $res = FavoriteUtil::getFavoritePosts($userid);
            Cache::tags('user:' . $userid)->add('user:' . $userid . ':fav', $res, $expiresAt);
        }*/

        $res = Cache::tags('user:' . $userid)->remember('user:' . $userid . ':fav', $expiresAt, function() use ($userid) {
            return FavoriteUtil::getFavoritePosts($userid);
        });

        return Response::create($res);

    }

    public function redditFavoritesByTags(Request $request)
    {

        $expiresAt = Carbon::now()->addMinutes(5);
        $tags = $request->all();
        unset($tags['_url']);
        sort($tags);
        $userid = $request->get('userid');
        $cacheTag = 'user:' . $userid . ':' . base64_encode(implode('', $tags));

        /*$res = Cache::get('user:' . $userid . ':' . base64_encode(implode('', $tags)));

        if ($res == null) {
            Cache::tags('user:' . $userid)->add('user:' . $userid . ':' . base64_encode(implode('', $tags)), $res, $expiresAt);
            $res = FavoriteUtil::getFavoritePosts($userid, $tags);
        }*/

        $res = Cache::tags('user:' . $userid)->remember($cacheTag, $expiresAt, function() use ($userid, $tags) {
            return FavoriteUtil::getFavoritePosts($userid, $tags);
        });

        return Response::create($res);

    }

}