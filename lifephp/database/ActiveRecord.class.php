<?php 
// -----------------------------------------------------------------------
// + LifePHP - Make coding life more efficient,Let the program run faster
// -----------------------------------------------------------------------
// + lifephp database active record class
// -----------------------------------------------------------------------
// + Copyright (c) 2016 http://www.lifephp.net  All rights reserved
// -----------------------------------------------------------------------
// + Author:  shiquanwu <2448154608@qq.com>
// -----------------------------------------------------------------------
// + Version: 1.0
// -----------------------------------------------------------------------

namespace lifephp\database;
use Life;
class ActiveRecord extends BaseActiveRecord
{

    /**
     * @uses  Returns the database connection used by this AR class.
     * By default, the "db" application component is used as the database connection.
     * @return Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Life::$frame->db;
    }

    /**
     * @uses create the sql command with the database connection.
     * @param string $sql the sql that will be executed.
     * @return connection the database connection used by this AR class
     */
    public static function createCommand($Sql)
    {
        return static::getDb()->createCommand($Sql);
    }

    /**
     * @uses Inserts a row into the associated database table using the attribute values of this record.
     *
     * For example, to insert a customer record:
     *
     * ```php
     * $customer = new User;
     * $customer->name = $name;
     * $customer->email = $email;
     * $customer->insert();
     * ```
     *
     * @param array $attributes list of attributes that need to be saved. Defaults to null,
     * meaning all attributes that are loaded from DB will be saved.
     * @return boolean whether the attributes are valid and the record is inserted successfully.
     * @throws \Exception in case insert failed.
     */
    public function insert( $attributes = null)
    {
    	if ($attributes === null) {
    		$attributes = $this->getAttributes();
    	}	
    	foreach ($attributes as $column => $value) {
    		if (!empty($value)) {
    			$valueArr[] = "'" . $value . "'";   			
    		}
    	}

    	$columnStr = implode(',', $this->attributes());
    	$valueStr  = implode(',', $valueArr);
    	$optSql = $this->getInsertSqlStatement(static::tableName(), ['columnStr'=>$columnStr, 'valueStr'=>$valueStr]);
    	$result = static::createCommand($optSql)->execute();
        return $result;
    }

    /**
     * @uses Update all rows of the associated database table which is suitable for the condition using the attribute values of this record.
     *
     * For example, to update all customer records which need to be changed:
     *
     * ```php
     * $customer = new User;
     * $customer->email = $email;
     * $customer->update(['id'=>234]);
     * ```
     *
     * @param array $attributes list of attributes that need to be saved. Defaults to null,
     *        meaning all attributes that are loaded from DB will be saved.
     *        array $condition the condition that are suitable for.      
     *      
     * @return boolean whether the attributes are valid and the record is updated successfully.
     * @throws \Exception in case update failed.
     */
    public function update($condition = [], $attributes = null)
    {
        if ($attributes === null) {
            $attributes = $this->getAttributes();
        }   
        foreach ($attributes as $column => $value) {
            if (!empty($value)) {
                $updateColumnArr[] = "$column = ' " . $value . "'";               
            }
        }

        $updateColumnStr = implode(',', $updateColumnArr);
        $optSql = $this->getUpdateSqlStatement(static::tableName(), ['where'=>$condition, 'updateColumnStr'=>$updateColumnStr]);
        $result = static::createCommand($optSql)->execute();
        return $result;
    }

    /**
     * @uses Delete the rows from table according to the where condition.
      * For example, to delete some records from this table:
     *
     * ```php
     * $record = Comment::model()->delete(['id' => 2]);   
     * ```
     * @param array $condition the condition that the records belong to.
     * @return the row nums which are affected.
     */
    public function delete($condition = [])
    {
        $optSql = $this->getDeleteSqlStatement(static::tableName(), ['where'=>$condition]);
        $result = static::createCommand($optSql)->execute();
        return $result;
    }

    /**
     * @uses get current model object    
     * @return object $this current model object. 
     */
    public static function model()
    {   	
    	$modelName = get_called_class();
    	return new $modelName();
    }

    /**
     * @uses Select all rows from table according to the where condition.
     * For example, to query all records from this table:
     *
     * ```php
	 * $record = Comment::model()->queryAll(['select'=>'id,content','where'=>'user_id > 1000']);     
     * ```
     * @param array $condition the query condition,including in the select column and where column.
     * @return array the all records in result set.
     */
    public function queryAll($condition = array())
    {
        $optSql = $this->getSelectSqlStatement(static::tableName(), $condition);
    	$result = static::createCommand($optSql)->queryAll();
    	return $result;
    }

    /**
     * @uses Select the first row from table according to the where condition.
      * For example, to query all records from this table:
     *
     * ```php
	 * $record = Comment::model()->queryOne(['select'=>'id,content','where'=>'user_id = 2']);   
     * ```
     * @param array $condition the query condition,including in the select column and where column.
     * @return array the all records in result set.
     */
    public function queryOne($condition = array())
    {
        $optSql = $this->getSelectSqlStatement(static::tableName(), $condition);
    	$result = static::createCommand($optSql)->queryOne();
    	return $result;
    }
}
