<?if(!isset($social_params['button_title'])) $social_params['button_title'] = "Мой мир на mail.ru";?>
<a onclick="window.open('http://connect.mail.ru/share?share_url=<?=urlencode($social_params['page_url'])?>', 'moimir', 'width=626, height=436'); return false;" href="http://connect.mail.ru/share?share_url=<?=urlencode($social_params['page_url'])?>" rel="nofollow" title="<?=$social_params['button_title']?>"><?=img('images/social/'.$social_params['button_size'].'/moi-mir.png')?></a>