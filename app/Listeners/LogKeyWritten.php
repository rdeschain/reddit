<?php

namespace App\Listeners;

use App\Jobs\SaveRedditArticle;
use App\Post;
use Illuminate\Cache\Events\KeyWritten;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogKeyWritten implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  KeyWritten $event
     * @return void
     */
    public function handle(KeyWritten $event)
    {
        if (strpos($event->key, 'article') !== FALSE) {

            //dispatch job ot queue
            dispatch(new SaveRedditArticle($event->value));
        }
    }
}
