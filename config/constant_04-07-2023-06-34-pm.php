<?php

return [

    'home_page_path'                =>'/images/home_page',

    'gallary_page_path'             =>'/images/gallary_page',

    'gallary_page_path_compress'    =>'/images/gallary_page/gallary_page_path_compress',

    'markets_files'                 =>'/images/market_images',
    'product_files'                 =>'/images/product_images',
    'location_files'                =>'/images/location_images',

    'logo_pack_files'               =>'/images/logo_pack_files',

    'STATUS'                        => ['1'  => 'Active','2'=>'Inactive'],

    'Mandatry_filed'                => '&nbsp;<span class="text-danger">*</span>',
    'EVENT_PAYMENT_TYPE'            => ['1'  => 'PAYMENT','2'=>'ACCEPTED'],
    'MAIL_TYPE'                    => ['1'  => 'Sendmail','2'=>'SMTP','3'=>'Mailgun'],
    'EVENT_STATUS'                  => [
                                        '1'   => 'EVENT OFFLINE',
                                        '2'   => 'EVENT LIVE, BUT INVISIBLE',
                                        '3'   => 'EVENT LIVE',
                                        '4'   => 'EVENT LIVE, BUT NOT BOOKABLE',
                                        '5'   => 'EVENT BOOKED OUT'
                                       ],
    'EVENT_TAX_TYPE'                => ['1'  => 'INCLUDED','2'=>'EXCLUDED'],
    'DISCOUNT_TYPE'                 => ['1'  => 'AMOUNT','2'=>'PERCENTAGE'],
    'PRODUCT_TYPE'                  => ['1'  => 'Input',
                                            '2'  =>'Checkbox' ,
                                            '3'  =>'Radio button' ,
                                            '4'  =>'Textarea',
                                            '5'  =>'Upload',
                                        ],
    



] ?>