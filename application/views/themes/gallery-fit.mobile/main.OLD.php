<?
//get photos list
$photos_model = load_model('photos_model');
//$photos = $photos_model->getRandom(20);
$photosData = $photos_model->getPhotos(0,0,999);
$photos = $photosData['list'];

//get pages data
$pages['contacts'] = $BC->pages_model->getByLink('contact_us');
$pages['about'] = $BC->pages_model->getByLink('about');
$pages['about_makeup'] = $BC->pages_model->getBySlug('pro-makiyazh');

//get captcha image
$captcha_model = load_model('captcha_model');
$cap_img = $captcha_model->make();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title><?=$head['site_title']?></title>
        <link rel="stylesheet" href="https://ajax.aspnetcdn.com/ajax/jquery.mobile/1.1.1/jquery.mobile-1.1.1.min.css" />
        <?=include_minified($BC->_getTheme().'css/my.css','css')?>
        <style>
            h1{font-size:25px !important;}
            h5{font-size:12px !important;}
            .red, .required{color:red;}
            .green, .success{color:green;}
            #gallery{}
            #gallery img{float:left;margin:1px;}
        </style>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        <script src="https://ajax.aspnetcdn.com/ajax/jquery.mobile/1.1.1/jquery.mobile-1.1.1.min.js"></script>
        <?=include_minified($BC->_getTheme().'js/my.js','js')?>
    </head>
    <body>
    
        <!-- Gallery Page -->
        <div data-role="page" id="page1" data-title="<?=$head['site_title']?> :: Галерея">
            <div data-theme="a" data-role="header">
                <h1>
                    Галерея
                </h1>
                <div data-role="navbar" data-iconpos="right">
                    <ul>
                        <li>
                            <a href="#page1" data-theme="" data-icon="grid" class="ui-btn-active ui-state-persist">
                                Галерея
                            </a>
                        </li>
                        <li>
                            <a href="#page2" data-theme="" data-icon="info">
                                Про мене
                            </a>
                        </li>
                        <li>
                            <a href="#page3" data-theme="" data-icon="arrow-r">
                                Контакти
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div data-role="content" style="padding: 5px">
                <!--
                <div class="ui-grid-a">
                    <?$i=0; foreach ($photos as $record): $i++;?>
                    <div class="ui-block-<?if($i%2):?>a<?else:?>b<?endif?>"><a href="#img<?=$i?>" data-role="button" data-rel="dialog" data-transition="pop"><?=img(array('src'=>'images/data/s/photos/'.$record['file_name'],'width'=>230,'height'=>162,'alt'=>''))?></a></div>
                    <?endforeach?>
                </div>
                -->
                
                <!--
                <?$i=0; foreach ($photos as $record): $i++;?>
                <div style='float:left;margin:0 5px;'><a href="#img<?=$i?>" data-role="button" data-rel="dialog" data-transition="pop"><?=img(array('src'=>'images/data/s/photos/'.$record['file_name'],'width'=>230,'height'=>162,'alt'=>''))?></a></div>
                <?endforeach?>
                -->
                <div id="gallery"></div>
                <div style="clear:both"></div>
                <p><a href="javascript:;" data-role="button" data-inline="true" data-icon="down" id="more_photos">More</a></p>
            </div>
            <div data-theme="a" data-role="footer">
                <h5>
                    Maria Kovalska &copy; 2012 <a href="#about_makeup">Про макіяж</a>
                </h5>
            </div>
        </div>
        
        <!-- About Page -->
        <div data-role="page" id="page2" data-title="<?=$head['site_title']?> :: Про мене">
            <div data-theme="a" data-role="header">
                <h1>
                    Про мене
                </h1>
                <div data-role="navbar" data-iconpos="right">
                    <ul>
                        <li>
                            <a href="#page1" data-theme="" data-icon="grid">
                                Галерея
                            </a>
                        </li>
                        <li>
                            <a href="#page2" data-theme="" data-icon="info" class="ui-btn-active ui-state-persist">
                                Про мене
                            </a>
                        </li>
                        <li>
                            <a href="#page3" data-theme="" data-icon="arrow-r">
                                Контакти
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div data-role="content" style="padding: 15px">
                <img src="<?=$BC->_getTheme()?>images/maria-kovalska.jpg" width="350" height="258" class="p2" alt="Maria Kovalska">
                <?=$pages['about']['body']?>
            </div>
            <div data-theme="a" data-role="footer">
                <h5>
                    Maria Kovalska &copy; 2012 <a href="#about_makeup">Про макіяж</a>
                </h5>
            </div>
        </div>
        
        <!-- Contacts Page -->
        <div data-role="page" id="page3" data-title="<?=$head['site_title']?> :: Контакти">
            <div data-theme="a" data-role="header">
                <h1>
                    Контакти
                </h1>
                <div data-role="navbar" data-iconpos="right">
                    <ul>
                        <li>
                            <a href="#page1" data-theme="" data-icon="grid">
                                Галерея
                            </a>
                        </li>
                        <li>
                            <a href="#page2" data-theme="" data-icon="info">
                                Про мене
                            </a>
                        </li>
                        <li>
                            <a href="#page3" data-theme="" data-icon="arrow-r" class="ui-btn-active ui-state-persist">
                                Контакти
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div data-role="content" style="padding: 15px">
                <?=$pages['contacts']['body']?>
        
                <p class="required">* <?=language('required_fields')?></p>
                <div class="green" id="success"></div>
                <div class="red" id="errors"></div>
                
                <form id="contact_form" action="#" method="post">
                  <label><?=language('name')?>:
                    <input type="text" name="name">
                  </label>
                  <label><?=language('email')?>:
                    <input type="text" name="email">
                  </label>
                  <label><?=language('message')?>:
                    <textarea name="message"></textarea>
                  </label>
                  <?=$cap_img?>
                  <label><?=language('captcha')?>:
                    <input type="text" name="captcha">
                  </label>
                  <div class="btns und"> 
                    <input type="submit" value="<?=language('submit')?>" />
                  </div>
                </form>
            </div>
            <div data-theme="a" data-role="footer">
                <h5>
                    Maria Kovalska &copy; 2012 <a href="#about_makeup">Про макіяж</a>
                </h5>
            </div>
        </div>
        
        <!-- About Make-up -->
        <div data-role="page" id="about_makeup" data-title="<?=$head['site_title']?> :: Про макіяж">
            <div data-theme="a" data-role="header">
                <h1>
                    Про макіяж
                </h1>
                <div data-role="navbar" data-iconpos="right">
                    <ul>
                        <li>
                            <a href="#page1" data-theme="" data-icon="grid">
                                Галерея
                            </a>
                        </li>
                        <li>
                            <a href="#page2" data-theme="" data-icon="info">
                                Про мене
                            </a>
                        </li>
                        <li>
                            <a href="#page3" data-theme="" data-icon="arrow-r">
                                Контакти
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div data-role="content" style="padding: 15px">
                <?=$pages['about_makeup']['body']?>
                <p><?=$BC->settings_model['site_partners']?></p>
            </div>
            <div data-theme="a" data-role="footer">
                <h5>
                    Maria Kovalska &copy; 2012 <a href="#about_makeup">Про макіяж</a>
                </h5>
            </div>
        </div>
        
        <?$i=0; foreach ($photos as $record): $i++;?>
        <div data-role="page" id="img<?=$i?>">
            <div data-role="content" data-theme="a">	
        		<div class="dialog-photo" name="<?=$record['file_name']?>"><img src="https://ajax.aspnetcdn.com/ajax/jquery.mobile/1.1.1/images/ajax-loader.gif" alt="" /></div>
        		<p><a href="#page1" data-rel="back" data-role="button" data-inline="true" data-icon="back">Back to gallery</a></p>	
        	</div>
        </div>
        <?endforeach?>
        
        <script>
            $j = jQuery.noConflict();
            $j(document).ready(function(){
                $j("a[data-rel='dialog']").click(function(){
                    var photo_id = $j(this).attr('href');
                    var photo_name = $j(photo_id + " .dialog-photo").attr('name');
                    $j(photo_id + " .dialog-photo").html("<img src='<?=base_url().'images/data/b/photos/'?>"+photo_name+"' width='460' alt='' />");
                });
            });
            
            var photos = [];
            <?$i=0;foreach ($photos as $record):$i++;?>
            photos[<?=$i?>] = '<?=$record['file_name']?>';
            <?endforeach?>
            var lastPhoto = 1;
            
            function show_photos(count)
            {
                var i = lastPhoto;
                lastPhoto += count;
                
                while(i<lastPhoto)
                {
                    if(i>=photos.length) 
                    {
                        $j("#more_photos").hide();
                        break;
                    }
                    
                    $j("#gallery").append("<img src='<?=base_url().'images/data/s/photos/'?>"+photos[i]+"' width='230' height='162' alt='' />");
                    i++;
                }
                
            }
            
            show_photos(6);
            
            $j("#more_photos").click(function(){
                show_photos(6);
            });
        </script>
        <!-- Load Application Packeges config -->
        <?=include_js($BC->_getBaseURL().'app_js/config')?>
        
        <?=include_minified($BC->_getFolder('js').'custom/contact_us/send_form.js','inline_js')?>
    </body>
</html>