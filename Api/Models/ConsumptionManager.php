<?php
require_once '../Database/rb.php';
class ConsumptionManager 
{	
	function __construct(){
		require_once '../Database/dbsetup.php';
	}
	//ConsumptionType
	function getConsumptionType($id){				
		$consumptionType = R::load('consumptiontype', $id);;
		return $consumptionType;
	}
	function getAllConsumptionTypes(){
		$consumptionTypes = R::findAll( 'consumptiontype' );
		return $consumptionTypes;
	}	
	function createConsumptionType($description){		
		  $consumptionType = R::dispense( 'consumptiontype' );
		  $consumptionType->description = $description;
		  $id = R::store( $consumptionType );
		  return $id;
	}	
	
	//Consumption
	function getOpenConsumption($tableId){				
		$consumption = R::findOne('consumption','table_id = ? AND status = "open" ORDER BY id DESC', [$tableId]);
		return $consumption;
	}	
	function getOpenConsumptions(){
		$consumptions = R::find('consumption','status = "open" ORDER BY id DESC');
		return $consumptions;
	}
	function getConsumption($id){				
		$consumption = R::load('consumption', $id);;
		return $consumption;
	}	
	function getClosedConsumptionsTotalByDates($startDate, $endDate){
		$consumptions = R::find( 'consumption', 'status="close" AND closed >= ? AND closed <= ? ORDER BY id', [ $startDate, $endDate ] );
		$total = 0.00;
		foreach( $consumptions as $consumption ) {
			$total += floatval($consumption->total);
		}
		return $total;
	}
	function getClosedConsumptionsByDates($startDate, $endDate){
		$consumptions = R::find( 'consumption', 'status="close" AND closed >= ? AND closed <= ? ORDER BY id DESC', [ $startDate, $endDate ] );
		return $consumptions;
	}
	function createConsumption($tableId, $tableDescription, $consumptionTypeId, $consumptionTypeDescription){		
		  $consumption = R::dispense( 'consumption' );
		  $consumption->tableId = $tableId;
		  $consumption->tableDescription = $tableDescription;
		  $consumption->consumptionTypeId = $consumptionTypeId;
		  $consumption->consumptionTypeDescription = $consumptionTypeDescription;
		  $consumption->subtotal = 0.00;
		  $consumption->discountDescription = "Descuento";
		  $consumption->discount = 0.00;
		  $consumption->total = 0.00;
		  $consumption->status = "open";
		  $consumption->created = date("Y-m-d H:i:s");		  
		  $id = R::store( $consumption );
		  return $id;
	}	
	function updateConsumptionTotal($id){		
		$items = self::getItemsByConsumption($id);
		$subtotal = 0.00;		
		foreach( $items as $item ) {
			$subtotal += floatval($item->subtotal);
		}		
		$consumption = self::getConsumption($id);
		$consumption->subtotal = $subtotal;
		$consumption->total = $consumption->subtotal - $consumption->discount;
		$consumption->lastModified = date("Y-m-d H:i:s");
		R::store( $consumption );
		$consumption->fresh();
		/*if(sizeof($items)==0){
			self::cancelConsumption($consumption->id);			
		}*/
		return $consumption;		
	}	
	function updateConsumptionType($consumptionId, $consumptionTypeId){
		$consumption = self::getConsumption($consumptionId);
		$consumptionType = self::getConsumptionType($consumptionTypeId);
		$consumption->consumptionTypeId = $consumptionType->id;
		$consumption->consumptionTypeDescription = $consumptionType->description;
		$consumption->lastModified = date("Y-m-d H:i:s");
		R::store( $consumption );
		$consumption->fresh();
		return $consumption;
	}
	function updateConsumptionDiscount($consumptionId, $discountDescription, $discount){
		$consumption = self::getConsumption($consumptionId);
		$consumption->discountDescription = $discountDescription;
		$consumption->discount = $discount;
		$consumption->lastModified = date("Y-m-d H:i:s");
		R::store( $consumption );
		$consumption = self::updateConsumptionTotal($consumptionId);
		return $consumption;
	}
	function closeConsumption($id){
		$consumption = self::getConsumption($id);
		$consumption->status="close";
		$consumption->closed = date("Y-m-d H:i:s");
		R::store( $consumption );
		$consumption->fresh();
		return $consumption;
	}	
	function cancelConsumption($id){
		$consumption = self::getConsumption($id);
		$consumption->status="cancel";
		$consumption->lastModified = date("Y-m-d H:i:s");
		R::store( $consumption );
		$consumption->fresh();
		return $consumption;
	}
	
	//Item
	function getItemsByConsumption($consumptionId){				
		$consumptions = R::find( 'item', ' consumption_id = ? ORDER BY id', [ $consumptionId] );
		return $consumptions;
	}
	
	function getItemByProductId($consumptionId, $productId){
		$item = R::findOne('item','consumption_id = ? AND product_id = ? ORDER BY id DESC', [$consumptionId, $productId]);;
		return $item;
	}
	
	function getItem($id){				
		$item = R::load('item', $id);;
		return $item;
	}
	
	function createItem($consumptionId, $productId, $productDescription, $productUnitPrice){		
		  $item = R::dispense( 'item' );
		  $item->consumptionId = $consumptionId;
		  $item->productId = $productId;
		  $item->productDescription = $productDescription;
		  $item->productUnitPrice = $productUnitPrice;
		  $item->quantity = 0;
		  $item->subtotal = 0.0;
		  $id = R::store( $item );
		  return $id;
	}
	
	function increaseItemQuantity($itemId){
		$item = self::getItem($itemId);
		$item->quantity++;
		$item->subtotal = $item->quantity * $item->productUnitPrice;
		R::store( $item );
		$item->fresh();
		self::updateConsumptionTotal($item->consumptionId);
		return $item;
	}
	
	function decreaseItemQuantity($itemId){
		$item = self::getItem($itemId);
		$item->quantity--;
		$item->subtotal = $item->quantity * $item->productUnitPrice;
		R::store( $item );
		$item->fresh();
		if($item->quantity==0){
			R::trash( $item );
		}
		self::updateConsumptionTotal($item->consumptionId);		
		return $item;
	}
	function updateItemPrice($itemId, $priceAmount){
		$item = self::getItem($itemId);
		$item->productUnitPrice = $priceAmount;
		$item->subtotal = $item->quantity * $item->productUnitPrice;
		R::store( $item );
		$item->fresh();
		self::updateConsumptionTotal($item->consumptionId);
		return $item;
	}
}

?>