<?php

namespace Database\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends AbstractModel
{
    protected $table = 'posts';

    protected $primaryKey = 'id_post';

    protected $fillable = [
        'title',
        'content',
    ];

    protected $validation_rules = [
        'title' => 'required',
        'content' => 'required',
    ];

    protected $relationshipsModels = [
        'authors' => PostAuthor::class,
    ];

    protected $relationshipsLimits = [
        'authors' => ':3',
    ];

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'posts_authors', 'id_post', 'id_user')->withTimestamps();
    }
}
