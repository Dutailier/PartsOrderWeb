<?php

include_once(ROOT . 'libs/database.php');
include_once(ROOT . 'libs/entities/comment.php');

class Comments
{
    public static function Attach(Comment $comment)
    {
        $query = 'EXEC [addLine]';
        $query .= '@orderId = "' . intval($comment->getOrderId()) . '"';
        $query .= '@userId = "' . intval($comment->getUserId()) . '"';
        $query .= '@text = "' . trim($comment->getText()) . '"';

        $rows = Database::Execute($query);

        if (empty($rows)) {
            throw new Exception('The comment wasn\'t added.');
        }

        $comment->setId($rows[0]['id']);
        $comment->setCreationDate($rows[0]['creationDate']);

        return $comment;
    }

    public static function FilterByOrderId($id)
    {
        $query = 'EXEC [getCommentsByOrderId]';
        $query .= '@orderId = "' . intval($id) . '"';

        $rows = Database::Execute($query);

        $comments = array();
        foreach ($rows as $row) {

            $comment = new Comment(
                $row['orderId'],
                $row['userId'],
                $row['text'],
                $row['creationDate']
            );
            $comment->setId($row['id']);

            $comments[] = $comment;
        }
        return $comments;
    }
}