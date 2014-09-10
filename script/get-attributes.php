<html>
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    </head>
    <body>
        
        
        <?php
        echo '<pre>Starting prepaire import file.<br/>';
        function pr_ar($a){echo '<pre>'; print_r($a);die('here');}
        // initialize magento environment for 'default' store
        //@require_once '../../app/Mage.php';
        require_once '../app/Mage.php';
        //$model=Mage::getModel('eav/entity_setup','core_setup');
    
        $need_attrs = array("Производитель", "Модель", "Артикул", "Тип", "Объем памяти", "Дисплей", "Аудиоформаты", "FM-тюнер", "Слот расширения памяти", "Тип питания",
            "Физические размеры", "Цвет", "Гарантия мес", "Вес", "Диктофон", "Видеоформаты", "Фотоформаты", "Дополнительные функции", "Слот для карт памяти", "Поддержка типов носителей",
            "Тип телевизора", "Диагональ экрана", "Разрешение экрана", "Тип экрана", "Углы обзора", "Тип тюнера", "Акустика", "Интерфейсы", "Подстветка", "Цвет панели", "Размеры (Ш х В х Г) ",
            "Вес, кг", "Класс товара", "Матрица", "Световой поток", "ANSI-Люмен", "Разрешающая способность", "Поддерживаемые разрешения", "Контрастность", "Лампа (мощность) ",
            "Ресурс лампы, часов", "Входы", "Встроенный звук", "Трапецеидальная коррекция", "Порты управления", "Видеостандарты", "Размеры, мм", "Выход", "Шум", "Параметры оптики",
            "HDTV совместимость", "Объем", "Скорость вращения шпинделя", "Интерфейс", "Линейка (Серия)", "Объем встроенной памяти", "Длительность записи",
            "Цвет корпуса", "Формат", "Диагональ (дюйм)", "Беспроводные возможности", "Класс", "Диагональ", "Угол наклона/поворота", "Максимальная нагрузка", "Функции",
            "Поддерживаємій стандарт VESA", "Расстояние от стены", "Видеовыходы", "Аудиовыходы", "Порты и интерфейсы", "Возможность установки HDD", "Сетевые интерфейсы", 
            "Поддержка видео кодеков", "Интернет функции", "Размер", "Метод охлаждения", "Источник питания", "Выходное напряжение", "Мощность (не более)", "Размеры", "Тип памяти",
            "Частота шины", "Пропускная способность шины", "Латентность (тайминги)", "Номинальное напряжение", "Кол-во модулей в комплекте", "Охлаждение", "Серия", "Тип поставки",
            "Интерфейс подключения", "Объём памяти", "Скорость чтения", "Скорсекость записи", "Тип флеш-памяти", "Яркость", "Гарантия", "Габариты", "Размер экрана", "Процессор", "Чипсет",
            "Объем и тип HDD", "Оптический накопитель", "Графическая система", "Сетевой адаптер", "Беспроводные подключения", "Порты USB", "Видеоразъемы", "Card-reader", "Мультимедия", 
            "Операционная система", "Емкость батареи", "Проекционное соотношение", "Память", "Жосткий диск", "RAID", "Оптический привод", "Сетевой контроллер", "Блок питания", "Корзина",
            "Внешние размеры", "Класс планшета", "Объем", "памяти", "Объем накопителя", "Расширение памяти", "Тыловая камера", "Фронтальная камера", "Тип сокета", "Семейство процессора",
            "Количество ядер", "Тактовая частота ядра", "Тип шины", "Intel® Smart Cache", "Технология CMOS", "Частота функционирования", "Объем жесткого диска", "Сетевая карта", "Интерфесы",
            "Мультимедиа", "Размери (ШxВxГ)", "Сокет", "Разрешение видео", "Микрофон", "Срок работы батареи", "Нагрузка", "Высота", "Основание", "VESA", "Наличие вентилятора", 
            "Поддерживаемые форматы", "Аккумулятор", "Размеры полотна (ВxШ)", "Размеры рабочей зоны (ВxШ)", "Програмное обеспечение", "Тип полотна", "Операционная смстема", "Технология",
            "Тип подставки", "Габаритные размеры (ВxШxГ)", "Порты", "Интерфейс накопителей", "Уровни RAID", "Скорость передачи данных", "Количество портов", "Тип хранилища", "Язык",
            "Расстояние от потолка", "Поддержка моделей", "Производитель ноутбука", "Емкость", "Количество ячеек", "Напряжение", "Модель ноутбука", "P/N совместимых аккумуляторов",
            "Стандарт цифрового ТВ", "Размер дисплея", "Полотно", "Поддержка видео форматов", "Поддержка аудио форматов", "Поддержка форматов изображения", "Жесткий диск", "Выходная мощность",
            "Комплект", "Внешние коннекторы", "Тактова частота шини", "Объем кеш-памяти -го уровня", "Объем кеш-памяти -го уровня", "Слоты для памяти", "Слоты расширения", "Камера", "Тип матрицы",
            "Класс видеокамеры", "Тип носителя", "Зум оптический/цифровой", "Стабилизация", "Звук", "Карта памяти", "Разъемы", "Фото", "Обьем", "Графическое ядро", "Объект детекции",
            "Частота передачи", "Тип элемента питания", "Экран", "Дополнительные мониторы", "Крепление", "Открытие замка", "Тип крепления", "Цветность панели", "Минимальная освещенность",
            "Угол обзора", "Управление замком", "Тип детектирования", "Тип установки", "Вид", "Разрешение", "Тип сьемки", "Размер матрицы", "Тип сенсора и его производитель", 
            "Тип процессора и его производитель", "Фокусное расстояние", "Баланс белого", "Диапазон рабочих температур", "К-во и модель видеокамер", "Количество и тип видеовходов", 
            "Количество и тип аудиовходов", "Количество BNC видеовыходов", "VGA выходы", "Стандарты записи видео", "К-во каналов записи D в реальном времени", "Количество USB", "Ethernet",
            "Управление PTZ", "Поддержка CMS", "Кулер", "HDMI", "Возможность внешнего применения", "Кронштейн", "Ток заряда батареи", "Протокол", "Частота GSM сети", "Модель GSM модуля",
            "Возможность наружного применения", "Тип сенсора", "Покрываемая площадь", "Дальность детектирования", "Тип сенсора движения", "Высота инсталляции", "Мощность блока питания",
            "Рекомендованная глубина шкафа");
        
        
        define( 'ATRR_APENDIX', 'custom_attribute_' );
        
        
        
        $currentStore = Mage::app()->getStore()->getId();
        Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
        
        Mage::app('default'); // Default or your store view name.
        
        
        $need_attrs = array();
        $__mapper = fopen(Mage::getBaseDir(). DS . 'var'. DS .'import'. DS . '123.csv', 'r');
        if($__mapper)
        while(!feof($__mapper))
        {
            $tmp = fgetcsv($__mapper);
            $need_attrs[] = $tmp[0];
        }
        fclose($__mapper);
        unset($need_attrs[211]);
        
        /*
        $i = 1;
        $attr_model = Mage::getModel('catalog/resource_eav_attribute');
        $code = ATRR_APENDIX . $i;
        $attr = $attr_model->loadByCode('catalog_product', $code );
        
        while( $attr->getAttributeId() ){
            echo $code. ' deleted<br>';
            $i++;
            $code = ATRR_APENDIX . $i;
            $attr_model = Mage::getModel('catalog/resource_eav_attribute');
            $attr = $attr_model->loadByCode('catalog_product', $code );
            if($attr->getAttributeId()){
                $attr->delete();
            }
        }
        
        die;
        */
        /*
        $attributeSetModel = Mage::getModel('eav/entity_attribute_set');
                ->getCollection();
        die($attributeSetId->getSelect());
        $a        ->addFieldToFilter('entity_type_id', 3)
                ->addFieldToFilter('attribute_set_name', 'Default')
                ->getFirstItem();
        pr_ar($attributeSetId);
        */
        $connect =  mysql_connect('127.0.0.1', 'root', 'spywszxvxqvc');
        if($connect){
            mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $connect);
            $db = mysql_select_db ('brain', $connect);
            $attrs = array();
            $attrs_values = array();
            $attrs_opt = array();
            $attrs_opt_values = array();
            $created_attrs = array();
            $attr_opt_count = array();
            
            if($db){
                
                $filename = Mage::getBaseDir(). DS . 'var'. DS .'import'. DS . 'catalog_product_'. date('Ymd_His') .'.csv';//_His
                $attrMapperFile = Mage::getBaseDir(). DS . 'var'. DS . 'mapper.csv';
                
                $mapper = fopen($attrMapperFile, 'r');
                $mapperArray = array();
                if( $mapper){
                    while(! feof($mapper))
                    {
                        $mapperArray[] = fgetcsv($mapper);
                    }
                    $newMapper = array();
                    if( $mapperArray ){
                        foreach( $mapperArray[0] as $i => $val ){
                            $newMapper[$val] = $mapperArray[1][$i];
                        }
                        $mapperArray = $newMapper;
                    }
                }
                
                fclose($mapper);
                
                
                $sql = ' select * from product';
                $result = mysql_query($sql);
                if($result){
                    while( $row = mysql_fetch_assoc($result)){
                        foreach ($row as $key => $value ){
                            if($key == 'options' && $value ){
                                $a = unserialize($value);
                                if(is_array($a)){
                                    foreach($a as $el){
                                        if( !in_array( $el->name, $need_attrs )){
                                            continue;
                                        }
                                        
                                        if( !in_array( $el->name, $attrs_opt )){
                                            $attrs_opt[] = $el->name;
                                        }
                                        if(!in_array( $el->value, $attrs_opt_values[$el->name] )){
                                            $attrs_opt_values[$el->name][] = $el->value;
                                        }
                                    }
                                }
                            } else {
                                if( !in_array( $key, $attrs )){
                                    $attrs[] = $key;
                                }
                                if( in_array( $key, array('warranty'))){
                                    if(!in_array( $value, $attrs_values[$key] )){
                                        $attrs_values[$key][] = $value;
                                    }
                                }
                            }
                        }
                    }
                }
                
                
                //echo count($attrs_opt_values);
                //pr_ar($attrs_opt_values);
                //print_r($attrs_values['warranty']); die;
                $all_attrs = array_merge( $attrs, $attrs_opt );
                /*
                 * 0] => Array
                    (
                        [value] => 26
                        [label] => 0
                    )
                 */
                
                //сщздаем гарантию
                natsort( $attrs_values['warranty'] );
                createAttribute('warranty', 'Гарантия', 'select' );
                $created_attrs['warranty'] = addAttributeValues( 'warranty', $attrs_values['warranty']);
                //создаем артикул
                createAttribute('articul', 'Артикул', 'text' );
                
                //print_r($warranty);die;
                
                $attrs_opt_values_new = array();
                
                $newMapper = array();
                //print_r($mapperArray);
                if($attrs_opt){
                    $i = (int)end($mapperArray);
                    foreach ( $attrs_opt as $optName ){
                        if( !in_array(  $optName, $need_attrs)){
                            continue;
                        }
                        if(isset( $mapperArray[$optName] )){
                            $code = ($optName != 'Производитель') ?  $mapperArray[$optName] : 'manufacturer';
                        } else {
                            $code = $i;
                            $i++;
                        }
                        //echo $code.' '.$optName.'<br>';
                        
                        $newMapper[0][] = $optName;
                        $newMapper[1][] = ($optName != 'Производитель') ? $code : 'manufacturer';
                    }
                    //pr_ar($newMapper);
                    
                    $mapper = fopen($attrMapperFile, 'w');
                    if( $mapper){
                        foreach($newMapper as $string)
                        {
                            fputcsv($mapper, $string);
                        }
                        fclose($mapper);
                    }
                    $mapperArray = array_combine($newMapper[0], $newMapper[1]);
                }
                //print_r($mapperArray);
                //print_r($attrs_opt_values);die;
                
                foreach ( $mapperArray as $label => $code ){
                    $code = ($code != 'manufacturer') ? ATRR_APENDIX . $code : $code;
                    natsort( $attrs_opt_values[$label] );
                    createAttribute( $code, $label, 'select' );
                    $created_attrs[$label] = addAttributeValues( $code, $attrs_opt_values[$label]);
                }
                
                //print_r($created_attrs);
                
                $count = count($all_attrs); 
                $vArray = array_fill( 1, $count, '');
                
                $defaultArray = array('attribute_set', 'type',  'product_websites', 'visibility', 'status', 'qty', 'weight', 'category_ids', 'tax_class_id' );
                $defaultValues = array( 'Default',        'simple', 'base',              4,             1,   '9999', 1,      '',             0       );
                
                
                $csvArrayBase = array_combine($all_attrs, $vArray);
                $all_attrs = array_merge( $defaultArray, $all_attrs );
                
                $defaultValues = array_combine($defaultArray, $defaultValues);
                //print_r($defaultValues);print_r($csvArrayBase);
                $csvArrayBase = array_merge( $defaultValues, $csvArrayBase);
                //pr_ar($csvArrayBase);
                $file = fopen( $filename,"w");
                if($file){
                    
                    fputcsv($file, $all_attrs);
                    
                    $sql = ' select  GROUP_CONCAT(pc.categoryID) as category_ids,p.* from product as p left join product_category as pc on p.productID = pc.productID where pc.categoryID != 1 group by p.productID ';
                    $result = mysql_query($sql);
                    if($result){
                        $i = 0;
                        $j=0;
                        while( $row = mysql_fetch_assoc($result)){
                            $csvArray = $csvArrayBase;
                            foreach ($row as $key => $value ){
                                if($key == 'options' && $value ){
                                    $a = unserialize($value);
                                    //print_r($a);
                                    //pr_ar($created_attrs);
                                    if(is_array($a)){
                                        foreach($a as $el){
                                            if( !in_array( $el->name, $need_attrs)){
                                                continue;
                                            }
                        
                                            $csvArray[$el->name] = $created_attrs[$el->name][$el->value];
                                        }
                                    }
                                } else {
                                    switch ( $key ){
                                        case 'warranty':
                                            $csvArray[$key] = $created_attrs[$key][$value];
                                        break;
                                        case 'price':
                                            $csvArray['price'] = $row['price_uah'];
                                        case 'real_image':
                                            $csvArray['real_image'] = '../web/images/' . $row['real_image'];
                                        break;
                                        case 'brief_description':
                                            $csvArray['brief_description'] = empty($row['brief_description']) ? $row['name']: $row['brief_description'];
                                        break;
                                        case 'description':
                                            $csvArray['description'] = empty($row['description']) ? ( empty($row['brief_description']) ? $row['name']: $row['brief_description'] ): $row['description'];
                                        break;
                                        default: $csvArray[$key] = $value; break;
                                    }
                                }
                            }
                            fputcsv( $file , $csvArray);
                            /*$i++;
                            if($i == 20000 ){
                                $i = 0; $j++;
                                fclose($file);
                                echo 'You can find you\'r csv file here: '.$filename. '<br/>';
                                $filename = Mage::getBaseDir(). DS . 'var'. DS .'import'. DS . 'catalog_product_'. date('Ymd_His') .'_part_'.$j.'.csv';
                                $file = fopen( $filename,"w");
                                if($file){
                                    fputcsv($file, $all_attrs);
                                } else die('Cant create new file');
                            }*/
                        }
                    }
                }
                fclose($file);
                
                



            }
        }
        
        
        echo 'Done. You can find you\'r csv file here: '.$filename. '<br/>';
        
        Mage::app()->setCurrentStore($currentStore);
        

        
