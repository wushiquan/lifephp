<?php 
// -----------------------------------------------------------------------
// + LifePHP - Make coding life more efficient,Let the program run faster
// -----------------------------------------------------------------------
// + Application Comment table model class
// -----------------------------------------------------------------------
// + Copyright (c) 2016 http://www.lifephp.net  All rights reserved
// -----------------------------------------------------------------------
// + Author:  shiquanwu <2448154608@qq.com>
// -----------------------------------------------------------------------
// + Version: 1.0
// -----------------------------------------------------------------------

namespace frontend\models;

use Life;
use lifephp\database\ActiveRecord;

class Comment extends ActiveRecord
{
	public $content;
	public $user_id;

    /**
     * @var the value which represents first level comment 
     */
    const COMMENT_LEVEL_FIRST = 1;
    
    /**
     * @uses  the table name associated with this model class
     */
    public static function tableName()
    {
        return "`tbl_comment`";
    }

    /**
     * @uses model attributeLabels
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'content' => '评论内容',
            'use_id' => '用户关联ID',
        ];
    }
}
