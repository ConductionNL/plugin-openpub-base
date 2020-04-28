<?php

namespace OWC\OpenPub\Base\RestAPI\ItemFields;

class CommentField extends \OWC\OpenPub\Base\Support\CreatesFields
{
    /**
     * Creates an array of comments.
     *
     * @param \WP_Post $post
     *
     * @return array
     */
    public function create(\WP_Post $post): array
    {
        $result           = [];
        $result['count']  = (int) $post->comment_count;
        $result['status'] = $post->comment_status;
        $result['items']  = [];

        if (!in_array($post->comment_status, ['open'])) {
            return $result;
        }

        $result['items'] = $this->getComments($post->ID);

        return $result;
    }

    /**
     * Get comment items of a post.
     *
     * @param int    $postID
     *
     * @return array
     */
    protected function getComments(int $postID): array
    {
        $comments = get_comments([
            'post_id' => $postID,
            'status'  => 'approve'
        ]);

        if (! $comments) {
            return [];
        }

        return array_map(function (\WP_Comment $comment) {
            return [
                'id'       => $comment->comment_ID,
                'author'   => $comment->comment_author,
                'content'  => $comment->comment_content,
                'date'     => $comment->comment_date
            ];
        }, $comments);
    }
}
