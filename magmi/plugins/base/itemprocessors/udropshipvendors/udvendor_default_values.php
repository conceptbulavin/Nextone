<?php
/**
 * Class SampleItemProcessor
 * @author dweeves
 *
 * This class is a sample for item processing   
*/ 
class UdropshopDefaultValuesItemProcessor extends Magmi_ItemProcessor
{

	protected $_dset=array();
	protected $_dcols=array();
	
    public function getPluginInfo()
    {
        return array(
            "name" => "Udropship vendor default values",
            "author" => "mtr",
            "version" => "0.0.1",
        	//"url" => $this->pluginDocUrl("Udropship_Default_Values_setter")
        );
    }
	

	
	
	public function processItemBeforeId(&$item,$params=null)
	{/*
		foreach($this->_dcols as $col)
		{
			$item[$col]=$this->_dset[$col];
		}
		return true;*/
	}
	
	public function processItemAfterId(&$item,$params=null)
	{
        $ssql = 'SELECT vendor_product_id as vpid from `'.$this->tablename("udropship_vendor_product").'` WHERE product_id=?';
        $pvendor = $this->selectOne($ssql,array($params['product_id']),'vpid');

        if( !$pvendor ){
            $isql = 'INSERT INTO '.$this->tablename("udropship_vendor_product")."(vendor_id,product_id,vendor_sku,vendor_cost,stock_qty)
            VALUES(1,'{$params['product_id']}','{$item['sku']}','{$item['cost']}',0)";
            $this->insert($isql,array());

            $isql = 'INSERT INTO '.$this->tablename("udropship_vendor_product")."(vendor_id,product_id,vendor_sku,vendor_cost,stock_qty)
            VALUES(2,'{$params['product_id']}','{$item['sku']}','{$item['cost']}',0)";
            $this->insert($isql,array());
        }
        return true;
	}
	
	/*
	public function processItemException(&$item,$params=null)
	{
		
	}*/
	/*
	public function initialize($params)
	{
		foreach($params as $k=>$v)
		{
			if(preg_match_all("/^DEFAULT:(.*)$/",$k,$m) && $k!="DEFAULT:columnlist")
			{
				$this->_dset[$m[1][0]]=$params[$k];
			}
		}
	}
	
	public function getPluginParams($params)
	{
		$pp=array();
		foreach($params as $k=>$v)
		{
			if(preg_match("/^DEFAULT:.*$/",$k))
			{
				$pp[$k]=$v;
			}
		}	
		return $pp;
	}
	
	
	public function processColumnList(&$cols,$params=null)
	{
		$dcols=array_diff(array_keys($this->_dset),array_intersect($cols,array_keys($this->_dset)));
		foreach($dcols as $col)
		{
			if(!empty($this->_dset[$col]))
			{
				$cols[]=$col;
				$this->_dcols[]=$col;								
			}
		}
		$this->log("Adding Columns ".implode(",",$dcols),"startup");
		
		return true;
	}
	*/
static public function getCategory()
	{
		return "Temashop";
	}
}