function addAttributeValues( $code, $values)
{
    $attr_model = Mage::getModel('catalog/resource_eav_attribute');
    $attr = $attr_model->loadByCode('catalog_product', $code );
    $attr_id = $attr->getAttributeId();
    
    $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
    $attribute = Mage::getSingleton('eav/config')->getAttribute( 'catalog_product', $code );
    if ($attribute->usesSource()) {
        $options = $attribute->getSource()->getAllOptions(false);
        
        $existsValues = getAttributeValues( $options );
        $newValues = array_diff( $values, $existsValues );
        
        if( $newValues ){
            foreach($newValues as $value){
                $value = trim($value);
                if(empty($value)){
                    continue;
                }
                $option['attribute_id'] = $attr_id;
                $option['value']['value'][0] = $value;
                $setup->addAttributeOption($option);
            }
        }
        $attribute = Mage::getSingleton('eav/config')->getAttribute( 'catalog_product', $code );
        if ($attribute->usesSource()) {
            $options = $attribute->getSource()->getAllOptions(false);
            $newOpt = getNewAttributes( $options );
            return $newOpt;
        }
    }
    return array();
}

function getAttributeValues( $options )
{
    $values = array();
    foreach($options as $option){
        $values[] = $option['label'];
    }
    return $values;
}

