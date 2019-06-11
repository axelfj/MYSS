<?php

class DTOPost_Comment_Tag
{
    private $posts;
    private $comments;
    private $tags;

    // Setters.
    public function setPosts($posts)
    {
        $this->posts = $posts;
    }

    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    // Getters.
    public function getPosts()
    {
        return $this->posts;
    }

    public function getComments()
    {
        return $this->comments;
    }

    public function getTags()
    {
        return $this->tags;
    }
}