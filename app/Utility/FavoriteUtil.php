<?php

/**
 * Created by PhpStorm.
 * User: nbkn9dy
 * Date: 11/22/17
 * Time: 11:34 AM
 */

namespace App\Utility;


use DB;

/**
 * Class FavoriteLib
 * @package App\Library
 */
class FavoriteUtil
{

    public static function getFavoritePosts($userid, array $tags = null)
    {
        $posts = DB::table('users as u')
            ->select('f.*', 'p.*', 't.*')
            ->join('favorites as f', 'f.user_id', 'u.id')
            ->join('posts as p', 'p.reddit_id', 'f.reddit_id')
            ->leftJoin('tags as t', 't.id', 'f.tag_id')
            ->where('f.user_id', $userid);

        if ($tags != null) {

            $tags_imp = implode("','", $tags);
            $qry_tags = "'" . $tags_imp . "'";
            $posts->whereRaw("t_t.name in ($qry_tags)");
        }

        $postsRes = $posts->get();

        $tmp = [];
        foreach ($postsRes as $p) {

            if (!isset($tmp[$p->reddit_id])) {
                $tmp[$p->reddit_id] = ['reddit_id' => $p->reddit_id,
                                       'permalink' => $p->permalink,
                                       'url' => $p->url,
                                       'author' => $p->author];

                $tmp[$p->reddit_id]['tags'] = $p->name == null ? [] : [$p->name];

            } else {

                $tmp[$p->reddit_id]['tags'][] = $p->name;
            }
        }

        //clean up
        $res = [];
        foreach ($tmp as $t) {
            $res[] = $t;
        }

        return $res;
    }

}