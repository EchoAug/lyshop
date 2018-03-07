<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/19
 * Time: 16:05
 */

namespace app\api\Controller;

use app\api\model\Feed as FeedModel;

class Feed
{
    /**
     * @url 'api/feed/feedback'
     * @post ['message']
     * @return []
     */
    public function feedBack()
    {
        try {
            $feed = new FeedModel();
            $feed->message = input('post.message');
			$feed->uid = input('post.uid');
            $feed->bid = input('post.bid');
            $feed->save();
            return 1;
        } catch (\Exception $e) {
            return 0;
        }
    }
}