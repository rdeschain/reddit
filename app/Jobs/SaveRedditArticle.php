<?php

namespace App\Jobs;

use App\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SaveRedditArticle implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $article;

    /**
     * SaveRedditArticle constructor.
     * @param array $article
     */
    public function __construct(array $article)
    {
        $this->article = $article;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Post::firstOrCreate(['reddit_id' => $this->article['reddit_id']], [
            'reddit_id' => $this->article['reddit_id'],
            'url' => $this->article['url'],
            'permalink' => $this->article['permalink'],
            'author' => $this->article['author']]);
    }
}