function getNewAttributes( $options )
{
    $values = array();
    foreach($options as $option){
        $values[$option['label']] = $option['value'];
    }
    return $values;
}

function checkAttributeValue( $value, $options)
{
    foreach($options as $option){
        if($value == $option['label']){
            return true;
        }
    }
    return false;
}
$max_attr_values = 30;
function createAttribute($code, $label, $attribute_type /*, $attribute_set*/, $product_type = null )
{
    /*$attributeSetName   = $attribute_set;
    $attributeSetId = Mage::getModel('eav/entity_attribute')
                ->getCollection()
                ->addFieldToFilter('entity_type_id', 3)
                ->addFieldToFilter('attribute_set_name', $attributeSetName)
                ->getFirstItem();
    
    */
    $attr_model = Mage::getModel('catalog/resource_eav_attribute');
    $attr = $attr_model->loadByCode('catalog_product', $code );
    if($attr->getAttributeId()){
        return;
    }
    /*
    public function addAttributeSet($entityTypeId, $name, $sortOrder = null)
    {
        $data = array(
            'entity_type_id'        => $this->getEntityTypeId($entityTypeId),
            'attribute_set_name'    => $name,
            'sort_order'            => $this->getAttributeSetSortOrder($entityTypeId, $sortOrder),
        );*/
    
    //$model=Mage::getModel('eav/entity_setup','core_setup');
    $model = new Mage_Catalog_Model_Resource_Eav_Mysql4_Setup();
    //print_r($model);die;
    $model->addAttribute(Mage_Catalog_Model_Product::ENTITY, $code, array(
        'group'             => 'General',
        'type'              => 'int',
        'backend'           => 'catalog/product_attribute_backend_price',
        'frontend'          => '',
        'label'             => $label,
        'input'             => $attribute_type,//'text',
        //'class'             => 'validate-number',
        'source'            => '',
        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'visible'           => true,
        'required'          => false,
        'user_defined'      => true,
        //'default'           => '0.0000',
        'searchable'        => false,
        'filterable'        =>  count( $attrs_opt_values[$label] ) > $max_attr_values ? true : false,
        'comparable'        => false,
        'visible_on_front'  => true,
        'visible_in_advanced_search' => count( $attrs_opt_values[$label] ) > $max_attr_values ? true : false,
        'used_in_product_listing' => true,
        'used_for_sort_by' => false,
        'unique'            => false,
        //'sort_order'        => 5,
    ));
    
    return;
}
        
        ?>
        
    </body>
</html>

