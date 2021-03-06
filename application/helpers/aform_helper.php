<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Build "delete selected" link.
 *
 * @return string
 */
function aform_open__Delete_Selected()
{
    return form_open(alink(FALSE,'delete_selected'),array('name'=>'form'));
}

/**
 * Build table for display records list.
 *
 * @param array $cols
 * @param array $rows
 * @param bool $__no_checkbox show checkbox
 * @param bool $__edit show "edit" link
 * @param bool $__view show "view" link
 * @param array $moreAdminLinks array of additional admin links
 */
function show_records_table( array $cols, array $rows, $__no_checkbox=FALSE, $__edit=TRUE, $__view=FALSE, array $moreAdminLinks = array() )
{
    $CI =& get_instance();
    
    ?>
    <table class="list">
        <thead>
            <tr>
            	<th style="width:20px;">
            	   <?=($__no_checkbox?"":form_checkbox("toggle_all",'1',false,"onclick='ToggleAll()'"))?> <?//checkbox?>
            	</th> 
            	<?foreach ($cols as $col): if( isset($col['just_text']) && !isset($col['title'])) $col['title'] = $CI->_getFieldTitle($col['field']); ?> 
            	<th <?if( isset($col['width']) ):?>style="width:<?=$col['width']?>px"<?endif?>> <?//set width of col?>
            	   <?=((isset($col['title']))? $col['title'] : anchor_field_title($col['field']))?> <?//show col name with sort link?>
            	</th>
            	<?endforeach?>
            	<?if($__edit):?>
            	<th style="width:50px;">&nbsp;</th>
            	<?endif?>
            	<?if($__view):?>
            	<th style="width:50px;">&nbsp;</th>
            	<?endif?>
            </tr>
        </thead>
        <tbody>
            <?foreach ($rows as $row):?>
            <?$id_field = (isset($row['id'])?'id':'data_key')?>
            <tr>
            	<td>
            	   <?=(($__no_checkbox || isset($row['__no_checkbox']))?"":form_checkbox("check[{$row[$id_field]}]",'1',false))?> <?//checkbox?>
                </td> 
            	<?foreach ($cols as $col): 
            	    //set output [col_name]__output if exists, if no - just [col_name]
            	    $output = (isset($row[$col['field'].'__output']) ? $row[$col['field'].'__output'] : $row[$col['field']]);
					$bgColor = (isset($row[$col['field'].'__bgColor']) ? $row[$col['field'].'__bgColor'] : '');
            	?>
            	<td style="<?if($bgColor):?>background-color:<?=$bgColor?><?endif?>"><?=$output?></td>
            	<?endforeach?>
            	<?if($__edit):?>
            	<td><?=anchor_edit($row[$id_field])?></td> <?//link for edit record?>
            	<?endif?>
            	<?if($__view):?>
            	<td><?=anchor_view($row[$id_field])?></td> <?//link for view record?>
            	<?endif?>
            	<?foreach ($moreAdminLinks as $adminLink):?>
            	<td><?=anchor_admin($adminLink,$row[$id_field])?></td>
            	<?endforeach?>
            </tr>
            <?endforeach?>
        </tbody>
    </table>
    <?
}


/**
 * Build table for display records list with sortable ability.
 *
 * @param array $cols
 * @param array $rows
 * @param bool $__no_checkbox show checkbox
 * @param bool $__edit show "edit" link
 * @param bool $__view show "view" link
 * @param array $moreAdminLinks array of additional admin links
 */
function show_records_sortable( array $cols, array $rows, $__no_checkbox=FALSE, $__edit=TRUE, $__view=FALSE, array $moreAdminLinks = array() )
{
    $CI =& get_instance();
    
    ?>
    <table class="list">
        <thead>
            <tr>
            	<th style="width:20px;">
            	   <?=($__no_checkbox?"":form_checkbox("toggle_all",'1',false,"onclick='ToggleAll()'"))?> <?//checkbox?>
            	</th> 
            	<?foreach ($cols as $col): if( isset($col['just_text']) && !isset($col['title'])) $col['title'] = $CI->_getFieldTitle($col['field']); ?> 
            	<th <?if( isset($col['width']) ):?>style="width:<?=$col['width']?>px"<?endif?>> <?//set width of col?>
            	   <?=((isset($col['title']))? $col['title'] : anchor_field_title($col['field']))?> <?//show col name with sort link?>
            	</th>
            	<?endforeach?>
            	<?if($__edit):?>
            	<th style="width:100px;">&nbsp;</th>
            	<?endif?>
            	<?if($__view):?>
            	<th style="width:100px;">&nbsp;</th>
            	<?endif?>
            </tr>
        </thead>
     </table>
     <ul id="sortable_group">
        <?foreach ($rows as $idx=>$row):?>
        <?$id_field = (isset($row['id'])?'id':'data_key')?>
        <?$sort = $row['sort'] ? $row['sort'] : $idx?>
        <li id="sortables_<?=$sort?>" class="sortable_item">
        	<table class="list">
                <tbody>
                    <tr>
                    	<td style="width:20px;">
                    	   <?=(($__no_checkbox || isset($row['__no_checkbox']))?"":form_checkbox("check[{$row[$id_field]}]",'1',false))?> <?//checkbox?>
                        </td> 
                    	<?foreach ($cols as $col): 
                    	   //set output [col_name]__output if exists, if no - just [col_name]
                    	   $output = (isset($row[$col['field'].'__output']) ? $row[$col['field'].'__output'] : $row[$col['field']])
                    	?>
                    	<td <?if( isset($col['width']) ):?>style="width:<?=$col['width']?>px"<?endif?> <?if($col['field']=='sort'):?>class="sort_td"<?endif?>><?=$output?></td>
                    	<?endforeach?>
                    	<?if($__edit):?>
                    	<td style="width:100px;"><?=anchor_edit($row[$id_field])?></td> <?//link for edit record?>
                    	<?endif?>
                    	<?if($__view):?>
                    	<td style="width:100px;"><?=anchor_view($row[$id_field])?></td> <?//link for view record?>
                    	<?endif?>
                    	<?foreach ($moreAdminLinks as $adminLink):?>
		            	<td style="width:100px;"><?=anchor_admin($adminLink,$row[$id_field])?></td>
		            	<?endforeach?>
                    </tr>
                </tbody>
            </table>
        </li>
        <?endforeach?>
    </ul>
    <?
}