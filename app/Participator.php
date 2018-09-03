<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Participator extends Model
{
    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'participator';

    protected $fillable = ['yuedan_id','user_id','join_role','avatar_url',
        'join_time','is_join', 'name'];

    const CREATED_AT = null;
    const UPDATED_AT = null;

    /**
     * 临时头像配置
     *
     * @var array
     */
    public static $temp_avatar = [
        'zhenhuan' => 18,
        'animal-fruit' => 23,
        'marvel' => 32
    ];

    public static $rand_name = [
        'Sunny',
        'kobe',
        'sweetie',
        'sugar',
        'Angela',
        'bruce lee',
        'jackie chan',
        'Sabina',
        'lao wang',
        'Jeff Dean',
        'Barbara Liskov',
        'Elon Musk',
        'Larry Page',
        'Carl Sassenrath',
        'Mark Zuckerburg',
        'Brendan Eich',
        'Tim Berners-Lee',
        'Hedy Lamarr',
        'Bill Gates',
        'Monkey·D·Luffy',
        'Roronoa Zoro',
        'Nami',
        'Usopp Sogeking',
        'Sanji',
        'Tony Tony Chopper',
        'Nico Robin','FRANKY',
        'BROOK','GOL.D.ROGER','Silvers Rayleigh',
        'Shanks','Buggy','Edward Newgate','Kaidou',
        'Charlotte·Linlin','Marshall D. Teach',
        'Sir.Crocodile','Dracula Mihawk',
        'Bartholomew Kuma','Donquixote Doflamingo',
        'Boa Hancock ','Gekko Moria','Jimbei',
        'Trafalgar·Law','Kiddo','Drake',
        ' Killer','Jewelry · Bonney','Capone · becky','Urouge',
        'Sakazuki','Kprusoian','Emporio Ivankov',
        'Smoker','Tashigi','Lafitte','Van Auger',
        'Jesus Burgess','Ben beckman','Jesus cloth',
        'Rockstar','Marco','Josh','Thatch','Daz Bones',
        'Von clay','Hina','Bellamy',
        'Spandam','Rob·Lucci','Kaku','Jyabura','Blueno',
        'Kumadori','Fukurou',' Kalifa','Perona',
        'Shirahoshi',' Jango','Makino',' Higuma',
        'Rika','Kaya'
    ];

    /**
     * 返回临时头像组
     *
     * @return string
     */
    public static function getAvatarGroup()
    {
        return array_rand(self::$temp_avatar, 1);
    }

    public static function getAvatarUrl($group)
    {
        return '/images/'.$group.'/'.mt_rand(1, self::$temp_avatar[$group]).'.jpg';
    }

    public static function getRandName()
    {
        return self::$rand_name[array_rand(self::$rand_name, 1)];
    }

}
