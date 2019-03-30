<?php
require "library.php";
$getParams = array('page');
$paramsRequire = array();
foreach($getParams as $param){
    ${$param} = isset($_GET[$param]) ? $_GET[$param] : '';
    if(in_array($param,$paramsRequire)){
       if(${$param} == ''){
          throwError("Please fill all the parameter.");
       }
    }
}
if($page == ""){
	$page = 1;
}

$productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');

$collection = $productCollection->create()
            ->addAttributeToSelect('*')
            ->setPageSize(10) 
    		->setCurPage($page)
            ->load();


            ?>

	
<?php
$jsonData = array();
$dataArray= array();
$jsonData['total'] = $collection->getSize();
foreach ($collection as $product) {
	$productStockObj = $objectManager->get('Magento\CatalogInventory\Api\StockRegistryInterface')->getStockItem($product->getId());
	$orderCollection = $objectManager->get('Magento\Sales\Model\Order')->getCollection();
	$orderCollection->getSelect()
        ->join(
            'sales_order_item',
            'main_table.entity_id = sales_order_item.order_id'
        )->where('product_id = '.$product->getId());

    $orderCollection->getSelect()->group('main_table.entity_id');

    $itemPending = 0;
    $itemProcess = 0;
    $itemComplete = 0;
    $totalQty = $productStockObj->getQty();

    foreach ($orderCollection as $order) {
    	$orderItems = $order->getAllItems();

		foreach ($orderItems as $item) {
			if($item->getProductId() == $product->getId()){
				if($order->getStatus() == "pending"){
					$itemPending += $item->getQtyOrdered();
				}else if($order->getStatus() == 'processing'){
					$itemProcess += $item->getQtyOrdered();
				}else if($order->getStatus() == 'complete'){
					$itemComplete += $item->getQtyOrdered();
				}
			   
			} 
		}
       
    }
    $totalQty += $itemPending + $itemProcess + $itemComplete;
    
    
    $productData = array();
    $productData['name'] = $product->getName();
    $productData['sku'] = $product->getSku();
    $productData['qty_total'] = $totalQty;
    $productData['qty_available'] = $productStockObj->getQty();
    $productData['qty_pending'] = $itemPending;
    $productData['qty_processing'] = $itemProcess;
    $productData['qty_complete'] = $itemComplete;
    $dataArray[] = $productData;
  	
} 
$jsonData['result']=$dataArray;
echo json_encode($jsonData);



