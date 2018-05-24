<?php
namespace Responsive;
use Illuminate\Database\Eloquent\Model;
class NewsLetters extends Model
{
    public $table = 'news_letters';
public $fillable = ['user_id','email'];

}
