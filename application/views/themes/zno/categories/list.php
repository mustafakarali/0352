<?if($search_category_id == 1):?>
    <?load_theme_view('categories/top-category');?>
<?else:?>

    <h1><?=$BC->_getPageTitle()?></h1>

    <table class="table table-bordered table-striped">
    <tbody>
        <tr>
            <?$i=0; foreach ($categories as $item): $i++?>

                <?if($i!=1 && !(($i-1)%3)):?></tr><tr><?endif?>

                <td width="33%" style="text-align:center">

                    <p>
                        <?=anchor_base("{$controller}/index/category/".$item['id'],$item['category'])?>
                    </p>

                    <?if(@$item['file_name']):?>
                    <p>
                        <a title="<?=htmlspecialchars($item['category'])?>" href="<?=site_url($BC->_getBaseURL()."{$controller}/index/category/".$item['id'])?>">
                            <?=img('images/data/s/products_categories_list/'.$item['file_name'])?>
                        </a>
                    </p>
                    <?endif?>

                </td>

            <?endforeach;?>

            <?while($i%3):$i++?><td width="33%"></td><?endwhile?>
        </tr>
    </tbody>
    </table>

<?endif?>