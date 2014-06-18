<html>
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    </head>
    <body>
        
        
        <?php

        // initialize magento environment for 'default' store
        require_once '../app/Mage.php';
        //Mage::app('default'); // Default or your store view name.
        $currentStore = Mage::app()->getStore()->getId();
        Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
        
        $connect =  mysql_connect('127.0.0.1', 'root', 'goblin21');
        if($connect){
            mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $connect);
            $db = mysql_select_db ('brain', $connect);
            if($db){
                createCategoryRec( 1 , 0 );



            }
        }
        
        Mage::app()->setCurrentStore($currentStore);
        
        function createCategoryRec( $id, $lvl ){
            $sql = ' select * from category'.' where parentID = '. $id  ;
            //echo $sql.'   =====>>>> '.$lvl.' <br>';
            $result = mysql_query($sql);
            if($result){
                while( $row = mysql_fetch_assoc($result)){
                    createCategory($row, $lvl);
                }

            }
        }
        
        
        function createCategory( $row, $lvl = 0 ){
            $first = true;
            $category = Mage::getModel('catalog/category')->load($row['categoryID']);
            
            $category->setStoreId(Mage_Core_Model_App::ADMIN_STORE_ID);
            $category->setId($row['categoryID']);
            $general['name'] = $row['name'];
            if( $category->getId() ){
                $first = false;
            }
            if($lvl == 0 && $first){
                $general['path'] = "1/2/". $row['categoryID'];
            }
            $general['description'] = $row['name'];
            $general['meta_title'] = $row['name']; //Page title
            $general['meta_keywords'] = $row['name'];
            $general['meta_description'] = $row['name'];
            $general['display_mode'] = "PRODUCTS"; //static block and the products are shown on the page
            $general['is_active'] = 1;
            $general['is_anchor'] = 1;
            //$general['url_key'] = strtolower($row['name']);//url to be used for this category's page by magento.
            //$general['image'] = "cars.jpg";
            $category->addData($general);
            //print_r($category->getData());die;
            $category->save();
            if( $lvl > 0 && $first ){
                $id = $category->getId();
                $parent = Mage::getModel('catalog/category')->load($row['parentID']);
                $path = $parent->getPath();
                $category->setPath( $path.'/'.$row['categoryID'] ); // Important you get this right.
                $category->save();
            }
            
            $lvl = $lvl + 1;
            createCategoryRec( $row['categoryID'], $lvl );
        }
        
        
        ?>
        
    </body>
</html>

