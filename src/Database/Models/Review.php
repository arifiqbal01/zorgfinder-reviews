<?php
namespace ZorgFinder\Reviews\Database\Models;

class Review
{
    public int $id;
    public int $provider_id;
    public ?int $user_id;
    public int $rating;
    public ?string $comment;
    public int $approved;
    public string $created_at;
}
