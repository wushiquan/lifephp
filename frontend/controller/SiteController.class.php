<?php 
// -----------------------------------------------------------------------
// + LifePHP - Make coding life more efficient,Let the program run faster
// -----------------------------------------------------------------------
// + Application site controller
// -----------------------------------------------------------------------
// + Copyright (c) 2016 http://www.lifephp.net  All rights reserved
// -----------------------------------------------------------------------
// + Author:  shiquanwu <2448154608@qq.com>
// -----------------------------------------------------------------------
// + Version: 1.0
// -----------------------------------------------------------------------

namespace frontend\controller;

use Life;
use lifephp\mvc\Controller;
use lifephp\common\Tool;
use lifephp\database\Connection;
use frontend\models\Comment;
class SiteController extends Controller
{
    /**
     * @uses   the index page 
     * @access public
     * @return void
     */
    public function actionIndex()
    {
        $orderno = 'SM23748934';
        $username = '13824394613';
    	$this->display('index',['order'=>$orderno, 'username'=>$username]);
    }

    /**
     * @uses   Test lifephp database CURD operation, such like the execution create, update, read and delete sql statement.
     * @access public
     * @return void
     */
    public function actionTestDb()
    {
        //INSRET EXAMPLE
        // $CommnetModel = new Comment();
        // $CommnetModel->user_id = mt_rand(1,1000000);
        // $CommnetModel->content = "love you " . $CommnetModel->user_id;
        // $rows = $CommnetModel->insert();

        //SELECT EXAMPLE
        //$record = Comment::model()->queryAll(['select'=>'id,content','where'=>'user_id > 1000']);        
        //$record = Comment::model()->queryOne(['select'=>'id,content','where'=>'user_id = 2']);  

        //UPDATE EXAMPLE
        $CommnetModel = new Comment();  
        $CommnetModel->user_id = 123456;
        $CommnetModel->content = "we are 123456";
        $rows = $CommnetModel->update(['id'=>2]);
 
        //DELETE EXAMPLE
        // $rows = Comment::model()->delete(['id'=>15]);          

        // $connection = Life::$frame->db;
        // $userid = mt_rand(1,1000000);
        // $sql = 'INSERT INTO `tbl_comment` (content,user_id) values("love you'.$userid.'","'.$userid.'")';
        // $rows = $connection->createCommand($sql)->execute();

         // $connection = Life::$frame->db;
         // $sql = 'SELECT * FROM `tbl_comment`';
         // $rows = $connection->createCommand($sql)->queryAll();  

        Tool::printr($rows);
    }

    /**
     * @uses   The test action which is used for test. 
     * @access public
     * @return void
     */
    public function actionTest()
    {
        $this->redirect('/index.php?r=test/index&test=action&name=wushiquan',2,'redirect after 2s');    
    }
}
