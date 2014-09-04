<?php

/**
 * Description of ContentCounterClass
 *
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */

if (!defined('DOKU_IOCEXPORT_COUNTER_TYPE_TOTAL')) 
        define('DOKU_IOCEXPORT_COUNTER_TYPE_TOTAL',0);
if (!defined('DOKU_IOCEXPORT_COUNTER_TYPE_NEWCONTENT')) 
        define('DOKU_IOCEXPORT_COUNTER_TYPE_NEWCONTENT',1);

class ContentCounterClass {
    private $counterType = DOKU_IOCEXPORT_COUNTER_TYPE_TOTAL;
    private $total=0;
    private $newContent=0;
    
    public function reset(){
        $this->counterType = DOKU_IOCEXPORT_COUNTER_TYPE_TOTAL;
        $this->total = 0;
        $this->newContent=0;
    }
    
    public function incTotal($value){
        $this->total += $value;
    }

    public function incNewContent($value){
        $this->counterType = DOKU_IOCEXPORT_COUNTER_TYPE_NEWCONTENT;
        $this->newContent+=$value;
        $this->total += $value;
    }
    public function incReusedContent($value){
        $this->counterType = DOKU_IOCEXPORT_COUNTER_TYPE_NEWCONTENT;
        $this->total += $value;
    }
    
    public function toJsonEncode(){
        /*TO DO 
         * 1) Canviar comentaris lieterals
         * 2) Externalitzar la funció toJsonEncode
         */
        $result['counterType'] =  $this->counterType;
        $result['newContentCounter']['tag']='de nova creació';
        $result['newContentCounter']['value']=  $this->newContent;
        $result['reusedContentCounter']['tag']='de reaprofitament';
        $result['reusedContentCounter']['value']=  $this->total-$this->newContent;
        $result['totalCounter'] = array('tag' => 'Total',
                'value' => $this->total,);             
        return json_encode($result);
    }
}
