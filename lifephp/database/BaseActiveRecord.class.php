<?php 
// -----------------------------------------------------------------------
// + LifePHP - Make coding life more efficient,Let the program run faster
// -----------------------------------------------------------------------
// + lifephp database base active record class
// -----------------------------------------------------------------------
// + Copyright (c) 2016 http://www.lifephp.net  All rights reserved
// -----------------------------------------------------------------------
// + Author:  shiquanwu <2448154608@qq.com>
// -----------------------------------------------------------------------
// + Version: 1.0
// -----------------------------------------------------------------------

namespace lifephp\database;
use lifephp\mvc\Model;
class BaseActiveRecord extends Model
{
	/**
     * @var array attribute values indexed by attribute names
     */
    private $_attributes = [];

	/**
     * @var string the sql statement that would be executed.
     */
    protected $_sqlstatement = "";

    /**
     * @uses PHP getter magic method.
     * This method is overridden so that attributes and related objects can be accessed like properties.
     *
     * @param string $name property name
     * @throws \yii\base\InvalidParamException if property name is wrong
     * @return mixed property value
     */
    public function __get($name)
    {
        if (isset($this->_attributes[$name]) || array_key_exists($name, $this->_attributes)) {
            return $this->_attributes[$name];
        } elseif ($this->hasAttribute($name)) {
            return null;
        } else {
            $value = parent::__get($name);
            return $value;
        }
    }

    /**
     * @uses  PHP setter magic method.
     * This method is overridden so that AR attributes can be accessed like properties.
     * @param string $name property name
     * @param mixed $value property value
     */
    public function __set($name, $value)
    {
        if ($this->hasAttribute($name)) {
            $this->_attributes[$name] = $value;
        } else {
            $this->setAttr($name, $value);
        }
    }


    /**
     * @uses  Returns a value indicating whether the model has an attribute with the specified name.
     * @param string $name the name of the attribute
     * @return boolean whether the model has an attribute with the specified name.
     */
    public function hasAttribute($name)
    {
        return isset($this->_attributes[$name]) || in_array($name, $this->attributes());
    }

    /**
     * @uses   Returns the whole sql statement of the 'insert' operation.
     * @param  string $table the table name used to perform insert operation.
     * @param  array $params the parameter array which should be used.
     * @return If success then returns the whole sql statement
     */
    public function getInsertSqlStatement($table = '', $params = [])
    {
		$tableName = $this->getTableName($table);  	
    	if (!empty($params) && isset($params['columnStr']) && isset($params['valueStr']) ) {
    		$this->_sqlstatement = self::getSqlKeyWord('insert');
    		$this->_sqlstatement .= " $tableName ({$params['columnStr']}) ";
			$this->_sqlstatement .= self::getSqlKeyWord('values');
			return $this->_sqlstatement . "({$params['valueStr']})";
    	}
    }
  
    /**
     * @uses   Returns the whole sql statement of the 'update' operation.
     * @param  string $table the table name used to perform update operation.
     * @param  array $params the parameter array which should be used.
     * @return If success then returns the whole sql statement
     *         otherwise, returns null if fail.        
     */
    public function getUpdateSqlStatement($table = '', $params = [])
    {
        $tableName = $this->getTableName($table);   
        if (!empty($params) && isset($params['updateColumnStr'])) {
            $this->_sqlstatement = self::getSqlKeyWord('update');
            $this->_sqlstatement .= " $tableName " . self::getSqlKeyWord('set') . " {$params['updateColumnStr']} ";
            if(!empty($params['where']) && is_array($params['where'])){
                $this->_sqlstatement .= $this->parseWhere($params['where']);
            }
            return $this->_sqlstatement;
        }
        return null;
    }

    /**
     * @uses   Returns the whole sql statement of the 'delete' operation.
     * @param  string $table the table name used to perform delete operation.
     * @param  array $params the parameter array which should be used.
     * @return If success then returns the whole sql statement
     *         otherwise, returns null if fail.        
     */
    public function getDeleteSqlStatement($table = '', $params = [])
    {
        $tableName = $this->getTableName($table);   
        if (!empty($params) && isset($params['where'])) {
            $this->_sqlstatement = self::getSqlKeyWord('delete') . " $tableName ";
            if(!empty($params['where']) && is_array($params['where'])){                
                $this->_sqlstatement .= $this->parseWhere($params['where']);
            }
            return $this->_sqlstatement;
        }
        return null;
    }

    /**
     * @uses   Returns the keyword of database with the key.
     * @param  string $table the table name used to perform insert operation.
     * @return 
     */
    protected static function getSqlKeyWord($key)
    {
    	switch($key){
    		case 'insert':
    			return 'INSERT INTO';
    			break;
    		case 'select':
    			return 'SELECT';
    			break;
            case 'update':
                return 'UPDATE';
                break;  
            case 'delete':
                return 'DELETE FROM';
                break;                               
     		case 'values':
    			return 'VALUES';
    			break;  
     		case '*':
    			return '*';
    			break;   
     		case 'from':
    			return 'FROM';
    			break;  
      		case 'where':
    			return 'WHERE';
    			break;   
            case 'set':
                return 'SET';
                break; 
            case 'and':
                return 'AND';
                break;                    	   			   			    			 			
    		default:
    		    return '';
    		    break;		
    	}
    }

    /**
     * @uses   Returns the whole sql statement of the 'select' operation.
     * @param  string $table the table name used to perform select operation.
     * @param  array $params the parameter array which should be used.
     * @return If success then returns the whole sql statement
     */
    public function getSelectSqlStatement($table = '', $params = [])
    {
    	$tableName = $this->getTableName($table);
    	$this->_sqlstatement = self::getSqlKeyWord('select');
    	if (!empty($params['select'])) {
    		$this->_sqlstatement .= " " . $params['select'] . " ";
    	} else {
    		$this->_sqlstatement .= " " . self::getSqlKeyWord('*') . " ";
    	}

		$this->_sqlstatement .= self::getSqlKeyWord('from') . " $tableName ";
		if (!empty($params['where'])) {
    		$this->_sqlstatement .= self::getSqlKeyWord('where') . " {$params['where']} ";
    	}
    	return $this->_sqlstatement;
    }

    /**
     * @uses   Returns current table name.
     * @param  string $table the table name.
     * @return string $tableName Current table name
     */
    private function getTableName($table)
    {
    	if ($table === '') {
			$tableName = static::tableName();   
    	} else {
    		$tableName = $table;
    	}
    	return $tableName;
    }

    /**
     * @uses   Parses the where conditions.
     * @param  array $where the where array needs to be parsed.
     * @return string the whole where sql.
     */
    private function parseWhere($where)
    {
        if(!empty($where) && is_array($where)){
            $whereParams = [];
            foreach($where as $where_key => $where_val){
                $whereParams[] = "$where_key = '$where_val'";
            }
             
            return self::getSqlKeyWord('where') . ' ' . implode(' ' . self::getSqlKeyWord('and') . ' ', $whereParams);
        }
        return null;
    }
}
